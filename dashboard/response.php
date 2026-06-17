<?php
header("Content-Type: application/json");

/**
 * DOKU Snap API Response Handler
 * URL: http://localhost:8080/dokupayment/jokul-checkout/response/response.php
 */

include ("include/DbConnector.php");
include ("include/daftar_fungsi.php");


// latest transaction status of transaction | format: 00 (Success) / 03 (Pending) / 04 (Refunded) / 05 (Canceled) / 06 (Failed)


// Initialize database object
if (!isset($db)) {
    $db = new DbConnector();
}

// 2. Ambil data JSON dari body request
$raw_json = file_get_contents('php://input');

// Log the received JSON and headers to Apache error log
if (!empty($raw_json)) {
    $incoming_headers = getallheaders();
    $log_data = [
        'METHOD' => $_SERVER['REQUEST_METHOD'],
        'URL' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
        'HEADERS' => $incoming_headers,
        'BODY' => json_decode($raw_json, true)
    ];
    
    error_log("DOKU TRAP (COPY THIS TO REST CLIENT): " . json_encode($log_data));
    
    // Also log in a more readable format for the user
    error_log("DOKU Notification Received Headers: " . json_encode($incoming_headers));
    error_log("DOKU Notification Received JSON: " . $raw_json);
	exit();
} else {
    $incoming_headers = getallheaders();
    error_log("Json kosong. Headers: " . json_encode($incoming_headers));
	exit();
}	

$sql = "select * from payment_gateway where aktif = '1'";
$res = $db->query($sql);
$row = $db->fetchArray($res);

// Configuration - Replace with your actual credentials
$clientId = $row['kode_id']; // Example: $clientId = 'BRN-0231-1685002220456';
$secretKey = $row['secret_code']; // Example: $secretKey = 'SK-z7x...';

// Get Headers
$headers = getallheaders();
$timestamp = $headers['X-TIMESTAMP'] ?? '';
$signatureHeader = $headers['X-SIGNATURE'] ?? '';
$partnerId = $headers['X-PARTNER-ID'] ?? '';
$externalId = $headers['X-EXTERNAL-ID'] ?? '';

// Get JSON Body
$rawBody = file_get_contents('php://input');
$body = json_decode($rawBody, true);

// Verify Signature
$targetPath = $_SERVER['REQUEST_URI']; // The path where DOKU sends the notification
if (!verifySignature($headers, $rawBody, $secretKey, $targetPath)) {
    doku_log("Signature Verification Failed", "Invalid signature received", $externalId ?: 'No-ID');
    http_response_code(401);
    echo json_encode([
        "responseCode" => "4010000",
        "responseMessage" => "Unauthorized - Invalid Signature"
    ]);
    exit;
}

// Log the request
doku_log("Incoming Notification", $rawBody, $externalId ?: 'No-ID');

if (!$body) {
    echo json_encode([
        "responseCode" => "4000000",
        "responseMessage" => "Invalid Request Body"
    ]);
    exit;
}

// Extract relevant data
$trxId = $body['trxId'] ?? '';
$customerNo = $body['customerNo'] ?? '';
$virtualAccountNo = $body['virtualAccountNo'] ?? '';
$virtualAccountName = $body['virtualAccountName'] ?? '';
$paidAmountValue = $body['paidAmount']['value'] ?? '0';
$paymentRequestId = $body['paymentRequestId'] ?? '';
$jenispayment = $body['channel']['id'];

// Database Operation (Example)
try {
    $db = new DbConnector2();
    
    // Note: The user mentioned database 'maxiline' and table 'tr_confirm'
    // We escape the values to prevent SQL injection
    $trxIdEscaped = $db->escaped($trxId);
    $vaNoEscaped = $db->escaped($virtualAccountNo);
    $amountEscaped = $db->escaped($paidAmountValue);
    $customerNoEscaped = $db->escaped($customerNo);
    $timestampEscaped = $db->escaped($timestamp);

    $externalIdEscaped = $db->escaped($externalId);

    // Example Query - Added WHERE clause to avoid updating all rows
    $sql = "UPDATE tr_confirm set asal_bank = '$jenispayment', asal_norek = '$vaNoEscaped' WHERE kode_invoice = '$externalIdEscaped'";    
    $result = $db->query($sql);

    if ($result) {
        doku_log("DB Insert Success", "Successfully inserted trxId: $trxId", $externalId);
    } else {
        doku_log("DB Insert Failed", "Failed to insert trxId: $trxId - Query: " . $db->getQuery(), $externalId);
    }

} catch (Exception $e) {
    doku_log("DB Error", $e->getMessage(), $externalId);
}

// Return Success Response to DOKU
$response = [
    "responseCode" => "2005600",
    "approvalCode" => "201039000200", // Example approval code
    "responseMessage" => "Request has been processed successfully"
];

echo json_encode($response);

/**
 * Simple Logging Function
 */
 
function doku_log($class, $log_msg, $id = '')
{
    $log_filename = __DIR__ . "/../../doku_log"; // Portable path relative to current script
    $log_header = date(DATE_ATOM, time()) . ' [' . $class . '] ' . '---> ' . $id . " : ";
    if (!file_exists($log_filename)) {
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename . '/log_response_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, $log_header . $log_msg . "\n", FILE_APPEND);
}

/**
 * Calculate Digest for DOKU Snap API
 */
function calculateDigest($rawBody)
{
    return base64_encode(hash('sha256', $rawBody, true));
}

/**
 * Verify Signature for DOKU Snap API Notification
 */
function verifySignature($headers, $rawBody, $secretKey, $targetPath)
{
    // Fix X-TIMESTAMP and other header keys to be consistent (some servers might lowercase headers)
    $headers = array_change_key_case($headers, CASE_UPPER);
    
    $timestamp = $headers['X-TIMESTAMP'] ?? '';
    $signatureHeader = $headers['X-SIGNATURE'] ?? '';
    $clientId = $headers['X-PARTNER-ID'] ?? '';
    $requestId = $headers['X-EXTERNAL-ID'] ?? '';

    // Remove 'HMACSHA256=' prefix from signature header if present
    $receivedSignature = str_replace('HMACSHA256=', '', $signatureHeader);

    // Calculate Digest
    $digest = calculateDigest($rawBody);

    // Construct Component Signature
    $componentSignature = "Client-Id:" . $clientId . "\n" .
        "Request-Id:" . $requestId . "\n" .
        "Request-Timestamp:" . $timestamp . "\n" .
        "Request-Target:" . $targetPath . "\n" .
        "Digest:" . $digest;

    // Calculate HMAC-SHA256
    $calculatedSignature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

    return $receivedSignature === $calculatedSignature;
}
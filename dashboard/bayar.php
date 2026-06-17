<?php
include ("include/config.php");
include ("include/DbConnector.php");

$db = new DbConnector();

$sql = "select * from payment_gateway where aktif = '1'";
$res = $db->query($sql);
$row = $db->fetchArray($res);

//print_r($row);
//exit();

// Log incoming payload for debugging

$input = json_decode(file_get_contents("php://input"), true);

// Insert into tr_confirm (pending status)
$now = date("Y-m-d H:i:s");
$customerName = $input['customerName'];
$nameinv = $input['nameinv'];
$amount = $input['amount'];

$back = "https://maxi-line.net/dashboard/invoice_pelanggan_add.php?id=".maxiline($input['id_tb_pendaftaran'], 'e')."&i=".maxiline($input['id_tr_invoice'], 'e')."&j=1";

$sqld = "insert into tr_confirm (nama, kode_invoice, tgl_transfer, jml_transfer, ket_transfer, id_payment_gateway, st_confirm) values ('$customerName', '$nameinv', '$now', '$amount', 'Pembayaran menggunakan DOKU', '1', '1')";
$resd = $db->query($sqld);

if (!$resd) {
    header('Content-Type: application/json');
    $errors = sqlsrv_errors();
    echo json_encode(['error' => 'Database insertion failed', 'query' => $sqld, 'sqlsrv_errors' => $errors]);
    exit;
}

// Get the last inserted ID and tgl_transfer
$sql_last = "SELECT id_tr_confirm as idnya, tgl_transfer from tr_confirm where id_tr_confirm = (SELECT SCOPE_IDENTITY())";
$res_last = $db->query($sql_last);
$row_last = $db->fetchArray($res_last);

if (!$row_last) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to retrieve last insert ID']);
    exit;
}

$unik = date('YmdHis');
$requestId = $row_last['idnya'].$unik;
// Convert tgl_transfer to GMT/UTC ISO8601
$date_transfer = $row_last['tgl_transfer'];
// In SQL Server, tgl_transfer might be a DateTime object from fetchArray
if (is_object($date_transfer)) {
    $date_transfer->setTimezone(new DateTimeZone('UTC'));
    $dateTimeFinal = $date_transfer->format("Y-m-d\TH:i:s\Z");
} else {
    $dt = new DateTime($date_transfer, new DateTimeZone('Asia/Jakarta'));
    $dt->setTimezone(new DateTimeZone('UTC'));
    $dateTimeFinal = $dt->format("Y-m-d\TH:i:s\Z");
}

$requestBody = array (
    'order' => array (
        'amount' => $input["amount"],
        'invoice_number' => $input["nameinv"], // Change to your business logic
        'currency' => 'IDR',
        'callback_url' => $back,
        'line_items' => 
        array (
        0 => 
        array (
            'name' => $input["nameinv"],
            'price' => $input["amount"],
            'quantity' => 1,
        ),
        ),
    ),
    'payment' => array (
        'payment_due_date' => $input["expiredTime"],
    ),
    'customer' => array (
        'id' => $requestId,
        'name' => $input["customerName"],
        'email' => $input["email"],
        'phone' => $input["phoneNumber"],
        'address' => $input["address"],
        'country' => $input["country"],
    ),
);

$clientId = $row['kode_id']; // Match working script's Client ID
$secretKey = $row['secret_code']; // Hardcoded Secret Key (Hidden from payload)

$getUrl = 'https://api-sandbox.doku.com';

$targetPath = '/checkout/v1/payment';
$url = $getUrl . $targetPath;

// Generate digest
$digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

// Prepare signature component
$componentSignature = "Client-Id:".$clientId ."\n".
                    "Request-Id:".$requestId . "\n".
                    "Request-Timestamp:".$dateTimeFinal ."\n".
                    "Request-Target:".$targetPath ."\n".
                    "Digest:".$digestValue;

// Generate signature
$signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

// Execute request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = array(
    'Content-Type:application/json',
    'Client-Id:' . $clientId,
    'Request-Id:' . $requestId,
    'Request-Timestamp:' . $dateTimeFinal,
    'Signature:HMACSHA256=' . $signature,
);

// Log the outgoing request body and headers to Apache error log
error_log("DOKU Payment Request Sent Headers: " . json_encode($headers));
error_log("DOKU Payment Request Sent JSON: " . json_encode($requestBody));

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Set response json
$responseJson = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

// Echo the response
if ($httpCode == 200) {
    $res = json_decode($responseJson, true);
    $res['internal_request_id'] = $row_last['idnya'];
    echo json_encode($res);
} else {
    // Return debug info on failure
    header('Content-Type: application/json');
    echo json_encode([
        'http_code' => $httpCode,
        'headers_sent' => $headers,
        'request_body' => $requestBody,
        'internal_request_id' => $row_last['idnya'],
        'doku_response' => json_decode($responseJson, true)
    ]);
}

?>
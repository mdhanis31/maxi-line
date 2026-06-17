<?php

/********************************************************
 * GLOBAL LOG CONFIG
 ********************************************************/
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/maxi-line/log/error.log');
ini_set('display_errors', 0);
error_reporting(E_ALL);

/********************************************************
 * ERROR & EXCEPTION HANDLER
 ********************************************************/
set_error_handler(function ($severity, $message, $file, $line) {
    error_log("[".date('Y-m-d H:i:s')."] PHP ERROR: $message in $file:$line");
});

set_exception_handler(function ($e) {
    error_log("[".date('Y-m-d H:i:s')."] EXCEPTION: ".$e->getMessage().
        " in ".$e->getFile().":".$e->getLine());
});

/********************************************************
 * SUPPORT CLI & WEB + IP WHITELIST + LOCK
 ********************************************************/

 $allowedIps = [
    '127.0.0.1',
    '::1',
    '160.20.79.226' // GANTI dengan IP server Anda
];

// === Jika via WEB ===
if (php_sapi_name() !== 'cli') {
    $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';

    if (!in_array($remoteIp, $allowedIps)) {
        http_response_code(403);
        exit('Forbidden');
    }
}

// === LOCK FILE (anti double run) ===
$lock = fopen('/tmp/check_pppoe_active.lock', 'c');
if (!flock($lock, LOCK_EX | LOCK_NB)) {
    exit("Script masih berjalan\n");
}

// === PASTIKAN PATH AMAN ===
chdir(__DIR__);

// ======================================================
// SCRIPT ASLI ANDA
// ======================================================

include __DIR__ . "/include/config.php";
include __DIR__ . "/include/DbConnector.php";
require_once __DIR__ . "/include/MikrotikApi.php";

/********************************************************
 * HELPER LOG
 ********************************************************/
function appLog($msg) {
    error_log("[".date('Y-m-d H:i:s')."] ".$msg);
}

appLog("START CHECK PPPoE");

$db = new DbConnector();

$sql = "SELECT * FROM tb_pendaftaran WHERE id_tb_paket NOT IN ('10', '11', '12', '13') AND st_layanan = '8' OR st_layanan = '10'";
$res = $db->query($sql);

$sent = 0;
$skipped = 0;
$today = date('Y-m-d');

while ($row = $db->fetchArray($res)) {
    $kode_daftar = $row['kode_daftar'];
    $name = $kode_daftar . '@maxi-line.net';

    try {
        $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
        $isActive = $mikrotik->actPppoecon($name);

        // Cek apakah hari ini sudah kirim email
        $cek = $db->query("SELECT COUNT(*) AS total FROM tb_log_email_down WHERE kode_daftar='$kode_daftar' AND tgl_down = '$today'"); // AND tgl_down = '$today'
        $res_cek = $db->fetchArray($cek);

        if ($res_cek['total'] > 0) {
            $skipped++;
            continue;
        }

        if (!$isActive) {
            // Kirim Email.
            $recipients = ['cs@maxi-line.net'];
            $cc = ['sales@maxi-line.net', 'prodcust-dev@manunggalsistemsejahtera.com', 'sendayu@manunggalintegrasi.com'];
            $bcc = [];
            $subject = "Perangkat Down - " . $row['kode_daftar'] . "-" . date('H:i:s');
    
            // $linkp3 = "https://www.maxi-line.net/dashboard/invoice_p.php?id=".maxiline($row3['id_tr_invoice'], 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
        
            $mailContent = '
                <html>
                    <head>
                        <title>Perangkat Down - '.$row['kode_daftar'].'</title>
                    </head>
                    <body>
                        <p>Halo Teknisi M@xi-Line!</p>
                        <p>
                            Beberapa perangkat dimatikan, mengalami masalah koneksi, atau sedang melakukan restart.<br>
                            Harap teknisi segera melakukan pengecekan terhadap device dari pelanggan :
                        </p>
                        <table style="margin-left: 30px;">
                            <tr>
                                <td>Kode Pelanggan</td>
                                <td>:</td>
                                <td>'.$kode_daftar.'</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>'.$row['nama'].'</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>'.$row['alamat'].'</td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td>:</td>
                                <td>'.$row['telp'].'</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td><b>Non-aktif</b></td>
                            </tr>
                        </table>
                        <br>
                        <p style="font-style: italic">Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p>
                    </body>
                </html>
            ';
    
            $sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);

            if ($sendMail) {
                // Simpan log email down
                $qry = "INSERT INTO tb_log_email_down(kode_daftar, tgl_down) VALUES ('$kode_daftar', '$today')";

                if($res = $db->query($qry)) {
                    $sent++;
                } else {
                    // echo "Gagal simpan log email pelanggan '".$row['kode_daftar']."'!";
                    appLog("Gagal simpan log email pelanggan '".$row['kode_daftar']."'!");
                }
            }
        }
    } catch (Exception $e) {
        // echo " <script>alert('".$e->getMessage()."');</script>
        // <script>history.back();</script>";
        // exit();
        appLog("ERROR PPPoE {$kode_daftar}: ".$e->getMessage());
    }
}

appLog("SELESAI | TERKIRIM: $sent | DISKIP: $skipped");
// echo "SELESAI | TERKIRIM: $sent | DISKIP: $skipped\n";
?>
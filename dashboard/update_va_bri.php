<?php
@session_start();
include "include/config.php";
include "include/DbConnector.php";
$db = new DbConnector();

$kodeaman = $_SESSION['token'];
$kode_daftar = SafeSQL($_POST['kode_daftar']);
$va_bri = SafeSQL($_POST['va_bri']);

if ($kodeaman && $_POST['token'] == $kodeaman) {
    unset($_SESSION['token']);
	session_write_close();

    $sql = "UPDATE tb_pendaftaran SET va_bri='$va_bri' WHERE kode_daftar='$kode_daftar'";

    if ($db->query($sql)) {
        $response = [
            'status' => "success",
            'msg' => "Data berhasil disimpan."
        ];
    } else {
        $response = [
            'status' => "failed",
            'msg' => "Data gagal disimpan, Re-fresh halaman & submit ulang"
        ];
    }

} else {
    
    $response = [
        'status' => "failed",
        'msg' => "Terdapat kesalahan, Re-fresh halaman & submit ulang"
    ];
}

echo json_encode($response);
?>
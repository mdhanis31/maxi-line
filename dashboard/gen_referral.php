<?php
@session_start();
include "include/config.php";
include "include/DbConnector.php";
include "include/ReferralCodeGenerator.php";

$db = new DbConnector();
$gen  = new ReferralCodeGenerator();
$kodeaman = $_SESSION['token'];
$id = maxiline(SafeSQL($_POST['id']), 'd');

if ($kodeaman && $_POST['token'] == $kodeaman) {
    unset($_SESSION['token']);
	session_write_close();

    // Generate kode refferal
    $qry = $db->query("SELECT * FROM tb_user WHERE id_tb_user = '$id'");
    $row = $db->fetchArray($qry);

    if (!empty($row)) {
        $referral = $gen->generate();

        if ($row['referral'] == $referral) {
            $response = [
                'status'=> false,
                'msg' => 'Kode referral sudah pernah digunakan!'
            ];
        } else {
            $qry2 = $db->query("UPDATE tb_user SET referral = '$referral' WHERE id_tb_user = '$id'");
            
            if ($qry2) {
                $response = [
                    'status'=> true,
                    'msg' => 'Kode referral berhasil di generate!'
                ];
            } else {
                $response = [
                    'status'=> false,
                    'msg' => 'Kode referral gagal di generate!'
                ];
            }
        }
    } else{
        $response = [
            'status'=> false,
            'msg'=> 'Data user tidak ditemukan!'
        ];
    }
    
} else {
    $response = [
        'status'=> false,
        'msg' => 'Terdapat kesalahan, Re-fresh halaman & generate ulang',
    ];
}

echo json_encode($response);

?>
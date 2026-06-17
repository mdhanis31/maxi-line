<?php 
@session_start();

include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";

$db = new DbConnector();
$kodeaman = $_SESSION['token'];
$token = SafeSQL($_POST['token']);

$id_tb_pendaftaran = maxiline(SafeSQL($_POST['id_tb_pendaftaran']), 'd');
$tgl_instalasi = date("Y-m-d", strtotime($_POST['tgl_pasang']))." ".$_POST['waktu_pasang'];
$id_tb_paket = SafeSQL($_POST['id_tb_paket']);

$perangkat_in = SafeSQL($_POST['perangkat_in']);
$sn_in = SafeSQL($_POST['sn_in']);
$perangkat_out = SafeSQL($_POST['perangkat_out']);
$sn_out = SafeSQL($_POST['sn_out']);
$long = SafeSQL($_POST['long']);
$lat = SafeSQL($_POST['lat']);
$id_tb_user = SafeSQL(implode(",", $_POST['id_tb_user']));

$id_tb_instalasi = maxiline(SafeSQL($_POST['id_tb_instalasi']), 'd');
$st_instalasi = SafeSQL($_POST['st_instalasi']);
$ket_instalasi = SafeSQL($_POST['ket_instalasi']);


if (empty($kodeaman) || $token !== $kodeaman) {
    $arrMsg = [
        'status' => false,
        'message' => 'Terdapat kesalahan, Re-fresh halaman & submit ulang',
        'redirect' => ''
    ];

    echo json_encode($arrMsg);
    exit();
}

if ($_POST['f'] == 'n') {

    // check tb_rencana
    $sqla = "SELECT TOP(1) * FROM tb_rencana WHERE id_tb_pendaftaran = '$id_tb_pendaftaran' AND rencana = '2' AND st_rencana = '1'";
    $qrya = $db->query($sqla);
    $resa = $db->fetchAssoc($qrya);

    if (!empty($resa)) {
        // update sts_survey
        $sql1 = $db->query("UPDATE tb_survey SET sts_survey = '2' WHERE id_tb_pendaftaran ='$id_tb_pendaftaran'");

        // insert into tb_instalasi
        $sqlb = "INSERT INTO tb_instalasi(id_tb_rencana, id_tb_pendaftaran, perangkat_in, sn_in, perangkat_out, sn_out, long, lat, tgl_instalasi, id_tb_user) 
        VALUES ('{$resa['id_tb_rencana']}', '$id_tb_pendaftaran', '$perangkat_in', '$sn_in', '$perangkat_out', '$sn_out', '$long', '$lat', '$tgl_instalasi', '$id_tb_user')";
        $qryb = $db->query($sqlb);

        if ($qryb) {

            $arrMsg = [
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'redirect' => 'pendaftaran_dtl.php?id=' . $_POST['id_tb_pendaftaran']
            ];
        } else {
            $arrMsg = [
                'status' => false,
                'message' => 'Data gagal disimpan!',
                'redirect' => ''
            ];
        }
    } else {
        $arrMsg = [
            'status' => false,
            'message' => 'Buat jadwal terlebih dahulu!',
            'redirect' => 'pendaftaran_dtl.php?id=' . $_POST['id_tb_pendaftaran']
        ];
    }

    echo json_encode($arrMsg);
    
} elseif ($_POST['f'] == 'e') {
    
    // update tb_instalasi
    $sqlc = "UPDATE tb_instalasi SET 
        tgl_instalasi='$tgl_instalasi', 
        perangkat_in='$perangkat_in',
        sn_in='$sn_in',
        perangkat_out='$perangkat_out',
        sn_out='$sn_out',
        long='$long',
        lat='$lat',
        id_tb_user='$id_tb_user'
    WHERE id_tb_instalasi='$id_tb_instalasi'";

    // echo json_encode($sqlc);

    $qryc = $db->query($sqlc);

    if ($qryc) {
        $arrMsg = [
            'status' => true,
            'message' => 'Data berhasil disimpan',
            'redirect' => 'form_instalasi.php?id='.maxiline($id_tb_instalasi, 'e').'&s=e&token='.$_POST['token']
        ];
    } else {
        $arrMsg = [
            'status' => false,
            'message' => 'Data gagal disimpan!',
            'redirect' => ''
        ];
    }

    echo json_encode($arrMsg);

} elseif ($_POST['f'] == 'r') {
    // echo json_encode($id_tb_pendaftaran);
    
    // st_layanan
    if($st_instalasi == 1) {
        $st_layanan = "5"; // Pemasangan selesai, menunggu proses selanjutnya
    } elseif($st_instalasi == 2) {
        $st_layanan = "6"; // Pemasangan selesai, menunggu pengaktifan
    } elseif($st_instalasi == 3) {
        $st_layanan = "2"; // Diluar Jangkauan Layanan
    } else {
        $st_layanan = "4"; // Survey selesai, menunggu pemasangan
    }
    
    $sqld = "UPDATE tb_instalasi SET st_instalasi='$st_instalasi', ket_instalasi='$ket_instalasi' WHERE id_tb_instalasi='$id_tb_instalasi'";
    $qryd = $db->query($sqld);
    
    if ($qryd) {
        // update tb_pendaftaran
        $sqle = "UPDATE tb_pendaftaran SET st_layanan = '$st_layanan' where id_tb_pendaftaran = '$id_tb_pendaftaran'";
        $qrye = $db->query($sqle);

        if ($qrye) {
            $arrMsg = [
                'status' => true,
                'message' => 'Tanggapan berhasil dikirim!',
                'redirect' => ''
            ];  
        } else {
            $arrMsg = [
                'status' => false,
                'message' => 'Data pendaftaran gagal diupdate!',
                'redirect' => ''
            ];
        }
    } else {
        $arrMsg = [
            'status' => false,
            'message' => 'Tanggapan gagal dikirim!',
            'redirect' => ''
        ];
    }

    echo json_encode($arrMsg);
}
?>
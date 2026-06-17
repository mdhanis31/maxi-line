<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d H:i:s");

$id_tb_rencana = maxiline($_GET['id'], 'd');
$tipenya = maxiline($_GET['j'], 'd');

//echo $id_tr_invoice."<br>";
//print_r($_GET)."<br>";
//echo $id_user_pengirim."<br>";
//echo $maile;
//echo $id_tb_rencana."<br>";
//echo $tipenya;
//exit;

if($tipenya == "jadwal"){
$sqla = $db->fetchArray($db->query("select * from tb_rencana where id_tb_rencana = '$id_tb_rencana'"));
} else if($tipenya == "survey"){
$sqla = $db->fetchArray($db->query("select * from tb_survey where id_tb_survey = '$id_tb_rencana'"));	
} else if($tipenya == "instalasi"){
$sqla = $db->fetchArray($db->query("select * from tb_instalasi where id_tb_instalasi = '$id_tb_rencana'"));	
} else if($tipenya == "aktivasi"){
$sqla = $db->fetchArray($db->query("select * from tb_aktivasi where id_tb_aktivasi = '$id_tb_rencana'"));	
} else if($tipenya == "perawatan"){
$sqla = $db->fetchArray($db->query("select * from tb_perawatan where id_tb_perawatan = '$id_tb_rencana'"));	
} else if($tipenya == "pemutusan"){
$sqla = $db->fetchArray($db->query("select * from tb_pemutusan where id_tb_pemutusan = '$id_tb_rencana'"));	
} else if($tipenya == "laporan"){
$sqla = $db->fetchArray($db->query("select * from tb_laporan where id_tb_laporan = '$id_tb_rencana'"));
$sqlf = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$sqla[id_tb_user]'"));	
} else {
echo "<script>location.href='index.php';</script>";
exit();
}	
	
$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'"));

$id_usere = explode(',',$sqla['id_tb_user']);
foreach($id_usere as $idu) {
$sqlc = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$idu'"));
$namanyi[] = $sqlc['nm_user'];
}
$namae = implode(', ', $namanyi);

$sqld = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$sqlb[id_tb_user]'"));

if($tipenya == "jadwal"){
$judul = "Jadwal ".$st_rencana[intval("0".$sqla['rencana'])]." layanan MAXI LINE"; 
$isi = "Kami informasikan bahwa pada ".tglindo(date_format($sqla['tgl_rencana'], 'Y-m-d'))." ".date_format($sqla['tgl_rencana'], 'H:i:s')." WIB, kami menjadwalkan staf kami ".$namae." untuk melakukan ".$st_rencana[intval("0".$sqla['rencana'])]." layanan MAXI LINE ke rumah anda."; 	
} else if($tipenya == "survey"){
$judul = "Hasil survey layanan MAXI LINE"; 
$isi = "Kami informasikan bahwa pada ".tglindo(date_format($sqla['tgl_survey'], 'Y-m-d'))." ".date_format($sqla['tgl_survey'], 'H:i:s')." WIB, staf kami ".$namae." telah melakukan survey ke rumah anda.</p><p> Pengajuan layanan MAXI LINE anda ".$st_survey[intval("0".$sqla['st_survey'])]." dengan keterangan ".$sqla['ket_survey']."."; 	
} else if($tipenya == "instalasi"){
$judul = "Hasil instalasi layanan MAXI LINE"; 
$isi = "Kami informasikan bahwa pada ".tglindo(date_format($sqla['tgl_instalasi'], 'Y-m-d'))." ".date_format($sqla['tgl_instalasi'], 'H:i:s')." WIB, staf kami ".$namae." telah melakukan instalasi ke rumah anda.</p><p> Status instalasi layanan MAXI LINE anda ".$st_survey[intval("0".$sqla['st_instalasi'])]." dengan keterangan ".$sqla['ket_instalasi']."."; 	
} else if($tipenya == "aktivasi"){
$judul = "Hasil aktivasi layanan MAXI LINE"; 
$isi = "Kami informasikan bahwa pada ".tglindo(date_format($sqla['tgl_aktivasi'], 'Y-m-d'))." ".date_format($sqla['tgl_aktivasi'], 'H:i:s')." WIB, staf kami ".$namae." telah melakukan activasi layanan untuk rumah anda.</p><p> Status aktivasi layanan MAXI LINE anda ".$st_survey[intval("0".$sqla['st_aktivasi'])]." dengan keterangan ".$sqla['ket_aktivasi']."."; 	
} else if($tipenya == "perawatan"){
$judul = "Hasil perawatan / perbaikan layanan MAXI LINE"; 
$isi = "Kami informasikan bahwa pada ".tglindo(date_format($sqla['tgl_perawatan'], 'Y-m-d'))." ".date_format($sqla['tgl_perawatan'], 'H:i:s')." WIB, staf kami ".$namae." telah melakukan perawatan atau perbaikan layanan MAXI LINE untuk rumah anda.</p><p> Status perawatan atau perbaikan layanan MAXI LINE anda ".$st_survey[intval("0".$sqla['st_perawatan'])]." dengan keterangan ".$sqla['ket_perawatan']."."; 	
} else if($tipenya == "pemutusan"){
$judul = "Hasil pengambilan perangkat layanan MAXI LINE"; 
$isi = "Kami informasikan bahwa pada ".tglindo(date_format($sqla['tgl_pemutusan'], 'Y-m-d'))." ".date_format($sqla['tgl_pemutusan'], 'H:i:s')." WIB, staf kami ".$namae." telah melakukan pengambilan perangkat MAXI LINE di rumah anda, dikarenakan anda telah berhenti berlangganan layanan kami.</p><p> Status pengambilan perangkat MAXI LINE anda ".$st_survey[intval("0".$sqla['st_pemutusan'])]." dengan keterangan ".$sqla['ket_pemutusan']."."; 	
} else if($tipenya == "laporan"){
$judul = "Laporan ".$st_rencana[intval("0".$sqla['jns_laporan'])]." layanan MAXI LINE ID Pendaftaran ".$sqlb['kode_daftar']." a/n ".$sqlb['nama'].""; 
$isi = html_entity_decode($sqla['isi_laporan'])."<br><br><br>".tglindo(date_format($sqla['tgl_laporan'], 'Y-m-d'))."<br><br>".$sqlf['nm_user']; 	
}
	
	if($tipenya == "laporan"){
	$recipients = ['business.admin@manunggalintegrasi.com'];
	} else {
	$recipients = [$sqlb['email']];
	}
	
	$ccs = $mailccArr;
	if($tipenya == "laporan"){
	$bccs = ['business.admin@manunggalintegrasi.com'];
	} else {	
	$bccs = ['cs@maxi-line.net', 'business.admin@manunggalintegrasi.com', 'muhammad_dhani@manunggalintegrasi.com'];
	}
	$subject = "$judul";
	$mailContent = '
		<html>
		<head>
		<title>'.$judul.'</title>
		</head>
		<body>
		<p>Dengan Hormat</p>
		<p>'.$isi.'</p>
		<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		</body>
		</html>
	';
	//echo $isi;
	//exit;
	
	$sendMail = sendToMails($recipients, $ccs, $bccs, $subject, $mailContent);
	
	if($tipenya == "laporan"){
		if(!$sendMail){
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
		//exit();
		echo " <script>alert('email notifikasi gagal dikirim, hubungi administrator!');</script>
		<script>history.back;</script>";	
		exit();
		} else {
		$id_daftar = maxiline($sqla['id_tb_pendaftaran'], 'e');			
		echo "<script>location.href='pendaftaran_dtl.php?id=$id_daftar';</script>";
		exit();
		}
	} else {
		if(!$sendMail){
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
		//exit();
		echo " <script>alert('email notifikasi gagal dikirim, hubungi administrator!');</script>
		<script>history.back(-2);</script>";	
		exit();
		} else {
		$id_daftar = maxiline($sqla['id_tb_pendaftaran'], 'e');			
		 echo "<script>location.href='pendaftaran_dtl.php?id=$id_daftar';</script>";
		exit();
		}
	}	
?>   
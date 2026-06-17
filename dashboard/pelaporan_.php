<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";
//require_once 'plugins/PHPmailer/class.phpmailer.php';
//exit();

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");

$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
$id_tr_pelaporan = SafeSQL($_POST['id_tr_pelaporan']);
$jns_laporan = SafeSQL($_POST['jns_laporan']);
$subyek = SafeSQL($_POST['subyek']);
$isi = SafeSQL($_POST['isi']);
$code_pelaporan = SafeSQL($_POST['code_pelaporan']);

if($jns_laporan == 1){
$jnslaporan = "Pembayaran";
$lanjutnya = "Tim finance kami akan segera membalas laporan anda.";
	$sqle = $db->query("select * from tb_user where level_user = '4' and sts_delete = '1'");
	while($rowe = $db->fetchArray($sqle)) {
		$mail[] =  $rowe['email'];
	}
	$maile = "'" . implode("','", $mail) . "'";
} else if($jns_laporan == 2){
$jnslaporan = "Teknis";
$lanjutnya = "Tim teknis kami akan segera membalas laporan anda.";
	$sqle = $db->query("select * from tb_user where sts_delete = '1' and (level_user = '1' or level_user = '2' or level_user = '3')");
	while($rowe = $db->fetchArray($sqle)) {
	$mail[] =  $rowe['email'];	
	}
	$maile = "'" . implode("','", $mail) . "'";
}	

//echo $maile."<br>";
//exit();

if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
	//---------------------------------	
	if(empty($subyek)) {
    ?><script>
      alert('Subyek harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($isi)) {
    ?><script>
      alert('Isi tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	$sqla = $db->query ("insert into tr_pelaporan (id_tb_pendaftaran, subyek_laporan, isi_laporan, jns_laporan, code_pelaporan, sts_data) values ('$id_tb_pendaftaran', '$subyek', '$isi', '$jns_laporan', '$code_pelaporan', '2')");
	$akhir = $db->fetchArray($db->query("select SCOPE_IDENTITY() as lastid from tr_pelaporan"));
		
	if ($sqla) {	

	// insert gambar
	
	if(!empty($_FILES['file_laporan']['name'][0])) {
	//echo "upload";
	//exit;
	$fileCount = count($_FILES['file_laporan']['tmp_name']);
	//echo "$fileCount";
	//exit;		
	for ($i = 0; $i < $fileCount; $i++) {
	
	$namepp = $_FILES['file_laporan']['name'][$i];
	$temp = $_FILES['file_laporan']['tmp_name'][$i];    
    $type = $_FILES['file_laporan']['type'][$i];
    $size = $_FILES['file_laporan']['size'][$i];
    	
	$permissible_extension = array("jpg","png","jpeg","pdf");
	$ext = pathinfo($namepp, PATHINFO_EXTENSION);
	$test = getimagesize($temp);
    $width = $test[0];
    $height = $test[1];
	
	if (empty($_FILES)) {	
	?><script>
	alert('Pilih gambar yg akan diupload!');
	history.back();
	</script><?php
	exit();
	} else if (!in_array($ext, $permissible_extension)) {	
	?><script>
	alert('File harus dalam format jpg,jpeg,png/pdf!');
	history.back();
	</script><?php
	exit();
	} else  if ($size >= 5000000) {	
	?><script>
	alert('Size file dokumen tidak boleh lebih dari 5Mb!');
	history.back();
	</script><?php
	exit();
	} else if ($width > 1200 || $height > 1200) {
	?><script>
	alert('Ukuran file tidak boleh lebih dari 1200x1200 pixels!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/file_laporan/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
	exit();
	} else {
	
	//---------- input db
	$sqlb = $db->query ("insert into tr_file_pelaporan (id_tr_pelaporan, link_gbr) values ('$akhir[lastid]', 'dist/file_laporan/$namepp')");
	//echo "$sqlb";
	//exit;
	}
   }
	if ($sqlb) {
		
	$sqlc = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
	$sqld = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sqlc[id_tb_pendaftaran]'"));
	
	// kirim email.
	$recipients = [$sqld['email']];
	$cc = $mail;
	$bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintergrasi.com', 'sendayu@manunggalintegrasi.com'];
	$subject = "Pelaporan - $code_pelaporan";
	$mailContent = '
		<html>
		<head>
		<title>Pelaporan - '.$code_pelaporan.'</title>
		</head>
		<body>
		<p>Dengan Hormat</p>
		<p>Kami telah menerima pelaporan dengan detail sebagai berikut :<br>
		Kode Customer : '.$sqld['kode_daftar'].'<br>
		Nama : '.$sqld['nama'].'<br>
		Jenis Laporan : '.$jnslaporan.'<br>
		Subyek : '.$subyek.'<br>
		Isi : '.$isi.'
		</p>
		<p>'.$lanjutnya.'</p>
		<br>
		<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		</body>
		</html>
	';

	$sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);
	
	// $mail = new PHPMailer;
	
	// $alias = 'Maxi-Line';
	// $username = 'webapps_notif@manunggalsistemsejahtera.com';
	// $password = 'Sejahtera01*';
	// $dari = 'cs@maxi-line.net';
		
	// // Konfigurasi SMTP
	// $mail->isSMTP(true);
	// $mail->SMTPDebug  = 1;
	// $mail->Host = 'smtp.office365.com';
	// $mail->Port       = 587;
	// $mail->SMTPSecure = 'tls';
	// $mail->SMTPAuth   = true;
	// $mail->Username = $username;
	// $mail->Password = $password;
	
	// $mail->setFrom($dari, $alias);
    // $mail->addReplyTo($dari, $alias);
	
	// // Menambahkan penerima
	// $mail->addAddress($sqld['email']);
	
	// // Menambahkan beberapa penerima
	// // $mail->addAddress('penerima2@contoh.com');
	// // $mail->addAddress('penerima3@contoh.com');
	
	// // Menambahkan cc atau bcc 
	// $mail->addCC($maile);
	// $bccs = ['business.admin@manunggalintegrasi.com', 'muhammad_dhani@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
	
	// foreach ($bccs as $bcc) {
	// 	# code...
	// 	$mail->addBCC($bcc);
	// }
	// // $mail->addBCC('sendayu@manunggalintegrasi.com');
	// // Subjek email
	// $mail->Subject = "Pelaporan - $code_pelaporan";
	
	// // Mengatur format email ke HTML
	// $mail->isHTML(true);
	
	// $mailContent = '
	// 	<html>
	// 	<head>
	// 	<title>Pelaporan - '.$code_pelaporan.'</title>
	// 	</head>
	// 	<body>
	// 	<p>Dengan Hormat</p>
	// 	<p>Kami telah menerima pelaporan dengan detail sebagai berikut :<br>
	// 	Kode Customer : '.$sqld['kode_daftar'].'<br>
	// 	Nama : '.$sqld['nama'].'<br>
	// 	Jenis Laporan : '.$jnslaporan.'<br>
	// 	Subyek : '.$subyek.'<br>
	// 	Isi : '.$isi.'
	// 	</p>
	// 	<p>'.$lanjutnya.'</p>
	// 	<br>
	// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
	// 	</body>
	// 	</html>
	// ';

	// $mail->Body = $mailContent;
	// Menambahakn lampiran
	//$mail->addAttachment('lmp/file1.pdf');
	//$mail->addAttachment('lmp/file2.png', 'nama-baru-file2.png'); //atur nama baru

	if(!$sendMail){
		// echo 'Mailer Error: ' . $mail->ErrorInfo;
		// exit();
		echo " <script>alert('Email Pelaporan gagal dikirim, hubungi administrator!');</script>
		<script>history.back();</script>";	
		exit();
	} else {
		$sqlc = $db->query ("update tr_pelaporan set sts_data = '1' where id_tr_pelaporan = '$akhir[lastid]' ");
		echo "<script>alert('Laporan berhasil dikirim!');</script>
		<script>location.href='pelaporan_v.php';</script>";
		exit();
	}
	} else { 
	echo " <script>alert('File gagal diupload!');</script>
			<script>history.back();</script>";
			exit();
	}
	
  } else {
	$sqlc = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
	$sqld = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sqlc[id_tb_pendaftaran]'"));
	
	// kirim email.
	$recipients1 = [$sqld['email']];
	$cc1 = $mail;
	$bcc1 = ['muhammad_dhani@manunggalintegrasi.com','business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
	$subject1 = "Pelaporan - $code_pelaporan";
	$mailContent1 = '
		<html>
		<head>
		<title>Pelaporan - '.$code_pelaporan.'</title>
		</head>
		<body>
		<p>Dengan Hormat</p>
		<p>Kami telah menerima pelaporan dengan detail sebagai berikut :<br>
		Kode Customer : '.$sqld['kode_daftar'].'<br>
		Nama : '.$sqld['nama'].'<br>
		Jenis Laporan : '.$jnslaporan.'<br>
		Subyek : '.$subyek.'<br>
		Isi : '.$isi.'
		</p>
		<p>'.$lanjutnya.'</p>
		<br>
		<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		</body>
		</html>
	';

	$sendMail1 = sendToMails($recipients1, $cc1, $bcc1, $subject1, $mailContent1);
	// echo $maile;
	// exit();

	// $mail = new PHPMailer;
	
	// $alias = 'Maxi-Line';
	// $username = 'webapps_notif@manunggalsistemsejahtera.com';
	// $password = 'Sejahtera01*';
	// $dari = 'cs@maxi-line.net';
	
	// // Konfigurasi SMTP
	// $mail->isSMTP(true);
	// $mail->SMTPDebug  = 1;
	// $mail->Host = 'smtp.office365.com';
	// $mail->Port       = 587;
	// $mail->SMTPSecure = 'tls';
	// $mail->SMTPAuth   = true;
	// $mail->Username = $username;
	// $mail->Password = $password;
	
	// $mail->setFrom($dari, $alias);
    // $mail->addReplyTo($dari, $alias);
	
	// // Menambahkan penerima
	// $mail->addAddress($sqld['email']);
	
	// // Menambahkan beberapa penerima
	// // $mail->addAddress('penerima2@contoh.com');
	// // $mail->addAddress('penerima3@contoh.com');
	
	// // Menambahkan cc atau bcc 
	// $mail->addCC($maile);
	// $mail->addBCC('business.admin@manunggalintegrasi.com', 'muhammad_dhani@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com');
	// // Subjek email
	// $mail->Subject = "Pelaporan - $code_pelaporan";
	
	// // Mengatur format email ke HTML
	// $mail->isHTML(true);
	
	// $mailContent = '
	// 	<html>
	// 	<head>
	// 	<title>Pelaporan - '.$code_pelaporan.'</title>
	// 	</head>
	// 	<body>
	// 	<p>Dengan Hormat</p>
	// 	<p>Kami telah menerima pelaporan dengan detail sebagai berikut :<br>
	// 	Kode Customer : '.$sqld['kode_daftar'].'<br>
	// 	Nama : '.$sqld['nama'].'<br>
	// 	Jenis Laporan : '.$jnslaporan.'<br>
	// 	Subyek : '.$subyek.'<br>
	// 	Isi : '.$isi.'
	// 	</p>
	// 	<p>'.$lanjutnya.'</p>
	// 	<br>
	// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
	// 	</body>
	// 	</html>
	// ';

	// $mail->Body = $mailContent;
	// Menambahakn lampiran
	//$mail->addAttachment('lmp/file1.pdf');
	//$mail->addAttachment('lmp/file2.png', 'nama-baru-file2.png'); //atur nama baru
	if(!$sendMail1){
		// echo 'Mailer Error: ' . $mail->ErrorInfo;
		// exit();
		echo " <script>alert('email Pelaporan gagal dikirim, hubungi administrator!');</script>
		<script>history.back();</script>";	
		exit();
	} else {
		$sqlc = $db->query ("update tr_pelaporan set sts_data = '1' where id_tr_pelaporan = '$akhir[lastid]' ");
		echo "<script>alert('Laporan berhasil dikirim!');</script>
			  <script>location.href='pelaporan_v.php';</script>";
			  exit();
	}
	  }
		} else { 
		echo " <script>alert('Laporan gagal dikirim!');</script>
			   <script>history.back();</script>";
				exit();
	   } 	
	 } 	
	} else {
   // log potential CSRF attack.
	  echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	  <script>history.back();</script>";
	  exit();
	}	
   } else if ($_POST['j'] == 'b') {
   
    unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {  
	
//---------------------------------	
	if(empty($judul)) {
    ?><script>
      alert('Judul harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($note)) {
    ?><script>
      alert('Isi note tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
	$sqla = $db->query ("update tb_note set judul='$judul', note='$note' where id_tb_note='$id_tb_note'");
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Data berhasil diupdate!');</script>
			  <script>location.href='note_v.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Data gagal diupdate!');</script>
			   <script>history.back();</script>";
				exit();
	   } 	
	 } 
	} else {
// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
 } 
} else if ($_GET['g'] == 'h') {

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_GET['token']==$kodeaman) {

	$id_tr_file_pelaporan = maxiline(SafeSQL($_GET['id']), 'd');
	$sqla = $db->fetchArray($db->query("SELECT * from tr_file_pelaporan where id_tr_file_pelaporan = '$id_tr_file_pelaporan'"));
	unlink("$sqla[link_gbr]");
	$sqlb = "delete from tr_file_pelaporan where id_tr_file_pelaporan='$id_tr_file_pelaporan'";
    $resb = $db->query($sqlb);
    if ($sqlb) {
		$idne = maxiline($sqla['id_tr_pelaporan'], 'e');
		echo "<script>alert('File berhasil dihapus!');</script>
			  <script>location.href='pelaporan_add.php?id=$idne';</script>";
			  exit();
		} else { 
		echo " <script>alert('File gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 

 } else {
	// log potential CSRF attack.
	echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
	exit();
 } 
} else if($_POST['set'] == "pic"){
	$id_tb_user = SafeSQL($_POST['id_tb_user']);
	
	$sqla = $db->query ("update tr_pelaporan set id_tb_user = '$id_tb_user' where id_tr_pelaporan = '$id_tr_pelaporan'");
	$sqlb = $db->fetchArray( $db->query ("select * from tr_pelaporan where id_tr_pelaporan = '$id_tr_pelaporan'"));
	$id = maxiline($sqlb['id_tr_pelaporan'], 'e');
	$code = maxiline($sqlb['code_pelaporan'],'e');
	
	 if ($sqla) {		
		echo "<script>alert('PIC berhasil ditambahkan!');</script>
			  <script>location.href='pelaporan_add.php?id=$id&i=$code';</script>";
			  exit();
		} else { 
		echo " <script>alert('PIC gagal ditambahkan!');</script>
			   <script>history.back();</script>";
				exit();
	   } 	
} else if($_POST['ubah'] == "pic"){
	$id_tb_user = SafeSQL($_POST['id_tb_user']);
	
	$sqla = $db->query ("update tr_pelaporan set id_tb_user = '$id_tb_user' where id_tr_pelaporan = '$id_tr_pelaporan'");
	$sqlb = $db->fetchArray( $db->query ("select * from tr_pelaporan where id_tr_pelaporan = '$id_tr_pelaporan'"));
	$id = maxiline($sqlb['id_tr_pelaporan'], 'e');
	$code = maxiline($sqlb['code_pelaporan'],'e');
	
	 if ($sqla) {		
		echo "<script>alert('PIC berhasil diupdate!');</script>
			  <script>location.href='pelaporan_add.php?id=$id&i=$code';</script>";
			  exit();
		} else { 
		echo " <script>alert('PIC gagal diupdate!');</script>
			   <script>history.back();</script>";
				exit();
	   } 	
}		
?>   
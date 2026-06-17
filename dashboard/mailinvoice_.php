<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";
// require_once 'plugins/PHPmailer/class.phpmailer.php';

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d H:i:s");

$id_tr_invoice = maxiline($_GET['id'], 'd');

//echo $id_tr_invoice."<br>";
//print_r($id_user_tujuan)."<br>";
//echo $id_user_pengirim."<br>";
//echo $maile;
//exit;

$sqla = $db->fetchArray($db->query("select * from tr_invoice where id_tr_invoice = '$id_tr_invoice'"));
$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'"));

//echo $sqlb['email']."<br>";
//exit;

// kirim email
$recipients = [$sqlb['email']];
$cc = ['cs@maxi-line.net'];
$bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com'];
$subject = "INVOICE MAXI LINE - $sqla[no_invoice]";

$linkp = "http://maxi-line.net/dashboard/invoice_p.php?id=".maxiline($id_tr_invoice, 'e')."&token=ee3699b71069491a9bc15b2de313c50a";

$mailContent = '
	<html>
	<head>
	<title>INVOICE MAXI LINE - '.$sqla['no_invoice'].'</title>
	</head>
	<body>
	<p>Dengan Hormat</p>
	<br>
	<p>Kami informasikan bahwa pada '.tglindo(date_format($sqla['tgl_invoice'], 'Y-m-d')).' billing kami telah menerbitkan invoice untuk layanan yang anda gunakan. <p>Silahkan login melalui tautan berikut <a href="http://maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="http://maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a> untuk dapat melihat detil invoice, atau anda dapat melihat langsung melalui tautan berikut <a href="'.$linkp.'" target="_blank" data-saferedirecturl="'.$linkp.'"><b>--- '.$sqla['no_invoice'].' ---</b></a></p>
	<p>Mohon untuk melakukan pembayaran sebelum tanggal '.tglindo(date_format($sqla['tgl_expired'], 'Y-m-d')).'</p>
	<br>
	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
	</body>
	</html>
';
$sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);

if(!$sendMail){
	//echo 'Mailer Error: ' . $mail->ErrorInfo;
	//exit();
	echo " <script>alert('Email respon gagal dikirim, hubungi administrator!');</script>
	<script>history.back(-2);</script>";
	exit();
} else {
	echo "<script>location.href='invoice_v.php';</script>";
	exit();
}

// kirim email lama
// $mail = new PHPMailer;

// $alias = 'Maxi-Line';
// $username = 'business.admin@manunggalintegrasi.com';
// $password = 'M4nungg4l01*';
// $dari = 'maxisupport@maxi-line.net';

// Konfigurasi SMTP
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

// $mail->addAddress($sqlb['email']);
	
// Menambahkan beberapa penerima
//$mail->addAddress('penerima2@contoh.com');
//$mail->addAddress('penerima3@contoh.com');

// Menambahkan cc atau bcc 
//$mail->addCC($maile);
// $mail->addBCC('business.admin@manunggalintegrasi.com');
// Subjek email
// $mail->Subject = "INVOICE MAXI LINE - $sqla[no_invoice]";

// Mengatur format email ke HTML
// $mail->isHTML(true);

// $linkp = "http://maxi-line.net/dashboard/invoice_p.php?id=".maxiline($id_tr_invoice, 'e')."&token=ee3699b71069491a9bc15b2de313c50a";

// $mailContent = '
// 	<html>
// 	<head>
// 	<title>INVOICE MAXI LINE - '.$sqla['no_invoice'].'</title>
// 	</head>
// 	<body>
// 	<p>Dengan Hormat</p>
// 	<br>
// 	<p>Kami informasikan bahwa pada '.tglindo(date_format($sqla['tgl_invoice'], 'Y-m-d')).' billing kami telah menerbitkan invoice untuk layanan yang anda gunakan. <p>Silahkan login melalui tautan berikut <a href="http://maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="http://maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a> untuk dapat melihat detil invoice, atau anda dapat melihat langsung melalui tautan berikut <a href="'.$linkp.'" target="_blank" data-saferedirecturl="'.$linkp.'"><b>--- '.$sqla['no_invoice'].' ---</b></a></p>
// 	<p>Mohon untuk melakukan pembayaran sebelum tanggal '.tglindo(date_format($sqla['tgl_expired'], 'Y-m-d')).'</p>
// 	<br>
// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
// 	</body>
// 	</html>
// ';

// $mail->Body = $mailContent;
	
// if(!$mail->send()){
// 	//echo 'Mailer Error: ' . $mail->ErrorInfo;
// 	//exit();
// 	echo " <script>alert('email respon gagal dikirim, hubungi administrator!');</script>
// 	<script>history.back(-2);</script>";
// 	exit();
// } else {
// 	echo "<script>location.href='invoice_v.php';</script>";
// 	exit();
// }
?>   
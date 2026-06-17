<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";
// require_once 'plugins/PHPmailer/class.phpmailer.php';

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d H:i:s");

$id_user_tu = $_GET['id'];
$id_user_pengirim = $_SESSION['id_tb_user'];

$id_user_tujuan = explode(",",$id_user_tu);

//echo $id_user_tujuan."<br>";
//print_r($id_user_tujuan)."<br>";
//echo $id_user_pengirim."<br>";


$ids = join("','",$id_user_tujuan);  
$sqla = $db->query("select * from tb_user where id_tb_user IN ('$ids')");
$maili = array();

while($rowa = $db->fetchArray($sqla)) {
	$maili[] =  $rowa['email'];	
}
		
//echo $maile;
//exit;

$sqlb = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$id_user_pengirim'"));

// kirim email
$recipients = $maili;
$cc = ['cs@maxi-line.net'];
$bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com'];
$subject = "Pesan Maxi-Line";
$mailContent = '
	<html>
	<head>
	<title>Pesan dari - '.$sqlb['nm_user'].'</title>
	</head>
	<body>
	<p>Dengan Hormat</p>
	<p>Anda mendapat pesan dari '.$sqlb['nm_user'].', login ke aplikasi maxi-line untuk melihat dan membalas pesan tersebut.</p>
	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
	</body>
	</html>
';

$sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);

if(!$sendMail){
	//echo 'Mailer Error: ' . $mail->ErrorInfo;
	//exit();
	echo " <script>alert('email respon gagal dikirim, hubungi administrator!');</script>
	<script>history.back(-2);</script>";
	exit();
} else {
	echo "<script>location.href='pesan_v.php';</script>";
	exit();
}

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
	
// foreach($maili as $maile){
// 	$mail->addAddress($maile);

	// Menambahkan beberapa penerima
	//$mail->addAddress('penerima2@contoh.com');
	//$mail->addAddress('penerima3@contoh.com');
	
	// Menambahkan cc atau bcc 
	//$mail->addCC($maile);
	// $mail->addBCC('business.admin@manunggalintegrasi.com');
	// Subjek email
	// $mail->Subject = "Pesan Maxi-Line";
	
	// Mengatur format email ke HTML
	// $mail->isHTML(true);
	
	// $mailContent = '
	// 	<html>
	// 	<head>
	// 	<title>Pesan dari - '.$sqlb['nm_user'].'</title>
	// 	</head>
	// 	<body>
	// 	<p>Dengan Hormat</p>
	// 	<p>Anda mendapat pesan dari '.$sqlb['nm_user'].', login ke aplikasi maxi-line untuk melihat dan membalas pesan tersebut.</p>
	// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
	// 	</body>
	// 	</html>
	// ';

// 	$mail->Body = $mailContent;
// }

// if(!$mail->send()){
// 	//echo 'Mailer Error: ' . $mail->ErrorInfo;
// 	//exit();
// 	echo " <script>alert('email respon gagal dikirim, hubungi administrator!');</script>
// 	<script>history.back(-2);</script>";
// 	exit();
// } else {	
// 	echo "<script>location.href='pesan_v.php';</script>";
// 	exit();
// }
?>

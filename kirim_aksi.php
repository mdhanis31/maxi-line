<?php
@session_start();

//print_r($_SESSION);

//memanggil library phpmailer
// require_once 'plugins/PHPmailer/class.phpmailer.php';
include 'dashboard/include/config.php';

$kodeaman = $_SESSION['token'];
$nama = $_POST['name'];
$email = $_POST['email'];
$subjek = $_POST['subject'];
$pesan = $_POST['message'];
$nama_lengkap = $_POST['nama_lengkap'];
//echo "Kode aman = ".md5($_POST['capjay']).'a4xn'."<br>";
//echo "cookie ".$_COOKIE['maxilinecookie'];
//exit();
//print_r($_POST);
//exit();

	
$filter = array("www","http","https","robot","crypto","cryptaxbot","loli","lolita","loly","porn","slot","hentai","bot","jackpot","gambling","gacor","mega win","aloha","test");
	
foreach ($filter as $filters) {
	//if (strstr($string, $url)) { // mine version
	if (strpos($pesan, $filters) !== FALSE) { // Yoshi version
		echo " <script>alert('Pesan berhasil dikirim!');</script>
		<script>location.href='index.php';</script>";
		exit();
	}
}

foreach ($filter as $filters) {
	//if (strstr($string, $url)) { // mine version
	if (strpos($subjek, $filters) !== FALSE) { // Yoshi version
		echo " <script>alert('Pesan berhasil dikirim!');</script>
		<script>location.href='index.php';</script>";
		exit();
	}
}

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
	if(!empty($_POST['nama_lengkap'])) {
	 ?><script>
		alert('Kesalahan system, silahkan ulangi kembali');
		location.href='index.php';
		</script><?php
		exit();
	} elseif(md5($_POST['capjay']).'a4xn' != $_COOKIE['maxilinecookie']) {
    ?><script>
		alert('Captcha Salah');
		history.back();
		</script><?php
	  exit();
	} elseif(empty($nama)) {
    ?><script>
		alert('Nama harus diisi');
		history.back();
		</script><?php
	  exit();
	} elseif(empty($email)) {
    ?><script>
		alert('Email harus diisi');
		history.back();
		</script><?php
		exit();
	} elseif(empty($pesan)) {
    ?><script>
		alert('Pesan harus diisi');
		history.back();
		</script><?php
		exit();
	} elseif(empty($subjek)) {
    ?><script>
		alert('Subjek harus diisi');
		history.back();
		</script><?php
		exit();
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))  {
	 ?><script>
		alert('email tidak valid');
		history.back();
		</script><?php
		exit();
	} else {
	
		$isipesan = "<p>Anda telah mendapatkan pesan dengan informasi sebagai berikut,</p><p>Nama : ".$nama."<br>Email : ".$email."<br>Subyek : ".$subjek."<br>Pesan : ".$pesan."</p><br><p style=\"font-size: small;\"><em>Pesan ini berasal dari form kontak di www.maxi-line.net</em></p>";
		
		// kirim email.
		$recipients = ['cs@maxi-line.net', 'customerservice@manunggalgroup.com'];
		$cc = 'business.admin@manunggalintegrasi.com';
		$bcc = ['muhammad_dhani@manunggalintegrasi.com'];
		$subject = $subjek;
		$mailContent = $isipesan;

		$result = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);
		// exit();

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
		//	$mail->SMTPSecure = 'tls';
		//	$mail->Port = 587;
	
		// $mail->setFrom($dari, $alias);
		// $mail->addReplyTo($dari, $alias);
		
		// Menambahkan penerima
		// $mail->addAddress('techsupport@manunggalintegrasi.com');
		// $mail->addAddress('customerservice@manunggalintegrasi.com');
		
		// Menambahkan beberapa penerima
		//$mail->addAddress('solution.engineer@maxi-line.net');
		//$mail->addAddress('penerima3@contoh.com');
		
		// Menambahkan cc atau bcc 
		//$mail->addCC('techsupport@manunggalintegrasi.com');
		// $mail->addBCC('business.admin@manunggalintegrasi.com');
	
		// Subjek email
		// $mail->Subject = "$subjek";
		
		// Mengatur format email ke HTML
		// $mail->isHTML(true);
		
		// $mailContent = "$isipesan";

		// $mail->Body = $mailContent;
		// Menambahakn lampiran
		//$mail->addAttachment('lmp/file1.pdf');
		//$mail->addAttachment('lmp/file2.png', 'nama-baru-file2.png'); //atur nama baru
		
		// if(!$mail->send()){
		// $arrResult['response'] = 'success';
		// } else {
		// $arrResult['response'] = 'error';
		// echo "There was a problem sending the form.: " . $mail->ErrorInfo;
		// exit;
		// }
		
		# kirim email.	
		if(!$result){
			//	echo " <script>alert('Pesan gagal dikirim $mail->ErrorInfo!');</script>
			echo " <script>alert('Pesan gagal dikirim!');</script>
			<script>history.back();</script>";
			exit();
		} else {
			echo " <script>alert('Pesan berhasil dikirim!');</script>
			<script>location.href='index.php';</script>";
			exit();
		}
	}
} else {
	// log potential CSRF attack.
	echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
	exit();
}
 

?>
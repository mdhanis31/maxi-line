<?php
@session_start();
//print_r($_SESSION);

include "include/config.php";
include "include/DbConnector.php";
require_once 'plugins/PHPmailer/class.phpmailer.php';
//exit();

$db = new DbConnector();

$sekarang = date("Y-m-d H:i:s");

$kodeaman = $_SESSION['token'];
//print_r($_POST);
//echo "$kodeaman";
//exit();
if ($_POST['j'] == 'b') {
	
	if(md5($_POST['capjay']).'a4xn' != $_COOKIE['maxilinecookie']) {
	?><script>
		alert('Captcha Salah');
		history.back();
		</script><?php
		exit();
	}

	$email = SafeSQL($_POST['email']);

	$sql = "SELECT * FROM tb_user WHERE email='$email'";
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	$total = $db->queryNumRows($sql);
	$jml = $db->getNumRows($total);
	//$jmlx = $db->affectedRows($res);

	//echo "jumlahnya :".$jml."<br>";
	//echo "jumlahnyo :".$jmlx."<br>";
	//echo $row['email']."<br>";
	//echo $email."<br>";
	//print_r($_POST);
	//exit();

	if ($jml == 0) {  
?><script>
      alert('Email belum terdaftar!');
      location.href='daftar.php';
      </script><?php
	  exit();
	} else {
		
		$cdauth = $row['id_tb_user'].",".$row['email'].",".$sekarang;
		$maskcdauth = maxiline( $cdauth, 'e' );
	
		//echo $maskcdauth;
		//exit;
		
		// kirim email. 
	
		$mail = new PHPMailer;
		
		$alias = 'Maxi-Line';
		$username = 'webapps_notif@manunggalsistemsejahtera.com';
		$password = 'Sejahtera01*';
		$dari = 'cs@maxi-line.net';

		// Konfigurasi SMTP
		$mail->isSMTP(true);
		$mail->SMTPDebug  = 1;
		$mail->Host = 'smtp.office365.com';
		$mail->Port       = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth   = true;
		$mail->Username = $username;
		$mail->Password = $password;
		
		$mail->setFrom($dari, $alias);
		$mail->addReplyTo($dari, $alias);
		
		// Menambahkan penerima
		$mail->addAddress($email);
	
		// Menambahkan cc atau bcc 
		$mail->addCC('business.admin@manunggalintegrasi.com');
		$mail->addCC('muhammad_dhani@manunggalintegrasi.com');
		
		//$mail->addBCC('bcc@contoh.com');
		// Subjek email
		$mail->Subject = "Recovery Password MAXI-LINE";
		
		// Mengatur format email ke HTML
		$mail->isHTML(true);
	
		$mailContent = '
			<html>
			<head>
			<title>Recovery Password anda</title>
			</head>
			<body>
			<p>Dengan Hormat</p>
			<br>
			<p>Berikut kami kirimkan link untuk reset password atas permintaan anda melalui fitur recovery password. </p>		   
			<p>Klik link berikut <a href="http://www.maxi-line.net/dashboard/reset_pass.php?i='.$maskcdauth.'" target="_blank" data-saferedirecturl="http://localhost:8080/maxi-line/dashboard/reset_pass.php?i='.$maskcdauth.'"><b>--- RESET PASSWORD MAXI-LINE ---</b></a> untuk mereset password anda. </p> 
			<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
			</body>
			</html>
		';

		$mail->Body = $mailContent;
		// Menambahakn lampiran
		//$mail->addAttachment('lmp/file1.pdf');
		//$mail->addAttachment('lmp/file2.png', 'nama-baru-file2.png'); //atur nama baru
		
		// # kirim email.	
		if(!$mail->send()){
			echo " <script>alert('email recovery password gagal dikirim, hubungi administrator!');</script>
			<script>history.back();</script>";	
			exit();
			//echo 'Mailer Error: ' . $mail->ErrorInfo;
			//exit();
		} else {
		?><script>
			alert('From Reset password sudah dikirimkan ke email anda!');
			location.href='login.php';
		</script><?php
		exit();
			//	echo 'Pesan telah terkirim';
			//	exit();
		}			
	}
} else {
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {
		// username and password sent from form
		$myusername = SafeSQL($_POST['username']);
		$mypassword = md5($_POST['password']);
		$sekarang = date("Y-m-d H:i:s");

		//print_r($_POST);
		//echo "$mypassword";
		//exit();
		if(empty($_POST['username'])) {
		?><script>
		alert('Username harus diisi');
		location.href='index.php';
		</script><?php
		exit();
		} else if(md5($_POST['capjay']).'a4xn' != $_COOKIE['maxilinecookie']) {
		?><script>
		alert('Captcha Salah');
		history.back();
		</script><?php
		exit();
		} else if(empty($_POST['password'])) {
		?><script>
		alert('Password harus diisi');
		location.href='index.php';
		</script><?php
		exit();
		} else {

			$sql = "SELECT * FROM tb_user WHERE username='$myusername' and password='$mypassword' and sts_delete = '1'";
			$res = $db->query($sql);
			$row = $db->fetchArray($res);

			if ($row!=0) {
				session_start();	
				$_SESSION['nama']     = $row['nm_user'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['level_user'] = $row['level_user'];	
				$_SESSION['id_tb_user']  = $row['id_tb_user'];

				if(!empty($row['id_tb_pendaftaran'])){
					$_SESSION['id_tb_pendaftaran']  = $row['id_tb_pendaftaran'];	
				}	
				//$sqld = $db->query("UPDATE tb_user SET current_login='$sekarang' WHERE nm_user = '$myusername'") ;
				//print_r($_POST);
				//exit();
				?>
				<script>document.location.href="login.php"</script>
				<?php exit();
			} else {
				echo "<script>alert('User atau Password salah atau Anda belum terdaftar, silahkan hubungi administrator!'); document.location.href=\"login.php\"</script>";
				exit();
			}
		}
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
}
?>
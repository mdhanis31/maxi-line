<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";

//memanggil library phpmailer
// require_once 'plugins/PHPmailer/class.phpmailer.php';

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token']; 

$reg_code = SafeSQL($_POST['reg_code']);
$nama = SafeSQL($_POST['nama']);
$telp = SafeSQL($_POST['telp']);
$email = SafeSQL($_POST['email']);
$tipe_identitas = SafeSQL($_POST['tipe_identitas']);
$no_identitas = SafeSQL($_POST['no_identitas']);
$alamat = SafeSQL($_POST['alamat']);
$telepon = SafeSQL($_POST['telepon']);
$id_tb_lokasi = SafeSQL($_POST['id_tb_lokasi']);
$st_layanan = SafeSQL($_POST['st_layanan']);
$id_tb_paket = SafeSQL($_POST['id_tb_paket']);
$id_data_kd_pos = SafeSQL($_POST['id_data_kd_pos']);

//print_r($_POST)."<br>";
//print_r($id_proyek_lokasis)."<br>";	
//print_r($alamat_usahas)."<br>";
//exit;

//$alamat_perseroan = SafeSQL($_POST['alamat_perseroan']);
//$rt_rw_perseroan = SafeSQL($_POST['rt_rw_perseroan']);
//$perseroan_daerah_id = SafeSQL($_POST['perseroan_daerah_id']);
//$kode_pos_perseroan = SafeSQL($_POST['kode_pos_perseroan']); 

//$rowx = $db->fetchArray($db->query("select * from v_wilayah_administrasi where id_desa ='$perseroan_daerah_id'"));
	
//$alamat_lengkap = $alamat_perseroan.", ".$rowx['desa'].", rt/rw. ".$rt_rw_perseroan.", ".$rowx['kecamatan'].", ".$rowx['kabupaten'].", ".$rowx['provinsi'].", ".$kode_pos_perseroan;
//print_r($_POST)."<br>";
//echo $alamat_lengkap;
//exit();

	if(md5($_POST['capjay']).'a4xn' != $_COOKIE['maxilinecookie']) {
	?><script>
	  alert('Captcha Salah');
	  history.back();
	  </script><?php
	  exit();
	}

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
	$sql = "SELECT * FROM tb_pendaftaran where email = '$email'";
	$total = $db->queryNumRows($sql);	
	$jml = $db->getNumRows($total);
		
	if ($jml == 0) {	
		
		//---------------------------------	
		if(empty($email)) {
		?><script>
		alert('Email harus diisi');
		history.back();
		</script><?php
		exit();
		} else if((!empty($email)) && (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
		?><script>
		alert('Email tidak valid');
		history.back();
		</script><?php
		exit();
		} else if(empty($tipe_identitas)) {
		?><script>
		alert('Tipe identitas harus dipilih');
		history.back();
		</script><?php
		exit();
		} else if(empty($no_identitas)) {
		?><script>
		alert('No identitas harus diisi');
		history.back();
		</script><?php
		exit();
		} else if(empty($nama)) {
		?><script>
		alert('Nama harus diisi');
		history.back();
		</script><?php
		exit();
		} else if(empty($id_tb_lokasi)) {
		?><script>
		alert('Area / Kompleks harus dipilih');
		history.back();
		</script><?php
		exit();
		} else if(empty($alamat)) {
		?><script>
		alert('Alamat harus diisi');
		history.back();
		</script><?php
		exit();
		} else if(empty($telp)) {
		?><script>
		alert('Nomor telepon harus diisi');
		history.back();
		</script><?php
		exit();
		} else if(empty($id_tb_paket)) {
		?><script>
		alert('Nama paket harus dipilih!');
		history.back();
		</script><?php
		exit();
		} else {
			// insert database
			$sqla = "insert into tb_pendaftaran (kode_daftar, email, nama, telp, alamat, id_tb_lokasi, st_layanan, tipe_identitas, no_identitas, id_tb_paket, id_data_kd_pos) values ('$reg_code', '$email', '$nama', '$telp', '$alamat', '$id_tb_lokasi', '$st_layanan', '$tipe_identitas', '$no_identitas', '$id_tb_paket', '$id_data_kd_pos')";
			$resa = $db->query($sqla);

			if ($resa) {
			
				/* 	$akhir = $db->fetchArray($db->query("select SCOPE_IDENTITY() as lastid from tb_pelanggan"));
				
				include('dblog/DbConnector2.php');
				$ua=getBrowser();
				$browser= $ua['name'] . " " . $ua['version'];
				//$ip = getUserIP();
				$ip = $_SERVER['REMOTE_ADDR'];
				$os = $ua['platform'];

				$db = new DbConnector2();		
				$sqlb =  $db->query("insert into tr_daftar (kode_aplikasi, id_tb_pelanggan, sistem_os, browser, ip) values ('4', '$akhir[lastid]', '$os', '$browser', '$ip')");
				*/
										
				// kirim email.
				$recipients = [$email];
				$cc = ['cs@maxi-line.net'];
				$bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com'];
				$subject = "Pendaftaran MAXI LINE - $reg_code";
				$attach = '';
				$mailContent = '
					<html>
					<head>
					<title>Pendaftaran MAXI LINE - '.$reg_code.'</title>
					</head>
					<body>
					<p>Dengan Hormat</p>
					<p>Anda telah mendaftar untuk berlangganan jaringan internet MAXI LINE dengan kode pendaftaran : <strong><a rel="nofollow" style="text-decoration:none; color:#333">'.$reg_code.'</a></strong>.</p>
					<p>Teknisi kami akan segera melakukan survei untuk menentukan apakah area anda masuk dalam jangkauan layanan kami. Anda dapat menelusuri status pendaftaran anda melalui link berikut ini :</p>
					<p><a href="http://maxi-line.net/cek_pendaftaran.php" target="_blank" data-saferedirecturl="http://maxi-line.net/cek_pendaftaran.php"><b>--- KLIK DISINI ---</b></a></p>
					<p>Masukkan kode pendaftaran <b>'.$reg_code.'</b> dan email anda pada form yang tersedia. 
					</p>
					<p>Hasil survei dan kelanjutan proses pendaftaran akan kami informasikan melalui email atau nomor telepon yang telah anda daftarkan</p>
					
					<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
					</body>
					</html>
				';

				$sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);

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
				
				// // Menambahkan penerima
				// $mail->addAddress($email);
				
				// Menambahkan beberapa penerima
				//$mail->addAddress('penerima2@contoh.com');
				//$mail->addAddress('penerima3@contoh.com');
				
				// Menambahkan cc atau bcc 
				// $mail->addCC('techsupport@manunggalintegrasi.com');
				// $mail->addBCC('business.admin@manunggalintegrasi.com');
				// Subjek email
				// $mail->Subject = "Pendaftaran MAXI LINE - $reg_code";
		
				// Mengatur format email ke HTML
				// $mail->isHTML(true);
		
				// $mailContent = '
				// 	<html>
				// 	<head>
				// 	<title>Pendaftaran MAXI LINE - '.$reg_code.'</title>
				// 	</head>
				// 	<body>
				// 	<p>Dengan Hormat</p>
				// 	<br>
				// 	<p>Anda telah mendaftar untuk berlangganan jaringan internet MAXI LINE dengan kode pendaftaran : <strong><a rel="nofollow" style="text-decoration:none; color:#333">'.$reg_code.'</a></strong>.</p>
				// 	<p>Teknisi kami akan segera melakukan survei untuk menentukan apakah area anda masuk dalam jangkauan layanan kami. Anda dapat menelusuri status pendaftaran anda melalui link berikut ini :</p>
				// 	<p><a href="http://maxi-line.net/cek_pendaftaran.php" target="_blank" data-saferedirecturl="http://maxi-line.net/cek_pendaftaran.php"><b>--- KLIK DISINI ---</b></a></p>
				// 	<p>Masukkan kode pendaftaran <b>'.$reg_code.'</b> pada form yang tersedia. 
				// 	</p>
				// 	<p>Hasil survei dan kelanjutan proses pendaftaran akan kami informasikan melalui email atau nomor telepon yang telah anda daftarkan</p>
				// 	<br>
				// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
				// 	</body>
				// 	</html>
				// ';

				// $mail->Body = $mailContent;
				// Menambahakn lampiran
				//$mail->addAttachment('lmp/file1.pdf');
				//$mail->addAttachment('lmp/file2.png', 'nama-baru-file2.png'); //atur nama baru
		
				// # kirim email.
				if(!$sendMail){
					//echo 'Mailer Error: ' . $mail->ErrorInfo;
					//exit();
					echo " <script>alert('email pendaftaran gagal dikirim, hubungi administrator!');</script>
					<script>history.back();</script>";
					exit();
				} else {
					echo " <script>alert('Pendaftaran berhasil, cek email anda untuk informasi selanjutnya!');</script>
					<script>location.href='../index.php';</script>";
					exit();
					//	echo 'Pesan telah terkirim';
					//	exit();
				}
			} else {
				echo " <script>alert('Mohon maaf pendaftaran anda tidak berhasil!');</script>
				<script>history.back();</script>";
				exit();
			}
			// insert database
		}
	} else {
	 ?><script>
      alert('Email sudah terdaftar!');
	  history.back();
      </script><?php
	  exit();
	}
} else {
	// log potential CSRF attack.
	echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	   <script>history.back();</script>";
	exit();
}
?>
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

$id_tr_pelaporan = SafeSQL($_POST['id_tr_pelaporan']);
$st_respon = SafeSQL($_POST['st_respon']);
$respon = SafeSQL($_POST['respon']);
$sts_laporan = SafeSQL($_POST['sts_laporan']);
$tipe_respon = SafeSQL($_POST['tipe_respon']);
$tgl_rencana = date("Y-m-d", strtotime(SafeSQL($_POST['tgl_rencana'])));
$waktu_rencana = SafeSQL($_POST['waktu_rencana']);
$timesc = $tgl_rencana." ".$waktu_rencana;

$sqle = $db->fetchArray($db->query("select * from tr_pelaporan where id_tr_pelaporan = '$id_tr_pelaporan'"));
$sqld = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sqle[id_tb_pendaftaran]'"));

if($sqle['jns_laporan'] == 1){
	$sqlf = $db->query("select * from tb_user where level_user = '4'");
	while($rowf = $db->fetchArray($sqlf)) {
	$maili[] =  $rowf['email'];
	}		
	$dari = "Finance";
} elseif($sqle['jns_laporan'] == 2){
	$sqlf = $db->query("select * from tb_user where level_user = '2' or level_user = '3'");
	while($rowf = $db->fetchArray($sqlf)) {
	$maili[] =  $rowf['email'];
	}
	$dari = "Support";
}

//print_r($_POST)."<br>";
//exit();
if ($_POST['r'] == 'n') {
		
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {
		
		//---------------------------------	
		if(empty($respon)) {
			?><script>
			alert('Respon tidak boleh ksong!');
			history.back();
			</script><?php
			exit();
		} else {
		
			if(!empty($sts_laporan)){
				$sql = $db->query ("update tr_pelaporan set sts_laporan = '$sts_laporan' where id_tr_pelaporan = '$id_tr_pelaporan'");	
			}
		
			$sqla = $db->query("insert into tr_respon (id_tr_pelaporan, respon, id_tb_user, st_respon, tipe_respon, tgl_respon) values ('$id_tr_pelaporan', '$respon', '$_SESSION[id_tb_user]', '$st_respon', '$tipe_respon', '$timesc')");
		
			//echo $sqla;
			//exit;
			$akhir = $db->fetchArray($db->query("select SCOPE_IDENTITY() as lastid from tr_respon"));

			// insert gambar
			if ($sqla) {
		
				//upload gambar
				if(!empty($_FILES['file_respon']['name'][0])) {
		
					//echo "upload";
					//exit;
					$fileCount = count($_FILES['file_respon']['tmp_name']);
					//echo "$fileCount";
					//exit;
					for ($i = 0; $i < $fileCount; $i++) {
		
						$namepp = $_FILES['file_respon']['name'][$i];
						$temp = $_FILES['file_respon']['tmp_name'][$i];
						$type = $_FILES['file_respon']['type'][$i];
						$size = $_FILES['file_respon']['size'][$i];
			
						$permissible_extension = array("jpg","png","pdf","jpeg");
						$ext = pathinfo($namepp, PATHINFO_EXTENSION);
						$test = getimagesize($temp);
						$width = $test[0];
						$height = $test[1];
		
						if (empty($_FILES)) {
							$sqlz = $db->query ("delete from tr_respon where id_tr_respon = '$akhir[lastid]'");
							?><script>
							alert('Pilih gambar yg akan diupload!');
							history.back();
							</script><?php
							exit();
						} elseif (!in_array($ext, $permissible_extension)) {
							$sqlz = $db->query ("delete from tr_respon where id_tr_respon = '$akhir[lastid]'");
							?><script>
							alert('File harus dalam format jpg, jpeg, png atau pdf!');
							history.back();
							</script><?php
							exit();
						} elseif ($size >= 5000000) {
							$sqlz = $db->query ("delete from tr_respon where id_tr_respon = '$akhir[lastid]'");
							?><script>
							alert('Size file dokumen tidak boleh lebih dari 5Mb!');
							history.back();
							</script><?php
							exit();
						} elseif ($width > 1200 || $height > 1200) {
							$sqlz = $db->query ("delete from tr_respon where id_tr_respon = '$akhir[lastid]'");
							?><script>
							alert('Ukuran file tidak boleh lebih dari 1200x1200 pixels!');
							history.back();
							</script><?php
							exit();
						} elseif (!move_uploaded_file($temp, "dist/file_respon/" . $namepp)) {	
							$sqlz = $db->query ("delete from tr_respon where id_tr_respon = '$akhir[lastid]'");
							?><script>
							alert('File tidak bisa diupload!');
							history.back();
							</script><?php
							exit();
						} else {
							//---------- input db
							$sqlb = $db->query ("insert into tr_file_respon (id_tr_respon, link_gbr) values ('$akhir[lastid]', 'dist/file_respon/$namepp')");
							//echo "$sqlb";
							//exit;	
						}

						if (!$sqlb) {
							$sqlz = $db->query ("delete from tr_respon where id_tr_respon = '$akhir[lastid]'");
						}
					}
				}

				// kirim email
				$recipients = [];
				$cc = ['cs@maxi-line.net'];
				$bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
				$subject = "Respon Pelaporan - $sqle[code_pelaporan]";

				if($_SESSION['level_user'] != 5) {
					$lanjutnya = "Anda telah mendapatkan pesan baru dari ".$dari." untuk laporan anda ".$sqle['code_pelaporan'].".";
					$mailContent = '
							<html>
							<head>
							<title>Respon Pelaporan - '.$sqle['code_pelaporan'].'</title>
							</head>
							<body>
							<p>Dengan Hormat</p>
							<p>'.$lanjutnya.'</p>
							<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
							</body>
							</html>
						';
					// Menambahkan penerima
					// $mail->addAddress($sqld['email']);
					$recipients = [$sqld['email']];
				} elseif($_SESSION['level_user'] == 5) {
					$lanjutnya = "Anda mendapatkan pesan baru dari ".$sqld['nama']." untuk laporan ".$sqle['code_pelaporan'].".";
					$mailContent = '
							<html>
							<head>
							<title>Respon Pelaporan - '.$sqle['code_pelaporan'].'</title>
							</head>
							<body>
							<p>Dengan Hormat</p>
							<p>'.$lanjutnya.'</p>
							<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
							</body>
							</html>
						';
					$recipients = $maili;
				}

				$sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);

				if(!$sendMail){
					//echo 'Mailer Error: ' . $mail->ErrorInfo;
					//exit();
					echo " <script>alert('email respon gagal dikirim, hubungi administrator!');</script>
					<script>history.back();</script>";
					exit();
				} else {
					$idnya = maxiline($id_tr_pelaporan, 'e');
					echo "<script>location.href='pelaporan_add.php?id=$idnya';</script>";
					exit();
				}
			
				// kirim email lama
				// $mail = new PHPMailer;
				
				// $alias = 'Maxi-Line';
				// $username = 'business.admin@manunggalintegrasi.com';
				// $password = 'M4nungg4l01*';
				// $dari = 'maxisupport@maxi-line.net';
				
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
				
				// if($_SESSION['level_user'] != 5) {
				// 	$lanjutnya = "Anda telah mendapatkan pesan baru dari ".$dari." untuk laporan anda ".$sqle['code_pelaporan'].".";
				// 	// Menambahkan penerima
				// 	$mail->addAddress($sqld['email']);
				// } elseif($_SESSION['level_user'] == 5) {
				// 	$lanjutnya = "Anda mendapatkan pesan baru dari ".$sqld['nama']." untuk laporan ".$sqle['code_pelaporan'].".";
				// 	foreach($maili as $maile) {
				// 		$mail->addAddress($maile);
				// 	}
				// }

				// Menambahkan beberapa penerima
				//$mail->addAddress('penerima2@contoh.com');
				//$mail->addAddress('penerima3@contoh.com');
				
				// Menambahkan cc atau bcc 
				//$mail->addCC($maile);
				// $mail->addCC('techsupport@manunggalintegrasi.com');
				// $mail->addBCC('business.admin@manunggalintegrasi.com');
				// $mail->addBCC('sendayu@manunggalintegrasi.com');
				// Subjek email
				// $mail->Subject = "Respon Pelaporan - $sqle[code_pelaporan]";

				// Mengatur format email ke HTML
				// $mail->isHTML(true);
					
				// $mailContent = '
				// 	<html>
				// 	<head>
				// 	<title>Respon Pelaporan - '.$sqle['code_pelaporan'].'</title>
				// 	</head>
				// 	<body>
				// 	<p>Dengan Hormat</p>
				// 	<p>'.$lanjutnya.'</p>
				// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
				// 	</body>
				// 	</html>
				// ';

				// $mail->Body = $mailContent;

				// if(!$mail->send()){
				// 	//echo 'Mailer Error: ' . $mail->ErrorInfo;
				// 	//exit();
				// 	echo " <script>alert('email respon gagal dikirim, hubungi administrator!');</script>
				// 	<script>history.back();</script>";	
				// 	exit();
				// } else {
				// 	$idnya = maxiline($id_tr_pelaporan, 'e');
				// 	echo "<script>location.href='pelaporan_add.php?id=$idnya';</script>";
				// 	exit();
				// }
			} else { 
				echo " <script>alert('Respon gagal dikirim!');</script>
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
} else if ($_GET['c'] == 'y') {
   $id_tr_pelaporan = maxiline($_GET['id'], 'd');
   $kode = maxiline($_GET['i'], 'd');
   
   //echo $id_tr_pelaporan."<br>";
   //echo $kode;
   //exit;

   $sqlz = $db->fetchArray($db->query("SELECT * from tr_pelaporan where id_tr_pelaporan = '$id_tr_pelaporan'"));
	
	//---------------------------------	
	if($kode != "close") {
    ?><script>
      alert('Data tidak ditemukan!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($sqlz['id_tr_pelaporan'])) {
    ?><script>
      alert('Data tidak ditemukan!');
      history.back();
      </script><?php
	  exit();
	} else {
		$sqla = $db->query ("update tr_pelaporan set sts_laporan = '3', tgl_close='$tgl' where id_tr_pelaporan='$id_tr_pelaporan'");
		// insert database
		if ($sqla) {

			$sqlx = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
		
			// kirim email
			$cc1 = ['cs@maxi-line.net'];
			$bcc1 = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
			$recipients1 = [$sqlx['email']];
			$subject1 = "Pelaporan - $sqlz[code_pelaporan] selesai";
			$mailContent1 = '
				<html>
				<head>
				<title>Pelaporan - '.$sqlz['code_pelaporan'].' Selesai</title>
				</head>
				<body>
				<p>Dengan Hormat</p>
				<p>Pelaporan anda dengan kode '.$sqlz['code_pelaporan'].' telah selesai.<br>
				Terima kasih telah menjadi keluarga besar MAXI-LINE.
				</p>
				<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p> 
				</body>
				</html>
			';

			$sendMail1 = sendToMails($recipients1, $cc1, $bcc1, $subject1, $mailContent1);

			if(!$sendMail1){
				//echo 'Mailer Error: ' . $mail->ErrorInfo;
				//exit();
				echo " <script>alert('email respon gagal dikirim, hubungi administrator!');</script>
				<script>history.back();</script>";	
				exit();
			} else {
				echo "<script>alert('Case closed!');</script>
					<script>location.href='pelaporan_add.php?id=$_GET[id]';</script>";
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
			
			// Menambahkan penerima
			// $mail->addAddress($sqlx['email']);
			
			// Menambahkan beberapa penerima
			//$mail->addAddress('penerima2@contoh.com');
			//$mail->addAddress('penerima3@contoh.com');
			
			// Menambahkan cc atau bcc 
			//$mail->addCC($maile);
			// $mail->addCC('techsupport@manunggalintegrasi.com');
			// $mail->addBCC('business.admin@manunggalintegrasi.com');
			// $mail->addBCC('sendayu@manunggalintegrasi.com');
			// Subjek email
			// $mail->Subject = "Pelaporan - $sqlz[code_pelaporan] selesai";
			
			// Mengatur format email ke HTML
			// $mail->isHTML(true);
			
			// $mailContent = '
			// 	<html>
			// 	<head>
			// 	<title>Pelaporan - '.$sqlz['code_pelaporan'].' Selesai</title>
			// 	</head>
			// 	<body>
			// 	<p>Dengan Hormat</p>
			// 	<p>Pelaporan anda dengan kode '.$sqlz['code_pelaporan'].' telah selesai.<br>
			// 	Terima kasih telah menjadi keluarga besar MAXI-LINE.
			// 	</p>
			// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p> 
			// 	</body>
			// 	</html>
			// ';

			// $mail->Body = $mailContent;
			
			// if(!$mail->send()){
			// 	//echo 'Mailer Error: ' . $mail->ErrorInfo;
			// 	//exit();
			// 	echo " <script>alert('email respon gagal dikirim, hubungi administrator!');</script>
			// 	<script>history.back();</script>";	
			// 	exit();
			// } else { 		
			// 	echo "<script>alert('Case closed!');</script>
			// 	<script>location.href='pelaporan_add.php?id=$_GET[id]';</script>";
			// 	exit();
			// } 
		} else { 
			echo " <script>alert('Laporan gagal ditutup!');</script>
			<script>history.back();</script>";
			exit();
		}
	}
 
} else if ($_GET['g'] == 'h') {

	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_GET['token']==$kodeaman) {

		$id_tr_file_pelaporan = maxiline(SafeSQL($_GET['id']), 'd');
		$sqla = $db->fetchArray($db->query("SELECT * from tr_file_pelaporan where id_tr_file_pelaporan = '$id_tr_file_pelaporan'"));
		unlink("$sqla[link_gbr]");
		$sqlb = "delete from tr_file_pelaporan where id_tr_file_pelaporan='$id_tr_file_pelaporan'";
		$resb = $db->query($sqld);
		if ($sqld) {
			$idne = maxiline($sqla['id_tr_pelaporan'], 'e');
			echo "<script>alert('Data berhasil dihapus!');</script>
				<script>location.href='pelaporan_add.php?id=$idne';</script>";
				exit();
		} else { 
			echo " <script>alert('Data gagal dihapus!');</script>
				<script>history.back();</script>";
					exit();
		}

	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
			<script>history.back();</script>";
		exit();
	}
}
?>   
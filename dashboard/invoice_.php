<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";
require_once "include/MikrotikApi.php";

// require_once 'plugins/PHPmailer/class.phpmailer.php';

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d H:i:s");

$a = explode('-',$_POST["tgl_invoice"]);
$tanggal = $a[2].'-'.$a[1].'-'.$a[0];
$waktue = $now->format("H:i:s");
$waktu = $tanggal." ".$waktue;
$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
$id_tb_user = $_SESSION['id_tb_user'];

//echo $_POST['$tgl_invoice']."<br>";
//echo $tanggal."<br>";
//echo $waktu."<br>";
//print_r($_POST)."<br>";
//print_r($_POST['nama_transaksi'])."<br>";
//exit;


if ($_GET['n'] == 'y') {
	
	$id_tb_pendaftaran = maxiline(SafeSQL($_GET['id']),'d');
	
	if (empty($id_tb_pendaftaran)) {	 
		echo " <script>alert('Pendaftaran tidak ditemukan!');</script>
	   <script>location.href='index.php';</script>";
		exit();
	}
	
	$sq = "SELECT * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
	$re = $db->query($sq);
	$ro = $db->fetchArray($re);	
	
	$no_invoice = SafeSQL($_GET['kd']);
	$jns_invoice = SafeSQL($_GET['j']);
	
	$now = new DateTime();
	$th = $now->format("Y");
	$bl = $now->format("m");
	$Th = $now->format("y");
	$tg = $now->format("j");
	
	if($tg > 5){
	$tgle = $now->format("Y-m-05 01:00:00");
	$tglinvoice = date("Y-m-d", strtotime('+1 month' , strtotime($tgle))) ;
	$tglexpired = date("Y-m-d H:i:s", strtotime('+1 week' , strtotime($tglinvoice)));
	} else {
	$tglinvoice = $now->format("Y-m-05 01:00:00");
	$tglexpired = date("Y-m-d H:i:s", strtotime('+1 week' , strtotime($tglinvoice)));
	}
	//echo $_GET['id']."ini <br>";
	//echo $id_tb_pendaftaran."ini <br>";
	//exit;
	
	$sql = "insert into tr_invoice (id_tb_pendaftaran, id_tb_paket, no_invoice, tgl_invoice, tgl_expired, jns_invoice, id_tb_user_create) values ('$id_tb_pendaftaran', '$ro[id_tb_paket]', '$no_invoice', '$tglinvoice', '$tglexpired', '1','$id_tb_user')";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	
	// insert database
	if ($res) {	
		$sqla = "SELECT SCOPE_IDENTITY() as lastid from tr_invoice";
		$resa = $db->query($sqla);
		$rowa = $db->fetchArray($resa);		
		
		$id_invoice = maxiline($rowa['lastid'], 'e');
		$id_daftar = maxiline($id_tb_pendaftaran, 'e');
			 
		echo "<script>alert('Draft invoice disimpan!');</script>
		<script>location.href='invoice_add.php?id=$id_daftar&i=$id_invoice&j=1';</script>";
		exit();
			
		} else {
			echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
			   <script>history.back();</script>";
			exit();
		}		
} else if ($_POST['input'] == "y") {
	
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
	$nama_transaksi = SafeSQL($_POST['nama_transaksi']);
	$harga_transaksi = SafeSQL($_POST['harga_transaksi']);
	$jumlah = SafeSQL($_POST['jumlah']);
	$satuan = SafeSQL($_POST['satuan']);
	$sub_total = $jumlah * $harga_transaksi;
	//echo $id_tb_pendaftaran;
	//exit;
	
	$sql = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total) values ('$id_tb_pendaftaran', '$id_tr_invoice', '$nama_transaksi', '$harga_transaksi', '$jumlah', '$satuan', '$sub_total')";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	$id_tb_pendaftarans = maxiline($id_tb_pendaftaran, 'e');
	$id_tr_invoices = maxiline($id_tr_invoice, 'e');
	if ($sql) {	 
		echo "<script>location.href='invoice_add.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=1';</script>";
		exit();
	} else { 
		echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
		   <script>history.back();</script>";
		exit();
	} 	
} else if ($_GET['k'] == 'h') {
   
    unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_GET['token']==$kodeaman) {  
	
		//---------------------------------	
		$id_tr_transaksi = SafeSQL($_GET['i']);
		
		$sqla = $db->query("delete from tr_transaksi where id_tr_transaksi='$id_tr_transaksi'");
		// insert database

		if ($sqla) {
			echo "<script>history.back();</script>";
			exit();
		} else { 
			echo " <script>alert('Item gagal dihapus!');</script>
			<script>history.back();</script>";
			exit();
		}
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
	} 
} else if ($_POST['edit'] == "n") {
	
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
	$nama_transaksi = SafeSQL($_POST['nama_transaksi']);
	$harga_transaksi = SafeSQL($_POST['harga_transaksi']);
	$id_tr_transaksi = SafeSQL($_POST['id_tr_transaksi']);
	$jumlah = SafeSQL($_POST['jumlah']);
	$satuan = SafeSQL($_POST['satuan']);
	$sub_total = $jumlah * $harga_transaksi;
	//echo $id_tb_pendaftaran;
	//exit;
	
	$sql = "update tr_transaksi set nama_transaksi = '$nama_transaksi', harga_transaksi = '$harga_transaksi', jumlah = '$jumlah', satuan = '$satuan', sub_total = '$sub_total' where id_tr_transaksi = '$id_tr_transaksi'";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	$id_tb_pendaftarans = maxiline($id_tb_pendaftaran, 'e');
	$id_tr_invoices = maxiline($id_tr_invoice, 'e');
	if ($sql) {	 
		echo "<script>location.href='invoice_add.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=1';</script>";
		exit();
	} else { 
		echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
		   <script>history.back();</script>";
		exit();
	} 	
} else if ($_POST['terbit'] == 'ya') {
   
    unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {  
	
		//---------------------------------	
		$nama_transaksi1 = explode(',', $_POST['nama_transaksi1']);
		$harga_transaksi1 = explode(',', $_POST['harga_transaksi1']);
		
		$nama_transaksi2 = explode(',', $_POST['nama_transaksi2']);
		$harga_transaksi2 = explode(',', $_POST['harga_transaksi2']);
		$sub_total2 = explode(',', $_POST['sub_total2']);
		
		$nama_transaksi = $_POST['nama_transaksi'];
		$harga_transaksi = $_POST['harga_transaksi'];
		$sub_total = $_POST['sub_total'];
		$jumlah = $_POST['jumlah'];
		$satuan = $_POST['satuan'];
		
		$total = $_POST['total'];
		$id_tr_invoice = $_POST['id_tr_invoice'];
	
		//print_r($nama_transaksi1);
		//exit;
		$sqlb = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
		
		$sqlg = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transaksi', '$harga_transaksi', '$jumlah', 'Rp', '$sub_total', '3')";
		//echo $sql;
		//exit;	
		$resg = $db->query($sqlg);
		
		$sqla = $db->query("update tr_invoice set tot_tagih = '$total', tgl_data = '$tgl' where id_tr_invoice='$id_tr_invoice'");
	
		$sqlc = $db->query("update tb_pendaftaran set st_layanan = '8' where id_tb_pendaftaran='$sqlb[id_tb_pendaftaran]'");
		$sqld = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
		
		foreach ($nama_transaksi1 as $a=>$nama_transak1) { 
			$nama_transak1 = $nama_transak1;
			$harga_transak1 = $harga_transaksi1[$a];
			
			$sqle = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transak1', '$harga_transak1', '1', 'Rp', '$harga_transak1', '2')";
			//echo $sql;
			//exit;	
			$rese = $db->query($sqle);
		}
			
		foreach ($nama_transaksi2 as $b=>$nama_transak2) { 
			$nama_transak2 = $nama_transak2;
			$harga_transak2 = $harga_transaksi2[$b];
			$sub_tot2 = $sub_total2[$b];
			
			$sqlf = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transak2', '$sub_tot2', '$harga_transak2', '%', '$sub_tot2', '2')";
			//echo $sql;
			//exit;	
			$resf = $db->query($sqlf);
		}
		$sqlg = $db->fetchArray($db->query("SELECT * from tb_pendaftaran where id_tb_pendaftaran='$sqlb[id_tb_pendaftaran]'"));
		$passo = md5($sqlg['kode_daftar']);
		$sqlh = $db->query("insert into tb_user (id_tb_pendaftaran, nm_user, email, telp, level_user, username, password, alamat) values ('$sqlg[id_tb_pendaftaran]', '$sqlg[nama]', '$sqlg[email]', '$sqlg[telp]', '5', '$sqlg[email]', '$passo', '$sqlg[alamat]')");	
	
		// insert database
		if ($sqlh) {
			// kirim email.
			$recipients = [$sqlg['email']];
			$cc = 'business.admin@manunggalintegrasi.com';
			$bcc = ['maxisupport@maxi-line.net','techsupport@manunggalintegrasi.com'];
			$subject = "USER MAXI LINE - $sqlg[kode_daftar]";

			$sqlx = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
			$linkp = "http://maxi-line.net/dashboard/invoice_p.php?id=".maxiline($id_tr_invoice, 'e')."&token=ee3699b71069491a9bc15b2de313c50a";

			$mailContent = '
				<html>
				<head>
				<title>USER MAXI LINE - '.$sqlg['kode_daftar'].'</title>
				</head>
				<body>
				<p>Dengan Hormat</p>
				<br>
				<p>Layanan berlangganan jaringan internet MAXI LINE anda telah aktif & kami telah membuat akun untuk anda. Akun tersebut dapat anda gunakan
				untuk melihat invoice, mengirim pesan atau melaporkan gangguan. Anda dapat login melalui link berikut ini :</p>
				<p><a href="http://maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="http://maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a></p>
				<p>Gunakan username <b>'.$sqlg['email'].'</b> dengan password <b>'.$sqlg['kode_daftar'].'</b>, setelah login silahkan mengganti username dan password untuk mengamankan akun anda</p>
				<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
				</body>
				</html>
			';
			$sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);

			// $mail = new PHPMailer;
			
			// $alias = 'Maxi Line';
			// $username = 'business.admin@manunggalintegrasi.com';
			// $password = 'M4nungg4l01*';
			// $dari = 'maxisupport@maxi-line.net';
			
			// Konfigurasi SMTP
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
			// $mail->addAddress($sqlg['email']);
		
			// Menambahkan beberapa penerima
			//$mail->addAddress('penerima2@contoh.com');
			//$mail->addAddress('penerima3@contoh.com');
		
			// Menambahkan cc atau bcc
			// $mail->addCC('grafis.ptc@gmail.com');
			// $mail->addBCC('business.admin@manunggalintegrasi.com');
			//  $mail->addBCC('techsupport@manunggalintegrasi.com');
			// Subjek email
			// $mail->Subject = "USER MAXI LINE - $sqlg[kode_daftar]";
		
			// Mengatur format email ke HTML
			// $mail->isHTML(true);
		
			// $sqlx = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
			// $linkp = "http://maxi-line.net/dashboard/invoice_p.php?id=".maxiline($id_tr_invoice, 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
			
			// $mailContent = '
			// 	<html>
			// 	<head>
			// 	<title>USER MAXI LINE - '.$sqlg['kode_daftar'].'</title>
			// 	</head>
			// 	<body>
			// 	<p>Dengan Hormat</p>
			// 	<br>
			// 	<p>Layanan berlangganan jaringan internet MAXI LINE anda telah aktif & kami telah membuat akun untuk anda. Akun tersebut dapat anda gunakan
			// 	untuk melihat invoice, mengirim pesan atau melaporkan gangguan. Anda dapat login melalui link berikut ini :</p>
			// 	<p><a href="http://maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="http://maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a></p>
			// 	<p>Gunakan username <b>'.$sqlg['email'].'</b> dengan password <b>'.$sqlg['kode_daftar'].'</b>, setelah login silahkan mengganti username dan password untuk mengamankan akun anda</p>
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
				// echo 'Mailer Error: ' . $mail->ErrorInfo;
				// exit();
				echo " <script>alert('Email pembuatan akun baru gagal, hubungi administrator!');</script>
				<script>history.back();</script>";	
				exit();
			} else {
				$id_invoice = maxiline($id_tr_invoice, 'e');
				$id_daftar = maxiline($sqlb['id_tb_pendaftaran'], 'e');
				echo "<script>alert('Invoice berhasil diterbitkan!');</script>
				<script>location.href='invoice_add.php?id=$id_daftar&i=$id_invoice';</script>";
				exit();
			}			
		} else { 
			echo " <script>alert('Invoice gagal diterbitkan!');</script>
				<script>history.back();</script>";
					exit();
		} 		  
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
	} 
} else if ($_GET['h'] == 'y') {
   $id_tr_invoice = maxiline(SafeSQL($_GET['id']),'d');
   $sql = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
   
	if(empty($sql['id_tr_invoice'])){
		echo " <script>alert('Invoice tidak ditemukan!');</script>
		<script>history.back();</script>";
		exit();   
	} else {
		$sqla = $db->query("delete from tr_invoice where id_tr_invoice='$id_tr_invoice'");
		$sqla = $db->query("delete from tr_transaksi where id_tr_invoice='$id_tr_invoice'");
		if ($sqla) {	 
		echo " <script>alert('Draft invoice berhasil dihapus');</script>
			<script>location.href='invoice_draft.php';</script>";
			exit();
		} else { 
		echo " <script>alert('Draft invoice gagal dihapus!');</script>
			<script>history.back();</script>";
				exit();
		} 
	}
} else if ($_POST['invoice'] == "simpan") {
	$sql = "update tr_invoice set tgl_invoice = '$waktu' where id_tr_invoice = '$id_tr_invoice'";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	if ($res) {	 
		echo " <script>alert('Draft invoice berhasil diupdate');</script>
			<script>location.href='invoice_draft.php';</script>";
			exit();
	} else { 
		echo " <script>alert('Draft invoice gagal diupdate!');</script>
			<script>history.back();</script>";
				exit();
	} 
} else if ($_GET['l'] == 'y') {
	$id_tr_invoice = maxiline(SafeSQL($_GET['id']),'d');
	
	// if(empty($_SESSION['id_tb_user'])) {
	// 	echo "<script>location.href='login.php';</script>";
	// 	exit();
	// }
	
	// checking
	$sqlcheck = $db->fetchArray($db->query("select * from tr_invoice where id_tr_invoice = '$id_tr_invoice'"));

	if(empty($sqlcheck['id_tr_invoice'])){
		echo " <script>alert('Invoice tidak ditemukan!');</script>
		<script>history.back();</script>";
		exit();
	}	
	//	
	
	$sql = "update tr_invoice set sts_lunas = '2', tgl_dibayar = '$tgl ', id_tb_user_lunas = '$id_tb_user' where id_tr_invoice = '$id_tr_invoice'";
	$res = $db->query($sql);

	$sqla = $db->fetchArray($db->query("select * from tr_invoice where id_tr_invoice = '$id_tr_invoice'"));
	$sqlc = $db->fetchArray($db->query("select * from tr_confirm where kode_invoice = '$sqla[no_invoice]' and st_confirm = '1'"));
	$sqld = $db->query("update tr_confirm set st_confirm = '2', tgl_confirm = '$tgl' where id_tr_confirm = '$sqlc[id_tr_confirm]'");
	
	if ($res) {
		/// kirim email	
		$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'"));	

		// kirim email. 
		$recipients1 = [$sqlb['email']];
		$cc1 = '';
		$bcc1 = ['business.admin@manunggalintegrasi.com', 'muhammad_dhani@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
		$subject1 = "Pembayaran Invoice - $sqla[no_invoice]";
		$mailContent1 = '
			<html>
			<head>
			<title>PEMBAYARAN INVOICE MAXI LINE - '.$sqla['no_invoice'].'</title>
			</head>
			<body>
			<p>Dengan Hormat</p>
			<p>Terima kasih, Kami telah menerima pembayaran anda untuk nomor invoice '.$sqla['no_invoice'].'.</p>
			<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
			</body>
			</html>
		';
		$sendMail1 = sendToMails($recipients1, $cc1, $bcc1, $subject1, $mailContent1);

		// $mail = new PHPMailer;
		
		// $alias = 'Maxi Line';
		// $username = 'business.admin@manunggalintegrasi.com';
		// $password = 'M4nungg4l01*';
		// $dari = 'maxisupport@maxi-line.net';
		
		// Konfigurasi SMTP
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
		// $mail->addAddress($sqlb['email']);
		//$mail->addAddress('business.admin@manunggalintegrasi.com');

	
		// Menambahkan cc atau bcc
		// $mail->addCC('grafis.ptc@gmail.com');
		// $mail->addBCC('business.admin@manunggalintegrasi.com');
		//$mail->addBCC('techsupport@manunggalintegrasi.com');
		// Subjek email
		// $mail->Subject = "Pembayaran Invoice - $sqla[no_invoice]";
	
		// Mengatur format email ke HTML
		// $mail->isHTML(true);
		
		// $mailContent = '
		// 	<html>
		// 	<head>
		// 	<title>PEMBAYARAN INVOICE MAXI LINE - '.$sqla[no_invoice].'</title>
		// 	</head>
		// 	<body>
		// 	<p>Dengan Hormat</p>
		// 	<p>Terima kasih, Kami telah menerima pembayaran anda untuk nomor invoice '.$sqla[no_invoice].'.</p>
		// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		// 	</body>
		// 	</html>
		// ';

		// $mail->Body = $mailContent;
		// Menambahakn lampiran
		//$mail->addAttachment('lmp/file1.pdf');
		//$mail->addAttachment('lmp/file2.png', 'nama-baru-file2.png'); //atur nama baru
		
		// # kirim email.
		if(!$sendMail1){
			// echo 'Mailer Error: ' . $mail->ErrorInfo;
			// exit();
			echo " <script>alert('Email konfirmasi pembayaran gagal dikirim, hubungi administrator!');</script>
			<script>history.back();</script>";
			exit();
		} else {
			/// end kirim email
			if($_GET['st'] == 1 or $_GET['st'] == 2 or $_GET['st'] == 3){
				$idte = maxiline($sqla['id_tb_pendaftaran'], 'e');

				// Aktifkan layanan with Mikrotik Api
				$qry = "SELECT * FROM tr_invoice WHERE sts_lunas IN (1,3) AND id_tb_pendaftaran = '". $sqla['id_tb_pendaftaran']. "'";
				$sq = $db->queryNumRows($qry);
				$countRow = $db->getNumRows($sq);

				if ($countRow == 0) {
					// Jika status layanan suspend
					if ($sqlb['st_layanan'] == 10) {
						// Get code pendaftaran
						$name = $sqlb['kode_daftar'] . '@maxi-line.net';
	
						// With Class MikrotikApi
						try {
							$mikrotik = new MikrotikApi('160.20.79.252', 'administrator', 'KompasArah2022@');
							$secret = $mikrotik->getSecret($name);
							
							if (!empty($secret)) {
								if ($mikrotik->enableService($secret[0]['.id'])) {
									echo " <script>alert('Layanan internet pelanggan '".$sqlb['nama']."' gagal diaktifkan.');</script>
									<script>history.back();</script>";
									exit();
								} else {
									$id_daftar = SafeSQL($sqlb['id_tb_pendaftaran']);
									$sql = "UPDATE tb_pendaftaran SET st_layanan = '8' WHERE id_tb_pendaftaran = '$id_daftar'";
									$res = $db->query($sql);
										
									if ($res) {
										// kirim email.
										$recipients = [$sqlb['email']];
										$cc = ['cs@maxi-line.net'];
										$bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
										$subject = "Pengaktifan Layanan Internet";
										$mailContent = '
											<html>
											<head>
											<title>MAXI-LINE - Pengaktifan Layanan Internet</title>
											</head>
											<body>
											<p>Kepada Yth. Pelanggan MAXI-LINE</p>
											<p>
												Kami menginformasikan bahwa layanan internet pelanggan "'.$sqlb['nama'].'" telah aktif.
												Anda dapat menikmati kembali layanan internet MAXI-LINE seperti sedia kala.<br> Kami mohon maaf atas ketidaknyamanan yang Anda alami sebelumnya. <br><br>
												Jika anda memiliki kendala anda dapat melakukan Pelaporan/Open tiket melalui website kami <a href="https://www.maxi-line.net/dashboard/login.php">www.maxi-line.net</a> <br><br>
												Terima kasih telah menjadi keluarga besar MAXI-LINE.
											</p>
											<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p>
											</body>
											</html>
										';
			
										$sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);
			
										if ($sendMail) {
											$kode_daftar = SafeSQL($sqlb['kode_daftar']);

											if (isset($_SESSION['id_tb_user']) || !empty($_SESSION['id_tb_user'])) {
												$id_tb_user = $_SESSION['id_tb_user'];
											} else {
												$id_tb_user = 'System';
											}
		
											$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		
											$sql = "INSERT INTO tb_log_services(kode_daftar, st_layanan, user_aksi, url_aksi) VALUES ('".$kode_daftar."', '8', '".$id_tb_user."','".$url."' )";
											$result = $db->query($sql);
											
											if ($result) {
												if(($sqla['tgl_invoice'])->format("Y-m") != $now->format("Y-m")) {
													echo " <script>alert('Status pembayaran invoice diterima dan email berhasil dikirim. Layanan internet pelanggan '".$sqlb['nama']."' telah diaktifkan.');</script>
													<script>location.href='invoice_pending.php';</script>";
													exit();
												} else {
													echo " <script>alert('Status pembayaran invoice diterima dan email berhasil dikirim. Layanan internet pelanggan '".$sqlb['nama']."' telah diaktifkan.');</script>
													<script>location.href='invoice_add2.php?id=$idte&j=2&b=t';</script>";
													exit();
												}
											} else {
												echo " <script>alert('Email berhasil dikirim! Log Pelanggan Services gagal dicatat!.');</script>
												<script>history.back();</script>";
												exit();
											}
										} else {
											echo " <script>alert('Email gagal dikirim! Silahkan hubungi Administrator.');</script>
											<script>history.back();</script>";
											exit();
										}
									}
								}
							} else {
								echo " <script>alert('Akun PPPOE pelanggan '".$sqlb['nama']."' tidak ditemukan. Silahkan hubungi NOC/Administrator untuk mengaktifkan layanan.');</script>
								<script>history.back();</script>";
								exit();
							}
						} catch (Exception $e) {
							echo " <script>alert('".$e->getMessage()."');</script>
							<script>history.back();</script>";
							die();
						}
					} else {
						if(($sqla['tgl_invoice'])->format("Y-m") != $now->format("Y-m")) {
							echo " <script>alert('Status pembayaran invoice diterima dan email berhasil dikirim!');</script>
							<script>location.href='invoice_pending.php';</script>";
							exit();
						} else {
							echo " <script>alert('Status pembayaran invoice diterima dan email berhasil dikirim.');</script>
							<script>location.href='invoice_add2.php?id=$idte&j=2&b=t';</script>";
							exit();
						}
					}
				}

			} else {
				if(!empty($sqlc['id_tr_confirm'])) {
					
					$sqle = $db->query("update tb_pendaftaran set st_layanan = '9' where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'");
					$sqlf = $db->query("update tb_user set sts_delete = '2' where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'");
					
					echo " <script>alert('Status pembayaran invoice diterima');</script>
					<script>location.href='invoice_confirm.php';</script>";
					exit();
				} else {
					
					$sqle = $db->query("update tb_pendaftaran set st_layanan = '9' where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'");
					$sqlf = $db->query("update tb_user set sts_delete = '2' where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'");		
					
					echo " <script>alert('Status pembayaran invoice diterima');</script>
						<script>location.href='invoice_pending.php';</script>";
						exit();
				}
			}
		}
	} else { 
		echo " <script>alert('Status pembayaran invoice gagal diupdate!');</script>
		<script>history.back();</script>";
		exit();
	}
} else if ($_POST['t'] == 'y') {
	$id_tr_confirm = SafeSQL($_POST['id_tr_confirm']);
	$alasan = SafeSQL($_POST['alasan']);
	$sql = "update tr_confirm set st_confirm = '3',  tgl_confirm = '$tgl', alasan_tolak = '$alasan' where id_tr_confirm = '$id_tr_confirm' and st_confirm = '1'";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	$sqla = $db->fetchArray($db->query("select * from tr_confirm where id_tr_confirm = '$id_tr_confirm'"));
	$sqlc = $db->fetchArray($db->query("select * from tr_invoice where no_invoice = '$sqla[kode_invoice]'"));
	if ($res) {
		/// kirim email	
		$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sqlc[id_tb_pendaftaran]'"));
		if(empty($_POST['alasan'])){
		$isi = "Mohon maaf pembayaran anda bermasalah, silahkan hubungi CS kami."; } else {
		$isi = "Mohon maaf pembayaran anda bermasalah karena ".$_POST['alasan'].", silahkan hubungi CS kami."; }	
	
		// kirim email. 
		$recipient2 = [$sqlb['email']];
		$cc2 = 'business.admin@manunggalintegrasi.com';    
		$bcc2 = ['maxisupport@maxi-line.net','techsupport@manunggalintegrasi.com'];
		$subject2 = "Pembayaran Invoice - $sqla[kode_invoice]";
		$mailContent2 = '
			<html>
			<head>
			<title>PEMBAYARAN INVOICE MAXI LINE - '.$sqla['no_invoice'].'</title>
			</head>
			<body>
			<p>Dengan Hormat</p>
			<p>'.$isi.'</p>
			<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
			</body>
			</html>
		';

		$sendMail2 = sendToMails($recipient2, $cc2, $bcc2, $subject2, $mailContent2);

		// $mail = new PHPMailer;
		
		// $alias = 'Maxi Line';
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
		// $mail->addAddress($sqlb['email']);
	
		// Menambahkan beberapa penerima
		//$mail->addAddress('penerima2@contoh.com');
		//$mail->addAddress('penerima3@contoh.com');
	
		// Menambahkan cc atau bcc
		// $mail->addCC('grafis.ptc@gmail.com');
		// $mail->addBCC('business.admin@manunggalintegrasi.com');
		//  $mail->addBCC('techsupport@manunggalintegrasi.com');
		// Subjek email
		// $mail->Subject = "Pembayaran Invoice - $sqla[kode_invoice]";
	
		// Mengatur format email ke HTML
		// $mail->isHTML(true);
		
		// $mailContent = '
		// 	<html>
		// 	<head>
		// 	<title>PEMBAYARAN INVOICE MAXI LINE - '.$sqla[no_invoice].'</title>
		// 	</head>
		// 	<body>
		// 	<p>Dengan Hormat</p>
		// 	<p>'.$isi.'</p>
		// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		// 	</body>
		// 	</html>
		// ';

		// $mail->Body = $mailContent;
		// Menambahakn lampiran
		//$mail->addAttachment('lmp/file1.pdf');
		//$mail->addAttachment('lmp/file2.png', 'nama-baru-file2.png'); //atur nama baru
		
		// # kirim email.	
		if(!$sendMail2){
			echo " <script>alert('email konfirmasi pembayaran gagal dikirim, hubungi administrator!');</script>
			<script>history.back();</script>";	
			exit();
		} else {
			echo " <script>alert('Status pembayaran invoice ditolak');</script>
			<script>location.href='invoice_confirm.php';</script>";
			exit();
		}	
	} else { 
		echo " <script>alert('Invoice gagal ditolak!');</script>
			<script>history.back();</script>";
				exit();
	}  
} else if ($_POST['rubah_paket'] == 'ya') {
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);	
	$id_tb_paket = SafeSQL($_POST['id_tb_paket']);
	$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);	
	
	$sqle = $db->query("update tb_pendaftaran set id_tb_paket = '$id_tb_paket' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
	
	if($sqle) {
		
	$id_invoice = maxiline($id_tr_invoice, 'e');
	$id_daftar = maxiline($id_tb_pendaftaran, 'e');
		 
	echo "<script>location.href='invoice_add.php?id=$id_daftar&i=$id_invoice&j=1';</script>";
	exit();
		
	} else {
		echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
		   <script>history.back();</script>";
		exit();
	}	
}
?>
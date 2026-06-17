<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";

require_once 'plugins/PHPmailer/class.phpmailer.php';

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

//echo $_POST['$tgl_invoice']."<br>";
//echo $tanggal."<br>";
//echo $waktu."<br>";
//print_r($_POST)."<br>";
//print_r($_POST['nama_transaksi'])."<br>";
//exit;

if ($_GET['n'] == 'y') {
	
	$id_tb_pendaftaran = maxiline(SafeSQL($_GET['id']),'d');
	$no_invoice = SafeSQL($_GET['kd']);
	$jns_invoice = SafeSQL($_GET['j']);
	//echo $_GET['id']."ini <br>";
	//echo $id_tb_pendaftaran."ini <br>";
	//exit;
	
	$sql = "insert into tr_invoice (id_tb_pendaftaran, no_invoice, tgl_invoice, jns_invoice) values ('$id_tb_pendaftaran', '$no_invoice', '$tgl', '$jns_invoice')";
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
		<script>location.href='invoice_add.php?id=$id_daftar&i=$id_invoice&j=$_GET[j]';</script>";
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
	
	$total = $_POST['total'];
	$id_tr_invoice = $_POST['id_tr_invoice'];
	
	//print_r($nama_transaksi1);
	//exit;
	$sqlb = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
	
	$sqlg = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transaksi', '$harga_transaksi', '1', 'Rp', '$sub_total', '3')";
	//echo $sql;
	//exit;	
	$resg = $db->query($sqlg);
	
	$sqla = $db->query("update tr_invoice set sts_invoice = '2', tot_tagih = '$total' where id_tr_invoice='$id_tr_invoice'");
	
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
	$mail = new PHPMailer;
	
	$alias = 'Maxi Line';
    $username = 'business.admin@manunggalintegrasi.com';
    $password = 'M4nungg4l01*';
    $dari = 'maxisupport@maxi-line.net';
	
	// Konfigurasi SMTP
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
	$mail->addAddress($sqlg['email']);
  
    // Menambahkan beberapa penerima
    //$mail->addAddress('penerima2@contoh.com');
    //$mail->addAddress('penerima3@contoh.com');
  
    // Menambahkan cc atau bcc
    // $mail->addCC('grafis.ptc@gmail.com');
    $mail->addBCC('business.admin@manunggalintegrasi.com');
	 $mail->addBCC('techsupport@manunggalintegrasi.com');
    // Subjek email
    $mail->Subject = "USER MAXI LINE - $sqlg[kode_daftar]";
  
    // Mengatur format email ke HTML
    $mail->isHTML(true);
	
	$sqlx = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
	$linkp = "http://maxi-line.net/dashboard/invoice_p.php?id=".maxiline($id_tr_invoice, 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
	
	$mailContent = '
		<html>
		<head>
		<title>USER MAXI LINE - '.$sqlg[kode_daftar].'</title>
		</head>
		<body>
		<p>Dengan Hormat</p>
		<br>
		<p>Layanan berlangganan jaringan internet MAXI LINE anda telah aktif & kami telah membuat akun untuk anda. Akun tersebut dapat anda gunakan
		untuk melihat invoice, mengirim pesan atau melaporkan gangguan. Anda dapat login melalui link berikut ini :</p>
		<p><a href="http://maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="http://maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a></p>
		<p>Gunakan username <b>'.$sqlg[email].'</b> dengan password <b>'.$sqlg[kode_daftar].'</b>, setelah login silahkan mengganti username dan password untuk mengamankan akun anda</p>
		<p>Untuk melihat invoice anda silahkan klik link berikut : <a href="'.$linkp.'" target="_blank" data-saferedirecturl="'.$linkp.'"><b>--- '.$sqlx[no_invoice].' ---</b></a></p>
		<br>
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
	echo 'Mailer Error: ' . $mail->ErrorInfo;
	exit();
	echo " <script>alert('email pembuatan akun baru gagal, hubungi administrator!');</script>
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
}
?>   
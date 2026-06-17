<?
include "include/config.php";
include "include/DbConnector.php";

include "invoice_p_file.php";
require_once 'plugins/PHPmailer/class.phpmailer.php';

$db = new DbConnector();

// cek pelaporan yg lbh dr 3 hari tidak ditutup
$now = new DateTime();
$tgl = $now->format("Y-m-d H:i:s");

//echo $tgl;
//exit;

$sql = "SELECT max(id_tr_respon) as id_tr_respon FROM v_pelaporan_respon WHERE sts_laporan = '2' group by code_pelaporan";
$res = $db->query($sql);
while($row = $db->fetchArray($res)) {
$sqla = $db->fetchArray($db->query("select *, DATEADD(DAY, 3, tgl_data) AS expired from tr_respon where id_tr_respon = '$row[id_tr_respon]'"));
$sqle = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$sqla[id_tb_user]'"));
$tgexpired = $sqla['expired']->format("Y-m-d H:i:s");
//exit;
if($tgl > $tgexpired and $sqle['level_user'] != 5){
	$sqlb = "UPDATE tr_pelaporan set sts_laporan = '3', tgl_close='$tgl' where id_tr_pelaporan = '$sqla[id_tr_pelaporan]'";
	$resb = $db->query($sqlb); 

	if ($resb){
	$sqlc = $db->fetchArray($db->query("select * from tr_pelaporan where id_tr_pelaporan = '$sqla[id_tr_pelaporan]'"));
	$sqld = $db->fetchArray($db->query("select * from tb_user where id_tb_pendaftaran = '$sqlc[id_tb_pendaftaran]'"));
	
	$mail = new PHPMailer;
	
	$alias = 'Maxi-Line';
	$username = 'business.admin@manunggalintegrasi.com';
	$password = 'M4nungg4l01*';
	$dari = 'maxisupport@maxi-line.net';
	
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
	$mail->addAddress($sqld['email']);
	
	// Menambahkan beberapa penerima
	//$mail->addAddress('penerima2@contoh.com');
	//$mail->addAddress('penerima3@contoh.com');
	
	// Menambahkan cc atau bcc 
	//$mail->addCC($maile);
	$mail->addBCC('business.admin@manunggalintegrasi.com');
	// Subjek email
	$mail->Subject = "Pelaporan - $sqlc[code_pelaporan] selesai";
	
	// Mengatur format email ke HTML
	$mail->isHTML(true);
	
	$mailContent = '
		<html>
		<head>
		<title>Pelaporan - '.$sqlc['code_pelaporan'].' Selesai</title>
		</head>
		<body>
		<p>Dengan Hormat</p>
		<p>Pelaporan anda dengan kode '.$sqlc['code_pelaporan'].' telah selesai.<br>
		Terima kasih telah menjadi keluarga besar MAXI-LINE.
		</p>
		<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p> 
		</body>
		</html>
	';

	$mail->Body = $mailContent;	
	
//	if(!$mail->send()){
	//echo 'Mailer Error: ' . $mail->ErrorInfo;
	//exit();
//	echo 'Mailer Error: ' . $mail->ErrorInfo;
//	exit();
//	}
	
	}	
	
 }	
}

// cek auto terbit tiap invoice
$sql1 = "SELECT * FROM tr_invoice WHERE sts_invoice = '1' ";
$res1 = $db->query($sql1);
while($row1 = $db->fetchArray($res1)) {
$tgterbit = $row1['tgl_invoice']->format("Y-m-d H:i:s");

//echo $row1['id_tr_invoice']."<br>";
//echo $tgl."<br>";
//echo $tgterbit."<br>";
//echo $row1['tot_tagih']."<br>";
//exit;

if(!empty($row1['id_tr_invoice']) and $tgl > $tgterbit ){
	
	// create pdf file
	//echo $row1['id_tr_invoice'];
	//exit;
	
	$create = createpdf($row1['id_tr_invoice']);
	
	//echo $create;
	//exit;
	
	/*
	if($create == 1) {
	echo "<script>alert('Nomer invoice tidak ditemukan!');</script>
	<script>location.href='404.html';</script>";
	exit();
	}
	*/
	
	$judul = "invoice_".$row1['no_invoice'].".pdf";
	// end create pdf file
	
	$sql2 = "UPDATE tr_invoice set sts_invoice = '2' where id_tr_invoice = '$row1[id_tr_invoice]'";
	$res2 = $db->query($sql2);
	
	if ($res2){	
	$sql3 = $db->fetchArray($db->query("select * from tb_user where id_tb_pendaftaran = '$row1[id_tb_pendaftaran]'"));
	
	$mail = new PHPMailer;
	
	$alias = 'Maxi-Line';
	$username = 'business.admin@manunggalintegrasi.com';
	$password = 'M4nungg4l01*';
	$dari = 'maxisupport@maxi-line.net';
	
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
	
	$mail->addAddress($sql3['email']);
	
	// Menambahkan beberapa penerima
	//$mail->addAddress('penerima2@contoh.com');
	//$mail->addAddress('penerima3@contoh.com');
	
	$mail->addAttachment("invoice_tagihan/".$judul);
	
	// Menambahkan cc atau bcc 
	//$mail->addCC($maile);
	$mail->addBCC('business.admin@manunggalintegrasi.com');
	
	
	// Subjek email
	$mail->Subject = "INVOICE MAXI LINE - $row1[no_invoice]";
  
    // Mengatur format email ke HTML
    $mail->isHTML(true);
	
	$linkp = "https://www.maxi-line.net/dashboard/invoice_p.php?id=".maxiline($row1[id_tr_invoice], 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
	
	$mailContent = '
		<html>
		<head>
		<title>INVOICE MAXI LINE - '.$row1['no_invoice'].'</title>
		</head>
		<body>
		<p>Dengan Hormat</p>
		<br>
		<p>Kami informasikan bahwa pada '.tglindo(date_format($row1['tgl_invoice'], 'Y-m-d')).' billing kami telah menerbitkan invoice untuk layanan yang anda gunakan. <p>Silahkan login melalui tautan berikut 
		<a href="https://www.maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="https://www.maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a> untuk dapat melihat detil invoice, atau anda dapat melihat langsung melalui tautan berikut 
		<a href="'.$linkp.'" target="_blank" data-saferedirecturl="'.$linkp.'"><b>--- '.$row1['no_invoice'].' ---</b></a></p>
		<p>Mohon untuk melakukan pembayaran sebelum tanggal '.tglindo(date_format($row1['tgl_expired'], 'Y-m-d')).'</p>
		<br>
		<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		</body>
		</html>
	';

	$mail->Body = $mailContent;
	if(!$mail->send()){
	echo 'Mailer Error: ' . $mail->ErrorInfo;
	exit();}
		
	}
}	
}

// cek invoice yg belum bayar expired
/*
$sql3 = "SELECT * FROM tr_invoice WHERE sts_invoice = '2' and sts_lunas = '1' and sts_data = '1' and tgl_expired < GETDATE()";
$res3 = $db->query($sql3);
while($row3 = $db->fetchArray($res3)) {
	
	$sql7 = "UPDATE tr_invoice set sts_data = '2' where id_tr_invoice = '$row3[id_tr_invoice]'";
	$res7 = $db->query($sql7);
	
	if ($res7){	
	$sql8 = $db->fetchArray($db->query("select * from tb_user where id_tb_pendaftaran = '$row3[id_tb_pendaftaran]'"));
	
	$mail = new PHPMailer;
	
	$alias = 'Maxi-Line';
	$username = 'business.admin@manunggalintegrasi.com';
	$password = 'M4nungg4l01*';
	$dari = 'maxisupport@maxi-line.net';
	
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
	
	$mail->addAddress($sql8['email']);
	
	// Menambahkan beberapa penerima
	//$mail->addAddress('penerima2@contoh.com');
	//$mail->addAddress('penerima3@contoh.com');
	
	// Menambahkan cc atau bcc 
	//$mail->addCC($maile);
	$mail->addBCC('business.admin@manunggalintegrasi.com');
	// Subjek email
	$mail->Subject = "INVOICE MAXI LINE - $row3[no_invoice] Expired";
  
    // Mengatur format email ke HTML
    $mail->isHTML(true);
	
	$linkp3 = "https://www.maxi-line.net/dashboard/invoice_p.php?id=".maxiline($row3[id_tr_invoice], 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
	
	$mailContent = '
		<html>
		<head>
		<title>INVOICE MAXI LINE - '.$row3['no_invoice'].' Expired</title>
		</head>
		<body>
		<p>Dengan Hormat</p>		
		<p>Kami informasikan bahwa pada hari ini tagihan berlangganan anda telah jatuh tempo, dimohon untuk segera melunasi pembayaran anda.
		<br>
		<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		</body>
		</html>
	';

	$mail->Body = $mailContent;	
	
	if(!$mail->send()){
	echo 'Mailer Error: ' . $mail->ErrorInfo;
	exit();}
	}
}
*/

// expired invoice yg lebih dr masa akhir
$sql5 = "SELECT * FROM tr_invoice WHERE sts_invoice = '2' and sts_lunas = '1' and sts_data = '2' and DATEADD(day, 1, tgl_expired) < GETDATE()";
$res5 = $db->query($sql5);
while($row5 = $db->fetchArray($res5)) {
	$sql6 = "UPDATE tr_invoice set sts_lunas = '3' where id_tr_invoice = '$row5[id_tr_invoice]'";
	$res6 = $db->query($sql6);		
}

// hapus laporan yg st_data = 2
$sql7 = "DELETE FROM tr_laporan WHERE sts_data = '2' ";
$res7 = $db->query($sql7);
?>
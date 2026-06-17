<?php
include "include/config.php";
include "include/DbConnector.php";
include "invoice_p_file.php";
require_once "include/MikrotikApi.php";

$db = new DbConnector();

// cek pelaporan yg lbh dr 3 hari tidak ditutup
$now = new DateTime();
$tgl = $now->format("Y-m-d H:i:s");

//echo $tgl;
//exit;

$sql = "SELECT max(id_tr_respon) as id_tr_respon FROM v_pelaporan_respon WHERE sts_laporan = '2' group by code_pelaporan";
$res = $db->query($sql);

// echo $sql;
// exit;

while($row = $db->fetchArray($res)) {
	$sqla = $db->fetchArray($db->query("select *, DATEADD(DAY, 3, tgl_data) AS expired from tr_respon where id_tr_respon = '$row[id_tr_respon]'"));
	$sqle = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$sqla[id_tb_user]'"));
	$tgexpired = $sqla['expired']->format("Y-m-d H:i:s");
	//exit;

	if($tgl > $tgexpired && $sqle['level_user'] != 5){
		$sqlb = "UPDATE tr_pelaporan set sts_laporan = '3', tgl_close='$tgl' where id_tr_pelaporan = '$sqla[id_tr_pelaporan]'";
		$resb = $db->query($sqlb);

		if ($resb) {
			$sqlc = $db->fetchArray($db->query("select * from tr_pelaporan where id_tr_pelaporan = '$sqla[id_tr_pelaporan]'"));
			$sqld = $db->fetchArray($db->query("select * from tb_user where id_tb_pendaftaran = '$sqlc[id_tb_pendaftaran]'"));
	
			// Kirim Email.
			$recipients1 = [$sqld['email']];
			$cc1 = ['cs@maxi-line.net'];
			$bcc1 = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com'];
			$subject1 = "Pelaporan - $sqlc[code_pelaporan] selesai";
			$mailContent1 = '
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

			$sendMail1 = sendToMails($recipients1, $cc1, $bcc1, $subject1, $mailContent1);

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
			// $mail->addAddress($sqld['email']);
			
			// Menambahkan beberapa penerima
			//$mail->addAddress('penerima2@contoh.com');
			//$mail->addAddress('penerima3@contoh.com');
			
			// Menambahkan cc atau bcc 
			//$mail->addCC($maile);
			// $mail->addBCC('business.admin@manunggalintegrasi.com');
			// Subjek email
			// $mail->Subject = "Pelaporan - $sqlc[code_pelaporan] selesai";
			
			// Mengatur format email ke HTML
			// $mail->isHTML(true);
			
			// $mailContent = '
			// 	<html>
			// 	<head>
			// 	<title>Pelaporan - '.$sqlc['code_pelaporan'].' Selesai</title>
			// 	</head>
			// 	<body>
			// 	<p>Dengan Hormat</p>
			// 	<p>Pelaporan anda dengan kode '.$sqlc['code_pelaporan'].' telah selesai.<br>
			// 	Terima kasih telah menjadi keluarga besar MAXI-LINE.
			// 	</p>
			// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p> 
			// 	</body>
			// 	</html>
			// ';

			// $mail->Body = $mailContent;
	
			if(!$sendMail1){
				echo "<script>alert('Pesan gagal dikirim! Silahkan hubungi administrator.');</script>
				<script>history.back();</script>";
				exit();
			} else {
				# code...
				echo "<script>alert('Pesan Berhasil dikirim!');</script>
				<script>history.back();</script>";
				exit();
			}
	
		}
	
	}
}

// cek auto terbit tiap invoice
$sql1 = "SELECT * FROM tr_invoice WHERE sts_invoice = '1'";
$res1 = $db->query($sql1);

while($row1 = $db->fetchArray($res1)) {
	$tgterbit = $row1['tgl_invoice']->format("Y-m-d H:i:s");

	//echo $row1['id_tr_invoice']."<br>";
	//echo $tgl."<br>";
	//echo $tgterbit."<br>";
	//echo $row1['tot_tagih']."<br>";
	//exit;

	if(!empty($row1['id_tr_invoice']) && ($tgl >= $tgterbit)){
		
		$sql4 = "SELECT * FROM tr_transaksi WHERE id_tr_invoice = '$row1[id_tr_invoice]'";
		$jm = $db->queryNumRows($sql4);
		$tota = $db->getNumRows($jm);
		
		if(!empty($tota)) {
		
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

		$sql2 = "UPDATE tr_invoice set sts_invoice = '2' where id_tr_invoice = '$row1[id_tr_invoice]'";
		$res2 = $db->query($sql2);
	
		if ($res2){	
			$sql3 = $db->fetchArray($db->query("select * from tb_user where id_tb_pendaftaran = '$row1[id_tb_pendaftaran]'"));
			$sql4 = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$row1[id_tb_pendaftaran]'"));
	
			// Kirim Email.
			$recipients2 = [$sql3['email']];
			$cc2 = ['cs@maxi-line.net'];
			$bcc2 = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com'];
			$subject2 = "INVOICE MAXI LINE - $row1[no_invoice]";

			$linkp = "https://www.maxi-line.net/dashboard/invoice_p.php?id=".maxiline($row1['id_tr_invoice'], 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
			$attach2 = "invoice_tagihan/".$judul;

			$mailContent2 = '
				<html>
				<head>
				<title>INVOICE MAXI LINE - '.$row1['no_invoice'].'</title>
				</head>
				<body>
				<p>Dengan Hormat</p>
				<br>
				<p>Pelanggan a/n '.$sql4['nama'].', ID Pelanggan no. '.$sql4['kode_daftar'].', kami informasikan bahwa pada '.tglindo(date_format($row1['tgl_invoice'], 'Y-m-d')).' billing kami telah menerbitkan invoice untuk layanan yang anda gunakan sebesar Rp. '.number_format($row1['tot_tagih'],0,',','.').'.</p> <p>Silahkan login melalui tautan berikut 
				<a href="https://www.maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="https://www.maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a> untuk dapat melihat detil invoice, atau anda dapat melihat langsung melalui tautan berikut 
				<a href="'.$linkp.'" target="_blank" data-saferedirecturl="'.$linkp.'"><b>--- '.$row1['no_invoice'].' ---</b></a></p>				
				<p>Mohon untuk melakukan pembayaran sebelum tanggal '.tglindo(date_format($row1['tgl_expired'], 'Y-m-d')).' ke nomer Virtual Akun BRI berikut : '.$sql4['va_bri'].', atau anda dapat mentransfer langsung ke Rek. BRI No. 051501000638307 a/n Manunggal Sistem Sejahtera. Untuk cara pembayaran dapat anda lihat melalui tautan berikut 
				<a href="https://www.maxi-line.net/carapembayaran/" target="_blank" data-saferedirecturl="https://www.maxi-line.net/carapembayaran/"><b>www.maxi-line.net/carapembayaran/</b></a></p>
				<br>
				<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
				</body>
				</html>
			';

			$sendMail2 = sendToMails($recipients2, $cc2, $bcc2, $subject2, $mailContent2, $attach2);
			$html = "Tagihan internet maxi-line pelanggan ".$sql4['nama'].", ID Pelanggan no. ".$sql4['kode_daftar']."   Rp. ".number_format($row1['tot_tagih'],0,',','.').", harap dibayar ke VA BRI ".$sql4['va_bri']." atau ke BRI No. 051501000638307 a/n Manunggal Sistem Sejahtera. Cara pembayaran cek di www.maxi-line.net/carapembayaran/, terima kasih";

			if (substr($sql4['telp'], 0, 1) == '0') {
				$ta =  "+62".substr($sql4['telp'], 1);
				$kirimya = sendSMS($ta, $html);
			} else if (substr($sql4['telp'], 0, 1) == '6') {
				$ta =  "+".$sql4['telp'];
				$kirimya = sendSMS($ta, $html);
			} else if (substr($sql4['telp'], 0, 1) == '+') {
			$ta =  $sql4['telp'];
				$kirimya = sendSMS($ta, $html);
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
			
			// $mail->addAddress($sql3['email']);
			//$mail->addAddress('business.admin@manunggalintegrasi.com');
			
			// Menambahkan cc atau bcc 
			//$mail->addCC($maile);
			// $mail->addBCC('business.admin@manunggalintegrasi.com');
			// Subjek email
			// $mail->Subject = "INVOICE MAXI LINE - $row1[no_invoice]";
		
				// Mengatur format email ke HTML
				// $mail->isHTML(true);
			
			// $linkp = "https://www.maxi-line.net/dashboard/invoice_p.php?id=".maxiline($row1['id_tr_invoice'], 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
	
			// $mailContent = '
			// 	<html>
			// 	<head>
			// 	<title>INVOICE MAXI LINE - '.$row1['no_invoice'].'</title>
			// 	</head>
			// 	<body>
			// 	<p>Dengan Hormat</p>
			// 	<br>
			// 	<p>Kami informasikan bahwa pada '.tglindo(date_format($row1['tgl_invoice'], 'Y-m-d')).' billing kami telah menerbitkan invoice untuk layanan yang anda gunakan. <p>Silahkan login melalui tautan berikut 
			// 	<a href="https://www.maxi-line.net/dashboard/" target="_blank" data-saferedirecturl="https://www.maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a> untuk dapat melihat detil invoice, atau anda dapat melihat langsung melalui tautan berikut 
			// 	<a href="'.$linkp.'" target="_blank" data-saferedirecturl="'.$linkp.'"><b>--- '.$row1['no_invoice'].' ---</b></a></p>
			// 	<p>Mohon untuk melakukan pembayaran sebelum tanggal '.tglindo(date_format($row1['tgl_expired'], 'Y-m-d')).'</p>
			// 	<br>
			// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
			// 	</body>
			// 	</html>
			// ';

			// $mail->Body = $mailContent;
			// if(!$sendMail2){
			// 	echo " <script>alert('Pesan gagal dikirim! Silahkan hubungi administrator');</script>
			// 	<script>history.back();</script>";
			// 	exit();
			// } else {
			// 	echo " <script>alert('Pesan berhasil dikirim!');</script>
			// 	<script>history.back();</script>";
			// 	exit();
			// }
		}
	  }
	}
}

// cek invoice yg belum bayar expired
$sql3 = "SELECT * FROM tr_invoice WHERE sts_invoice = '2' and sts_lunas = '1' and sts_data = '1' and DATEADD(DAY, 6, tgl_expired) < GETDATE()";
$res3 = $db->query($sql3);

while($row3 = $db->fetchArray($res3)) {
	
	$sql7 = "UPDATE tr_invoice set sts_data = '2' where id_tr_invoice = '$row3[id_tr_invoice]'";
	$res7 = $db->query($sql7);
	
	if ($res7) {
		$sql8 = $db->fetchArray($db->query("select * from tb_user where id_tb_pendaftaran = '$row3[id_tb_pendaftaran]'"));
		$sql18 = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$row3[id_tb_pendaftaran]'"));

		// Kirim Email.
		$recipients3 = [$sql8['email']];
		$cc3 = ['cs@maxi-line.net'];
		$bcc3 = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com'];
		$subject3 = "INVOICE MAXI LINE - $row3[no_invoice] Expired";

		$linkp3 = "https://www.maxi-line.net/dashboard/invoice_p.php?id=".maxiline($row3['id_tr_invoice'], 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
	
		$mailContent3 = '
			<html>
			<head>
			<title>INVOICE MAXI LINE - '.$row3['no_invoice'].' Expired</title>
			</head>
			<body>
			<p>Dengan Hormat</p>
			<p>Pelanggan a/n '.$sql18['nama'].', ID Pelanggan no. '.$sql18['kode_daftar'].', kami informasikan bahwa pada hari ini tagihan berlangganan anda sebesar Rp. '.number_format($row3['tot_tagih'],0,',','.').' telah jatuh tempo, dimohon untuk segera melunasi pembayaran anda dengan melakukan transfer ke nomer Virtual Akun BRI berikut : '.$sql8['va_bri'].', atau anda dapat mentransfer langsung ke Rek. BRI No. 051501000638307 a/n Manunggal Sistem Sejahtera. Untuk cara pembayaran dapat anda lihat melalui tautan berikut <a href="https://www.maxi-line.net/carapembayaran/" target="_blank" data-saferedirecturl="https://www.maxi-line.net/carapembayaran/"><b>www.maxi-line.net/carapembayaran/</b></a>.</p>
			
			Apabila anda ingin melihat detail tagihan yang belum dibayarkan, silahkan login melalui tautan berikut <a href="https://www.maxi-line.net/dashboard/" target="_blank"  data-saferedirecturl="https://www.maxi-line.net/dashboard/"><b>--- KLIK DISINI ---</b></a>.
			</p>
			<br>
			<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
			</body>
			</html>
		';

		$sendMail3 = sendToMails($recipients3, $cc3, $bcc3, $subject3, $mailContent3);

		if(!$sendMail3){
			echo " <script>alert('Pesan gagal dikirim! Silahkan hubungi administrator');</script>
			<script>history.back();</script>";
			exit();
		} else {
			$html = "Tagihan internet maxi-line pelanggan ".$sql4['nama'].", ID Pelanggan no. ".$sql4['kode_daftar']." Rp " .number_format($row3['tot_tagih'],0,',','.')." telah jatuh tempo. Segera bayar ke VA BRI ".$sql8['va_bri']." atau ke BRI No. 051501000638307 a/n Manunggal Sistem Sejahtera untuk menghindari pemutusan. Cara pembayaran cek di www.maxi-line.net/carapembayaran/, terima kasih.";
			
			if (substr($sql8['telp'], 0, 1) == '0') {
				$ta =  "+62".substr($sql8['telp'], 1);
				$kirimya = sendSMS($ta, $html);
			} else if (substr($sql8['telp'], 0, 1) == '6') {
				$ta =  "+".$sql8['telp'];
				$kirimya = sendSMS($ta, $html);
			} else if (substr($sql8['telp'], 0, 1) == '+') {
				$ta =  $sql8['telp'];
				$kirimya = sendSMS($ta, $html);
			}

			echo " <script>alert('Pesan berhasil dikirim!');</script>
			<script>history.back();</script>";
			exit();
		}
	
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
		
		// $mail->addAddress($sql8['email']);
	
		// // Menambahkan beberapa penerima
		// //$mail->addAddress('penerima2@contoh.com');
		// //$mail->addAddress('penerima3@contoh.com');
		
		// // Menambahkan cc atau bcc 
		// //$mail->addCC($maile);
		// $mail->addCC('business.admin@manunggalintegrasi.com');
		// $mail->addBCC('muhammad_dhani@manunggalintegrasi.com');

		// // Subjek email
		// $mail->Subject = "INVOICE MAXI LINE - $row3[no_invoice] Expired";

		// // Mengatur format email ke HTML
		// $mail->isHTML(true);
	
		// $linkp3 = "https://www.maxi-line.net/dashboard/invoice_p.php?id=".maxiline($row3[id_tr_invoice], 'e')."&token=ee3699b71069491a9bc15b2de313c50a";
	
		// $mailContent = '
		// 	<html>
		// 	<head>
		// 	<title>INVOICE MAXI LINE - '.$row3['no_invoice'].' Expired</title>
		// 	</head>
		// 	<body>
		// 	<p>Dengan Hormat</p>		
		// 	<p>Kami informasikan bahwa pada hari ini tagihan berlangganan anda telah jatuh tempo, dimohon untuk segera melunasi pembayaran anda.
		// 	<br>
		// 	<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini. Terima Kasih.</p> 
		// 	</body>
		// 	</html>
		// ';

		// $mail->Body = $mailContent;
		
		// if(!$mail->send()){
		// 	echo 'Mailer Error: ' . $mail->ErrorInfo;
		// 	exit();
		// }
	}
}

// expired invoice yg lebih dr masa akhir
$sql5 = "SELECT * FROM tr_invoice WHERE sts_invoice = '2' and sts_lunas = '1' and sts_data = '2' and DATEADD(day, 1, tgl_expired) < GETDATE()";
// $sql5 = "SELECT * FROM tr_invoice WHERE sts_invoice = '2' and sts_lunas = '1' and DATEADD(day, 1, tgl_expired) < GETDATE()";
$res5 = $db->query($sql5);

while($row5 = $db->fetchArray($res5)) {
	$sql6 = "UPDATE tr_invoice set sts_lunas = '3' where id_tr_invoice = '$row5[id_tr_invoice]'";
	$res6 = $db->query($sql6);

	if ($res6) {
		// Non-aktifkan Layanan
		$id = $row5['id_tb_pendaftaran'];
        $sqle = "SELECT * FROM tb_pendaftaran WHERE id_tb_pendaftaran='$id' AND st_layanan='8'";
        $resc = $db->query($sqle);
        $rowa = $db->fetchArray($resc);
    
        if (!empty($rowa)) {
            $name = $rowa['kode_daftar'] . '@maxi-line.net';
    
            // With Class MikrotikApi
            try {
                $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
                $secret = $mikrotik->getSecret($name);
    
                if (!empty($secret)) {
                    if ($mikrotik->disableService($secret[0]['.id'])) {
						echo " <script>alert('Layanan internet pelanggan '".$rowa['nama']."' gagal dinonaktifkan.');</script>
						<script>history.back();</script>";
						exit();
                    } else {
                        $id_daftar = SafeSQL($rowa['id_tb_pendaftaran']);
                        $sqlf = "UPDATE tb_pendaftaran SET st_layanan = '10' WHERE id_tb_pendaftaran = '$id_daftar'";
                        $resd = $db->query($sqlf);
    
                        if ($resd) {
							$pppoe = $mikrotik->getPppoeIdByName($name);

                            if ($mikrotik->removePppoe($pppoe[0]['.id'])) {
								echo " <script>alert('Layanan internet pelanggan '".$rowa['nama']."' gagal dinonaktifkan.');</script>
								<script>history.back();</script>";
								exit();
                            } else {
								// kirim email.
								$recipients4 = [$rowa['email']];
								$cc4 = ['cs@maxi-line.net'];
								$bcc4 = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
								$subject4 = "Penonaktifan Layanan Internet";
								$mailContent4 = '
									<html>
									<head>
									<title>MAXI-LINE - Penonaktifan Layanan Internet</title>
									</head>
									<body>
									<p>Yth. pelanggan a/n '.$rowa['nama'].', ID Pelanggan no. '.$rowa['kode_daftar'].' dengan berat hati kami menginformasikan bahwa layanan internet anda telah dinonaktifkan  dikarenakan <b>"Masih ada Tagihan yang belum diselesaikan"</b>.<br></p>
									<p> Segera lakukan pembayaran untuk tagihan anda agar dapat menikmati kembali layanan dari MAXI-LINE.
										Jika anda memiliki kendala anda dapat melakukan Pelaporan/Open tiket melalui website kami <a href="https://www.maxi-line.net/dashboard/login.php">www.maxi-line.net</a>, terima kasih.
									</p>
									<p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p>
									</body>
									</html>
								';
	
								$sendMail4 = sendToMails($recipients4, $cc4, $bcc4, $subject4, $mailContent4);
	
								if (!$sendMail4) {
									
									echo " <script>alert('Pesan gagal dikirim! Silahkan hubungi administrator');</script>
									<script>history.back();</script>";
									exit();
									
								} else {
									
									$html = "Kami informasikan layanan internet maxi-line pelanggan ".$rowa['nama'].", ID Pelanggan no. ".$rowa['kode_daftar']." dihentikan sementara karena tagihan belum dibayar. Segera lunasi untuk aktivasi layanan internet anda, terima kasih.";
				
									if (substr($sql8['telp'], 0, 1) == '0') {
										$ta =  "+62".substr($sql8['telp'], 1);
										$kirimya = sendSMS($ta, $html);
									} elseif (substr($sql8['telp'], 0, 1) == '6') {
										$ta =  "+".$sql8['telp'];
										$kirimya = sendSMS($ta, $html);
									} elseif (substr($sql8['telp'], 0, 1) == '+') {
										$ta =  $sql8['telp'];
										$kirimya = sendSMS($ta, $html);
									}

									$kode_daftar = SafeSQL($rowa['kode_daftar']);

									if (isset($_SESSION['id_tb_user']) || !empty($_SESSION['id_tb_user'])) {
										$id_tb_user = $_SESSION['id_tb_user'];
									} else {
										$id_tb_user = 'System';
									}

									$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

									$sql = "INSERT INTO tb_log_services(kode_daftar, st_layanan, user_aksi, url_aksi) VALUES ('".$kode_daftar."', '10', '".$id_tb_user."','".$url."' )";
									$result = $db->query($sql);

									if ($result) {
										echo " <script>alert('Pesan berhasil dikirim. Layanan internet pelanggan '".$rowa['nama']."' telah dinonaktifkan.');</script>
										<script>history.back();</script>";
										exit();
									} else {
										echo " <script>alert('Pesan berhasil dikirim. Log Pelanggan Services gagal dicatat!');</script>
										<script>history.back();</script>";
										exit();
									}
								}
							}
                        }
                    }
                } else {
					echo " <script>alert('Akun PPPOE pelanggan '".$rowa['nama']."' tidak ditemukan. Silahkan hubungi NOC/Administrator untuk menonaktifkan layanan.');</script>
					<script>history.back();</script>";
					exit();
                }
                
            } catch (Exception $e) {
				echo " <script>alert('".$e->getMessage()."');</script>
				<script>history.back();</script>";
                exit();
            }
        } else {
			echo " <script>alert('Data pelanggan tidak ditemukan.');</script>
			<script>history.back();</script>";
			exit();
        }
	}
}

// hapus laporan yg st_data = 2
$sql7 = "DELETE FROM tr_laporan WHERE sts_data = '2' ";
$res7 = $db->query($sql7);


// close aktivasi bila sudah lewat seminggu (tidak bisa edit & hapus)
$sql8 = "select * from tb_aktivasi where sts_aktivasi = '1' and DATEADD(day, 7, tgl_aktivasi) < GETDATE()";
$res8 = $db->query($sql8);
while($row8 = $db->fetchArray($res8)) {
	if(!empty($row8['id_tb_aktivasi'])) {
	$sql9 = "update tb_aktivasi set sts_aktivasi = '2' where id_tb_aktivasi = '$row8[id_tb_aktivasi]' ";
	$res9 = $db->query($sql9);	
	}	
}

// close perawatan bila sudah lewat seminggu (tidak bisa edit & hapus)
$sql10 = "select * from tb_perawatan where sts_perawatan = '1' and DATEADD(day, 7, tgl_perawatan) < GETDATE()";
$res10 = $db->query($sql10);
while($row10 = $db->fetchArray($res10)) {
	if(!empty($row10['id_tb_perawatan'])) {
	$sql11 = "update tb_perawatan set sts_perawatan = '2' where id_tb_perawatan = '$row10[id_tb_perawatan]' ";
	$res11 = $db->query($sql11);	
	}	
}

// close pemutusan bila sudah lewat seminggu (tidak bisa edit & hapus)
$sql12 = "select * from tb_pemutusan where sts_pemutusan = '1' and DATEADD(day, 7, tgl_pemutusan) < GETDATE()";
$res12 = $db->query($sql12);
while($row12 = $db->fetchArray($res12)) {
	if(!empty($row12['id_tb_pemutusan'])) {
	$sql13 = "update tb_pemutusan set sts_pemutusan = '2' where id_tb_pemutusan = '$row12[id_tb_pemutusan]' ";
	$res13 = $db->query($sql13);	
	}	
}

// close instalsi bila sudah lewat seminggu (tidak bisa edit & hapus)
$sql14 = "select * from tb_instalasi where sts_instalasi = '1' and DATEADD(day, 7, tgl_instalasi) < GETDATE()";
$res14 = $db->query($sql14);
while($row14 = $db->fetchArray($res14)) {
	if(!empty($row14['id_tb_instalasi'])) {
	$sql15 = "update tb_instalasi set sts_instalasi = '2' where id_tb_instalasi = '$row14[id_tb_instalasi]' ";
	$res15 = $db->query($sql15);	
	}	
}

// close survey bila sudah lewat seminggu (tidak bisa edit & hapus)
$sql16 = "select * from tb_survey where sts_survey = '1' and DATEADD(day, 7, tgl_survey) < GETDATE()";
$res16 = $db->query($sql16);
while($row16 = $db->fetchArray($res16)) {
	if(!empty($row16['id_tb_survey'])) {
	$sql17 = "update tb_survey set sts_survey = '2' where id_tb_survey = '$row16[id_tb_survey]' ";
	$res17 = $db->query($sql17);	
	}	
}
?>
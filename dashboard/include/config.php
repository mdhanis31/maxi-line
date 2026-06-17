<?php
error_reporting(0);
//include "session.php";
//include "sambung.php";

header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

$conf['paging'] = 50;
$conf['range_page'] = 5;

$conf['tahun'] = 2017;

function format_indo($tgl_data){
	$bulanindo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
	$tahun = substr($tgl_data, -19, -15);
	$bulan = substr($tgl_data, 5, -12);
	$tgl   = substr($tgl_data, 8, -9);
	$waktu = substr($tgl_data, 11, -3);
 
	$result = $tgl . " " . $bulanindo[(int)$bulan-1] . " ". $tahun." ".$waktu;		
	return($result);
}

function SafeSQL($str){
	$filter = stripslashes(strip_tags(htmlspecialchars($str,ENT_QUOTES)));
	return $filter;
}

function tglindo($datenya)
{ $bulan = array (1 =>   'Januari',
  'Februari',
  'Maret',
  'April',
  'Mei',
  'Juni',
  'Juli',
  'Agustus',
  'September',
  'Oktober',
  'November',
  'Desember'
);
$split = explode('-', $datenya);
return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];}

function format_bulan($tgl_data){
	$bulanindo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
	
	$bulan = substr($tgl_data, 5, -12);	
 
	$result = $bulanindo[(int)$bulan-1];		
	return($result);
}

function format_login($current_login){
	$bulanindo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
	$tahun = substr($current_login, -19, -15);
	$bulan = substr($current_login, 5, -12);
	$tgl   = substr($current_login, 8, -9);
	$waktu = substr($current_login, 11, -3);
 
	$result = $tgl . " " . $bulanindo[(int)$bulan-1] . " ". $tahun." ".$waktu;		
	return($result);
}

// cek browser

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

// get user IP

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
        $temp = penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
}

function terbilang($nilai) {
    if($nilai<0) {
        $hasil = "minus ". trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }     		
    return $hasil;
}
	
$st_layanan = array(
 '1' => 'Menunggu Survey',
 '2' => 'Diluar Jangkauan Layanan',
 '3' => 'Survey selesai, menunggu proses selanjutnya',
 '4' => 'Survey selesai, menunggu pemasangan',
 '5' => 'Pemasangan selesai, menunggu proses selanjutnya',
 '6' => 'Pemasangan selesai, menunggu pengaktifan',
 '7' => 'Pengaktifan selesai, menunggu penerbitan invoice',
 '8' => 'Layanan telah aktif',
 '9' => 'Layanan berakhir',
 '10' => 'Layanan Ditangguhkan'
);

$tipe_respon = array(
 '1' => 'Diproses',
 '2' => 'Kunjungan',
 '3' => 'Kunjungan Ulang',
 '4' => 'Report',
 '5' => 'Tanggapan'
);

$st_confirm = array(
 '1' => '<div class="label label-warning">Waiting</div>',
 '2' => '<div class="label label-success">Diterima</div>',
 '3' => '<div class="label label-danger">Ditolak</div>'
);

$st_rencana = array(
 '1' => 'Survey',
 '2' => 'Pemasangan',
 '3' => 'Aktivasi',
 '4' => 'Perawatan / Perbaikan',
 '5' => 'Pengambilan Perangkat (Pemutusan)'
);

$st_level = array(
 '1' => 'Administrator',
 '2' => 'Technical',
 '3' => 'NOC',
 '4' => 'Finance',
 '5' => 'Pelanggan',
 '6' => 'Reseller'
);

$tipe_identitas = array(
 '1' => 'KTP',
 '2' => 'SIM C',
 '3' => 'SIM A'
);

$st_survey = array(
 '1' => 'Direview',
 '2' => 'Disetujui',
 '3' => 'Ditolak'
);

$st_instal = array(
    '0' => 'On Progress',
    '1' => 'Pending',
    '2' => 'Selesai',
    '3' => 'Gagal'
);

$st_aktif = array(
 '1' => 'Pending',
 '2' => 'Selesai',
 '3' => 'Gagal'
);

$st_perawatan = array(
 '1' => 'Pending',
 '2' => 'Selesai',
 '3' => 'Gagal'
);

$st_pemutusan = array(
 '1' => 'Pending',
 '2' => 'Selesai',
 '3' => 'Gagal'
);

$st_pm_konsul = array(
 '1' => 'Baru',
 '2' => 'Dibaca',
 '3' => 'Dibalas'
);

$sts_invoice = array(
 '1' => 'Draft',
 '2' => 'Terbit'
);

$sts_lunas = array(
 '1' => '<div class="label label-warning">Belum Dibayar</div>',
 '2' => '<div class="label label-success">Lunas</div>',
 '3' => '<div class="label label-danger">Expired</div>'
);

$sts_lunaz = array(
 '1' => 'Belum Dibayar',
 '2' => 'Lunas',
 '3' => 'Expired'
);

$jns_potongan = array(
 '1' => 'Pemasangan',
 '2' => 'Bulanan',
 '3' => 'Layanan',
 '4' => 'Pemutusan'
);
 
$jns_invoice = array(
 '1' => 'Pemasang & Bulanan',
 '2' => 'Bulanan',
 '3' => 'Layanan Tambahan & Bulanan',
 '4' => 'Akhir Layanan'
);

$jns_transaksi = array(
 '1' => 'Tambahan',
 '2' => 'Tambahan fix',
 '3' => 'Bulanan'
);

$jns_laporan = array(
 '1' => 'Pembayaran',
 '2' => 'Teknis'
);

$sts_laporan = array(
 '1' => 'Open',
 '2' => 'Diproses',
 '3' => 'Close'
);

$st_chat = array(
 '1' => 'Administrator',
 '2' => 'Technical',
 '3' => 'Support',
 '4' => 'Finance',
 '5' => 'Anda'
);

//file proses verifikasi pendaftaran
$id_tb_proses = array(
 '1' => 'id_tb_survey',
 '2' => 'id_tb_instalasi',
 '3' => 'id_tb_aktivasi'
);

$st_proses = array(
 '1' => 'File survey',
 '2' => 'File pemasangan',
 '3' => 'File pengaktifan'
);

$item_check = array(
    '1' => 'Telah dilakukan aktivasi layanan',
    '2' => 'Telah dilakukan uji koneksi',
    '3' => 'Jaringan berjalan normal',
    '4' => 'Perangkat telah diserahkan ke pelanggan'
);

$item_indoor = array(
    'Ruijie RG-EW300N' => 'Ruijie RG-EW300N',
    'Tenda N300' => 'Tenda N300',
    'Starlink Router Gen 2' => 'Starlink Router Gen 2',
    'Starlink Router Gen 3' => 'Starlink Router Gen 3',
    'Starlink Router Mini' => 'Starlink Router Mini'
);

$item_outdoor = array(
    'Lite AP GPS' => 'Lite AP GPS',
    'Nanostation Loco M5' => 'Nanostation Loco M5',
    'Nanostation Loco 5AC' => 'Nanostation Loco 5AC',
    'Litebeam 5AC Gen 2' => 'Litebeam 5AC Gen 2',
    'Litebeam 5AC M5 23' => 'Litebeam 5AC M5 23',
    'Starlink Standart Kit Gen 2' => 'Starlink Standart Kit Gen 2',
    'Starlink Standart Kit Gen 3' => 'Starlink Standart Kit Gen 3',
    'Starlink Mini Kit' => 'Starlink Mini Kit'
);

function jnzinvoice($data)
{ $nilai = array(
 '1' => 'Pemasang & Bulanan',
 '2' => 'Bulanan',
 '3' => 'Layanan Tambahan & Bulanan',
 '4' => 'Akhir Layanan'
);

return $nilai[$data];
}

function statuslunaz($data)
{ $nilai = array(
 '1' => 'Belum Dibayar',
 '2' => 'Lunas',
 '3' => 'Expired'
);

return $nilai[$data];
}

function maxiline( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

function sendToMails($recipients, $cc, $bcc, $subject, $mailContent, $attach = null)
{
    require_once 'plugins/PHPmailer/class.phpmailer.php';

    $alias = 'Maxi-Line';
    $username = 'webapps_notif@manunggalsistemsejahtera.com';
    $password = 'Sejahtera01*';
    $dari = 'cs@maxi-line.net';

    $mailer = new PHPMailer;

    // Konfigurasi Mail
    $mailer->isSMTP(true);
    $mailer->SMTPDebug = 1;
    $mailer->Host = 'smtp.office365.com';
    $mailer->Port       = 587;
    $mailer->SMTPSecure = 'tls';
    $mailer->SMTPAuth   = true;
    $mailer->Username = $username;
    $mailer->Password = $password;

    $mailer->setFrom($dari, $alias);
    $mailer->addReplyTo($dari, $alias);

    // CC
    // $mailer->AddCC($cc);
    
    // Set Subject
    $mailer->Subject = $subject;

    // Add Attachment
    if(!empty($attach)) {
        $mailer->addAttachment($attach);
    }

    // Mail Content
    $mailer->IsHTML(true);
    $mailer->Body = $mailContent;

    try {
        // CC
		if(!empty($cc)) {
            foreach ($cc as $ccs) {
                $mailer->AddCC($ccs);
			}
		}
		
        // BCC
		if(!empty($bcc)) {
			foreach ($bcc as $bccs) {
				$mailer->AddBCC($bccs);
			}
		}
    
        foreach ($recipients as $recipient) {
            $mailer->AddAddress(trim($recipient));
            
            // Send Mail
            // $mailer->Send();
            if ($mailer->Send()) {
                // echo " Email berhasil dikirim!";
                return true;
            } else {
                // echo " Email gagal dikirim! Mailer error : " . $mailer->ErrorInfo;
                return false;
            }
        }
    } catch (\Throwable $e) {
        echo " Email gagal dikirim! Mailer error : " . $mailer->ErrorInfo;
        return false;
    }
	
}

function convertBytes($size, $precision = 2) {
    $units = array('B','kB','MB','GB','TB');
    $step = 1024;
    
    $i = 0;
    while (($size / $step) > 0.9) {
        $size = $size / $step;
        $i++;
    }
    return round($size, $precision). ' ' .$units[$i];
}

function sendSMS($to, $content){
    // send sms
    $url = 'https://smsgateway.manunggalgroup.com/r_service.php';

    $reg_id = "maxiline";
    $username = "maxiline";
    $password = "Manunggal01*";
    $type = "S";  // G group, S single
    $konten =  array(['nomer' => $to, 'pesan' => $content]);

    /*
    $type = "G";  // G group, S single
    $konten =  array(['group' => 'satu', 'pesan' => 'Bla..bla..bla'],['group' => 'dua', 'pesan' => 'Bli..bli..bli']);
    */

    $json_code = json_encode(array('reg_id' => $reg_id, 'username' => $username, 'password' => $password, 'type' => $type, 'konten' => $konten));

    $headers = array('Content-Type: application/json');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_code);
    $res = curl_exec($ch);
    curl_close($ch);
}
?>

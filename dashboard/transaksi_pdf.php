<?
@session_start();
include "include/DbConnector.php";
include "include/config.php";
//memanggil library mpdf
include("plugins/mpdf/autoload.php");

if ($_SESSION['level_user'] == 5) { 
	echo " <script>alert('Anda tidak memiliki akses!');</script>
	<script>location.href='index.php';</script>";
	exit();
}

$db = new DbConnector();
$nows = new DateTime();
$now = $nows->format("dmYHis");
$saiki = $nows->format("Y-m-d");
$kodeaman = $_SESSION['token'];

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_GET['token']!=$kodeaman) {
 ?><script>
	alert('Anda tidak memiliki hak akses!');
	history.back();
	</script><?php
	exit();	
}

if (empty($data1)) { 
$data1 = SafeSQL($_GET[d1]);
$data2 = SafeSQL($_GET[d2]);

$date1 = date("Y-m-d", strtotime($data1));
$date2 = date("Y-m-d", strtotime($data2))." 23:59:59";
} else {
$date1 = date('Y-m-01'); // hard-coded '01' for first day
$date2  = date('Y-m-t')." 23:59:59";
$time4 = date("Y-m-d", strtotime($date1));
$time5 = date("Y-m-d", strtotime($date2));

}
$id_tb_pelanggan = $_SESSION['id_tb_pelanggan'];

$waktu = "Transaksi ".tglindo(date("Y-m-d", strtotime($data1)))." s/d ".tglindo(date("Y-m-d", strtotime($data2)));
$sql = "select tri.*, tbp.nama, tbk.nama_paket,
CONCAT(DAY(tgl_invoice),' ', CASE MONTH(tgl_invoice) 
    WHEN 1 THEN 'Januari' 
    WHEN 2 THEN 'Februari' 
    WHEN 3 THEN 'Maret' 
    WHEN 4 THEN 'April' 
    WHEN 5 THEN 'Mei' 
    WHEN 6 THEN 'Juni' 
    WHEN 7 THEN 'Juli' 
    WHEN 8 THEN 'Agustus' 
    WHEN 9 THEN 'September'
    WHEN 10 THEN 'Oktober' 
    WHEN 11 THEN 'November' 
    WHEN 12 THEN 'Desember' END,' ',YEAR(tgl_invoice)) AS tahunnya, CONCAT(FORMAT(tgl_invoice,'H:mm'),' ','WIB') as waktu
	from tr_invoice tri LEFT JOIN tb_pendaftaran tbp on tbp.id_tb_pendaftaran = tri.id_tb_pendaftaran
    LEFT JOIN tb_paket tbk on tbp.id_tb_paket = tbk.id_tb_paket where sts_lunas = '2' and (tgl_invoice >= '$date1' and tgl_invoice < '$date2') order by tgl_invoice desc";
$res = $db->query($sql);
$sqq = $db->fetchArray($db->query("SELECT count(id_tr_invoice) as jml FROM tr_invoice where (tgl_invoice >= '$date1' and tgl_invoice < '$date2' and sts_lunas = '2')"));
$sqw = $db->fetchArray($db->query("SELECT sum(tot_tagih) as income FROM tr_invoice where (tgl_invoice >= '$date1' and tgl_invoice < '$date2' and sts_lunas = '2')"));


$isinye = array();
while($row = $db->fetchArray($res)) {					
		$n++;		
		$isinye[] = "<tr>
		<td class=\"andis kiri\">".$n."</td>
		<td class=\"andis kiri\">".$row['nama']."</td>
		<td class=\"andis kiri\">".$row['no_invoice']."</td>
		<td class=\"andis kiri\">".tglindo(date_format($row['tgl_invoice'], 'Y-m-d'))."</td>
		<td class=\"andis kiri\">".tglindo(date_format($row['tgl_dibayar'], 'Y-m-d'))."</td>
		<td class=\"andis kiri\">".$row['nama_paket']."</td>
		<td class=\"andis kiri kanan\" align=\"right\" style=\"padding-right:10px;\">".number_format($row['tot_tagih'],0,',','.')."</td>
		</tr>";
}
$isinya = implode('',$isinye);

$header = '
<htmlpageheader name="letterheader">
 <table width="100%" border="0">
    <tr>
     <td width="50%" align="center">
	 <img src="dist/img/MaxiLineinvoice.png" alt="chek" height="60" align="right" /></td>      		
	</tr>
	<tr>
	<td align="left" align="center" style="padding-bottom: 30px;" >'.$sqz[alamat_usaha].'</td>
	</tr>
  </table>		
</htmlpageheader>

<htmlpagefooter name="numfooter">
    <div style="font-size: 10pt; text-align: left; padding-top: 3mm;">
            Halaman {PAGENO} dari {nbpg} | Laporan Transaksi di Generate pada '.tglindo($saiki).'
        </div>
</htmlpagefooter>
<style>
    @page {    
        margin-bottom: 2cm;
        margin-left: 1.5cm;
        margin-right: 1.5cm;  
		header: html_letterheader;
		footer: numfooter;
    }   
	
	table {   border-spacing: 0; }
	
	.andi {
		padding-top: 5px;
		border-top: 0.1px solid black;
		padding-bottom: 5px;		
	}
	
	.andis {		
		border-bottom: 0.1px solid black;
		padding-top: 5px;
		padding-bottom: 5px;
	}
	
	td.amir {
		padding-bottom: 10px;
		padding-top: 10px;
		border-top: 0.1px solid black;
		border-bottom: 0.1px solid black;
	}
	
	td.mira {	
		border-left: 1px solid black;
		padding-left: 10px;
	}
	
	td.mari {
		border-right: 1px solid black;
		padding-right: 10px;
	}
	
	td.kanan {
		border-right: 1px solid black;
		padding-left: 10px;
		padding-right: 5px;
	}
	
	td.kiri {	
		border-left: 1px solid black;
		padding-left: 10px;
		padding-right: 5px;
	}
	
	.ade {
		padding-top:10px;
		padding-bottom: 20px;
		line-height: 150%;		
	}   

	.dudi {
		padding-bottom: 10px;		
	}
</style>
';

$isi = '
<html>
<head>
</head>
<body>
		
		<h3 style="text-align: center;margin-bottom:20px;">Laporan '.$waktu.'
</h3>
<p></p>
		<table style="height: 24px; width: 100%;">
		<tbody>
		<tr>
		<td style="width: 40%;height: 20px;" class="andi kiri"><b>Jumlah Invoice</b></td>		
		<td class="andi kiri kanan"><b>'.$sqq['jml'].'<b></td>
		</tr>
		<tr>
		<td class="andi andis kiri"><b>Income</b></td>
		<td class="andi andis kiri kanan"><b>Rp. '.number_format($sqw['income'],0,',','.').',00</b></td>
		</tr>		
		</tbody>
		</table>
		<p></p>
		<table style="height: 24px; width: 100%;">
		<thead>
		<tr>
		<td class="andi andis kiri" valign="top" style="width: 6%;height: 24px;"><b>No.</b></td>
		<td class="andi andis kiri" valign="top" style="width: 22%;height: 24px;"><b>Nama Pelanggan</b></td>
		<td class="andi andis kiri" valign="top" style="width: 13%;height: 24px;"><b>Invoice</b></td>
		<td class="andi andis kiri" valign="top" style="width: 14%;height: 24px;"><b>Tanggal</b></td>
		<td class="andi andis kiri" valign="top" style="width: 18%;height: 24px;"><b>Dibayar</b></td>
		<td class="andi andis kiri" valign="top" style="width: 14%;height: 24px;"><b>Layanan</b></td>
		<td class="andi andis kiri kanan" valign="top" style="width: 13%;height: 24px;"><b>Tagihan (Rp.)</b></td>
		</tr>
		</thead>
		<tbody>
		'.$isinya.'
		</tbody>
		</table>		
</body>
</html>
 ';  

 
	$judul = 'Transaksi_maxiline'."$now";;
	$mpdf = new \Mpdf\Mpdf([
	'setAutoTopMargin' => 'stretch',
	'format' => 'A4',
	'default_font_size' => '10',
	'default_font' => 'arial']);
	$mpdf->WriteHTML($header);
	$mpdf->WriteHTML($isi);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output($judul.".pdf" ,'I');	
    exit;
?>

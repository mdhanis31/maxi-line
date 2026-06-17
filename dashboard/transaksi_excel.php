<?
@session_start();
include "include/DbConnector.php";
include "include/config.php";

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


header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Transaksi_maxiline".$now.".xls");

?>
<html>
<head>
	<title>Laporan Biaya  <?if($date1 != 0) {echo $tgl1." sampai ".$tgl2;}?></title>
</head>
<style>
td.andis {
	background-color: #e8e8e8;
	font-size :12px;
	text-align:left;
	line-height: 1;
}

td.andi {
	border: solid thin;
	font-size :11px;
	text-align:left;
	line-height: 1;
}

th.andis {
	background-color: #e8e8e8;
	font-size :12px;
	text-align:left;
	line-height: 1;
}

th.andi {
	border: solid thin;
	font-size :11px;
	text-align:left;
	line-height: 1;
}	
</style>
<body>
	<table class="table table-bordered" style="margin-top:40px;">
		<thead style="background-color: #e8e8e8;">
		<tr>
			<th valign="top" colspan="7" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><b>Jumlah Invoice : <?echo $sqq['jml'];?><b></th>	
		</tr>
		<tr>
			<th valign="top" colspan="7" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><b>Income : <?echo "Rp. ".number_format($sqw['income'],0,',','.').",00";?><b></th>	
		</tr>
		<tr>
			<th valign="top" colspan="7" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"></th>	
		</tr>
		<tr>
			<th valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;">No.</th>
			<th valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;">Nama Pelanggan</th>
			<th valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;">Invoice</th>
			<th valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;">Tanggal</th>
			<th valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;">Dibayar</th>
			<th valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;">Layanan</th>
			<th valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;">Tagihan (Rp.)</th>
		</tr>
		</thead>
		<tbody>
		<?
		while($row = $db->fetchArray($res)) {	
		$n++;
		?>				
		<tr>
			<td valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><?=$n;?></td>
			<td valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><?=$row['nama'];?></td>
			<td valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><?=$row['no_invoice'];?></td>
			<td valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><?=$row['tahunnya'];?></td>
			<td valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><?=$row['tahunbayar'];?></td>
			<td valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><?=$row['nama_paket'];?></td>
			<td valign="top" style="border: solid thin;font-size :11pt; text-align:left; line-height: 1;"><?=number_format($row['tot_tagih'],0,',','.');?>,00
			</td>
		</tr>
		<?}?>
		</tbody>
	</table> 		
</body>
</html>
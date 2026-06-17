<?
//@session_start();
include "include/config.php";
include "include/DbConnector.php";

//memanggil library mpdf
include("plugins/mpdf/autoload.php");

$db = new DbConnector();

$id_tr_invoice = maxiline(SafeSQL($_GET['id']), 'd');

$sq = "SELECT * FROM tr_invoice where id_tr_invoice = '$id_tr_invoice'";
$re = $db->query($sq);
$ro = $db->fetchArray($re);
if(empty($ro['id_tr_invoice'])) {
echo "<script>alert('Nomer invoice tidak ditemukan!');</script>
	<script>location.href='404.html';</script>";
	exit();	
}

$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$ro[id_tb_pendaftaran]'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$sqlb = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$ro[id_tb_paket]'"));

$header.= '
<style>
@page {
        margin-top: 4cm;
        margin-bottom: 2cm;
        margin-left: 2cm;
        margin-right: 2cm;
    }
	
*
{
	border: 0;
	box-sizing: content-box;
	color: inherit;
	font-family: inherit;
	font-size: inherit;
	font-style: inherit;
	font-weight: inherit;
	line-height: inherit;
	list-style: none;
	margin: 0;
	padding: 0;
	text-decoration: none;
	vertical-align: top;
}

h1 { font: bold 100% sans-serif; letter-spacing: 0.5em; text-align: center; text-transform: uppercase; }

table.noborder  { font-size: 12px; table-layout: fixed; width: 100%; }
table.noborder  { border-collapse: separate; border-spacing: 0px; }
.noborder th, td { padding: 0em;}
.noborder th, td { border: 0px solid black }
.noborder th { border: 0px solid black }
.noborder td { border: 0px solid black }
.noborder tr { border: 0px solid black }


table.inventory  { font-size: 75%; table-layout: fixed; width: 100%; padding-top: 15px;}
table.inventory  { border-collapse: separate; border-spacing: 2px; }
.inventory th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
.inventory th, td { border-radius: 0.25em; border-style: solid; }
.inventory th { background: #EEE; border-color: #BBB; }
.inventory td { border-color: #DDD; }

table.meta  { font-size: 75%; table-layout: fixed; width: 100%; }
table.meta  { border-collapse: separate; border-spacing: 2px; }
.meta th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
.meta th, td { border-radius: 0.25em; border-style: solid; }
.meta th { background: #EEE; border-color: #BBB; }
.meta td { border-color: #DDD; }

table.balance  { font-size: 75%; table-layout: fixed; width: 100%; }
table.balance  { border-collapse: separate; border-spacing: 2px; }
.balance th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
.balance th, td { border-radius: 0.25em; border-style: solid; }
.balance th { background: #EEE; border-color: #BBB; }
.balance td { border-color: #DDD; margin :  0px 1px 0px 1px; padding : 0px 1px 0px 1px;}

html { font: 16px/1 Open Sans, sans-serif; overflow: auto; padding: 0.5in; }
html { background: #999; cursor: default; }

body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }

header { margin: 0 0 3em; }
header:after { clear: both; content: ""; display: table; }

header h1 { background: #009bc2; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
header address p { margin: 0 0 0.25em; }
header span, header img { display: block; float: left; }
header span { max-width: 100%; position: relative; }
header img { max-height: 100%; max-width: 100%; }
header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }

article, article address, table.meta, table.inventory { margin: 0 0 3em; }
article:after { clear: both; content: ""; display: table; }
article h1 { clip: rect(0 0 0 0); position: absolute; }

article address { float: left; font-size: 125%; font-weight: bold; }

table.meta, table.balance { float: right; width: 36%; }
table.meta:after, table.balance:after { clear: both; content: ""; display: table; }

table.meta th { width: 40%; }
table.meta td { width: 60%; }

table.inventory { clear: both; width: 100%; }
table.inventory th { font-weight: bold; text-align: center; }

table.balance th, table.balance td { width: 50%; }
table.balance td { text-align: right; }

aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 0.2em; }
aside h1 { border-color: #999; border-bottom-style: solid; }

.add, .cut
{
	border-width: 1px;
	display: block;
	font-size: .8rem;
	padding: 0.25em 0.5em;	
	float: left;
	text-align: center;
	width: 0.6em;
}

.add, .cut
{
	background: #9AF;
	box-shadow: 0 1px 2px rgba(0,0,0,0.2);
	background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
	background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
	border-radius: 0.5em;
	border-color: #0076A3;
	color: #FFF;
	cursor: pointer;
	font-weight: bold;
	text-shadow: 0 -1px 2px rgba(0,0,0,0.333);
}

.add { margin: -2.5em 0 0; }

.add:hover { background: #00ADEE; }

.cut { opacity: 0; position: absolute; top: 0; left: -1.5em; }
.cut { -webkit-transition: opacity 100ms ease-in; }

tr:hover .cut { opacity: 1; }

@media print {
	* { -webkit-print-color-adjust: exact; }
	html { background: none; padding: 0; }
	body { box-shadow: none; margin: 0; }
	span:empty { display: none; }
	.add, .cut { display: none; }
}

@page { margin: 0; }
</style>
';

$isi = '
<html>
<head>
</head>
<body>
		<header>
			<h1>Invoice</h1>
			<table style="width:95%; border: 0px solid black; border-collapse: collapse;" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td style="border: 0px solid black;">
			<p><img alt="" src="dist\img\MaxiLineinvoice.png" style="width:30%; margin-top:15px;"></p>
			</td>
			<td style="border: 0px solid black;" align="right">
			<table style="width:80%;">
				<tr class="meta">
					<th class="meta" style="font-size:12px;">Kode. Invoice</th>
					<td class="meta" style="font-size:12px;">'.$ro['no_invoice'].'</td>
				</tr>
				<tr class="meta">
					<th class="meta" style="font-size:12px;">Jenis Invoice</th>
					<td class="meta" style="font-size:12px;">'.$jns_invoice[$ro['jns_invoice']].'</td>
				</tr>
				<tr class="meta">
					<th class="meta" style="font-size:12px;">Tanggal</th>
					<td class="meta" style="font-size:12px;">'.tglindo(date_format($ro['tgl_invoice'], 'Y-m-d')).'</td>
				</tr>
				<tr class="meta">
					<th class="meta" style="font-size:12px;">Batas Akhir</th>
					<td class="meta" style="font-size:12px;">'.tglindo(date_format($ro['tgl_expired'], 'Y-m-d')).'</td>
				</tr>
				<tr class="meta">
					<th class="meta" style="font-size:12px;">Status</th>
					<td class="meta" style="font-size:12px;">'.$sts_lunas[$ro['sts_lunas']].'</td>
				</tr>
			</table>
			</td>
			</tr>
			</table>
		</header>
		<article>
		<table style="width:95%; border: 0px solid black; border-collapse: collapse;" cellspacing="0" cellpadding="0" align="center">
		<tr>
		<td style="border: 0px solid black;padding:0px;margin:0px;width: 40%;" valign="top" alight="left">
			<table class="meta" style="margin:6px 6px 0px 0px; float: left; width: 100%">
				<tr>
					<th style="font-size:12px;margin-left:0px;" valign="top">No. Pelanggan</th>
					<td style="font-size:12px;" valign="top">'.$rowa['kode_daftar'].'</td>
				</tr>
				<tr>
					<th style="font-size:12px;" valign="top">Nama</th>
					<td style="font-size:12px;" valign="top">'.$rowa['nama'].'</td>
				</tr>
			</table>
		</td>
		<td style="border: 0px solid black;padding:0px;margin:0px;width: 10%;" valign="top" alight="left">
		</td>
		<td style="border: 0px solid black;padding:0px;margin:0px;width: 50%;float: right;" valign="top">
			<table class="meta" style="margin:6px 0px 6px 6px; float: right; width: 100%">
				<tr>
					<th style="font-size:12px;" valign="top">Alamat</th>
					<td style="font-size:12px;" valign="top">'.$rowa['alamat'].'</td>
				</tr>
				<tr>
					<th style="font-size:12px;" valign="top">Rekening Pembayaran<br>(Virtual Account BRI)</th>
					<td style="font-size:12px;" valign="top"><b>'.$rowa['va_bri'].'</b></td>
				</tr>
			</table>
		</td>
		</tr>
		</table>
			<table align="center" class="inventory" style="width:95%;">
				<thead>
					<tr>
						<th style="font-size:12px;width:48%;">Item</th>
						<th style="font-size:12px;width:12%;">Jumlah</th>
						<th style="font-size:12px;width:20%;">Harga</th>
						<th style="font-size:12px;width:20%;">Subtotal</th>
					</tr>
				</thead>
				<tbody>';
				
				$sql1 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '3'";
				$res1 = $db->query($sql1);
				$row1 = $db->fetchArray($res1);
				$diskon =  $row1['harga_transaksi'] - $row1['sub_total'];
				
				if(($ro['jns_invoice'] == 1 || $ro['jns_invoice'] == 4) && ($row1['jns_transaksi'] == 3 && $sqlb['harga_paket'] != $row1['sub_total'])){
					$keter = "<span style=\"font-size:12px;color:red;\">".number_format($diskon,0,',','.').",00"."</span>";
					$kiter = "<span style=\"font-size:12px;\">Pemakaian layanan hanya ".$row1['jumlah']." Hari</span>";
					$koter = "<span style=\"font-size:12px;color:red;\">Rp. </span>";
				} else {
					$keter = "";
					$kiter = "";
					$koter = "";
				}

				if(!empty($sqlb['id_paket_utama'])) {
				$sql9 = "select * from tb_paket where id_tb_paket = '$sqlb[id_paket_utama]'";
				$res9 = $db->query($sql9);
				$row9 = $db->fetchArray($res9);	
				
				$diskonpaket = $row9['harga_paket'] - $sqlb['harga_paket'];	
				$diskonpak = "<span style=\"font-size:12px;color:red;\">".number_format($diskonpaket,0,',','.').",00"."</span>";
				$rppaket = "<span style=\"font-size:12px;color:red;\">Rp. </span>";
				
	$isi.= '	<tr>
                  <td style="font-size:12px;" >'.$row9['nama_paket'].'</td>
				  <td style="font-size:12px;" align="center">-</td>
				  <td>
				  <table class="noborder">
				  <tr>
				  <td>Rp. </td>
				  <td align="right">'.number_format($row9['harga_paket'],0,',','.').",00".'</td>
				  </tr>
				  </table>
				  </td>
				  <td style="font-size:12px;" >
				  <table class="noborder" style="100%">
				  <tr>
				  <td>Rp. </td>
				  <td align="right">'.number_format($row9['harga_paket'],0,',','.').",00".'</td>
				  </tr>
				  </table>				 
				  </td>
				</tr>
	
				<tr>
                  <td style="font-size:12px;" >Diskon Promo</td>
				  <td style="font-size:12px;" align="center">-</td>
				  <td>
				  <table class="noborder">
				  <tr>
				  <td>'.$rppaket.'</td>
				  <td align="right">'.$diskonpak.'</td>
				  </tr>
				  </table>
				  </td>
				  <td style="font-size:12px;" >
				  <table class="noborder" style="100%">
				  <tr>
				  <td>'.$rppaket.'</td>
				  <td align="right">'.$diskonpak.'</td>
				  </tr>
				  </table>				 
				  </td>
				</tr>';
				
				} else {
					
	$isi.= '	<tr>
                  <td style="font-size:12px;" >'.$row1['nama_transaksi'].'</td>
				  <td style="font-size:12px;" align="center">-</td>
				  <td>
				  <table class="noborder">
				  <tr>
				  <td>Rp. </td>
				  <td align="right">'.number_format($row1['harga_transaksi'],0,',','.').",00".'</td>
				  </tr>
				  </table>
				  </td>
				  <td style="font-size:12px;" >
				  <table class="noborder" style="100%">
				  <tr>
				  <td>Rp. </td>
				  <td align="right">'.number_format($row1['harga_transaksi'],0,',','.').",00".'</td>
				  </tr>
				  </table>				 
				  </td>
				</tr>';	
				
				}	
				
				if(!empty($keter)) {
					
	$isi.= '	<tr>
                  <td style="font-size:12px;" >'.$kiter.'</td>
				  <td style="font-size:12px;" align="center">-</td>
				  <td>
				  <table class="noborder">
				  <tr>
				  <td>'.$koter.'</td>
				  <td align="right">'.$keter.'</td>
				  </tr>
				  </table>
				  </td>
				  <td style="font-size:12px;" >
				  <table class="noborder" style="100%">
				  <tr>
				  <td>'.$koter.'</td>
				  <td align="right">'.$keter.'</td>
				  </tr>
				  </table>				 
				  </td>
				</tr>';	
					
				}

	$isi.= '	<tr>
                  <th style="font-size:12px;" class="noborder"></th>
				  <th style="font-size:12px;" align="center" class="noborder"></th>
				  <th align="center" class="noborder"></th>
				  <td style="font-size:12px;" class="noborder">
				  <table class="noborder" style="100%">
				  <tr>
				  <td>Rp. </td>
				  <td align="right">'.number_format($row1['sub_total'],0,',','.').",00".'</td>
				  </tr>
				  </table>				 
				  </td>
				</tr>';	
				
				$sql2 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '1'";
				$res2 = $db->query($sql2);
				while($row2 = $db->fetchArray($res2)) {
				if($row2['satuan']=="Rp"){ $hrgt = number_format($row2['harga_transaksi'],0,',','.').",00";} else
				{ $hrgt = "<p>".number_format($row2['harga_transaksi'],0,',','.').",00</p><p style=\"font-size:10px;font-style:italic;\">per ".$row2['satuan']."</p>"; }
					
	$isi.= '	<tr>
                  <td style="font-size:12px;">'.$row2['nama_transaksi'].'</td>
				  <td align="right" style="font-size:12px;" align="center">'.$row2['jumlah'].'</td>
				  <td align="right" style="font-size:12px;">
				  <table class="noborder">
				  <tr>
				  <td>Rp. </td>
				  <td align="right">'.$hrgt.'</td>
				  </tr>
				  </table>
				  </td>
				  <td>
				  <table class="noborder" style="100%">
				  <tr>
				  <td>Rp. </td>
				  <td align="right">'.number_format($row2['sub_total'],0,',','.').",00".'</td>
				  </tr>
				  </table>
				  </td>
				</tr>';
				}
				
				$sql3 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '2' and harga_transaksi != '0'";
				$res3 = $db->query($sql3);
				while($row3 = $db->fetchArray($res3)) {
				if($row3['satuan'] == "%"){$persen = $row3['jumlah']." %";} else {$persen = "-";}
				if($row3['satuan'] == "%"){$hrg3 = "-";} else { 
				$hrg3 = 
				"<table class=\"noborder\">
				  <tr>
				  <td valign=\"top\">Rp. </td>
				  <td align=\"right\" valign=\"top\">".number_format($row3['harga_transaksi'],0,',','.').",00</td>
				  </tr>				  
				 </table>";}
					
	$isi.= '	<tr>
					<td style="font-size:12px;">'.$row3['nama_transaksi'].'</td>
					<td style="font-size:12px;" align="center">'.$persen.'</td>
					<td style="font-size:12px;" align="center">'.$hrg3.'</td>
					<td style="font-size:12px;">
					<table class="noborder" style="100%">
					  <tr>
					  <td>Rp. </td>
					  <td align="right">'.number_format($row3['sub_total'],0,',','.').",00".'</td>
					  </tr>
					  </table>
					</td>
				</tr>';
				}
	$isi.= '	<tr>
					<td style="border: 0px solid black;padding:0px;0px;"></td>
					<td style="border: 0px solid black;padding:0px;0px;"></td>
					<th style="font-size:12px;" align="left">Total</td>
					<td style="font-size:12px;">
					<table class="noborder" style="100%">
					  <tr>
					  <td>Rp. </td>
					  <td align="right">'.number_format($ro['tot_tagih'],0,',','.').",00".'</td>
					  </tr>
					  </table>
					</td>
				</tr>
				<tr>
					<td style="border: 0px solid black;padding:0px;0px;"></td>
					<td style="border: 0px solid black;padding:0px;0px;"></td>
					<th style="font-size:12px;" align="left">Harus Dibayar</td>
					<td style="font-size:12px;">
					<table class="noborder" style="100%">
					  <tr>
					  <td style="font-weight: bold;">Rp. </td>
					  <td style="font-weight: bold;" align="right">'.number_format($ro['tot_tagih'],0,',','.').",00".'</td>
					  </tr>
					  </table>
					</td>
				</tr>
				</tbody>
			</table>			
			</td>
			</tr>
			</table>
		</article>
		<aside>
			<h1 style="text-align:left;text-transform:none;"><span style="font-size:10px;font: bold 100% sans-serif; letter-spacing: 0px; text-align: center;"></span></h1>
			<div style="width:90%;font-size:11px;">			
			<ul>';
			 $sqls = "SELECT * from tb_note";
		     $ress = $db->query($sqls);
		     while($rows = $db->fetchArray($ress)) {
				if($rows['id_tb_note'] == 1){
					if(!empty($rowa['va_bri'])) {	
					$penggantinya = str_replace("inidiganti",'<b>'.$rowa['va_bri'].'</b>',$rows['note']);
					} else {
					$penggantinya = str_replace("inidiganti",'<i style="color:red;">Hubungi CS kami.</i>',$rows['note']);	
					}	
	$isi.=   '<li><strong>'.$rows['judul'].' : </strong>'.$penggantinya.'</li>';  
				} else {				
	$isi.=   '<li><strong>'.$rows['judul'].' : </strong>'.$rows['note'].'</li>';  
				}
			}
			
			
	$isi.='</div>
		</aside>
		<div style="margin-top:20px;">		
		<tr>
		<p style="font-size: 12px;text-align:center;">Email : cs@maxi-line.net | Telp : (024) 76405322</p>
		<p style="font-size: 14px;margin-top:5px;font-weight:bold;text-align:center;">www.maxi-line.net</p>
		</tr>
		</div>
	</body>
</html>
 ';  


	$judul = 'invoice_'."$ro[no_invoice]";
	$mpdf = new \Mpdf\Mpdf([
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

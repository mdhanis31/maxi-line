<? 
$page= "invoice";

include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$th = $now->format("Y");
$bl = $now->format("m");
$Th = $now->format("y");
$tgl = $now->format("j");
$tge = $now->format("Y-m-05");
$waktubesok = date("d-m-Y", strtotime('+1 month' , strtotime($tge))) ;
$blnbesok = date("m", strtotime($waktubesok)) ;
$thbesok = date("Y", strtotime($waktubesok)) ;

//echo $tglinvoice;

$id_tb_pendaftaran = maxiline(SafeSQL($_GET['id']), 'd');
$id_tr_invoice = maxiline(SafeSQL($_GET['i']), 'd');

$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

//bikin no invoice//
$sqlv = $db->fetchArray($db->query("select *, no_invoice as noinvoice from (SELECT MAX(id_tr_invoice) as idinvoice from tr_invoice WHERE MONTH(tgl_invoice) = '$blnbesok'  and YEAR(tgl_invoice) = '$thbesok')a left join tr_invoice tri on a.idinvoice = tri.id_tr_invoice"));
//echo $sqlv;
//exit;
if(empty($sqlv['idinvoice'])) {$awal = 0;} 
else {$awal = substr($sqlv['noinvoice'], -3);}
$pmhnakhir = '001';
$nor = $pmhnakhir + $awal;
$noreg = sprintf('%03d', $nor);
$kd_invoice = "MXL".$rowa['kode_daftar'].$bl.$Th.$noreg;
//end bikin no invoice//

//echo $kd_invoice."<br>";
//echo $awal."<br>";
//exit;;

$sq = "SELECT * FROM tr_invoice where id_tr_invoice = '$id_tr_invoice'";
$re = $db->query($sq);
$ro = $db->fetchArray($re);
$jm = $db->queryNumRows($sq);
$tota = $db->getNumRows($jm);
if(!empty($tota)){
$tglinvoice = $ro['tgl_invoice']->format('d-m-Y');
$tglexpired = $ro['tgl_expired']->format('d-m-Y');
} else {
if($tg > 5){
$tgle = $now->format("Y-m-05");
$tglinvoice = date("Y-m-d", strtotime('+1 month' , strtotime($tgle)))." ".$waktue ;
$tglexpired = date("Y-m-d H:i:s", strtotime('+1 week' , strtotime($tglinvoice)));
} else {
$tglinvoice = $now->format("Y-m-05")." ".$waktue;
$tglexpired = date("Y-m-d H:i:s", strtotime('+1 week' , strtotime($tglinvoice)));
}	
}

if(empty($tota) and $_GET['j'] == 2) {
$id_daftar = SafeSQL($_GET['id']);	
if($_GET['b']=="t"){$by = "b=t";} else {$by = "";}
echo "<script>location.href='invoice_2.php?id=$id_daftar&n=y&kd=$kd_invoice&j=2&$by';</script>";
	   exit();	
} else if(empty($tota) and $_GET['j'] == 3) {
$id_daftar = SafeSQL($_GET['id']);	
echo "<script>location.href='invoice_.php?id=$id_daftar&n=y&kd=$kd_invoice&j=3';</script>";
	   exit();	
}  if(empty($tota) and $_GET['j'] == 4) {
$id_daftar = SafeSQL($_GET['id']);	
echo "<script>location.href='invoice_.php?id=$id_daftar&n=y&kd=$kd_invoice&j=4';</script>";
	   exit();	
} else if(empty($tota) and $_GET['j'] == 1) {
$id_daftar = SafeSQL($_GET['id']);	
echo "<script>location.href='invoice_.php?id=$id_daftar&n=y&kd=$kd_invoice&j=1';</script>";
	   exit();	
} 

$sqlb = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$rowa[id_tb_paket]'"));

if($_SESSION['level_user'] == 4 or $_SESSION['level_user'] == 5 or $_SESSION['level_user'] == 2) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();
}

$sq2 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '3'";
$re2 = $db->query($sq2);
$ro2 = $db->fetchArray($re2);
$jm2= $db->queryNumRows($sq2);
$tota2 = $db->getNumRows($jm2);  
?>
  <style>
	tr {
	font-size: 14px;
	}

	td {
	font-size: 14px;
	}
	
	.select2-container .select2-selection {   
    height: 35px !important;
	}
  </style>
  
  <!-- daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
            <h1 class="m-0 text-dark"> <?if(empty($ro['id_tr_invoice'])) { echo "Buat";} else if($ro['sts_invoice'] == 1) {echo "Draft";} else {echo "Detail";}?> Invoice</h1>          
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Invoice</li>
            </ol>
		</section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
		<div class="row">
        <!-- Left col -->     
		  <section class="col-lg-12">
          <!-- solid sales graph -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title">Invoice <?=$ro['no_invoice'];?></h3>
				<div class="box-tools">				       
				</div>
            
            </div>
			    <!-- SELECT2 EXAMPLE --> 
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Kode Pelanggan
                  </div>				
				<input class="form-control" value="<?=$rowa['kode_daftar'];?>" disabled>				
				</div>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <div class="form-group">
				<div class="input-group">
                  <div class="input-group-addon">
                  Nama
                  </div>				
				<input class="form-control" value="<?=$rowa['nama'];?>" disabled>				
				</div>
              </div>
              </div>
			   <div class="form-group">
                <div class="form-group">
				<div class="input-group">
                  <div class="input-group-addon">
                  Alamat
                  </div>				
				<input class="form-control" value="<?=$rowa['alamat'];?>" disabled>				
				</div>
              </div>
              </div>
			   <div class="form-group">
                <div class="form-group">
				<div class="input-group">
                  <div class="input-group-addon">
                  Tanggal Pendaftaran
                  </div>				
				<input class="form-control" value="<?=tglindo(date_format($rowa['tgl_data'], 'Y-m-d'));?>" disabled>				
				</div>
              </div>
              </div>
			   <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Jenis Invoice
                  </div>				
				<input class="form-control" value="<?=$jns_invoice[intval("0".$ro['jns_invoice'])]?>" disabled >				
				</div>
              </div>
			   <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon" <?if($ro['sts_invoice'] == 1) {?> style="background-color: #9b9b9b;color:white;"<?} else {?>style="background-color: #343a40;color:white;"<?}?>>
                   Status Invoice
                  </div>				
				<input class="form-control" value="<?=$sts_invoice[intval("0".$ro['sts_invoice'])]?>" disabled >				
				</div>
              </div>
			
			<div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon" <?if($ro['sts_lunas'] == 1) {?> style="background-color: #dba400;color:white;"<?} else if($ro['sts_lunas'] == 2) {?>style="background-color: #44a50f;color:white;"<?} else if($ro['sts_lunas'] == 3) {?>style="background-color: #ff0000;color:white;"<?}?>>
                   Status Pembayaran
                  </div>				
				<input class="form-control" value="<?=$sts_lunaz[intval("0".$ro['sts_lunas'])]?>" disabled >				
				</div>
              </div>
			
			
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
               <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Paket
                  </div>				
				<?if(empty($tota2)) {?>
				  
					 <form action="invoice_2.php" method="post" enctype="multipart/form-data" name="form" id="formpaket">
					  <input type="hidden" name="id_tb_pendaftaran" value="<?=$rowa['id_tb_pendaftaran'];?>" >
					  <input type="hidden" name="rubah_paket" value="ya" >
					  <input type="hidden" name="id_tr_invoice" value="<?=$id_tr_invoice;?>" >
					  <select class="form-control" name="id_tb_paket" id="idpaket">
						<?
						$sq1 = "select * from tb_paket";
						$re1 = $db->query($sq1);		
						while($ro1 = $db->fetchArray($re1)){?>
						
						<option value="<?=$ro1['id_tb_paket'];?>" <?if($ro1['id_tb_paket'] == $rowa['id_tb_paket']){echo "selected";}?>><?=$ro1['nama_paket'];?></option>
						
						<?}?>
						</select>
					  </form>				  
				  
				  <?} else {?>
				<input class="form-control" value="<?=$sqlb['nama_paket'];?>" disabled>	
				<?}?>		
				</div>
              </div>
              <!-- /.form-group -->
				<?php
					$sql = $db->query("SELECT TOP(1) * FROM tb_aktivasi WHERE id_tb_pendaftaran = $id_tb_pendaftaran ORDER BY id_tb_aktivasi DESC");
					$rowb = $db->fetchRow($sql);
				?>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">Tanggal Pengaktifan</div>
						<input class="form-control" value="<?= tglindo(date_format($rowb['tgl_aktivasi'], 'Y-m-d')) ?? 'Layanan Belum Aktif' ?>" disabled>				
					</div>
				</div>
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Status
                  </div>				
				<input class="form-control" value="<?=$st_layanan[$rowa['st_layanan']];?>" disabled>				
				</div>
              </div>
			    <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Email
                  </div>				
				<input class="form-control" value="<?=$rowa['email'];?>" disabled>				
				</div>
              </div>
			    <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Telp
                  </div>				
				<input class="form-control" value="<?=$rowa['telp'];?>" disabled>				
				</div>
              </div>
			   <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Tanggal Invoice
                  </div>				
				<input class="form-control" name="tgl_invoice" id="datepicker" value="<?=$tglinvoice;?>" disabled>				
				</div>
              </div>
			   <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Tanggal Berakhir
                  </div>				
				<input class="form-control" name="tgl_invoice" id="datepicker" value="<?=$tglexpired;?>" disabled>				
				</div>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->		
          </div>
          <!-- /.row -->
        </div>
	  
	  
      <!-- /.box -->
            <div class="box-body border-radius-none">
              <div class="row">
			  <div class="col-md-6">
			  
		      <div class="box-header">
			  <i class="fa fa-th"></i><h3 class="box-title">Biaya Tambahan Fix</h3>
              </div>
			  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>  
				  <th>Item</th>
				  <th style="text-align:right">Nominal</th>
				  <th style="text-align:right">Satuan</th>
                </tr>
                </thead>
				<?if(empty($tota2)) {?>
                <tbody>
				<?
				 $sqld = "SELECT * FROM tb_potongan where satuan = 'Rp' and jns_potongan = '2' and status ='1'";
				 $resd = $db->query($sqld);
				 $nmpotongan1 = array();
				 $potongan1 = array();
				 while($rowd = $db->fetchArray($resd)) {
				 ?>
				 <tr>
                  <td><?=$rowd['nama_potongan'];?></td>
				  <td align="right"><?=number_format($rowd['jumlah'],0,',','.').",00";?></td>				 
				  <td>Rp.</td>
				  </tr>
				 <?
				 $nmpotongan1[] = $rowd['nama_potongan'];
				 $potongan1[] = $rowd['jumlah'];
				 }
				
				 $sqle = "SELECT * FROM tb_potongan where satuan = '%' and jns_potongan = '2' and status ='1'";
				 $rese = $db->query($sqle);
				 $potongan2 = array();
				 while($rowe = $db->fetchArray($rese)) {
				 ?>
				 <tr>
                  <td><?=$rowe['nama_potongan'];?></td>
				  <td align="right"><?=$rowe['jumlah'];?></td>
				  <td>%</td>
				  </tr>
				 <?
				  $nmpotongan2[] = $rowe['nama_potongan'];
				  $potongan2[] = $rowe['jumlah'];
				 }?>	
                </tbody>
				<?} else {?>
				<tbody>
				<?
				$sqld = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '2'";
				$resd = $db->query($sqld);
				while($rowd = $db->fetchArray($resd)) {?>
				<tr>
                  <td><?=$rowd['nama_transaksi'];?></td>
				  <td align="right"><?if($rowd['satuan'] == "%") {echo $rowd['jumlah']." %";} else {echo $rowd['harga_transaksi'];}?></td>
				  <td><?=$rowd['satuan'];?></td>
				</tr>	 
				<?}?>
				</tbody>
				<?}?>
              </table>
			  
			  <div class="box-header">
			  <i class="fa fa-th"></i><h3 class="box-title">Biaya Tambahan</h3>
			  <?if($ro['sts_invoice'] == 1) {?>
			  <button type="button" data-toggle="modal" data-target="#vermodal1" class="btn btn-danger btn-xs open_modale" style="float:right;"><i class="fa fa-plus-square"> Tambah Biaya</i></button> <?}?>
              </div>
			  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>  
				  <th>Item</th>
				  <th style="text-align:right;">Nominal</th>
				  <th style="text-align:right;">Satuan</th>
				  <th style="text-align:right;">Jumlah</th>
				  <th style="text-align:right;">Sub Total (Rp.)</th>					  
				  <th></th>
                </tr>
                </thead>
				<?
				if(empty($tota2)) {
				?>
                <tbody>
				 <? 
				 $sqlc = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice'";
				 $resc = $db->query($sqlc);
				 $nama_transaksi = array();
				 $harga_transaksi = array();
				 $satuan = array();
				 $jumlah = array();
				 $subtotal = array();
				 while($rowc = $db->fetchArray($resc)) {
				 ?>
				 <tr>
                  <td><?=$rowc['nama_transaksi'];?></td>
				  <td align="right"><?=number_format($rowc['harga_transaksi'],0,',','.').",00";?></td>
				  <td><?=$rowc['satuan'];?></td>
				  <td align="right"><?=$rowc['jumlah'];?></td>
				  <td align="right"><?
				  $jmltransaksi = $rowc['harga_transaksi'] * $rowc['jumlah'];				  
				  echo number_format($jmltransaksi,0,',','.').",00";?></td>				 
				  <td><?
				  if($ro['sts_invoice'] == 1) {?>
				  <button type="button" data-toggle="modal" data-target="#vermodal2" class="btn btn-info btn-xs open_modalu" id="<?=$rowc['id_tr_transaksi'];?>" id2="t"><i class="fa fa-edit"></i></button> 
				  <a href="invoice_.php?i=<?=$rowc['id_tr_transaksi'];?>&k=h&token=<?echo $kodeaman;?>" class="btn btn-danger btn-xs open_modale" onclick="return confirm('Hilangkan biaya?')"><i class="fa fa-close"></i></a> 
				  <?}
				  ?></td>
				  </tr>
				 <?
				 $nama_transaksi[] = $rowc['nama_transaksi'];
				 $harga_transaksi[] = $rowc['harga_transaksi'];
				 $satuan[] = $rowc['satuan'];
				 $jumlah[] = $rowc['jumlah'];
				 $subtotal[] = $jmltransaksi;
				 }?>				 
                </tbody>
				<?} else {?>
				<tbody>
				<?
				$sqlc = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '1'";
				$resc = $db->query($sqlc);
				while($rowc = $db->fetchArray($resc)) {?>
				<tr>
                  <td><?=$rowc['nama_transaksi'];?></td>
				  <td align="right"><?=number_format($rowc['harga_transaksi'],0,',','.').",00";?></td>
				  <td><?=$rowc['satuan'];?></td>
				  <td align="right"><?=$rowc['jumlah'];?></td>
				  <td align="right"><?=number_format($rowc['sub_total'],0,',','.').",00";?></td>
				  <td></td>
				</tr>
				<?}?>
				</tbody>
				<?}?>
              </table>
			  
			  <!-- tambahan 8des2022 -->
			  <!--
			  <div class="box-header">
			  <i class="fa fa-th"></i><h3 class="box-title">Voucher / Promo</h3>
			  <?
			  $sqlx = "SELECT * FROM tr_diskon where id_tr_invoice = '$id_tr_invoice'";
			  $resx = $db->query($sqlx);
			  $rowx = $db->fetchArray($resx);
			  
			  if($rowx['satuan'] == "Rp") {
			  $sq1 = "SELECT sum(dipake) as totdipake FROM tr_diskon where id_tr_voucher = '$rowx[id_tr_voucher]' and id_tr_invoice != '$id_tr_invoice'";
			  $re1 = $db->query($sq1);
			  $ro1 = $db->fetchArray($re1);
			  $sisa = $rowx['jumlah'] - $ro1['totdipake'];
			  }
			  ?>
              </div>
			  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>  
				  <th>Kode</th>
				  <th style="text-align:right">Nominal</th>
				  <th style="text-align:right">Satuan</th>				  
				  <th></th>
                </tr>
                </thead>				
                <tbody>				
				 <tr>
                  <td><?=$rowx['nama_voucher'];?></td>
				  <td align="right"><?if($rowx['satuan'] == "Rp") { Echo number_format($sisa,0,',','.').",00";} else {echo $rowx['dipake'];}?></td>
				  <td><?=$rowx['satuan'];?></td>
				  <td></td>
				  </tr>			
                </tbody>				
              </table> 
			  -->
						
			<!-- end tambahan 8des2022 -->
			</div>
			
		<!-- /.box -->            
			  <div class="col-md-6">
		      <div class="box-header">
			  <i class="fa fa-th"></i><h3 class="box-title">Total Biaya</h3>
              </div>
			  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>  
				  <th style="width:40%;">Item</th>
				  <th style="text-align:right;">Jumlah</th>
				  <th style="text-align:right;">Harga</th>
				  <th style="text-align:right;">Subtotal</th>
                </tr>
                </thead>
				<?
				if(empty($tota2)) {
					
				$sql9 = "select * from tb_paket where id_tb_paket = '$sqlb[id_paket_utama]'";
				$res9 = $db->query($sql9);
				$row9 = $db->fetchArray($res9);	
				
				$diskonpaket = $row9['harga_paket'] - $sqlb['harga_paket'];
				
				?>
                <tbody>
				<?
				if(!empty($sqlb['id_paket_utama'])) {
				?>
				<tr>
				<td><?=$row9['nama_paket'];?></td>
				<td align="right"></td>
				<td align="right">Rp. <?=number_format($row9['harga_paket'],0,',','.').",00";?></td>
				<td align="right"></td>
				</tr>	
				<tr>
				<td><span style="color:red;">Diskon Promo</span></td>
				<td align="right"></td>
				<td align="right"><span style="color:red;">- Rp. <?=number_format($diskonpaket,0,',','.').",00";?></span></td>
				<td align="right">Rp. <?=number_format($sqlb['harga_paket'],0,',','.').",00";?></td>
				</tr>
				<?} else {?>
				<tr>
				<td><?=$sqlb['nama_paket']?></td>
				<td align="right">-</td>
				<td align="right">Rp. <?=number_format($sqlb['harga_paket'],0,',','.').",00";?></td>
				<td align="right"><span style="float:left;">Rp.</span><?=number_format($sqlb['harga_paket'],0,',','.').",00";?></td>
				</tr>
				<?}

				$tottran = array_sum($subtotal) + $sqlb['harga_paket'];
				foreach ($nama_transaksi as $s=>$namat) { 
				$namatrans = SafeSQL($namat);
				$hargat = SafeSQL($harga_transaksi[$s]);
				$satuant = SafeSQL($satuan[$s]);
				$jumlaht = SafeSQL($jumlah[$s]);
				$subtotalt = SafeSQL($subtotal[$s]);
				 ?>
				 <tr>
                  <td><?=$namatrans;?></td>
				  <td align="right"><?=$jumlaht;?></td>
				  <td align="right">Rp. <?=number_format($hargat,0,',','.').",00";?><?if($satuant=="Rp"){} else {echo "<span style=\"font-size:12px;\"><br>tiap ".$satuant."</span>";}?></td>
				  <td align="right"><span style="float:left;">Rp.</span> <?=number_format($subtotalt,0,',','.').",00";?></td>
				  </tr>
				 <?}?>				 
            
				<?
				 $totpot = array_sum($potongan1);
				 foreach ($nmpotongan1 as $a=>$namapot1) { 
				 $nmpot1 = SafeSQL($namapot1);
				 $hargapot1 = SafeSQL($potongan1[$a]);
				?>
				 <tr>
                  <td><?=$nmpot1;?></td>
				  <td align="right">-</td>
				  <td align="right">Rp. <?=number_format($hargapot1,0,',','.').",00";?></td>
				  <td align="right"><span style="float:left;">Rp.</span> <?=number_format($hargapot1,0,',','.').",00";?></td>
				  </tr>	
				 <?}?>
             
				<?
				 $alltot = $tottran + $totpot;
				 $hargamintot = array();
				 foreach ($nmpotongan2 as $b=>$namapot2) { 
				 $n++;
				 $nmpot2 = SafeSQL($namapot2);
				 $hargapot2 = SafeSQL($potongan2[$b]);
				 if($n==1){
				 $hargapersen = ($alltot * $hargapot2) / 100;
				 $hargaminuspersen = $alltot + $hargapersen;
				 $hargamintot[] = $hargapersen;
				 } else {
				 $hargapersen = ($hargaminuspersen * $hargapot2) / 100;
				 $hargaminuspersen = $hargaminuspersen + $hargapersen;
				 $hargamintot[] = $hargapersen;
				 }
				?>
				 <tr>
                  <td><?=$nmpot2;?></td>				  
				  <td align="right"><?=$hargapot2;?>%</td>
				  <td align="right">-</td>
				  <td align="right"><span style="float:left;">Rp.</span> <?=number_format($hargapersen,0,',','.').",00";?></td>
				  </tr>	
				 <?}
				 $totpotsen = array_sum($hargamintot);
				 $totall = $alltot + $totpotsen;
				 ?>			 
				  <tr>
                  <td></td>				  
				  <td></td>
				  <td align="right"><b>Total</b></td>
				  <td align="right"><span style="float:left;"><b>Rp.</span> <?=number_format($totall,0,',','.').",00";?></b></td>
				  </tr>
                </tbody>
				<?} else {?>
				<tbody>
				<?
				$sql1 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '3'";
				$res1 = $db->query($sql1);
				$row1 = $db->fetchArray($res1);
				
				$sql9 = "select * from tb_paket where id_tb_paket = '$sqlb[id_paket_utama]'";
				$res9 = $db->query($sql9);
				$row9 = $db->fetchArray($res9);
				$diskonpaket = $row9['harga_paket'] - $sqlb['harga_paket'];
				
			
				if(!empty($sqlb['id_paket_utama'])) {
				?>
				<tr>
				<td><?=$row9['nama_paket'];?></td>
				<td align="right"></td>
				<td align="right">Rp. <?=number_format($row9['harga_paket'],0,',','.').",00";?></td>
				<td align="right"></td>
				</tr>	
				<tr>
				<td><span style="color:red;">Diskon Promo</span></td>
				<td align="right"></td>
				<td align="right"><span style="color:red;">- Rp. <?=number_format($diskonpaket,0,',','.').",00";?></span></td>
				<td align="right">Rp. <?=number_format($sqlb['harga_paket'],0,',','.').",00";?></td>
				</tr>
				<?} else {?>				
				<tr>
				<td><?=$sqlb['nama_paket']?></td>
				<td align="right">-</td>
				<td align="right">Rp. <?=number_format($sqlb['harga_paket'],0,',','.').",00";?></td>
				<td align="right"></td>
				</tr>
				<?}
				
				$sql2 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '1'";
				$res2 = $db->query($sql2);
				while($row2 = $db->fetchArray($res2)) {?>
				<tr>
                  <td><?=$row2['nama_transaksi'];?></td>
				  <td align="right"><?=$row2['jumlah'];?></td>
				  <td align="right">Rp. <?=number_format($row2['harga_transaksi'],0,',','.').",00";?><?if($row2['satuan']=="Rp"){} else {echo "<span style=\"font-size:12px;\"><br>tiap ".$row2['satuan']."</span>";}?></td>
				  <td align="right"><span style="float:left;">Rp.</span> <?=number_format($row2['sub_total'],0,',','.').",00";?></td>
				</tr>
				<?}
				$sql3 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '2' and harga_transaksi != '0'";
				$res3 = $db->query($sql3);
				while($row3 = $db->fetchArray($res3)) {?>
				<tr>
                  <td><?=$row3['nama_transaksi'];?></td>
				  <td align="right"><?if($row3['satuan'] == "%"){echo $row3['jumlah']." %";} else {echo "";}?></td>
				  <td align="right"><?if($row3['satuan'] == "%"){echo "";} else { echo "Rp. ".number_format($row3['harga_transaksi'],0,',','.').",00";}?></td>
				  <td align="right"><span style="float:left;">Rp.</span> <?=number_format($row3['sub_total'],0,',','.').",00";?></td>
				</tr>
				<?}?>
				<tr>
                  <td></td>				  
				  <td></td>
				  <td align="right"><b>Total</b></td>
				  <td align="right"><span style="float:left;"><b>Rp.</span> <?=number_format($ro['tot_tagih'],0,',','.').",00";?></b></td>
				  </tr>
				</tbody>
				<?}?>
              </table>
			  </div>
			</div>
			 <div style="margin-top:50px;">
			<div style="margin-top:50px;">	
			<?
			if($ro['sts_invoice'] == 2){?>
			<a href="invoice_p.php?id=<?=maxiline($id_tr_invoice, 'e');?>&token=<?=$kodeaman?>"  target="_BLANK" class="btn btn-warning">Cetak Invoice</a>
			<?} else if($rowa['st_layanan'] == 8 and !empty($tota2)){?>
			<form action="invoice_2.php" method="post" enctype="multipart/form-data" name="form" id="form3" style="width: 20%; display: contents;">
			<input type="hidden" name="tgl_invoice" value="<?=$tglinvoice;?>" id="tgl_invoice">
			<input type="hidden" name="id_tr_invoice" value="<?=$id_tr_invoice;?>" >
			<button  class="btn btn-warning" name="invoice" value="simpan">Simpan Draft</button>
			</form> 
			<?} else if(empty($tota2)){?>
			<form action="invoice_2.php" method="post" enctype="multipart/form-data" name="form" id="form2" >
			 <input type="hidden" name="nama_transaksi1" value="<?=implode(',', $nmpotongan1);?>" >
			 <input type="hidden" name="harga_transaksi1" value="<?=implode(',', $potongan1);?>" >
			 
			 <input type="hidden" name="nama_transaksi2" value="<?=implode(',', $nmpotongan2);?>" >
			 <input type="hidden" name="harga_transaksi2" value="<?=implode(',', $potongan2);?>" >
			 <input type="hidden" name="sub_total2" value="<?=implode(',', $hargamintot);?>" >
			 
			<?php 
				if(!empty($sqlb['id_paket_utama'])) {
					$namapaketnya = $row9['nama_paket'];
				} else {
					$namapaketnya = $sqlb['nama_paket'];
				}
			?>
			 <!-- < $sqlb['id_paket_utama'] ?> -->
			 <input type="hidden" name="nama_transaksi" value="<?=$namapaketnya;?>" >
			 <input type="hidden" name="harga_transaksi" value="<?=$sqlb['harga_paket'];?>" >
			 <input type="hidden" name="sub_total" value="<?=$sqlb['harga_paket'];?>" >
			 
			 <input type="hidden" name="total" value="<?=$totall;?>" >
			 <input type="hidden" name="id_tr_invoice" value="<?=$id_tr_invoice;?>" >
			 <input type="hidden" name="tgl_invoice" value="<?=$tglinvoice;?>" id="tgl_invoice">
			 <input type="hidden" name="token" value="<?=$kodeaman?>" />
			 <input type="hidden" name="terbit" value="ya" />
			 <button type="submit" class="btn btn-warning">Simpan Invoice</button>
			</form>
			<?}?>
			</div>
			</div>
            <!-- /.card-body -->
          </div>
			<?if($ro['sts_invoice'] == 1) {?>
		  <div class="box-header">
			  <i class="fa fa-bullhorn" style="color:red;"></i><h3 class="box-title" style="color:red;">System secara otomatis akan menerbitkan draft invoice setiap bulan ditanggal 5</h3>
          </div>
		  <?}?>		  
      </section><!-- /.container-fluid -->
	  
    </div>
    <!-- /.content -->
  </section>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
  <!-- /.content-wrapper -->
   <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; <?=$th;?> Maxi-Line.</strong> All rights
    reserved.
  </footer><!-- End #footer -->

</div>
<!-- ./wrapper -->
 <!-- Modal Default Size -->
	<div class="modal fade" id="vermodal2">
		<div class="modal-dialog">			
			<div class="modal-content" id="data">
			
			</div>
		</div>
	</div>
	<!-- // Modal Default Size -->
	

	 <!-- Modal Default Size -->	
	<div class="modal fade" id="vermodal1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Masukkan Biaya Tambahan</h4>
              </div>
              <div class="modal-body">
               <div class="col-md-12">
				<form class="form-horizontal" action="invoice_2.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
				
				<input type="hidden" name="token" value="<?=$kodeaman?>" />
				<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran;?>" />
				<input type="hidden" name="id_tr_invoice" value="<?=$id_tr_invoice;?>" />
				<input type="hidden" name="status" value="bulanan" />
				<input type="hidden" name="input" value="y" />
				      <div class="form-group"> 
                        <div class="col-sm-12">	
                          <input type="text" class="form-control" id="code1" name="nama_transaksi" placeholder="Masukkan nama item" required>
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">
                          <input type="number" class="form-control" id="code2" name="harga_transaksi" placeholder="Masukkan harga satuan" required>
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">	
                          <input type="number" class="form-control" id="code3" name="jumlah" placeholder="Masukkan jumlah item" required>
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">
                          <input type="text" class="form-control" id="code4" name="satuan" placeholder="Masukkan satuan item" required>
						</div>
					   </div>			
                  
				 <div style="margin-top:50px;">
				   <button type="submit" class="btn btn-info" style="margin-right:5px;">Tambahkan</button>
				   <button type="reset" class="btn btn-default" data-dismiss="modal">Batal</button>
				 </div>				
				</form>
				</div>
              </div>
              <div class="modal-footer">
              
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
	
  <!-- Modal Default Size -->
	<div class="modal fade" id="vermodal3" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">			
			<div class="modal-content" id="data1">
			<div class="card-body login-card-body">
                <div class="modal-header">
					<h4 class="modal-title" id="defaultModalLabel">Pembayaran</h4>
				</div>
				<div class="modal-body">
		
				</div>
			</div>
			</div>
		</div>
	</div> 
	<!-- // Modal Default Size -->
				
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
$(document).on('click','.open_modalu',function()
	{
		var id=$(this).attr("id");
		var id2=$(this).attr("id2");
		$.ajax({
			url:"list_item2.php",
			method:"post",
			data:{id:id, id2:id2},
			success:function(data)
			{
				$('#data').html(data);
				$('#vermodal2').modal("show");
			}
		});
		
		$('#vermodal2').on('hidden.bs.modal', function () {
        window.location.reload(true);
		});
	});

	$("#idpaket").change(function() {
     $('#formpaket').submit();
	});
</script>
</body>
</html>
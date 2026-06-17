<? 
$page= "pembayaran";

include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$th = $now->format("Y");

$id_tb_pendaftaran = maxiline(SafeSQL($_GET['id']), 'd');
$id_tr_invoice = maxiline(SafeSQL($_GET['i']), 'd');

$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

//bikin no invoice//
$sqlv = $db->fetchArray($db->query("SELECT MAX(id_tr_invoice) as idinvoice from tr_invoice"));
if(empty($sqlv['idinvoice'])) {$awal = 1;} 
else {$awal = $sqlv['idinvoice'];}
$pmhnakhir = '00000';
$nor = $pmhnakhir + $awal;
$noreg = sprintf('%05d', $nor);
$kd_invoice = "MXL".$rowa['kode_daftar'].$noreg;
//end bikin no invoice//

$sq = "SELECT * FROM tr_invoice where id_tr_invoice = '$id_tr_invoice'";
$re = $db->query($sq);
$ro = $db->fetchArray($re);
$jm = $db->queryNumRows($sq);
$tota = $db->getNumRows($jm);
$tglinvoice = $ro['tgl_invoice']->format('d-m-Y');
$tglexpired = $ro['tgl_expired']->format('d-m-Y');

$sqlb = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$rowa[id_tb_paket]'"));

?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
            <h1 class="m-0 text-dark"> Detail Invoice</h1>          
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
				<input class="form-control" value="<?=$sqlb['nama_paket'];?>" disabled>				
				</div>
              </div>
              <!-- /.form-group -->
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
				<input class="form-control" name="tgl_invoice" value="<?=$tglinvoice;?>" disabled>				
				</div>
              </div>
			   <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Tanggal Berakhir
                  </div>				
				<input class="form-control" name="tgl_invoice" value="<?=$tglexpired;?>" disabled>				
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
				<?if($ro['sts_invoice'] == 1) {?>
                <tbody>
				<?
				 $sqld = "SELECT * FROM tb_potongan where satuan = 'Rp' and jns_potongan = '1' and status ='1'";
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
				
				 $sqle = "SELECT * FROM tb_potongan where satuan = '%' and jns_potongan = '1' and status ='1'";
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
				$sqld = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '2' and harga_transaksi != '0'";
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
				  <th style="text-align:right">Nominal</th>
				  <th style="text-align:right">Satuan</th>
				  <th style="text-align:right">Jumlah</th>
				  <th style="text-align:right">Sub Total (Rp.)</th>					  
				  <th></th>
                </tr>
                </thead>
				<?
				if($ro['sts_invoice'] == 1) {
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
			</div>
			
			      <!-- /.box -->            
			  <div class="col-md-6">
		      <div class="box-header">
			  <i class="fa fa-th"></i><h3 class="box-title">Total Biaya</h3>
              </div>
			  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>  
				  <th>Item</th>
				  <th style="text-align:right">Jumlah</th>
				  <th style="text-align:right">Harga</th>
				  <th style="text-align:right">Subtotal</th>
                </tr>
                </thead>
				<?
				if($ro['sts_invoice'] == 1) {
				?>
                <tbody>
				<tr>
				<td><?=$sqlb['nama_paket']?></td>
				<td align="right">-</td>
				<td align="right">Rp. <?=number_format($sqlb['harga_paket'],0,',','.').",00";?></td>
				<td align="right"><span style="float:left;">Rp.</span><?=number_format($sqlb['harga_paket'],0,',','.').",00";?></td>
				</tr>
				<?
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
				  <td align="right">Rp. <?=number_format($hargat,0,',','.').",00";?><?if($satuant=="Rp"){} else {echo "/".$satuant;}?></td>
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
				 $hargapot2 = SafeSQL($potongan2[$a]);
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
				while($row1 = $db->fetchArray($res1)) {?>
				<tr>
                  <td><?=$row1['nama_transaksi'];?></td>
				  <td align="right">-</td>
				  <td align="right">Rp. <?=number_format($row1['harga_transaksi'],0,',','.').",00";?></td>
				  <td align="right"><span style="float:left;">Rp.</span> <?=number_format($row1['sub_total'],0,',','.').",00";?></td>
				</tr>
				<?}
				$sql2 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '1'";
				$res2 = $db->query($sql2);
				while($row2 = $db->fetchArray($res2)) {?>
				<tr>
                  <td><?=$row2['nama_transaksi'];?></td>
				  <td align="right"><?=$row2['jumlah'];?></td>
				  <td align="right">Rp. <?=number_format($row2['harga_transaksi'],0,',','.').",00";?><?if($row2['satuan']=="Rp"){} else {echo "/".$row2['satuan'];}?></td>
				  <td align="right"><span style="float:left;">Rp.</span> <?=number_format($row2['sub_total'],0,',','.').",00";?></td>
				</tr>
				<?}
				$sql3 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '2'";
				$res3 = $db->query($sql3);
				while($row3 = $db->fetchArray($res3)) {?>
				<tr>
                  <td><?=$row3['nama_transaksi'];?></td>
				  <td align="right"><?if($row3['satuan'] == "%"){echo $row3['jumlah']." %";} else {echo "-";}?></td>
				  <td align="right"><?if($row3['satuan'] == "%"){echo "-";} else { echo "Rp. ".number_format($row3['harga_transaksi'],0,',','.').",00";}?></td>
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
			<?if($ro['sts_invoice'] == 1) {?>
			<button  class="btn btn-info" id="terbit">Terbitkan Invoice</button>
			<?} else {?>
			<a href="invoice_p.php?id=<?=maxiline($id_tr_invoice, 'e');?>&token=<?=$kodeaman?>"  target="_BLANK" class="btn btn-warning">Cetak Invoice</a>
			<?}?>
			</div>
			</div>
            <!-- /.card-body -->
          </div>
		 <form action="invoice_.php" method="post" enctype="multipart/form-data" name="form" id="form2" >
		 <input type="hidden" name="nama_transaksi1" value="<?=implode(',', $nmpotongan1);?>" >
		 <input type="hidden" name="harga_transaksi1" value="<?=implode(',', $potongan1);?>" >
		 
		 <input type="hidden" name="nama_transaksi2" value="<?=implode(',', $nmpotongan2);?>" >
		 <input type="hidden" name="harga_transaksi2" value="<?=implode(',', $potongan2);?>" >
		 <input type="hidden" name="sub_total2" value="<?=implode(',', $hargamintot);?>" >
		 
		 <input type="hidden" name="nama_transaksi" value="<?=$sqlb['nama_paket'];?>" >
		 <input type="hidden" name="harga_transaksi" value="<?=$sqlb['harga_paket'];?>" >
		 <input type="hidden" name="sub_total" value="<?=$sqlb['harga_paket'];?>" >
		 
		 <input type="hidden" class="form-control" name="total" value="<?=$totall;?>" >
	     <input type="hidden" name="id_tr_invoice" value="<?=$id_tr_invoice;?>" >
		 <input type="hidden" name="token" value="<?=$kodeaman?>" />
		 <input type="hidden" name="terbit" value="ya" />
		</form>
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
				<form class="form-horizontal" action="invoice_.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
				
				<input type="hidden" name="token" value="<?=$kodeaman?>" />
				<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran;?>" />
				<input type="hidden" name="id_tr_invoice" value="<?=$id_tr_invoice;?>" />
				<input type="hidden" name="status" value="awal" />
				<input type="hidden" name="input" value="y" />
				      <div class="form-group"> 
                        <div class="col-sm-12">	
                          <input type="text" class="form-control" id="code" name="nama_transaksi" placeholder="Masukkan nama item" required>
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">
                          <input type="number" class="form-control" id="code" name="harga_transaksi" placeholder="Masukkan harga satuan" required>
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">	
                          <input type="number" class="form-control" id="code" name="jumlah" placeholder="Masukkan jumlah item" required>
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">
                          <input type="text" class="form-control" id="code" name="satuan" placeholder="Masukkan satuan item" required>
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
			url:"list_item.php",
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

$('#terbit').click(function(){	
	if (!confirm('Terbitkan Invoice & aktifkan layanan?')) {
	event.preventDefault();} else {	
    $('#form2').submit();
   	}
});
</script>
</body>
</html>
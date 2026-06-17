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
              </table>
			  
			  <div class="box-header">
			  <i class="fa fa-th"></i><h3 class="box-title">Biaya Tambahan</h3>
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
				<tbody>
				<?
				$sql1 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '3' ";
				$res1 = $db->query($sql1);
				$row1 = $db->fetchArray($res1);
				$subtotal = $row1['sub_total'];
				
				$sqlz = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$ro[id_tb_paket]'"));
				
				$sql9 = "select * from tb_paket where id_tb_paket = '$sqlz[id_paket_utama]'";
				$res9 = $db->query($sql9);
				$row9 = $db->fetchArray($res9);
				$diskonpaket = $row9['harga_paket'] - $sqlz['harga_paket'];
		
				if(!empty($sqlz['id_paket_utama'])) {?>	
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
				<td align="right"></td>
				</tr>
				<?} else {?>				
				<tr>
				<td><?=$row1['nama_transaksi']?></td>
				<td align="right">-</td>
				<td align="right">Rp. <?=number_format($row1['harga_transaksi'],0,',','.').",00";?></td>
				<td align="right"></td>
				</tr>
				<?}
				
				if($sqlz['harga_paket'] != $subtotal) {
				
				$sql10 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '3' ";
				$res10 = $db->query($sql10);
				$row10 = $db->fetchArray($res10);
				$diskon = $row10['harga_transaksi'] - $row10['sub_total'];	
				?>
				<tr>
				<td><span style="color:red;">Pemakaian layanan hanya <?=$row10['jumlah'];?> Hari</span></td>
				<td align="right"></td>
				<td align="right"><span style="color:red;">- Rp. <?=number_format($diskon,0,',','.').",00";?></span></td>
				<td align="right"></td>
				</tr>
				<?}?>
				<tr>
				<td></td>
				<td align="right"></td>
				<td align="right"></td>
				<td align="right"><span style="float:left;">Rp.</span> <?=number_format($subtotal,0,',','.').",00";?></td>
				</tr>
				
				<?				
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
              </table>
			  </div>
			</div>
			 <div style="margin-top:50px;">
			<div style="margin-top:50px;">	
			
			<a href="invoice_p.php?id=<?=maxiline($id_tr_invoice, 'e');?>&token=<?=$kodeaman?>"  target="_BLANK" class="btn btn-warning">Cetak Invoice</a>
			
			</div>
			</div>
            <!-- /.card-body -->
          </div>

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

</body>
</html>
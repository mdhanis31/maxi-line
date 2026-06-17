<? 
$page= "diskon";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();

$kodeaman = $_SESSION['token'];
$id = SafeSQL($_GET['i']);
$now = new DateTime();
$tgliki = $now->format("d-m-Y");
$kdtgl = $now->format("dmy");

$sql = "select * FROM tb_voucher where id_tb_voucher='$id'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$jml = $db->queryNumRows($sql);
$total = $db->getNumRows($jml);

if(!empty($row['id_tb_voucher'])) {
if($row['tipenya'] == 2 ) {	
$noo = vouchercloud_decode(substr($row1['nama_voucher'], 5));
$kodenya = substr($row1['nama_voucher'], 0, 5);
$lengkap = $kodenyi.$noo;
$taon = substr($lengkap, 5, 6);
$noe = substr($lengkap, 11);
}
$tglawal = $row['tglawal']->format("d-m-Y");
$tglakhir = $row['tglakhir']->format("d-m-Y");	
} else {
$sql1 = "Select b.nama_voucher as nama_voucher from (select max(id_tb_voucher) as id_tb_voucher FROM tb_voucher where tipenya='2')a left join tb_voucher b on a.id_tb_voucher = b.id_tb_voucher";
$res1 = $db->query($sql1);
$row1 = $db->fetchArray($res1);
if(!empty($row1['nama_voucher'])){
$noo = vouchercloud_decode(substr($row1['nama_voucher'], 5));
$kodenyi = substr($row1['nama_voucher'], 0, 5);
$lengkap = $kodenyi.$noo;
$taoi = substr($lengkap, 5, 6);
$noi = substr($lengkap, 11);
if($taoi == $kdtgl){
$noe = sprintf('%03d', $noi + 1);
$taon = $kdtgl;
} else {
$noe = sprintf('%03d', "001");	
$taon = $kdtgl;
}	
} else {
$noe = sprintf('%03d', "001");	
$taon = $kdtgl;	
}	
}	
?>

      

   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	  <?if ($_GET['a']=='b') { echo "Edit Diskon";} else if ($total=='0') { echo "Input Diskon";} else { echo "Detail Diskon";}?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Diskon</li>
      </ol>
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
     <section class="content">
	  <div class="row">
		
		    <section class="col-lg-10">
			  <div class="box box-primary">
				<div class="box-header with-border">
				  <h3 class="box-title"><?if ($_GET['a']=='b') { echo "Edit Diskon";} else if ($total=='0') { echo "Input Diskon";} else { echo "Detail Diskon";}?></h3>
				</div>
            <!-- /.card-header -->
				   <div class="box-body">
					  <div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
						  <li class="active"><a href="#voucher" data-toggle="tab">Voucher</a></li>
						  <li><a href="#promo" data-toggle="tab">Promo</a></li>
						</ul>
						<div class="tab-content">
						  <div class="active tab-pane" id="voucher">
							<!-- Post -->
							<div class="post">
				
							<form action="diskon_aksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
							<input type="hidden" name="d" value="v" />
							<input type="hidden" name="j" value="a" /> 					
							<input type="hidden" name="tipenya" value="2" /> 
							<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
							
							<div class="form-group">
								<label style="display: block;">Kode Voucher</label>
								<input type="text" id="noe" class="form-control" name="kodenya" pattern=".{4,4}" style="width:100px;display: initial;" <?if ($jmln == 0) {echo "placeholder=\"4 Huruf\" value=\"MXCLN\"";} else {echo "placeholder=\"4 Huruf\" value=\"$kodenya\"";}?> readonly> 
								 <input type="text" id="nonya" class="form-control" name="taon" style="width:200px;display: initial;" value="<?=$taon;?>" readonly> 
								 <input type="text" id="taon" class="form-control" name="noe" style="width:100px;display: initial;" value="<?=$noe;?>" readonly> 
							</div>	
							
							<div class="form-group">
								<label style="display: block;">Nominal Diskon</label>
								<input type="number" id="noe" class="form-control" name="nominal" style="width:40%;display: initial;" placeholder="Masukkan nominal" value="<?if ($jmln != 0) {echo "$row[jumlah]";}?>" required> 
								<select class="form-control" name="satuan" style="width:15%;display: initial;" required>
								<option value="">-- Pilih satuan --</option> 
								<option value="%" <? if($row['satuan'] == '%') { echo "selected";}?>> % </option>
								<option value="Rp" <? if($row['satuan'] == 'Rp') { echo "selected";}?>> Rp. </option>
								</select>						
							</div>	
							
							<label>Tanggal Berlaku</label>
							<div class="input-group input-daterange" style="width:60%;">						
								<? if($_GET['a']=='b') {?>
								<input type="text" class="form-control" id="tgl1" name="tglawal" value="<?=$tglawal;?>" required>                                      
								<?} else if ($total=='0'){?>
								<input type="text" class="form-control" id="tgl1" name="tglawal" value="<?=$tgliki;?>" required>                                      
								<?} else {?>
								<input type="text" class="form-control" id="tgl1" name="tglawal" value="<?=$tglawal;?>" required>                                      
								<?}?>
							<div class="input-group-addon">to</div>
								<? if($_GET['a']=='b') {?>
								<input type="text" class="form-control" id="tgl2" name="tglakhir" value="<?=$tglakhir;?>" required>                                      
								<?} else if ($total=='0'){?>
								<input type="text" class="form-control" id="tgl2" name="tglakhir" value="<?=$tgliki;?>" required>                                      
								<?} else {?>
								<input type="text" class="form-control" id="tgl2" name="tglakhir" value="<?=$tglakhir;?>" required>                                      
								<?}?>
							</div>	
							
				<!--			<div class="input-group input-daterange">
								<input type="text" name="tglawal" class="form-control" value="2012-04-05">
								<div class="input-group-addon">to</div>
								<input type="text" name="tglakhir" class="form-control" value="2012-04-19">
							</div> -->
							<br>
							<div class="form-group">
								<label>Jumlah</label>
								<? if ($_GET['a']=='b') {?>
								<input type="number" style="display: inline-block;"  id="jumlah" name="jumlah" class="form-control" value="<?php echo $row['jumlah'];?>" required>
								<?} else if ($total=='0') {?>
								<input type="number" style="display: inline-block;"  id="jumlah" name="jumlah" class="form-control" placeholder="Masukkan jumlah voucher" required>
								<?} else {?>
								<input type="number" style="display: inline-block;"  id="jumlah" class="form-control" value="<?php echo $row['jumlah'];?>" readonly>
								<?}?>
							</div>
						
							<div class="form-group">
							  <label>Keterangan</label>   
							  <? if ($_GET['a']=='b') {?>
								<textarea style="height:100px;" name="ket_voucher" id="alamat" class="form-control" ><?=$row['ket_voucher'];?></textarea>   
								 <?} else if ($total=='0') {?>
								<textarea style="height:100px;" name="ket_voucher" id="alamat" class="form-control" placeholder="Masukkan keterangan bila perlu"></textarea>
								<?} else {?>
								<textarea style="height:100px;" id="alamat" class="form-control" readonly><?=$row['ket_voucher'];?></textarea>  
								<?} ?>								   
							</div>
							
							<br>
							  <div style="margin-top:50px;">
								<div style="margin-top:50px;">	
								<button type="submit" class="btn btn-primary">Buat</button>	
								<button type="button" class="btn btn-warning" onclick="window.location.href='diskon_v.php'">Batal</button>                           
								</div>
							  </div>
							</form> 
						   </div>
						</div>
						
						<!-- tab panel 2 -->
						<div class="tab-pane" id="promo">
							<!-- Post -->
							<div class="post">
							
							<form action="diskon_aksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
							<input type="hidden" name="d" value="p" /> 
							<input type="hidden" name="j" value="a" /> 
							<input type="hidden" name="tipenya" value="1" /> 
							<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
							<div class="form-group">
								<label>Nama Promo</label>
								<? if ($_GET['a']=='b') {?>
								<input type="text" id="nama" name="nama_voucher" class="form-control" value="<?php echo $row['nama_voucher'];?>" required>
								 <?} else if ($total=='0') {?>
								   <input type="text" id="nama" name="nama_voucher" class="form-control" placeholder="Masukkan nama promo" required>
								<?} else {?>
								<input type="text" id="nama" class="form-nama_perusahaan" value="<?php echo $row['nama_voucher'];?>" readonly>
								<?} ?>
							</div>		
							
							<div class="form-group">
								<label style="display: block;">Nominal Diskon</label>
								<input type="number" id="noe" class="form-control" name="nominal" style="width:40%;display: initial;" placeholder="Masukkan nominal" value="<?if ($jml != 0) {echo "$row[jumlah]";}?>" required> 
								<select class="form-control" name="satuan" required style="width:15%;display: initial;">
								<option value="">-- Pilih satuan --</option> 
								<option value="%" <? if($row['satuan'] == '%') { echo "selected";}?>> % </option>
								<option value="Rp" <? if($row['satuan'] == 'Rp') { echo "selected";}?>> Rp. </option>
								</select>						
							</div>	
							
							<label>Tanggal Berlaku</label>
							<div class="input-group input-daterange" style="width:60%;">						
								<? if($_GET['a']=='b') {?>
								<input type="text" class="form-control" id="tgl3" name="tglawal" value="<?=$tglawal;?>" required>                                      
								<?} else if ($total=='0'){?>
								<input type="text" class="form-control" id="tgl3" name="tglawal" value="<?=$tgliki;?>" required>                                      
								<?} else {?>
								<input type="text" class="form-control" id="tgl3" name="tglawal" value="<?=$tglawal;?>" required>                                      
								<?}?>
							<div class="input-group-addon">to</div>
								<? if($_GET['a']=='b') {?>
								<input type="text" class="form-control" id="tgl4" name="tglakhir" value="<?=$tglakhir;?>" required>                                      
								<?} else if ($total=='0'){?>
								<input type="text" class="form-control" id="tgl4" name="tglakhir" value="<?=$tgliki;?>" required>                                      
								<?} else {?>
								<input type="text" class="form-control" id="tgl4" name="tglakhir" value="<?=$tglakhir;?>" required>                                      
								<?}?>
							</div>	
							
							<div class="form-group">
								<label>Jumlah</label>
								<? if ($_GET['a']=='b') {?>
								<input type="number" style="display: inline-block;"  id="jumlah" name="jumlah" class="form-control" value="<?php echo $row['jumlah'];?>" required>
								<?} else if ($total=='0') {?>
								<input type="number" style="display: inline-block;"  id="jumlah" name="jumlah" class="form-control" placeholder="Masukkan jumlah voucher" required>
								<?} else {?>
								<input type="number" style="display: inline-block;"  id="jumlah" class="form-control" value="<?php echo $row['jumlah'];?>" readonly>
								<?}?>
							</div>	
					
							<div class="form-group">
							  <label>Keterangan</label>   
							  <? if ($_GET['a']=='b') {?>
								<textarea style="height:100px;" name="ket_voucher" id="alamat" class="form-control" ><?=$row['ket_voucher'];?></textarea>   
								 <?} else if ($total=='0') {?>
								<textarea style="height:100px;" name="ket_voucher" id="alamat" class="form-control" placeholder="Masukkan keterangan bila perlu"></textarea>
								<?} else {?>
								<textarea style="height:100px;" id="alamat" class="form-control" readonly><?=$row['ket_voucher'];?></textarea>  
								<?} ?>								   
							</div>
							
							  <br>
							  <div style="margin-top:50px;">
								<div style="margin-top:50px;">	
								<button type="submit" class="btn btn-primary">Buat</button>	
								<button type="button" class="btn btn-warning" onclick="window.location.href='diskon_v.php'">Batal</button>                           
								</div>
							  </div>
							</form>
							<br>
							</div>
							<!-- /.post -->   
             
                  </div>
						<!-- end tab panel 2 -->
					 </div>
					 </div>
					</div>	
              </div><!-- /.card-body -->
            </section>
            <!-- /.nav-tabs-custom -->
          </div>
    </section>
	
   <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; <?=$th;?> Maxi-Line.</strong> All rights
    reserved.
  </footer>
</div>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap datepicker css -->
<link href="bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" media="screen">
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
$(function () {
 $('#tgl1').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
	  startDate: "dateToday"
    }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#tgl2').datepicker('setStartDate', minDate);
	$('#tgl2').datepicker('clearDates');
});

 $('#tgl2').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy'
    });	
	
  $('#tgl3').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
	  startDate: "dateToday"
    }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#tgl4').datepicker('setStartDate', minDate);
	$('#tgl4').datepicker('clearDates');
});

 $('#tgl4').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy'
    });	
});
</script>
</body>
</html>
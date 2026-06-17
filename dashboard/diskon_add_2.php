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
$noe = substr($row['nama_voucher'], -3);
$kodenya = substr($row['nama_voucher'], 4, -3);
$taon = substr($row['nama_voucher'], 0, 4);
}
$tglawal = $row['tglawal']->format("d-m-Y");
$tglakhir = $row['tglakhir']->format("d-m-Y");	
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
					<form action="diskon_aksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
					<input type="hidden" name="d" value="v" />
					<input type="hidden" name="j" value="a" /> 					
					<input type="hidden" name="tipenya" value="2" /> 
					<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
					
					<div class="box-body">
					<div class="form-group">
						<label style="display: block;">Kode Voucher</label>
						<input type="text" id="noe" class="form-control" name="kodenya" pattern=".{4,4}" style="width:100px;display: initial;" <?if ($jmln == 0) {echo "placeholder=\"4 Huruf\" value=\"MXCLD\"";} else {echo "placeholder=\"4 Huruf\" value=\"$kodenya\"";}?> required> 
						 <input type="text" id="nonya" class="form-control" name="taon" style="width:200px;display: initial;" value="<?if ($jmln == 0) {echo "$kdtgl";} else {echo "$taon";}?>" readonly> 
						 <input type="text" id="taon" class="form-control" name="noe" style="width:100px;display: initial;" value="<?if ($jmln == 0) {echo "001";} else {echo "$noe";}?>" readonly> 
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
              </div><!-- /.card-body -->
            </section>
            <!-- /.nav-tabs-custom -->
          </div>
    </section>
	
   <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

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
});
  

</script>
</body>
</html>
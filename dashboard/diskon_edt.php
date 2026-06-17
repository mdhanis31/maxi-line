<? 
$page= "diskon";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();

$kodeaman = $_SESSION['token'];
$now = new DateTime();
$id = maxiline(SafeSQL($_GET['i']), 'd');
$tgliki = $now->format("d-m-Y");
$kdtgl = $now->format("dmy");

$sql = "select * FROM tb_voucher where id_tb_voucher='$id'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$jml = $db->queryNumRows($sql);
$total = $db->getNumRows($jml);

if($row['tipenya'] == 2 ) {	
$noo = vouchercloud_decode(substr($row['nama_voucher'], 5));
$kodenya = substr($row['nama_voucher'], 0, 5);
$lengkap = $kodenya.$noo;
$taon = substr($lengkap, 5, 6);
$noe = substr($lengkap, 11);
}

$sql1 = "SELECT count(id_tr_voucher) as jmlterpake FROM tr_voucher where id_tb_voucher = '$id'";
$res1 = $db->query($sql1);  
$row1 = $db->fetchArray($res1);
$jmlsisa = $row['jumlah'] - $row1['jmlterpake'];

$tglawal = $row['tglawal']->format("d-m-Y");
$tglakhir = $row['tglakhir']->format("d-m-Y");	

?>
<style>
.disabled{
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}      
</style>
   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	  Edit Diskon
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit Diskon</li>
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
						<li class="<?if($row['tipenya'] == 2 ) {echo "active";} else {echo "disabled";}?>"><a href="#voucher" data-toggle="tab" <?if($row['tipenya'] == 2 ) {echo "active";} else {echo "disabled";}?>>Voucher</a></li>
						<li class="<?if($row['tipenya'] == 1 ) {echo "active";} else {echo "disabled";}?>"><a href="#promo" data-toggle="tab" <?if($row['tipenya'] == 1 ) {echo "active";} else {echo "disabled";}?>>Promo</a></li>
					</ul>				
                <div class="tab-content">        
                  <div class="tab-pane <?if($row['tipenya'] == 2 ) {echo "active";} else {echo "disabled";}?>" id="voucher">
                    <!-- The timeline -->
					<div class="post">
					<form action="diskon_aksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
					<input type="hidden" name="d" value="v" />
					<input type="hidden" name="j" value="b" /> 					
					<input type="hidden" name="tipenya" value="2" /> 
					<input type="hidden" name="id_tb_voucher" value="<?=$id;?>" /> 
					<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
					
					<div class="form-group">
						<label style="display: block;">Kode Voucher</label>
						<input type="text" id="noe" class="form-control" name="kodenya" pattern=".{4,4}" style="width:100px;display: initial;" placeholder="4 Huruf" value="<?=$kodenya;?>" readonly> 
						<input type="text" id="nonya" class="form-control" name="taon" style="width:200px;display: initial;" value="<?=$taon;?>" readonly> 
						<input type="text" id="taon" class="form-control" name="noe" style="width:100px;display: initial;" value="<?=$noe;?>" readonly> 
					</div>	
					
					<div class="form-group">
						<label style="display: block;">Nominal Diskon</label>
						<input type="number" id="noe" class="form-control" name="nominal" style="width:40%;display: initial;" placeholder="Masukkan nominal" value="<?=$row['nominal'];?>" required> 
						<select class="form-control" name="satuan" style="width:15%;display: initial;" required>
						<option value="">-- Pilih satuan --</option> 
						<option value="%" <? if($row['satuan'] == '%') { echo "selected";}?>> % </option>
						<option value="Rp" <? if($row['satuan'] == 'Rp') { echo "selected";}?>> Rp. </option>
						</select>						
					</div>	
					
					<label>Tanggal Berlaku</label>
					<div class="input-group input-daterange" style="width:60%;">
						<input type="text" class="form-control" id="tgl1" name="tglawal" value="<?=$tglawal;?>" required autocomplete="off">
					<div class="input-group-addon">to</div>
						<input type="text" class="form-control" id="tgl2" name="tglakhir" value="<?=$tglakhir;?>" required autocomplete="off">
					</div>	
					
					<br>
					<div class="form-group">
						<label>Jumlah</label>
						<input type="number" style="display: inline-block;"  id="jumlah" name="jumlah" class="form-control" value="<?php echo $row['jumlah'];?>" readonly>
					</div>					
								
					<div class="form-group">
					  <label>Keterangan</label> 
						<textarea style="height:100px;" name="ket_voucher" id="alamat" class="form-control" required><?=$row['ket_voucher'];?></textarea>   
					</div>
					
					<br>
					  <div style="margin-top:50px;">
						<div style="margin-top:50px;">	
						<button type="submit" class="btn btn-primary">Simpan</button>	
						<button type="button" class="btn btn-warning" onclick="window.location.href='diskon_v.php'">Batal</button>                           
						</div>
					  </div>
					</form> 
				   </div>
					</div>
					
					<div class="tab-pane <?if($row['tipenya'] == 1 ) {echo "active";} else {echo "disabled";}?>" id="promo">
                    <!-- Post -->
                    <div class="post">
					
					<form action="diskon_aksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
					<input type="hidden" name="d" value="p" /> 
					<input type="hidden" name="j" value="b" /> 
					<input type="hidden" name="id_tb_voucher" value="<?=$id;?>" /> 
					<input type="hidden" name="tipenya" value="1" /> 
					<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
					<input type="hidden" name="jmlsisa" value="<?=$jmlsisa;?>" /> 
					<input type="hidden" name="jmlall" value="<?=$row['jumlah'];?>" /> 
					<div class="form-group">
						<label>Nama Promo</label>						
						<input type="text" id="nama" name="nama_voucher" class="form-control" value="<?php echo $row['nama_voucher'];?>" required>						
					</div>		
					
					<div class="form-group">
						<label style="display: block;">Nominal Diskon</label>
						<input type="number" id="noe" class="form-control" name="nominal" style="width:40%;display: initial;" placeholder="Masukkan nominal" value="<?if ($jml!= 0) {echo "$row[nominal]";}?>" required> 
						<select class="form-control" name="satuan" required style="width:15%;display: initial;">
						<option value="">-- Pilih satuan --</option> 
						<option value="%" <? if($row['satuan'] == '%') { echo "selected";}?>> % </option>
						<option value="Rp" <? if($row['satuan'] == 'Rp') { echo "selected";}?>> Rp. </option>
						</select>						
					</div>	
					
					<label>Tanggal Berlaku</label>
					<div class="input-group input-daterange" style="width:60%;">
						<input type="text" class="form-control" id="tgl3" name="tglawal" value="<?=$tglawal;?>" required autocomplete="off">
					<div class="input-group-addon">to</div>
						<input type="text" class="form-control" id="tgl4" name="tglakhir" value="<?=$tglakhir;?>" required autocomplete="off">
					</div>	
					
					<br>
					<div class="form-group">
						<label>Jumlah</label>
						<input type="number" style="display: inline-block;"  id="jumlah" name="jumlah" class="form-control" value="<?php echo $jmlsisa;?>" required>
					</div>	
			
					<div class="form-group">
					  <label>Keterangan</label> 
						<textarea style="height:100px;" name="ket_voucher" id="alamat" class="form-control" required><?=$row['ket_voucher'];?></textarea> 
					</div>
					
					<br>
					  <div style="margin-top:50px;">
						<div style="margin-top:50px;">	
						<button type="submit" class="btn btn-primary">Simpan</button>	
						<button type="button" class="btn btn-warning" onclick="window.location.href='diskon_v.php'">Batal</button>                           
						</div>
					  </div>
					</form> 
				   </div>
                  </div>
                  <!-- /.tab-pane -->
		   <!--		 <input type="button" value="click to toggle fullscreen" onclick="toggleFullScreen()">
                /.tab-pane -->
				  
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
		 </section>
  
      </div><!-- /.container-fluid -->
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
<!-- page script -->
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
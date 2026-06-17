<? 
$page= "adcharge";
if ($_GET['idx'] == "bFZHcDF0alhzWTYvZGtYZTc1ZyswUT09") {
$pages = "pasang";
} else if ($_GET['idx'] == "Vy80RENjZGxxTTNDOHN5VkRJSGlJUT09") {
$pages = "bulanan";
} else if ($_GET['idx'] == "WEEvaENpL3VyK3BxTER3cTFYYVJqUT09") {
$pages = "layanan";
} else if ($_GET['idx'] == "ckxmWHJMNXpJeTNxbWlXdzQ5TjRKUT09") {
$pages = "pemutusan";
} else {
?>
<script>alert('Halaman tidak ditemukan!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();
}	 

include ("head.php"); 
include ("nav.php"); 

$idx = maxiline($_GET['idx'], 'd');

$db = new DbConnector();

$kodeaman = $_SESSION['token'];
$id = maxiline(SafeSQL($_GET['i']), 'd');


$now = new DateTime();
$th = $now->format("Y");

$sql = "select * from tb_potongan where id_tb_potongan='$id'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$jml = $db->queryNumRows($sql);
$total = $db->getNumRows($jml);

if ($total!='0' and $row['jns_potongan'] != $idx) {
?>
<script>alert('Data tidak ditemukan!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();	
}

$userlvl = array(1,4);
if(!in_array($_SESSION['level_user'], $userlvl)) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}
?>

      
<style>
.wysihtml5-sandbox {
resize: vertical;
}
</style>  
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	 <? if($total=='0') {echo "Tambah Biaya";} else {echo "Edit Biaya";}?>
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Biaya Tambahan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	  <div class="row">
			<!-- Left col --> 		
			  <section class="col-lg-10">
			  <div class="box box-primary">
				<div class="box-header with-border">
				  <h3 class="box-title"><?if ($_GET['a']=='b') {echo "Edit";} else {echo "Tambah";}?> Biaya <?=$pages;?></h3>
				</div>
			
			<form role="form" action="potongan_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<?if ($total=='0') {?>
			 <input type="hidden" name="j" value="a" />					 
			 <?} else {?>
			  <input type="hidden" name="j" value="b" />
			 <?}?>
			 <input type="hidden" name="id_tb_potongan" value="<?php echo $row['id_tb_potongan'];?>">						
			 <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
			 <input type="hidden" name="jns_potongan" value="<?=$idx;?>" />	
				 
			<div class="box-body">
			<div class="form-group">
				<label>Judul</label>
				<? if ($_GET['a']=='b') {?>
				<input type="text" id="nama" name="nama_potongan" class="form-control" value="<?php echo $row['nama_potongan'];?>" required>
				 <?} else if ($total=='0') {?>
				   <input type="text" id="nama" name="nama_potongan" class="form-control" placeholder="Masukkan judul potongan" required>
				<?} else {?>
				<input type="text" id="nama" class="form-nama_perusahaan" value="<?php echo $row['nama_potongan'];?>" readonly>
				<?} ?>
				</div>	  
			
			<div class="form-group">
					 <label>Satuan</label>      
					<select class="form-control" name="satuan" required>
					<option value="">-- Pilih satuan --</option> 
					<option value="%" <? if($row['satuan'] == '%') { echo "selected";}?>> % </option>
					<option value="Rp" <? if($row['satuan'] == 'Rp') { echo "selected";}?>> Rp. </option>
					</select>
			</div>
			
			<div class="form-group">
					  <label>Jumlah</label>             
						<? if($_GET['a'] == 'b') {?>
						<input type="number" name="jumlah" id="jumlah" class="form-control" value="<?=$row['jumlah']?>" > 
						<?} else {?>
						<input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan jumlah potongan" > 
						<?}?>             
			</div>
			
			<div class="form-group">
					<label>Status</label>      
					<select class="form-control" name="status" required>					
					<option value="1" <? if($row['status'] == 1) { echo "selected";}?>> Aktif </option>
					<option value="2" <? if($row['status'] == 2) { echo "selected";}?>> Tidak Aktif </option>
					</select>
			</div>
					
			<div class="form-group">
              <label>Keterangan</label>   
			  <? if ($_GET['a']=='b') {?>
				<textarea style="height:100px;" name="ket_potongan" id="alamat" class="form-control" required><?=$row['ket_potongan'];?></textarea>   
				 <?} else if ($total=='0') {?>
				<textarea style="height:100px;" name="ket_potongan" id="alamat" class="form-control" required placeholder="Masukkan keterangan bila diperlukan"></textarea>
				<?} else {?>
				<textarea style="height:100px;" id="alamat" class="form-control" readonly><?=$row['ket_potongan'];?></textarea>  
				<?} ?>
                           
            </div>
			
		    <div style="margin-top:50px;">
			<div style="margin-top:50px;">		
			  <button type="submit" name="kirim" value="b" class="btn btn-info" style="margin-right:5px;"><? if($_GET['a'] == 'b') {echo "Update";} else {echo "Simpan";}?></button>
			  <button type="reset" class="btn btn-default" onclick="window.location.href='potongan_v.php?idx=<?=$_GET['idx'];?>'">Batal</button>
			</div>
			</div>
		   </form>
		   </div>
			 
            </div>
			</section>
            <!-- /.card-body -->
      </div><!-- /.container-fluid -->
    </section>
	
   <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
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
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>  
  $(function () {  

    // bootstrap WYSIHTML5 - text editor

    $('.textarea').wysihtml5({
      toolbar: { fa: true, 
	  "font-styles": false,
	  link: false, // Button to insert a link.
      image: false, // Button to insert an image.
	  html: true
	  
	  }
    })
  })
</script>
</body>
</html>
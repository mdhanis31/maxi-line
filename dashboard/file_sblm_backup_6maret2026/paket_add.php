<? 
$page= "langganan";
$pages= "paket";
include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");

$db = new DbConnector();

$kodeaman = $_SESSION['token'];
$id = maxiline(SafeSQL($_GET['i']), 'd');

$sql = "select * from tb_paket where id_tb_paket = '$id'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$jml = $db->queryNumRows($sql);
$total = $db->getNumRows($jml);

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
      <h1> <? if($total=='0') {echo "Tambah Paket";} else {echo "Edit Paket";}?>
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Paket</li>
      </ol>
    </section>


    <!-- Main content -->
   <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col --> 		
		  <section class="col-lg-10">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Paket</h3>
            </div>
				
			<form role="form" action="paket_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<?if ($total=='0') {?>
			 <input type="hidden" name="j" value="a" />
			 <?} else {?>
			  <input type="hidden" name="j" value="b" />
			 <?}?>
			 <input type="hidden" name="id_tb_paket" value="<?php echo $row['id_tb_paket'];?>">						
			 <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
			 
			<div class="box-body">
			<div class="form-group">
				<label>Nama Paket</label>
				<?if ($total=='0') {?>
				<input type="text" id="nama_paket" name="nama_paket" class="form-control" placeholder="Masukkan nama paket" required>
				<?} else {?>
				<input type="text" id="nama_paket" name="nama_paket" class="form-control" value="<?php echo $row['nama_paket'];?>" required>
				<?}?>
            </div>			
			
			<div class="form-group">
				<label>Harga Paket / bulan</label>

                <div class="input-group">
                  <div class="input-group-addon">
                   Rp.
                  </div>
				<? if ($total=='0') {?>
				<input type="number" id="harga_paket" name="harga_paket" class="form-control" placeholder="Masukkan harga (hanya angka)" required>
				<?} else {?>
				<input type="number" id="harga_paket" name="harga_paket" class="form-control" value="<?php echo $row['harga_paket'];?>" required>
				<?}?>
				</div>
            </div>
						
			<div class="form-group">
				<label>Fitur / Isi Paket</label>
				<? if ($total=='0') {?>
				<textarea id="isi_paket" name="isi_paket" class="textarea form-control" placeholder="Masukkan fitur / isi paket"></textarea>
				<?} else {?>
				<textarea id="isi_paket" name="isi_paket" class="textarea form-control" placeholder="Masukkan fitur / isi paket"><?php echo $row['isi_paket'];?></textarea>
				<?}?>
            </div>

			<div id="opsi-tambahan" style="margin-bottom:10px">
				<label for="is_hidden">Sembunyikan Paket</label>
				<div class="row">
					<div class="col-md-6">
						<input type="radio" name="is_hidden" value="1" <?= $row['is_hidden'] == 1 ? 'checked' : ''; ?>> Tidak</input>
					</div>
					<div class="col-md-6">
						<input type="radio" name="is_hidden" value="2" <?= $row['is_hidden'] == 2 ? 'checked' : ''; ?>> Ya</input>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label>Paket Promo</label>
				
				<p><h5><i>Bila pilih ya paket tidak akan muncul di frontend, paket akan diassign manual oleh admin saat pertama kali create invoice</i></h5></p>
				<? if ($total=='0') {?>
				<select class="form-control select2" data-placeholder="Pilih" style="width: 100%;" id="paket_promo" name="paket_promo" tabindex="-1" aria-hidden="true">
				<option value="1">Tidak</option>
				<option value="2">Ya</option>
				</select>
				<?} else {?>
				<select class="form-control select2" data-placeholder="Pilih" style="width: 100%;" id="paket_promo" name="paket_promo" tabindex="-1" aria-hidden="true">
				<option value="1" <?if($row['paket_promo'] == 1) {echo "selected";}?>>Tidak</option>
				<option value="2" <?if($row['paket_promo'] == 2) {echo "selected";}?>>Ya</option>
				</select>
				<?}?>
            </div>
			
			<div class="form-group" id="divpaket">
				<label>Pilih paket utama untuk paket promo ini</label>
				<select class="form-control select2" data-placeholder="Pilih" style="width: 100%;" id="id_paket_utama" name="id_paket_utama" tabindex="-1" aria-hidden="true">
					<?
					$sqla = "select * from tb_paket where paket_promo = '1'";
					$resa = $db->query($sqla);
					while($rowa = $db->fetchArray($resa)){
					?>
					<option value="<?=$rowa['id_tb_paket'];?>" <?if($row['id_paket_utama'] == $rowa['id_tb_paket']) {echo "selected";}?>><?=$rowa['nama_paket'];?> | Rp. <?=number_format($rowa['harga_paket'],0,',','.').",00";?> </option>
					<?}?>
				</select>
            </div>
			
			<div style="margin-top:50px;">
			<?if ($total=='0') {?>
			  <button type="submit" name="draft" value="a" class="btn btn-info" style="margin-right:5px;">Simpan</button>
			  <button type="reset" class="btn btn-default" onclick="window.location.href='paket_v.php'">Batal</button>		
			<?} else {?>
			  <button type="submit" name="draft" value="a" class="btn btn-info" style="margin-right:5px;">Update</button>
			  <button type="reset" class="btn btn-default" onclick="window.location.href='paket_v.php'">Batal</button>
			<?}?>
			</div>
			</form>
		   </div>
	      </div>
		</section>
            <!-- /.card-body -->
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
  
  
	$(function() {
		// Inisialisasi tampilan awal berdasarkan nilai saat ini
		function togglePromoRelatedFields() {
			var isPromo = $('#paket_promo').val() == '2';

			if (isPromo) {
				$('#divpaket').show();
				$('#opsi-tambahan').hide();
			} else {
				$('#divpaket').hide();
				$('#opsi-tambahan').show();
			}
		}

		togglePromoRelatedFields(); // panggil saat halaman dimuat

		$('#paket_promo').change(function() {
			togglePromoRelatedFields(); // panggil saat terjadi perubahan
		});
	});
</script>
</body>
</html>
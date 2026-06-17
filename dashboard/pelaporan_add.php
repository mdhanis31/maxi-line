<?php
$page= "pelaporan";

include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();

$now = new DateTime();
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

$kodeaman = $_SESSION['token'];
$id = maxiline(SafeSQL($_GET['id']), 'd');

$sql = "select * from tr_pelaporan where id_tr_pelaporan = '$id'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$jml = $db->queryNumRows($sql);
$total = $db->getNumRows($jml);

$sqla = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
?>

<!-- Select2 -->
<link rel="stylesheet" href="plugins/select2/select2.css">
<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<style>
	.wysihtml5-sandbox {
		resize: vertical;
	}
	
	.select2-container .select2-selection--single {
		height: max-content;
	}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Pelaporan
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pelaporan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div class="row">
			<!-- Left col --> 		
			<section class="col-lg-8">
				<div class="box box-primary">
					<div class="box-header with-border">
					<h3 class="box-title"> <?if ($_GET['a']=='b') { echo "Edit";} else if ($total=='0') { echo "Input";} else { echo "Detail";}?>  Laporan</h3>
					</div>
					<!-- /.card-header -->            
					<form action="pelaporan_.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
						<?php if ($_GET['a']=='b') {?>
							<input type="hidden" name="j" value="b" />
							<input type="hidden" name="code_pelaporan" value="<?=$row['code_pelaporan'];?>" />
						<?php } else if($total=='0') {
							$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
							$codereg = "ML-";

							for ($i = 0; $i < 10; $i++) {
								$codereg .= $chars[mt_rand(0, strlen($chars)-1)];
							}	 
						?>
							<input type="hidden" name="j" value="a" />
							<input type="hidden" name="code_pelaporan" value="<?=$codereg;?>" />
						<?php } ?>

						<input type="hidden" name="id_tr_pelaporan" value="<?php echo $row['id_tr_pelaporan'];?>">						
						<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
						<? if($_SESSION['level_user'] == 5) {?>
						<input type="hidden" name="id_tb_pendaftaran" value="<?php echo $sqla['id_tb_pendaftaran']; ?>" />
						<?}?>
					
						<div class="box-body">
							<? if($_SESSION['level_user'] != 5) {
								
							$sqlz = "select * from tb_pendaftaran where id_tb_pendaftaran = '$row[id_tb_pendaftaran]'";
							$resz = $db->query($sqlz);
							$rowz = $db->fetchArray($resz);	
							?>
							<div class="form-group">
								<label>Customer</label>
								<?php if ($_GET['a']=='b') {?>
								<select class="select2 form-control" name="id_tb_pendaftaran" id="customere" style="width: 100%;" required data-placeholder="Ketik nama, username atau kode customer">									
									<option value="<?=$rowz['id_tb_pendaftaran']?>" selected ><?echo $rowz['kode_daftar']." - ".$rowz['nama'];?></option>
								</select>
								<?php } else if ($total=='0') {?>
								<select class="select2 form-control" name="id_tb_pendaftaran" id="customere" style="width: 100%;" required data-placeholder="Ketik nama, username atau kode customer">									
								</select>
								<?} else {?>
								<select class="form-control" name="id_tb_pendaftaran" disabled>
									<option value="<?=$rowz['id_tb_pendaftaran']?>" selected ><?echo $rowz['kode_daftar']." - ".$rowz['nama'];?></option>
								</select>
								<?}?>
							</div>	
							<?}?>
						
							<div class="form-group">
								<label>Jenis Laporan</label>
								<?php if ($_GET['a']=='b') {?>
									<select class="form-control" name="jns_laporan" required>	
										<option value="1" <?if($row['jns_laporan'] == 1){echo "selected";}?>>Pembayaran</option>
										<option value="2" <?if($row['jns_laporan'] == 2){echo "selected";}?>>Teknis</option>
									</select>
								<?php } else if ($total=='0') {?>
									<select class="form-control" name="jns_laporan" required>
										<option value="">-- Pilih laporan --</option>					
										<option value="1">Pembayaran</option>
										<option value="2">Teknis</option>
									</select>
								<?php } else {?>
									<select class="form-control" name="jns_laporan" disabled>
										<option value="1" <?if($row['jns_laporan'] == 1){echo "selected";}?>>Pembayaran</option>
										<option value="2" <?if($row['jns_laporan'] == 2){echo "selected";}?>>Teknis</option>
									</select>
								<?php } ?>
							</div>
					
							<div class="form-group">
								<label>Subyek</label>
								<?php if ($_GET['a']=='b') {?>
									<input type="text" id="judul" name="subyek" class="form-control" value="<?php echo $row['subyek_laporan'];?>" required>
								<?php } else if ($total=='0') {?>
									<input type="text" id="judul" name="subyek" class="form-control" placeholder="Masukkan subyek" required>
								<?php } else {?>
									<input type="text" id="judul" class="form-control" value="<?php echo $row['subyek_laporan'];?>" readonly>
								<?} ?>
							</div>	  
					
							<div class="form-group">
								<label>Isi</label>   
								<? if ($_GET['a']=='b') {?>
									<textarea style="height:100px;" name="isi" id="isi" class="form-control textarea" required><?=$row['isi_laporan'];?></textarea>   
								<?} else if ($total=='0') {?>
									<textarea style="height:100px;" name="isi" id="isi" class="form-control textarea" required placeholder="Masukkan isi"></textarea>
								<?} else {?>
									<textarea style="height:100px;" id="isi" class="form-control" readonly><?=$row['isi_laporan'];?></textarea>  
								<?}?>                          
							</div>
							<?if ($total=='0') {?>
								<div class="form-group">
									<label for="exampleInputFile">Upload File</label>
									<input type="file" id="exampleInputFile" name="file_laporan[]" multiple>

									<p class="help-block">File format jpg, jpeg, png atau pdf</p>
								</div>
							<?} else {?>
								<div class="form-group">
									<label>File</label> 
									<table class="table table-bordered" style="margin-top:5px;">
										<tbody>
											<tr>
												<?php
													$sqlb = "select * from tr_file_pelaporan where id_tr_pelaporan = '$id'";
													$resb = $db->query($sqlb);
													$query_jml = $db->queryNumRows($sqlb);
													$jmlh = $db->getNumRows($query_jml);
													
													if(empty($jmlh)) {
												
													} else {
													while($rowb = $db->fetchArray($resb)) {	
													$ext = pathinfo($rowb['link_gbr'], PATHINFO_EXTENSION);
												?>
							
												<td>
													<?if($ext == "jpg" or $ext == "jpeg"){ ?>
														<a href="<?=$rowb['link_gbr'];?>" title="file" target="_blank"><img src="dist\img\jpg_icon.png" alt="file" width="12%" /></a>
													<?} else if($ext == "pdf"){?>
														<a href="<?=$rowb['link_gbr'];?>" title="file" target="_blank"><img src="dist\img\pdf_icon.png" alt="file" width="12%" /></a>
													<?} else { }?>

													<a href="<?=$rowb['link_gbr'];?>" title="file" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-search-plus"></i></a>
													<? if($_SESSION['level_user'] == 5){ ?>
														<a href="pelaporan_.php?id=<?=maxiline($rowb['id_tr_file_pelaporan'], 'e');?>&g=h&token=<?echo $kodeaman;?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus file?')"><i class="nav-icon fa fa-times"></i></a>
													<?}?>
												</td>
												<?}}?>
											</tr>
										</tbody>
									</table>
								</div>  
					
								<div class="form-group">
									<label>PIC</label> 
									<?if(empty($row['id_tb_user']) and $_SESSION['level_user'] == 1) {?>
										<select class="form-control" name="id_tb_user" required>
											<option value="">-- Pilih PIC --</option>
											<?if($row['jns_laporan'] == 1) {
												$sql1 = "select * from tb_user where level_user = '4'"; } else {
												$sql1 = "SELECT * FROM tb_user WHERE level_user IN ('1', '2', '3') AND sts_delete = 1";	
											}	
											$res1 = $db->query($sql1);
											while ($row1 = $db->fetchArray($res1)) 
											{
											?>					
											<option value="<?=$row1['id_tb_user'];?>"><?=$row1['nm_user'];?> <?=$row1['Jabatan'];?></option>
											<?}?>
										</select>			 
									<?} else if($_SESSION['level_user'] == 5){
										$sql1 = "select * from tb_user where id_tb_user = '$row[id_tb_user]'";	
										$res1 = $db->query($sql1);
										$row1 = $db->fetchArray($res1);
									?>
										<input class="form-control" type="text" value="<?=$row1['nm_user'];?> <?=$row1['Jabatan'];?>" disabled>			 
									<?} else {?>
										<select class="form-control" name="id_tb_user" required>
											<?if($row['jns_laporan'] == 1) {
												$sql1 = "select * from tb_user where level_user = '4' and sts_delete = '1'"; 
											} else {
												$sql1 = "SELECT * FROM tb_user WHERE level_user IN ('1', '2', '3') AND sts_delete = 1";
											}	
											$res1 = $db->query($sql1);
											while ($row1 = $db->fetchArray($res1)) {
											?>
												<option value="<?=$row1['id_tb_user'];?>" <?if($row1['id_tb_user'] == $row['id_tb_user']) {echo "selected";}?>><?=$row1['nm_user'];?> <?=$row1['Jabatan'];?></option>
											<?}?>
										</select>			
									<?}?>	
								</div>	
					
								<div class="form-group">
									<div class="input-group">
									<div class="input-group-addon" <?if($row['sts_laporan'] == 1) {?> style="background-color: #f00;color:white;"<?} 
									else if($row['sts_laporan'] == 2) {?> style="background-color: #cf9500;color:white;"<?}
									else {?>style="background-color: #44a50f;color:white;"<?}?>>
									Status Laporan
									</div>				
									<input class="form-control" value="<?=$sts_laporan[intval("0".$row['sts_laporan'])]?>" disabled >				
									</div>
								</div>
							<?}?>

							<div style="margin-top:50px;">
								<?if(empty($row['id_tb_user']) and $_SESSION['level_user'] == 1) {?>
									<button type="submit" name="set" value="pic" class="btn btn-info" style="margin-right:5px;">Simpan PIC</button>
								<?} else if(!empty($row['id_tb_user']) and $_SESSION['level_user'] == 1) {?>
									<button type="submit" name="ubah" value="pic" class="btn btn-info" style="margin-right:5px;">Simpan perubahan PIC</button>
								<?}?>
					
								<? if ($total== 0) {?>
									<button type="submit" name="kirim" value="b" class="btn btn-info" style="margin-right:5px;">Kirim</button>
									<button type="reset" class="btn btn-default" onclick="window.location.href='pelaporan_v.php'">Batal</button>
								<?} else {?>
									<button type="reset" class="btn btn-default" onclick="window.location.href='pelaporan_v.php'">Kembali</button>
								<? if ($_SESSION['level_user'] == 5 and $row['sts_laporan'] != 3) {?>			  
									<a class="btn btn-warning" style="float:right;" href="respon_.php?id=<?=maxiline($row['id_tr_pelaporan'],'e');?>&i=<?=maxiline("close", 'e');?>&c=y">Pelaporan Selesai</a>
								<?}}?>
							</div>
						</div>
					</form>
				</div>
			</section>
			<!-- /.card-body -->
			<?php	
				$sqlb = "select * from tr_file_pelaporan where id_tr_pelaporan = '$id'";
				$resb = $db->query($sqlb);
				$query_jml = $db->queryNumRows($sqlb);
				$jmlh = $db->getNumRows($query_jml);		
				
				if ($total== 0) { } else {
			?>
		
			<div class="col-md-4">
				<!-- Chat box -->
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">Respon Pelaporan <?=$row['code_pelaporan'];?></h3>
						<div class="box-tools pull-right" data-toggle="tooltip" title="Status"></div>
					</div>
					<div class="box-body">
						<!-- chat item -->
						<?
							$sqlc = "select * from tr_respon where id_tr_pelaporan = '$id' order by tgl_data asc";
							$resc = $db->query($sqlc);
							$queryc = $db->queryNumRows($sqlc);
							$jmlc = $db->getNumRows($queryc);
				
							if ($jmlc == 0 and $_SESSION['level_user'] == 5) { echo "Belum ada respon"; } else {			
								while($rowc = $db->fetchArray($resc)) {	
						?>
						<div class="box box-<?if($rowc['st_respon'] == 5) {echo "warning";} else {echo "success";}?> box-solid">
							<div class="box-header with-border">
								<h3 class="box-title" style="font-size: 15px;"><b><?=$tipe_respon[intval("0".$rowc['tipe_respon'])]?></b></h3>
								<div class="box-tools pull-right">
									<?
										$tgla = tglindo(date_format($rowc['tgl_respon'], 'Y-m-d'));
										$jama = date_format($rowc['tgl_respon'], 'H:i');				   
										echo $tgla." | ".$jama; 
									?>
								</div>
							</div>
							<div class="box-body">
								<p>
									<?=$rowc['respon'];?>
								</p>
								<p>		
									<!-- gambar -->		
									<?
										$sqlb = "select * from tr_file_respon where id_tr_respon = '$rowc[id_tr_respon]'";
										$resb = $db->query($sqlb);
										$query_jml = $db->queryNumRows($sqlb);
										$jmlh = $db->getNumRows($query_jml);
						
										if(empty($jmlh)) {
					
										} else {
											while($rowb = $db->fetchArray($resb)) {	
												$ext = pathinfo($rowb['link_gbr'], PATHINFO_EXTENSION);
									?>
						
											<div style=" float: right; width: 20%; height: 40px; display: block;">
												<?if($ext == "jpg" or $ext == "jpeg" or $ext == "png"){ ?>
													<a href="<?=$rowb['link_gbr'];?>" title="file" target="_blank"><img src="dist\img\jpg_icon.png" alt="file" width="70%" /></a>
												<?} else if($ext == "pdf"){?>
													<a href="<?=$rowb['link_gbr'];?>" title="file" target="_blank"><img src="dist\img\pdf_icon.png" alt="file" width="70%" /></a>
												<?} else { }?>
												<!-- <a href="<?=$rowb['link_gbr'];?>" title="file" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-search-plus"></i></a> -->				
											</div>
									<?}}?>			
									<!-- //gambar -->
								</p>
							</div>
							<div class="box-footer">	
								<p>  
									<small class="text-muted pull-right">
										<?if($_SESSION['level_user'] == 5) { echo $st_chat[intval("0".$rowc['st_respon'])];} else
											{if($rowc['st_respon'] == 5 ) {
											$sqlz = $db->fetchArray($db->query("SELECT * from tr_pelaporan where id_tr_pelaporan = '$id'"));
											$sqlx = $db->fetchArray($db->query("SELECT * from tb_pendaftaran where id_tb_pendaftaran = '$sqlz[id_tb_pendaftaran]'"));
											echo $sqlx['nama'];							
											} else { echo $st_chat[intval("0".$rowc['st_respon'])];}					
											}
										?> <i class="fa fa-clock-o"></i> 
										<?
										$tgle = tglindo(date_format($rowc['tgl_data'], 'Y-m-d'));
										$jame = date_format($rowc['tgl_data'], 'H:i');				   
										echo $tgle." ".$jame;
										?>
									</small>
								</p>			
							</div>
						</div>	
						<?}?>
						<!-- /.item -->
					</div>
					<!-- /.chat -->
					<? if ($row['sts_laporan'] != 3) {?>		
						<div class="box-footer">
							<form action="respon_.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
								<input type="hidden" name="r" value="n" />
								<input type="hidden" name="id_tr_pelaporan" value="<?php echo $row['id_tr_pelaporan'];?>">	
								<input type="hidden" name="st_respon" value="<?=$_SESSION['level_user'];?>">
								<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />

								<? if ($jmlc == 0) {?>
									<input type="hidden" name="sts_laporan" value="2">		 
								<?} ?>
			 
								<? if($row['id_tb_user'] == $_SESSION['id_tb_user'] or $_SESSION['level_user'] == 1){ ?>
									<div class="form-group">
										<select class="form-control" name="tipe_respon" id="tipe_respon" placeholder="Pilih respon..." required>
											<option value="">-- Pilih respon --</option>
											<option value="1"> Diproses </option>
											<option value="2"> Kunjungan </option>
											<option value="3"> Kunjungan Ulang </option>
											<option value="4"> Report </option>
											<option value="5"> Tanggapan </option>
										</select>
									</div> 
								<?}?>

								<div class="form-group">			 
									<input type="<?= $_SESSION['level_user'] == 1 ? 'text' : 'hidden' ?>" class="form-control" id="datepicker" name="tgl_rencana" value="<?=$tgl;?>" required>
								</div>
								
								<div class="form-group bootstrap-timepicker">
									<input type="<?= $_SESSION['level_user'] == 1 ? 'text' : 'hidden' ?>" class="form-control timepicker" name="waktu_rencana" value="<?=$waktu;?>" required>
								</div>

								<div class="form-group"> 
									<textarea class="form-control" name="respon" placeholder="Tulis catatan..." required></textarea>
								</div>
								<div class="form-group">
									<input type="file" id="exampleInputFile" name="file_respon[]" multiple>
									<p class="help-block">File format jpg, jpeg, png atau pdf</p>
								</div>

								<?if(empty($row['id_tb_user'])) {} else {
									if($row['id_tb_user'] == $_SESSION['id_tb_user'] or $_SESSION['level_user'] == 1 or $_SESSION['level_user'] == 5) { 
								?>			 
									<button type="submit" name="kirim" id="kirim" class="btn btn-info" style="float:right;">Respon</button>
								<?}}?>
							</form>	
						</div>
					<?}}?>
				</div>
			</div>
			<!-- /.box (chat box) -->
		</div>
		<?}?>
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
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- page script -->
<script>
  $(function () {  

    // bootstrap WYSIHTML5 - text editor

    $('.textarea').wysihtml5({
      toolbar: { fa: true, 
	  "font-styles": false,
	  "emphasis": false, //Italics, bold, etc.
	  link: false, // Button to insert a link.
      image: false, // Button to insert an image.
	  html: true	  
	  }
    })
  })
</script>
<script>
$(function () {
 $('#datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy'
    })
	
 //Timepicker
    $('.timepicker').timepicker({
		showInputs: false,
		showMeridian: false     
    })
  })
  
$(document).ready(function(){
  $("#customere").select2({
  ajax: { 
   url: "daftar_customer.php",
   type: "post",
   dataType: 'json',
   delay: 250,
   data: function (params) {
	return {
	  searchTerm: params.term // search term
	};
   },
   processResults: function (response) {
	 return {
		results: response
	 };
   },
   cache: true
  }
 });
});
</script>
</body>
</html>
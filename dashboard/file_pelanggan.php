<?php

@session_start();
//print_r($_SESSION);
include "include/DbConnector.php";
include "include/config.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$id_tb_proses = SafeSQL($_POST['id']);
$st_proses = SafeSQL($_POST['id2']);
$jumlah = SafeSQL($_POST['id3']);
$id_tb_pendaftaran = SafeSQL($_POST['id4']);

$sqlf = "select * from tb_file_pendaftaran where id_tb_proses = '$id_tb_proses' and st_proses = '$st_proses'";
$resf = $db->query($sqlf);

if($_POST['id2'] == 1) {
	$sql = "select * from tb_survey where id_tb_survey = '$id_tb_proses'";
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	
	if($row['sts_survey'] == 1){$tombul = '';} else {$tombul = 'tombuil';}
	
	$judul = "survey";
} else if($_POST['id2'] == 2) { 
	$sql = "select * from tb_instalasi where id_tb_instalasi = '$id_tb_proses'";	
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	
	if($row['sts_instalasi'] == 1){$tombul = '';} else {$tombul = 'tombuil';}
	
	$judul = "pemasangan";
} else if($_POST['id2'] == 3) {
	$sql = "select * from tb_aktivasi where id_tb_aktivasi = '$id_tb_proses'";
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	
	if($row['sts_aktivasi'] == 1){$tombul = '';} else {$tombul = 'tombuil';}
	
	$judul = "pengaktifan";
} else if($_POST['id2'] == 4) {
	$sql = "select * from tb_perawatan where id_tb_perawatan = '$id_tb_proses'";
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	
	if($row['sts_perawatan'] == 1){$tombul = '';} else {$tombul = 'tombuil';}
	
	$judul = "perawatan / maintenance";
} else if($_POST['id2'] == 5) {
	$sql = "select * from tb_pemutusan where id_tb_pemutusan = '$id_tb_proses'";
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	
	if($row['sts_pemutusan'] == 1){$tombul = '';} else {$tombul = 'tombuil';}
	
	$judul = "pemutusan / pengambilan perangkat";
}

	
?>
   <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  
			<div class="modal-header">
				<h4 class="modal-title" style="width: 50%;display: contents;"><b>File data <?=$judul;?></b></h4>
			</div>
				<div class="modal-body">
				<table id="example1" class="table table-bordered table-striped">
				<thead>
				<th></th>
				<th>Tgl Upload</th>
				<th>Dokumen</th> 
				<th>User</th>
				<th>Keterangan</th>
				</thead>
				<tbody>
				<? while($rowf = $db->fetchArray($resf)) {
				$n++;	
				?>
				<tr>
				<td><?=$n;?></td>
				<td><?=date_format($rowf['tgl_data'], 'd-m-Y H:i:s');?></td>
				<td>				
				<?
				$ext = pathinfo($rowf['link_gbr'], PATHINFO_EXTENSION);
				if($ext == "pdf" or $ext == "PDF"){$gambare = 'dist\img\pdf_icon.png' ;} else {$gambare = $rowf['link_gbr'];}
				echo "<a href=\"$rowf[link_gbr]\" target=\"_blank\"><img src=\"$gambare\" alt=\"Foto\" width=\"20%\" ></a>";
				?>
				</td>
				<td>
				<?
				$sqlg = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowf[id_tb_user]'"));	
				if(empty($sqlg['nm_user'])) {echo "-";} else {
				echo "$sqlg[nm_user]";}
				?>
				</td>
				<td><?=$rowf['ket_gbr'];?></td>					
				</tr>
				<?}?>
				</tbody>
				</table>
				<?
				if($jumlah < 5){
				echo "<br><br><br><br><br><br><br><br><br><br>";
				}
				?>
				
				<br>				
	<!--		<button type="submit" class="btn btn-primary">Save</button> -->
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</form>
				</div>
				
				<div class="modal" id="myModal2" data-backdrop="static">
				<div class="modal-dialog">
				  <div class="modal-content">
				  <form role="form" action="file_pendaftaran_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
					<div class="modal-header">
					  <h4 class="modal-title">Tambah File</h4>
					  <button type="button" class="close" data-dismiss="modal">×</button>
					</div><div class="container"></div>
					<div class="modal-body">					
						<input type="hidden" name="j" value="a" />
						<input type="hidden" name="id_tb_proses" value="<?=$id_tb_proses;?>" />
						<input type="hidden" name="st_proses" value="<?=$st_proses;?>" />
						<input type="hidden" name="token" value="<?=$kodeaman?>" />
						<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran?>" />
						
						<div class="form-group">
						  <label for="exampleInputFile">Upload File</label>
						  <input type="file" id="exampleInputFile" name="file_pendaftaran[]" multiple required>

						  <p class="help-block">File format jpg atau pdf</p>
						</div>
						<div class="form-group">
						  <label>Keterangan</label>
							<textarea name="ket_gbr" id="ket_gbr" class="form-control" placeholder="Masukkan keterangan file"></textarea>
						</div>						
						
					</div>
					<div class="modal-footer">
					  <button type="submit" class="btn btn-primary">Save</button>
					  <a href="#" data-dismiss="modal" class="btn">Close</a>					  
					</div>
					</form>
				  </div>
				</div>
				</div>


<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>	

<script>
  $(function () {
    $('#example1').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false
    })
  })
</script>
<script>
$('.tombuil').remove();
</script>			
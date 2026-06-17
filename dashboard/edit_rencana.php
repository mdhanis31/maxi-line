<?php

@session_start();
//print_r($_SESSION);
include "include/DbConnector.php";
include "include/config.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$id_tb_rencana = SafeSQL($_POST['id']);
$st_layanan = SafeSQL($_POST['id2']);
$sqlc = "select * from tb_rencana where id_tb_rencana = '$id_tb_rencana'";
$resc = $db->query($sqlc);
$rowc = $db->fetchArray($resc);
	
?>

<div class="modal-header">
	<h4 class="modal-title"><b>Revisi Jadwal</b></h4>
</div>
<div class="modal-body">
	<form role="form" action="jadwal_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
		<input type="hidden" name="f" value="e" />
		<input type="hidden" name="id_tb_rencana" value="<?=$id_tb_rencana;?>" />
		<input type="hidden" name="id_tb_pendaftaran" value="<?=$rowc['id_tb_pendaftaran'];?>" />
		<input type="hidden" name="token" value="<?=$kodeaman?>" />

		<div class="form-group">
			<label for="exampleInputFile">Tanggal</label>
			<input type="text" class="form-control" id="datepicker1" name="tgl_rencana" value="<?=date_format($rowc['tgl_rencana'], 'd-m-Y');?>" required>
		</div>
		<div class="bootstrap-timepicker">
			<div class="form-group">
			<label for="exampleInputFile">Waktu</label>
			<input type="text" class="form-control timepicker1" name="waktu_rencana" value="<?=date_format($rowc['tgl_rencana'], 'H:i');?>" required>
			</div>
		</div>
		<div class="form-group">
                  <label for="exampleInputFile">Jadwal</label>
                 <select class="form-control select2" id="rencana" name="rencana" data-placeholder="pilih rencana" style="width: 100%;" required> 				 
				 <?$surv = array(1,2,3);
					$pasang = array(4,5);
					$aktif = array(6,7);
					$pasca = array(8,9,10);
				 if (in_array($st_layanan, $surv)) {?>
				 <option value="1" <?if($rowc['rencana'] == 1) {echo "selected";}?>>Survey</option>
				 <?} else if (in_array($st_layanan, $pasang)) {?>
				 <option value="2" <?if($rowc['rencana'] == 2) {echo "selected";}?>>Pemasangan</option>
				 <?} else if (in_array($st_layanan, $aktif)) {?>
				 <option value="3" <?if($rowc['rencana'] == 3) {echo "selected";}?>>Aktivasi</option>
				 <?} else if (in_array($st_layanan, $pasca)) {?>
				<option value="4" <?if($rowc['rencana'] == 4) {echo "selected";}?>>Perawatan / Perbaikan</option>
				<option value="5" <?if($rowc['rencana'] == 5) {echo "selected";}?>>Pengambilan Perangkat (Pemutusan)</option>
				<? } ?>
				</select>
                </div>
		<div class="form-group">
			<label for="exampleInputFile">Petugas</label>
			<!-- Default -->
			<!-- <select class="form-control select2" id="id_tb_user" name="id_tb_user[]" multiple="multiple" data-placeholder="Ketik nama user atau jabatan" style="width: 100%;" required> 				
				<?php $sqla = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowc[id_tb_user]'"));	?>
				<option value="<?=$sqla['id_tb_user'];?>" selected><?=$sqla['nm_user'];?> <?if(empty($sqla['jabatan'])) {;} else {echo "-";}?> <?=$sqla['jabatan'];?></option>
			</select> -->

			<!-- Change to Array -->
			<?php $expArr = explode(",", $rowc['id_tb_user']); ?>
			
			<!-- Option 1 -->
			<!-- <select class="form-control select2" name="id_tb_user[]" id="id_tb_user" multiple="multiple" data-placeholder="Ketik nama user atau jabatan" style="width: 100%;" required>
				<?php $querya = $db->query("select * from tb_user"); ?>

				<?php while ($sqla = $db->fetchArray($querya)) : ?>
					<option value="<?= $sqla['id_tb_user'] ?>" <?= in_array($sqla['id_tb_user'], $expArr) ? 'selected' : '' ?>>
						<?= $sqla['nm_user'] ?> <?= !empty($sqla['jabatan']) ? "- " . $sqla['jabatan'] : "" ?>
					</option>
				<?php endwhile; ?>
			</select> -->
			<!-- End Option 1 -->
			
			<!-- Option 2 -->
			<select class="form-control select2" name="id_tb_user[]" id="id_tb_user" multiple="multiple" data-placeholder="Ketik nama user atau jabatan" style="width: 100%;" required>
				<?php foreach ($expArr as $id_tb_user) : ?>
					<?php $sqla = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$id_tb_user'")); ?>
					<option value="<?= $sqla['id_tb_user'] ?>" <?= in_array($sqla['id_tb_user'], $expArr) ? "selected" : "" ?>>
						<?= $sqla['nm_user'] ?> <?= !empty($sqla['jabatan']) ? "- " . $sqla['jabatan'] : "" ?>
					</option>
				<?php endforeach; ?>
			</select>
			<!-- End Option 2 -->
		</div>
		<div class="form-group">
			<label for="exampleInputFile"></label>
			<input type="checkbox" name="kirimnotif" value="1" style="margin-left: 0px;"><span> Kirim notifikasi ke pelanggan</span>
		</div>
		<br>
		<button type="submit" class="btn btn-primary">Simpan</button>
		<button type="button" class="btn btn-default" style="margin-left:5px;" data-dismiss="modal">Cancel</button>
	</form>
</div>

<script>
	$(document).ready(function(){
		$("#id_tb_user").select2({
			ajax: { 
				url: "daftar_user.php",
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
<script>
$(function () {
	$('#datepicker1').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy'
    })
	
	//Timepicker
    $('.timepicker1').timepicker({
		showInputs: false,
		showMeridian: false
    })
  })
</script>
<?php

@session_start();
//print_r($_SESSION);
include "include/DbConnector.php";
include "include/config.php";

$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$id_tb_pendaftaran = SafeSQL($_POST['id']);
$sql = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
?>
 <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	 <!-- Bootstrap time Picker -->
	<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
	<style>
	.select2-container .select2-selection--single {
	height: max-content;}
}	</style>
	
			<div class="modal-header">
				<h4 class="modal-title"><b>Tambah Jadwal <?//$row['st_layanan']?></b></h4>
			</div>
				<div class="modal-body">
				<form role="form" action="jadwal_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
				<input type="hidden" name="u" value="f" />
				<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran;?>" />
				<input type="hidden" name="token" value="<?=$kodeaman?>" />
				<div class="form-group">
                  <label for="exampleInputFile">Tanggal</label>
                  <input type="text" class="form-control" id="datepicker" name="tgl_rencana" value="<?=$tgl;?>" required>
                </div>
				<div class="bootstrap-timepicker">
				<div class="form-group">
                  <label for="exampleInputFile">Waktu</label>
                  <input type="text" class="form-control timepicker" name="waktu_rencana" value="<?=$waktu;?>" required>
                </div>	
				</div>
				<div class="form-group">
                  <label for="exampleInputFile">Jadwal</label>
                 <select class="form-control select2" id="rencana" name="rencana" data-placeholder="pilih rencana" style="width: 100%;" required> 
				 <option value="">--- Pilih Jadwal ---</option>
				 
				<?
				$surv = array(1,2,3);
				$pasang = array(4,5);
				$pasca = array(8);
				$selesai = array(9,10);
				if (in_array($row['st_layanan'], $surv)) {?>
					<option value="1">Survey</option>
				<?php } elseif (in_array($row['st_layanan'], $pasang)) {?>
					<option value="2">Pemasangan</option>
				<?php } elseif (in_array($row['st_layanan'], $aktif)) {?>
					<option value="3">Pengaktifan</option>
				<?php } elseif (in_array($row['st_layanan'], $pasca)) {?>
					<option value="4">Perawatan / Perbaikan</option>
					<option value="5">Pengambilan Perangkat (Pemutusan)</option>
				<?php } elseif (in_array($row['st_layanan'], $selesai)) {?>
					<option value="5">Pengambilan Perangkat (Pemutusan)</option>
				<?php }  ?>
				</select>
                </div>
				<div class="form-group">
				<label for="exampleInputFile">Petugas</label>
				<select class="form-control select2" id="id_tb_user" name="id_tb_user[]" multiple="multiple" data-placeholder="Ketik nama user atau jabatan" style="width: 100%;" required> 				
				</select>
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

<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
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
</script>				
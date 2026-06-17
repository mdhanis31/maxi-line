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
				<h4 class="modal-title"><b>History Jadwal</b></h4>
			</div>
				<div class="modal-body">
				<form>				
				<div class="form-group">
                  <label for="exampleInputFile">Tanggal</label>
                  <input type="text" class="form-control" id="datepicker1" name="tgl_rencana" value="<?=date_format($rowc['tgl_rencana'], 'd-m-Y');?>" disabled>
                </div>
				<div class="bootstrap-timepicker">
				<div class="form-group">
                  <label for="exampleInputFile">Waktu</label>
                  <input type="text" class="form-control timepicker1" name="waktu_rencana" value="<?=date_format($rowc['tgl_rencana'], 'H:i');?>" disabled>
                </div>	
				</div>
		
				<div class="form-group">
                  <label for="exampleInputFile">Jadwal</label>
                  <input type="text" class="form-control" value="<?=$st_rencana[$rowc['rencana']];?>" disabled>
                </div>	
				
				<div class="form-group">
				<label for="exampleInputFile">Petugas</label>
				<select class="form-control select2" id="id_tb_user" name="id_tb_user[]" multiple="multiple" data-placeholder="Ketik nama user atau jabatan" style="width: 100%;" disabled> 				
				<?
				$id_usere = explode(',',$rowc['id_tb_user']);
				foreach($id_usere as $idu) {
				$sqla = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$idu'"));	?>
				<option value="<?=$sqla['id_tb_user'];?>" selected><?=$sqla['nm_user'];?> <?if(empty($sqla['jabatan'])) {;} else {echo "-";}?> <?=$sqla['jabatan'];?></option>
				<?}?>
				</select>
				</div>							
				<br>				
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
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
				<h4 class="modal-title"><b>Detil Jadwal</b></h4>
			</div>
				<div class="modal-body">
				<form role="form" action="#" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
				<input type="hidden" name="f" value="e" />
				<input type="hidden" name="id_tb_rencana" value="<?=$id_tb_rencana;?>" />
				<input type="hidden" name="id_tb_pendaftaran" value="<?=$rowc['id_tb_pendaftaran'];?>" />
				<input type="hidden" name="token" value="<?=$kodeaman?>" />
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
				<textarea class="form-control" disabled><?
				$id_usere = explode(',',$rowc['id_tb_user']);
				$s = 1;
				foreach($id_usere as $idu) {
				$sqla = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$idu'"));	
				if(empty($sqla['nm_user'])) {
				echo "-";
				} else {
				echo $s++.". ".$sqla['nm_user'] . "&#13;&#10;";
				}
				
				}?></textarea>
				</div>							
				<br>				
				<button type="button" class="btn btn-default" style="margin-left:5px;" data-dismiss="modal">Cancel</button>
				</form>
				</div>


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
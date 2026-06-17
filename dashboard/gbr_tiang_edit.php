<?php

@session_start();
//print_r($_SESSION);
include "include/DbConnector.php";
include "include/config.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$id_tb_gbr_lokasi = SafeSQL($_POST['id']);
$sql = "select * from tb_gbr_lokasi where id_tb_gbr_lokasi = '$id_tb_gbr_lokasi'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
	
?>
			<div class="modal-header">
				<h4 class="modal-title"><b>Revisi Foto</b></h4>
			</div>
			<div class="modal-body">
				<form role="form" action="lokasi_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
				<input type="hidden" name="r" value="f" />
				<input type="hidden" name="id_tb_gbr_lokasi" value="<?=$id_tb_gbr_lokasi;?>" />
				<input type="hidden" name="token" value="<?=$kodeaman?>" />
				<div class="form-group">
                  <label for="exampleInputFile">Nama</label>
                  <input type="text" id="exampleInputFile" class="form-control" name="nama_tiang" placeholder="Masukkan nama" value="<?=$row['nama_gbr'];?>" required>
                </div>
				
				<div class="form-group">
                  <label for="exampleInputFile">Keterangan Foto</label>
                  <textarea name="keterangan_tiang" class="form-control" placeholder="Tuliskan keterangan bila perlu"><?=$row['ket_gbr'];?></textarea>
                </div>
				
				<div class="form-group">
				<img src="<?=$row['link_gbr'];?>" width="200px">
				</div>
				<div class="form-group">
                  <label for="exampleInputFile">Upload File bila ingin mengganti foto</label>
                  <input type="file" id="exampleInputFile" name="foto_tiang">

                  <p class="help-block">File format jpg, jpeg, png</p>
                </div>
				<br>
				<button type="submit" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-default" style="margin-left:5px;" data-dismiss="modal">Cancel</button>
				</form>
			</div>	
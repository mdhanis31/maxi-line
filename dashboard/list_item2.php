<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";

//exit();
$db = new DbConnector();
$now = new DateTime();
$thnya = $now->format("Y");
$kodeaman = $_SESSION['token'];

$id_tr_transaksi = SafeSQL($_POST['id']);
$rowa = $db->fetchArray($db->query("select * from tr_transaksi where id_tr_transaksi ='$id_tr_transaksi'"));

?>
			 <div class="card-body login-card-body">
			 <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Revisi Biaya Tambahan</h4>
              </div>
              <div class="modal-body">
               <div class="col-md-12">
				<form class="form-horizontal" action="invoice_2.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" >
				
				<input type="hidden" name="token" value="<?=$kodeaman?>" />
				<input type="hidden" name="id_tb_pendaftaran" value="<?=$rowa['id_tb_pendaftaran'];?>" />
				<input type="hidden" name="id_tr_invoice" value="<?=$rowa['id_tr_invoice'];?>" />
				<input type="hidden" name="id_tr_transaksi" value="<?=$id_tr_transaksi;?>" />
				<input type="hidden" name="status" value="awal" />
				<input type="hidden" name="edit" value="n" />
				      <div class="form-group"> 
                        <div class="col-sm-12">	
						  <input type="text" class="form-control" id="code" name="nama_transaksi" value="<?=$rowa['nama_transaksi'];?>" placeholder="Masukkan nama item" required>       
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">	
						  <input type="number" class="form-control" id="code" name="harga_transaksi" value="<?=$rowa['harga_transaksi'];?>" placeholder="Masukkan harga satuan" required>   
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">
						  <input type="number" class="form-control" id="code" name="jumlah" value="<?=$rowa['jumlah'];?>" placeholder="Masukkan jumlah item" required>             
						</div>
					   </div>
					   
					    <div class="form-group"> 
                        <div class="col-sm-12">	
						  <input type="text" class="form-control" id="code" name="satuan" value="<?=$rowa['satuan'];?>" placeholder="Masukkan satuan item" required>              
						</div>
					   </div>			
                  
				 <div style="margin-top:50px;">
				   <button type="submit" class="btn btn-info" style="margin-right:5px;">Revisi</button>
				   <button type="reset" class="btn btn-default" data-dismiss="modal">Batal</button>
				 </div>				
				</form>
				</div>
              </div>
              <div class="modal-footer">
              </div>
              </div>
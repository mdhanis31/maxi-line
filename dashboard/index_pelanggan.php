<? 
$page= "index2";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

$kodeaman = $_SESSION['token'];
$id_tb_pendaftaran = $_SESSION['id_tb_pendaftaran'];

$sql = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$res = $db->query($sql);
$row = $db->fetchArray($res);

$sqla = "select * from tb_lokasi where id_tb_lokasi = '$row[id_tb_lokasi]'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$sqld = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$row[id_tb_paket]'"));	
	
?>
	<!-- Select2 -->
	<link rel="stylesheet" href="plugins/select2/select2.css">
	 <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	 <!-- Bootstrap time Picker -->
	<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<!--	<link rel="stylesheet" href="resources/dataTables.searchHighlight.css" type="text/css"/> 
	
	<link rel="stylesheet" type="text/css" href="resources/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="resources/buttons.dataTables.min.css">
	
	<script type="text/javascript" language="javascript" src="resources/jquery-1.12.4.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/jquery.dataTables.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/dataTables.buttons.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.colVis.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.flash.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/pdfmake.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/vfs_fonts.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.html5.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.print.min.js">
	</script>		
	<script type="text/javascript" language="javascript" src="resources/dataTables.searchHighlight.min.js">	
	</script>
	<script type="text/javascript" language="javascript" src="resources/jquery.highlight.js">	
	</script> -->
<!--<script type="text/javascript" class="init">
$(document).ready(function() {
	$.fn.DataTable.ext.pager.numbers_length = 6;
	var dataTable = $('#usaha').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :"json_zonasi.php", // json datasource
						type: "post",  // method  , by default get
					},
    	 "order": [[ 1, "desc" ]],
		"lengthMenu": [[10, 20, 30], [10, 20, 30]],
		
//		dom: 'Blfrtip',		
		searchHighlight: true,		       
    });	
	
/* 	 dataTable.on('draw.dt', function () {
    var info = dataTable.page.info();
    dataTable.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1 + info.start;
		});
	}); */
	
   $('#tableindex_filter input').unbind();
   $('#tableindex_filter input').bind('keyup', function(e) {
       if(e.keyCode == 13) {
        dataTable.search(this.value).draw();  
    }
   });
	
});
</script> -->
<style>
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0!important;
    border: none !important;
}

div.dt-buttons {
    margin-left: 350px;
}

.table {
	font-size: 14px;	
}
</style> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Detail Pelanggan</h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pelanggan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
		<div class="col-md-6">
		<div class="col-md-12">		
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Identitas</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" name="form" id="formnya">
				<div class="box-body">
				 <div class="form-group">
                  <label for="exampleInputtext">Kode Pendaftaran</label>
				  <input type="text" class="form-control" id="nama" name="nama" value="<?=$row['kode_daftar'];?>" disabled placeholder="-">
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Paket</label>
				  <input type="text" class="form-control" id="paket" name="id_tb_paket" value="<?=$sqld['nama_paket'];?>" disabled placeholder="-">
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Identitas</label>
				  <input type="text" class="form-control" id="nama" name="nama" value="<?=$tipe_identitas[intval("0".$row['tipe_identitas'])];?> - <?=$row['no_identitas'];?>" disabled placeholder="-">				 
                </div>
                <div class="form-group">
                  <label for="exampleInputtext">Nama Lengkap</label>
				  <input type="text" class="form-control" id="nama" name="nama" value="<?=$row['nama'];?>" disabled placeholder="-">
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Tanggal Pendaftaran</label>
				  <input type="text" class="form-control" id="nama_area" name="nama_area" value="<?=tglindo(date_format($row['tgl_data'], 'Y-m-d'));?>" disabled placeholder="-">				 
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Area / Kompleks</label>
				  <input type="text" class="form-control" id="nama_area" name="nama_area" value="<?=$rowa['nama_area'];?>" disabled placeholder="-">
                </div>
                <div class="form-group">
                  <label for="exampleInputtext">Lokasi (Nama Jalan)</label>				
                 <input type="text" class="form-control" id="nama_area" name="nama_area" value="<?=$rowa['alamat_tiang'];?>" disabled placeholder="-">					
                </div>
				<div class="form-group">
                <label for="exampleInputtext">Alamat</label>			   
			    <textarea type="text" class="form-control" disabled><?=$row['alamat'];?></textarea>			   
				</div>				
				<div class="form-group">
                  <label for="exampleInputtext">Email</label>				
                  <input type="text" class="form-control" id="kuota" name="kuota" value="<?=$row['email'];?>" disabled placeholder="-">				 
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Telepon</label>				 
				  <input type="text" class="form-control" name="tgl_pasang" value="<?=$row['telp'];?>" disabled>
                </div>				
				<div class="form-group">
                  <label for="exampleInputtext">Status</label>				
                  <input type="text" class="form-control" id="kuota" name="kuota" value="<?=$st_layanan[$row['st_layanan']];?>" disabled placeholder="-">				 
                </div>
              </div>
              <!-- /.box-body --> 
			  
            </form>
          </div>
          <!-- /.box -->
		 </div>	
		</div>
		
		<?
		$sqlb = "select * from tb_rencana where id_tb_pendaftaran = '$id_tb_pendaftaran'";
		$resb = $db->query($sqlb);
		$query_jml = $db->queryNumRows($sqlb);
		$jmlh = $db->getNumRows($query_jml);
		?>
		<div class="col-md-6">
		<div class="col-md-12">
		    <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">History Jadwal</h3>
			
            </div>
            <!-- /.box-header -->
            <!-- table start -->
			<div class="box-body border-radius-none">
              <div class="box-body">
				
				<table class="table table-bordered">
				<thead>
				<th>Tanggal</th>
				<th>Jadwal</th>
				<th>user</th>
				<th></th>
				</thead>
				<tbody>
				<? 
				$allrencana = array();
				$rencananya = array();
				while($rowb = $db->fetchArray($resb)) {?>
				<tr>
				<td><?=date_format($rowb['tgl_rencana'], 'd-m-Y H:i:s');?></td>
				<td><?=$st_rencana[intval("0".$rowb['rencana'])];?></td>
				<td>
				<?
				$sqla = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowb[id_tb_user]'"));	
				if(empty($sqla['nm_user'])) {echo "-";} else {
				echo "$sqla[nm_user]";}
				?>
				</td>
				<td>
				<a href="#" data-toggle="modal" data-target="#vermodal1" class="btn btn-info btn-sm open_modale" id="<?=$rowb['id_tb_rencana'];?>" id2="<?=$row['st_layanan'];?>">Detil</a>	
				</td>	
				</tr>
				<?}?>
				</tbody>
				</table>
							
				
				<!-- part 2 -->
				
				<!-- /part 2 -->
			  </div>
			</div>
          </div>
		</div>
		
		<?
		$sqlc = "select * from tb_survey where id_tb_pendaftaran = '$id_tb_pendaftaran'";
		$resc = $db->query($sqlc);
		$query_jmlc = $db->queryNumRows($sqlc);
		$jmlhc = $db->getNumRows($query_jmlc);
		?>
		<div class="col-md-12">
		    <div class="box box-primary collapsed-box">	
            <div class="box-header with-border">
              <h3 class="box-title">History Survey <?//print_r($rencananya)."<br>";?></h3>
			  <div class="box-tools">		
			    <button type="button" class="btn btn-box-tool" data-widget="collapse">									
				<i class="fa fa-plus"></i>									
				</button>
			  </div>
            </div>
            <!-- /.box-header -->
            <!-- table start -->
			<div class="box-body border-radius-none">
              <div class="box-body">
				<? 
				if(empty($jmlhc)) {
				echo "Belum ada survey";	
				} else {?>
				<table class="table table-bordered">
				<thead>
				<th>Tanggal</th>
				<th>Status</th>
				<th>File</th>
				<th>user</th> 
				<th></th>
				</thead>
				<tbody>
				<? while($rowc = $db->fetchArray($resc)) {
				$sql1 = "select * from tb_file_pendaftaran where id_tb_proses = '$rowc[id_tb_survey]' and st_proses = '1'";
				$res1 = $db->query($sql1);
				$query_jml1 = $db->queryNumRows($sql1);
				$jmlh1 = $db->getNumRows($query_jml1);	
				?>
				<tr>
				<td><?=date_format($rowc['tgl_survey'], 'd-m-Y H:i:s');?></td>
				<td><?=$st_survey[intval("0".$rowc['st_survey'])];?></td>
				<td>
				<a href="#" data-toggle="modal" data-target="#vermodal2" class="open_modali btn <?if(empty($jmlh1)) {echo "btn-info";} else {echo "btn-success";}?> btn-sm" id="<?=$rowc['id_tb_survey'];?>" id2="1" id3="<?=$jmlh1;?>" id4="<?=$id_tb_pendaftaran;?>"><i class="fa <?if(empty($jmlh1)) {echo "fa-plus-square";} else {echo "fa-search-plus";}?>"></i></a>
				</td>
				<td>
				<?
				$sqle = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowc[id_tb_user]'"));	
				if(empty($sqle['nm_user'])) {echo "-";} else {
				echo "$sqle[nm_user]";}
				?>
				</td>
				<td>
				<a class="btn btn-info btn-sm" href="survey_pelanggan.php?id=<?=maxiline($rowc['id_tb_survey'], 'e');?>&s=d&h=y&token=<?echo $kodeaman;?>">Detail</a>
				</td>	
				</tr>
				<?}?>
				</tbody>
				</table>
				<?}?>				
				
				<!-- part 2 -->
				
				<!-- /part 2 -->
			  </div>
			</div>
          </div>
		</div>
	
		<?
		$sqld = "select * from tb_instalasi where id_tb_pendaftaran = '$id_tb_pendaftaran'";
		$resd = $db->query($sqld);
		$query_jmld = $db->queryNumRows($sqld);
		$jmlhd = $db->getNumRows($query_jmld);
		?>
		<div class="col-md-12">
		    <div class="box box-primary collapsed-box">	
            <div class="box-header with-border">
              <h3 class="box-title">History Pemasangan</h3>
			  <div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">									
				<i class="fa fa-plus"></i>									
				</button>
			  </div>
            </div>
            <!-- /.box-header -->
            <!-- table start -->
			<div class="box-body border-radius-none">
              <div class="box-body">
				<table class="table table-bordered">
				<thead>
				<th>Tanggal</th>
				<th>Status</th>
				<th>File</th>
				<th>user</th> 
				<th></th>
				</thead>
				<tbody>
				<? while($rowd = $db->fetchArray($resd)) {
				$sql2 = "select * from tb_file_pendaftaran where id_tb_proses = '$rowd[id_tb_instalasi]' and st_proses = '2'";
				$res2 = $db->query($sql2);
				$query_jml2 = $db->queryNumRows($sql2);
				$jmlh2 = $db->getNumRows($query_jml2);	
				?>
				<tr>
				<td><?=date_format($rowd['tgl_instalasi'], 'd-m-Y H:i:s');?></td>
				<td><?=$st_instal[intval("0".$rowd['st_instalasi'])];?></td>
				<td>
				<a href="#" data-toggle="modal" data-target="#vermodal2" class="open_modali btn <?if(empty($jmlh2)) {echo "btn-info";} else {echo "btn-success";}?> btn-sm" id="<?=$rowd['id_tb_instalasi'];?>" id2="2" id3="<?=$jmlh2;?>" id4="<?=$id_tb_pendaftaran;?>"><i class="fa <?if(empty($jmlh2)) {echo "fa-plus-square";} else {echo "fa-search-plus";}?>"></i></a>
				</td>
				<td>
				<?
				$sqle = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowd[id_tb_user]'"));	
				if(empty($sqle['nm_user'])) {echo "-";} else {
				echo "$sqle[nm_user]";}
				?>
				</td>
				<td>
				<a class="btn btn-info btn-sm" href="instalasi_pelanggan.php?id=<?=maxiline($rowd['id_tb_instalasi'], 'e');?>&s=d&h=y&token=<?echo $kodeaman;?>">Detail</a>
				</td>	
				</tr>
				<?}?>
				</tbody>
				</table>		
				
				<!-- part 2 -->
				
				<!-- /part 2 -->
			  </div>			  
			</div>
          </div>
		</div>
		
				<?
		$sqlf = "select * from tb_aktivasi where id_tb_pendaftaran = '$id_tb_pendaftaran'";
		$resf = $db->query($sqlf);
		$query_jmlf = $db->queryNumRows($sqlf);
		$jmlhf = $db->getNumRows($query_jmlf);
		?>
		<div class="col-md-12">
		    <div class="box box-primary collapsed-box">		
            <div class="box-header with-border">
              <h3 class="box-title">History Pengaktifan</h3>
			  <div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">									
				<i class="fa fa-plus"></i>									
				</button>
			  </div>
            </div>
            <!-- /.box-header -->
            <!-- table start -->
			<div class="box-body border-radius-none">
              <div class="box-body">
				<table class="table table-bordered">
				<thead>
				<th>Tanggal</th>
				<th>Status</th>
				<th>File</th>
				<th>user</th> 
				<th></th>
				</thead>
				<tbody>
				<? while($rowf = $db->fetchArray($resf)) {
				$sql3 = "select * from tb_file_pendaftaran where id_tb_proses = '$rowf[id_tb_aktivasi]' and st_proses = '3'";
				$res3 = $db->query($sql3);
				$query_jml3 = $db->queryNumRows($sql3);
				$jmlh3 = $db->getNumRows($query_jml3);	
				?>
				<tr>
				<td><?=date_format($rowf['tgl_aktivasi'], 'd-m-Y H:i:s');?></td>
				<td><?=$st_aktif[intval("0".$rowf['st_aktivasi'])];?></td>
				<td>
					<a href="#" data-toggle="modal" data-target="#vermodal2" class="open_modali btn <?if(empty($jmlh3)) {echo "btn-info";} else {echo "btn-success";}?> btn-sm" id="<?=$rowf['id_tb_aktivasi'];?>" id2="3" id3="<?=$jmlh3;?>" id4="<?=$id_tb_pendaftaran;?>"><i class="fa <?if(empty($jmlh3)) {echo "fa-plus-square";} else {echo "fa-search-plus";}?>"></i></a>
				</td>
				<td>
				<?
				$sqlg = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowf[id_tb_user]'"));	
				if(empty($sqlg['nm_user'])) {echo "-";} else {
				echo "$sqlg[nm_user]";}
				?>
				</td>
				<td>
				<a class="btn btn-info btn-sm" href="aktivasi_pelanggan.php?id=<?=maxiline($rowf['id_tb_aktivasi'], 'e');?>&s=d&h=y&token=<?echo $kodeaman;?>">Detail</a>
				</td>	
				</tr>
				<?}?>
				</tbody>
				</table>			
				
				<!-- part 2 -->
				
				<!-- /part 2 -->
			  </div>
			</div>
          </div>
		</div>
		
				<?php
					$sqlg = "select * from tb_perawatan where id_tb_pendaftaran = '$id_tb_pendaftaran'";
					$resg = $db->query($sqlg);
					$query_jmlg = $db->queryNumRows($sqlg);
					$jmlhg = $db->getNumRows($query_jmlg);
				?>
				<div class="col-md-12">					
					<div class="box box-primary collapsed-box">					
						<div class="box-header with-border">
							<h3 class="box-title">History Perawatan / Perbaikan</h3>
							<div class="box-tools">
									<button type="button" class="btn btn-box-tool" data-widget="collapse">									
									<i class="fa fa-plus"></i>									
									</button>
							</div>
						</div>
						<!-- /.box-header -->
						<!-- table start -->
						<div class="box-body border-radius-none">
							<div class="box-body">
								<?php if(empty($jmlhg)) {
									echo "Belum ada perawatan / perbaikan";
								} else {?>
									<table class="table table-bordered">
										<thead>
											<th>Tanggal</th>
											<th>Status</th>
											<th>File</th>
											<th>User</th> 
											<th></th>
										</thead>
										<tbody>
											<?php while($rowg = $db->fetchArray($resg)) {
												$sql4 = "select * from tb_file_pendaftaran where id_tb_proses = '$rowg[id_tb_perawatan]' and st_proses = '4'";
												$res4 = $db->query($sql4);
												$query_jml4 = $db->queryNumRows($sql4);
												$jmlh4 = $db->getNumRows($query_jml4);
											?>
												<tr>
													<td><?=date_format($rowg['tgl_perawatan'], 'd-m-Y H:i:s');?></td>
													<td><?=$st_perawatan[intval("0".$rowg['st_perawatan'])];?></td>	
													<td>
													<a href="#" data-toggle="modal" data-target="#vermodal2" class="open_modali btn <?if(empty($jmlh4)) {echo "btn-info";} else {echo "btn-success";}?> btn-sm" id="<?=$rowg['id_tb_perawatan'];?>" id2="4" id3="<?=$jmlh4;?>" id4="<?=$id_tb_pendaftaran;?>"><i class="fa <?if(empty($jmlh4)) {echo "fa-plus-square";} else {echo "fa-search-plus";}?>"></i></a>
													</td>
													<td>
														<?php
															$expArr = explode(",", $rowg['id_tb_user']);

															$n = 1;
															foreach ($expArr as $data) {
																$sqle = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$data'"));

																if(empty($sqle['nm_user'])) {
																	echo "-";
																} else {
																	echo $n++ . ". " . $sqle['nm_user'] . "<br>";
																}
															}
														?>
													</td>
													<td>													
														<a class="btn btn-info btn-sm" href="perawatan_pelanggan.php?id=<?=maxiline($rowg['id_tb_perawatan'], 'e');?>&s=d&h=y&token=<?echo $kodeaman;?>">Detail</a>														
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } ?>
								<!-- part 2 -->
								<!-- /part 2 -->
							</div>
						</div>
					</div>
				</div>
				
				<?php
					$sqlh = "select * from tb_pemutusan where id_tb_pendaftaran = '$id_tb_pendaftaran'";
					$resh = $db->query($sqlh);
					$query_jmlh = $db->queryNumRows($sqlh);
					$jmlhh = $db->getNumRows($query_jmlh);
				?>
				<div class="col-md-12">					
					<div class="box box-primary collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title">History Pemutusan / Pengambilan Perangkat</h3>
							<div class="box-tools">	
								<button type="button" class="btn btn-box-tool" data-widget="collapse">									
								<i class="fa fa-plus"></i>									
								</button>
							</div>
						</div>
						<!-- /.box-header -->
						<!-- table start -->
						<div class="box-body border-radius-none">
							<div class="box-body">
								<?php if(empty($jmlhh)) {
									echo "Belum ada pemutusan / pengambilan perangkat";
								} else {?>
									<table class="table table-bordered">
										<thead>
											<th>Tanggal</th>
											<th>Status</th>
											<th>File</th>
											<th>User</th> 
											<th></th>
										</thead>
										<tbody>
											<?php while($rowh = $db->fetchArray($resh)) {
												$sql4 = "select * from tb_file_pendaftaran where id_tb_proses = '$rowh[id_tb_pemutusan]' and st_proses = '5'";
												$res4 = $db->query($sql4);
												$query_jml4 = $db->queryNumRows($sql4);
												$jmlh4 = $db->getNumRows($query_jml4);
											?>
												<tr>
													<td><?=date_format($rowh['tgl_pemutusan'], 'd-m-Y H:i:s');?></td>
													<td><?=$st_perawatan[intval("0".$rowh['st_pemutusan'])];?></td>
													<td>
													<a href="#" data-toggle="modal" data-target="#vermodal2" class="open_modali btn <?if(empty($jmlh4)) {echo "btn-info";} else {echo "btn-success";}?> btn-sm" id="<?=$rowh['id_tb_pemutusan'];?>" id2="5" id3="<?=$jmlh4;?>" id4="<?=$id_tb_pendaftaran;?>"><i class="fa <?if(empty($jmlh4)) {echo "fa-plus-square";} else {echo "fa-search-plus";}?>"></i></a>
													</td>
													<td>
														<?php
															$expArr = explode(",", $rowh['id_tb_user']);

															$n = 1;
															foreach ($expArr as $data) {
																$sqle = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$data'"));

																if(empty($sqle['nm_user'])) {
																	echo "-";
																} else {
																	echo $n++ . ". " . $sqle['nm_user'] . "<br>";
																}
															}
														?>
													</td>
													<td>														
														<a class="btn btn-info btn-sm" href="pemutusan_pelanggan.php?id=<?=maxiline($rowh['id_tb_pemutusan'], 'e');?>&s=d&h=y&token=<?echo $kodeaman;?>">Detail</a>										
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } ?>
								<!-- part 2 -->
								<!-- /part 2 -->
							</div>
						</div>
					</div>
				</div>
			</div>	
				<!-- right col -->
			</div>
		
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
	<!-- Modal Default Size jadwal -->
	<div class="modal fade" id="vermodal1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content" id="data">
			
			</div>
		</div>
	</div>
	<!-- // Modal Default Size -->
	
	
	<!-- Modal Default Size file -->
	<div class="modal fade" id="vermodal2" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="data2">
		
		</div>
	</div>
	</div>
	<!-- // Modal Default Size -->
  
  
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
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
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

<script>
$(document).ready(function () {
        var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
        if (window.location.hash && isChrome) {
            setTimeout(function () {
                var hash = window.location.hash;
                window.location.hash = "";
                window.location.hash = hash;
            }, 300);
        }
    });
</script>
<script>
	$(document).on('click','.open_modale',function() {
		var id=$(this).attr("id");
		var id2=$(this).attr("id2");
		$.ajax({
			url:"rencana_pelanggan.php",
			method:"post",
			data:{id:id, id2:id2},
			success:function(data)
			{
				$('#data').html(data);
				$('#vermodal1').modal("show");
			}
		});
			
		$('#vermodal1').on('hidden.bs.modal', function () {
			window.location.reload(true);
		});
	});
</script>
<script>
	$(document).on('click','.open_modali',function() {
		var id=$(this).attr("id");
		var id2=$(this).attr("id2");
		var id3=$(this).attr("id3");
		var id4=$(this).attr("id4");
		$.ajax({
			url:"file_pelanggan.php",
			method:"post",
			data:{id:id, id2:id2, id3:id3, id4:id4},
			success:function(data)
			{
				$('#data2').html(data);
				$('#vermodal2').modal("show");
			}
		});
			
		$('#vermodal1').on('hidden.bs.modal', function () {
			window.location.reload(true);
		});
	});
</script>
</body>
</html>

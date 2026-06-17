<? 
$page= "zonasi";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");

$kodeaman = $_SESSION['token'];

if(isset($_GET['id'])) {
$id_tb_lokasi = SafeSQL(maxiline($_GET['id'], 'd'));
$sql = "select * from tb_lokasi where id_tb_lokasi = '$id_tb_lokasi'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
if(empty($row['id_tb_lokasi'])) {
?>
<script>alert('Lokasi tidak ditemukan!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit(); 
}
}


if(empty($row['tgl_pemasangan'])){
$tglpasang = "-";
} else {
$tglpasang = date_format($row['tgl_pemasangan'], 'd-m-Y');	
}

$userlvl = array(1,2,3);
if(!in_array($_SESSION['level_user'], $userlvl)) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}
?>
	<!-- Select2 -->
	<link rel="stylesheet" href="plugins/select2/select2.css">
	 <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
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

.select2-container .select2-selection--single {
	height: max-content;
}

.table {
	font-size: 14px;	
}
</style> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	 <? if($_GET['f']=='d') {echo "Detil Coverage";} else if($_GET['f']=='e') {echo "Edit Coverage";} else {echo "Tambah Coverage";}?>
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Coverage</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col --> 
		<? if($_GET['f']=='d') {?>
		<div class="col-md-6">
		<?} else {?>
		  <section class="col-lg-10">
		<?}?>  
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Lokasi</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="lokasi_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="token" value="<?=$kodeaman?>" />			
			<? if($_GET['f']=='e') {?>
			<input type="hidden" name="f" value="e" />
			<input type="hidden" name="id_tb_lokasi" value="<?=$id_tb_lokasi;?>" />
			<?} else {?>
			<input type="hidden" name="f" value="n" />	
			<?}?>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputtext">Nama</label>
				  <? if($_GET['f']=='d') {?>
				  <input type="text" class="form-control" id="nama" name="nama" value="<?=$row['nama_tiang'];?>" disabled placeholder="-">
				  <?} else if($_GET['f']=='e') {?>
				  <input type="text" class="form-control" id="nama" name="nama" value="<?=$row['nama_tiang'];?>" required placeholder="Masukan nama">
				  <?} else {?>
                  <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukan nama">
				  <?}?>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">STO (Sentral Telepon Otomat)</label>
				  <? if($_GET['f']=='d') {?>
				  <input type="text" class="form-control" id="nama_area" name="nama_area" value="<?=$row['nama_area'];?>" disabled placeholder="-">
				  <?} else if($_GET['f']=='e') {?>
				  <input type="text" class="form-control" id="nama_area" name="nama_area" value="<?=$row['nama_area'];?>" required placeholder="Masukan nama area / kompleks">
				  <?} else {?>
                  <input type="text" class="form-control" id="nama_area" name="nama_area" required placeholder="Masukan nama area / kompleks">
				  <?}?>
                </div>
                <div class="form-group">
                  <label for="exampleInputtext">Alamat STO</label>
				  <? if($_GET['f']=='d') {?>
                  <textarea type="textarea" class="form-control" id="alamat" name="alamat" disabled placeholder="-"><?=$row['alamat_tiang'];?></textarea>
				  <?} else if($_GET['f']=='e') {?>
				  <textarea type="textarea" class="form-control" id="alamat" name="alamat" required placeholder="Masukkan alamat"><?=$row['alamat_tiang'];?></textarea>
				  <?} else {?>
				  <textarea type="textarea" class="form-control" id="alamat" name="alamat" required placeholder="Masukkan alamat"></textarea>
				  <?}?>
                </div>
				<div class="form-group">
                <label for="exampleInputtext">Coverage Desa / Kelurahan</label>
				<? if($_GET['f']=='d') {?>
				<div style="border: 1px solid #ccc; padding: 10px; min-height: 100px; white-space: pre-wrap;"><?
				  $desa = explode(',',$row['id_v_alamat']);
				  $alamate = array();
				  foreach($desa as $ndesa){
				  $rowa = $db->fetchArray($db->query("select * from v_alamat where id_data_kd_pos ='$ndesa'"));				 
				  $alamate[] = $rowa['kelurahan_desa'].','.$rowa['kecamatan'].','.$rowa['kabupaten_kota'].','.$rowa['kd_pos'];				  
				  }
				  
				  $grouped = [];

				  // 1. Kelompokkan data ke dalam array multidimensi
				  foreach ($alamate as $val) {
					$parts = explode(',', $val);
					$kelurahan = $parts[0];
					$kec_kota  = "Kec. ".ucfirst(strtolower($parts[1])) . " (" . ucfirst(strtolower($parts[2])) . ")"; // Contoh: TEMBALANG (SEMARANG)
					
					// Masukkan kelurahan ke dalam grup kecamatan yang sesuai
					$grouped[$kec_kota][] = $kelurahan;
				  }

				  // 2. Loop hasil pengelompokan untuk ditampilkan
				  foreach ($grouped as $header => $list_kelurahan) {
					echo "<strong>$header</strong><br>"; // Cetak Nama Kecamatan (Kota)
					
					foreach ($list_kelurahan as $kel) {
						$kelu = ucfirst(strtolower($kel));
						echo $kelu . "<br>"; // Cetak Daftar Kelurahan di bawahnya
					}
					
					echo "\n"; // Beri jarak antar kelompok (opsional)
				  }
				?>
				</div>
			    <?} else if($_GET['f'] == 'e') {?>	
                <select class="form-control select2" id="id_alamat" name="id_alamat[]" style="width: 100%;" required multiple> 
				<?
				$desa = explode(',',$row['id_v_alamat']);
				foreach($desa as $ndesa){
				$rowa = $db->fetchArray($db->query("select * from v_alamat where id_data_kd_pos ='$ndesa'"));				 
				$alamate = $rowa['kelurahan_desa'].' - '.$rowa['kecamatan'].' - '.$rowa['kabupaten_kota'].' - '.$rowa['kd_pos'];
				?>
				 <option value="<?=$rowa['id_data_kd_pos'];?>" selected="selected"><?=$alamate;?></option>
				<?}?>
				</select>
				<p class="help-block">Multiple select, klik control & pilih</p>
				<?} else {?>
				<select class="form-control select2" id="id_alamat" name="id_alamat[]" data-placeholder="Ketik kecamatan, kota atau desa" style="width: 100%;" required multiple> 
				</select>
				<p class="help-block">Multiple select, klik control & pilih</p>
			   <?}?>
				</div>
				<p></p>
				<div class="form-group">
                  <label for="exampleInputtext">Kuota Tarikan</label>
				   <? if($_GET['f']=='d') {?>
                  <input type="number" class="form-control" id="kuota" name="kuota" value="<?=$row['kuota_tarikan'];?>" disabled placeholder="-">
				  <?} else if($_GET['f']=='e') {?>
                  <input type="number" class="form-control" id="kuota" name="kuota" value="<?=$row['kuota_tarikan'];?>" required placeholder="Masukkan jumlah kuota">
				    <?} else {?>
                  <input type="number" class="form-control" id="kuota" name="kuota" required placeholder="Masukkan jumlah kuota">
				  <?}?>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Tanggal Pemasangan</label>
				  <? if($_GET['f']=='d') {?>
				  <input type="text" class="form-control" id="datepicker" name="tgl_pasang" value="<?=$tglpasang;?>" disabled>
				  <?} else if($_GET['f']=='e') {?>
				  <input type="text" class="form-control" id="datepicker" name="tgl_pasang" value="<?=$tglpasang;?>">
				  <?} else {?>
				  <input type="text" class="form-control" id="datepicker" name="tgl_pasang" >
				  <?}?>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Latitude</label>
				  <? if($_GET['f']=='d') {?>
                  <input type="text" class="form-control" id="latitude" name="latitude" disabled placeholder="-" value="<?=$row['latitude'];?>">
				  <?} else if($_GET['f']=='e') {?>
				  <input type="text" class="form-control" id="latitude" name="latitude" value="<?=$row['latitude'];?>" required placeholder="Masukkan koordinat latitude">
				  <?} else {?>
				  <input type="text" class="form-control" id="latitude" name="latitude" required placeholder="Masukkan koordinat latitude">
				  <?}?>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Longitude</label>
				  <? if($_GET['f']=='d') {?>
                  <input type="text" class="form-control" id="longitude" name="longitude" disabled placeholder="-" value="<?=$row['longitude'];?>">
				  <?} else if($_GET['f']=='e') {?>
				  <input type="text" class="form-control" id="longitude" name="longitude" value="<?=$row['longitude'];?>" required placeholder="Masukkan koordinat longitude">
				  <?} else {?>
				  <input type="text" class="form-control" id="longitude" name="longitude" required placeholder="Masukkan koordinat longitude">
				  <?}?>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Keterangan</label>
				  <? if($_GET['f']=='d') {?>
                  <textarea type="textarea" class="form-control" id="keterangan" name="keterangan" disabled placeholder="-"><?=$row['keterangan_tiang'];?></textarea>
				  <?} else if($_GET['f']=='e') {?>
				  <textarea type="textarea" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan keterangan bila perlu"><?=$row['keterangan_tiang'];?></textarea>
				  <?} else {?> 
				  <textarea type="textarea" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan keterangan bila perlu"></textarea>
                  <?}?>
				</div>
    <!--            <div class="form-group">
                  <label for="exampleInputFile">Gambar</label>
                  <input type="file" id="exampleInputFile" name="gbr_tiang">

                  <p class="help-block">File format jpg, jpeg atau png.</p>
                </div>      -->         
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
				 <? if($_GET['f']=='d') {?>
				 <a href="lokasi_add.php?id=<?=$_GET['id'];?>&f=e" class="btn btn-primary">Edit</a> 	 
				 <?} else {?>
				 <button type="submit" class="btn btn-primary">Submit</button>	 
				 <?}?>
				<button type="reset" class="btn btn-default" style="margin-left:5px;" onclick="window.location.href='lokasi_v.php'">Cancel</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
       <? if($_GET['f']=='d') {?>
		 </div>
		<?} else {?>
		 </section>
		<?}?>
		
		<? if($_GET['f']=='d') {
		$sqlb = "select * from tb_gbr_lokasi where id_tb_lokasi = '$id_tb_lokasi'";
		$resb = $db->query($sqlb);
		$query_jml = $db->queryNumRows($sqlb);
		$jmlh = $db->getNumRows($query_jml);
		?>
		<div class="col-md-6">
		    <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Foto</h3>
			  <div class="box-tools">
					<button type="button" data-toggle="modal" data-target="#vermodal2" class="btn btn-sm btn-primary open_modalu" style="margin-top:1px;" id="<?=$id;?>" id2="c"> 
					<i class="fa fa-plus-square"> </i>
					</button>         
				</div>
            </div>
            <!-- /.box-header -->
            <!-- table start -->
			<div class="box-body border-radius-none">
              <div class="box-body table-responsive">
				<? 
				if(empty($jmlh)) {
				echo "Belum ada gambar";	
				} else {
				while($rowb = $db->fetchArray($resb)) {				    					
				?>				
				<table class="table table-bordered" style="margin-top:5px;">
					<tbody>
					<tr>
						<td>	
							<a href="<?=$rowb['link_gbr'];?>" title="<?=$rowb['nama_gbr'];?>" target="_blank"><img src="<?=$rowb['link_gbr'];?>" alt="<?=$rowb['nama_gbr'];?>" width="200px" /></a>						
						</td>
						<td>
						<table  class="table table-bordered" width="100%">
							<tr>
							  <td width="30%" valign="left">Nama</td>
							  <td width="2%" valign="center"> : </td>
							  <td width="68%" valign="left"><?=$rowb['nama_gbr'];?></td>
							</tr>
							<tr>
							  <td>Upload</td>
							  <td>:</td>
							  <td><?=$rowb['tgl_data']->format("d-m-Y");?>, <?=$rowb['tgl_data']->format("H:i");?> WIB</td>
							</tr>
							<tr>					
							  <td>Keterangan</td>
							  <td>:</td>
							  <td><?=$rowb['ket_gbr'];?></td> 
							</tr>
						</table>
						</td>
					</tr>					
					</tbody>
				</table>
				<div style="margin-top:5px;">
				<button type="button" data-toggle="modal" data-target="#vermodal1" class="btn btn-info btn-sm open_modale" id="<?=$rowb['id_tb_gbr_lokasi'];?>"><i class="fa fa-edit"></i></button> 						
				<a href="lokasi_.php?id=<?=maxiline($rowb['id_tb_gbr_lokasi'], 'e');?>&g=h&token=<?echo $kodeaman;?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus foto?')"><i class="nav-icon fa fa-times"></i></a>
				</div>
				<?}}?>				
				
				<!-- part 2 -->
				
				<!-- /part 2 -->
			  </div>
			</div>
          </div>
		</div>
		<?}?>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

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

  <!-- Modal Default Size -->
	<div class="modal fade" id="vermodal1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">			
			<div class="modal-content" id="data">
			
			</div>
		</div>
	</div>
	<!-- // Modal Default Size -->
	
	<!-- Modal Default Size -->
	<div class="modal fade" id="vermodal2" role="dialog">
	  <div class="modal-dialog modal-lg" role="document" style="max-width: 50%;">			
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Tambah Foto</b></h4>
			</div>
				<div class="modal-body">
				<form role="form" action="lokasi_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
				<input type="hidden" name="u" value="f" />
				<input type="hidden" name="id_tb_lokasi" value="<?=$id_tb_lokasi;?>" />
				<input type="hidden" name="token" value="<?=$kodeaman?>" />
				<div class="form-group">
                  <label for="exampleInputFile">Nama</label>
                  <input type="text" id="exampleInputFile" class="form-control" name="nama_tiang" placeholder="Masukkan nama" required>
                </div>
				
				<div class="form-group">
                  <label for="exampleInputFile">Keterangan Foto</label>
                  <textarea name="keterangan_tiang" class="form-control" placeholder="Tuliskan keterangan bila perlu"></textarea>
                </div>
				
				<div class="form-group">
                  <label for="exampleInputFile">Upload File</label>
                  <input type="file" id="exampleInputFile" name="foto_tiang" required>

                  <p class="help-block">File format jpg, jpeg, png</p>
                </div>
				<br>
				<button type="submit" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-default" style="margin-left:5px;" data-dismiss="modal">Cancel</button>
				</form>
				</div>		
		</div>  
	   </div>
	</div>
	<!-- // Modal Default Size -->
	
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
<script>
$(function () {
 $('#datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy'
    })
  })
</script>
<script>
	 $(document).ready(function(){
	  $("#id_alamat").select2({
	  ajax: { 
	   url: "daftar_alamat.php",
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
<? if($_GET['f']=='d') {
?>
<script>
$(document).on('click','.open_modale',function()
	{
		var id=$(this).attr("id");
		var id2=$(this).attr("id2");
		$.ajax({
			url:"gbr_tiang_edit.php",
			method:"post",
			data:{id:id},
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
<?}?>
</script>
</body>
</html>

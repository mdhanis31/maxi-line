<? 
$page= "verifikasi";
$pages= "survey";


include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$wektuiki = $now->format("Y-m-d H:m:s");
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");

$kodeaman = $_SESSION['token'];

if($_GET['s'] == "d" or $_GET['s'] == "e") { 
$id_tb_survey = maxiline(SafeSQL($_GET['id']), 'd');
$sql = "select * from tb_survey where id_tb_survey = '$id_tb_survey'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$id_tb_pendaftaran = $row['id_tb_pendaftaran'];

$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$tglsurvey = date_format($row['tgl_survey'], 'd-m-Y');	
$jamsurvey = date_format($row['tgl_survey'], 'H:i');

} else {
$id_tb_pendaftaran = maxiline(SafeSQL($_GET['id']), 'd');
$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$sql = "select top 1 * from tb_rencana where id_tb_pendaftaran = '$id_tb_pendaftaran' and rencana = '1' and st_rencana = '1' order by tgl_rencana asc";
$res = $db->query($sql);
$row = $db->fetchArray($res);
if(empty($row['tgl_rencana'])){
$tglsurvey = "";
$jamsurvey = "";
} else {
$tglsurvey = date_format($row['tgl_rencana'], 'd-m-Y');	
$jamsurvey = date_format($row['tgl_rencana'], 'H:i');
}
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
	 <!-- Bootstrap time Picker -->
	<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
	
	<!-- bootstrap wysihtml5 - text editor -->
	<link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
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

.wysihtml5-sandbox {
resize: vertical;
}
</style> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	 <? if($_GET['s']=='d') {echo "Detil Data";} else if($_GET['s']=='e') {echo "Revisi Data";} else {echo "Tambah Data";}?>
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Survey</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col --> 
		<section class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Survey</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="survey_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="token" value="<?=$kodeaman?>" />
			<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran;?>" />
			<input type="hidden" name="id_tb_rencana" value="<?=$row['id_tb_rencana'];?>" />			
			<? if($_GET['s']=='e' and $_GET['t']!='u') {?>
			<input type="hidden" name="f" value="e" />
			<input type="hidden" name="id_tb_survey" value="<?=$id_tb_survey;?>" />
			<?} else if($_GET['t']=='u') {?>
			<input type="hidden" name="f" value="e" />
			<input type="hidden" name="t" value="u" />
			<input type="hidden" name="id_tb_survey" value="<?=$id_tb_survey;?>" />
			<?} else {?>
			<input type="hidden" name="f" value="n" />	
			<?}?>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputtext">Kode</label>
				  <input type="text" class="form-control" id="nama" value="<?=$rowa['kode_daftar'];?>" disabled placeholder="-">
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Nama</label>
				  <input type="text" class="form-control" id="nama" value="<?=$rowa['nama'];?>" disabled placeholder="-">
                </div>
				<?
				$sqlb = "select * from tb_lokasi where id_tb_lokasi = '$rowa[id_tb_lokasi]'";
				$resb = $db->query($sqlb);
				$rowb = $db->fetchArray($resb);
				?>
				<div class="form-group">
                  <label for="exampleInputtext">Area / Kompleks</label>
				  <input type="text" class="form-control" id="nama_area" value="<?=$rowb['nama_area'];?>" disabled placeholder="-">				  
                </div>
                <div class="form-group">
                  <label for="exampleInputtext">Lokasi (Nama Jalan)</label>
                  <textarea type="textarea" class="form-control" id="alamat" disabled placeholder="-"><?=$rowb['alamat_tiang'];?></textarea>				 
                </div>
				<div class="form-group">
                <label for="exampleInputtext">Desa / kecamatan</label>
			    <?
				  $rowc = $db->fetchArray($db->query("select * from v_alamat where id_data_kd_pos ='$rowb[id_v_alamat]'"));
				  if(empty($rowb['id_v_alamat'])){
				  $id_alamat = "-";
				  } else {
				  $id_alamat = $rowc['kelurahan_desa'].' - '.$rowc['kecamatan'].' - '.$rowc['kabupaten_kota'].' - '.$rowc['kd_pos'] ;
				  }	
				?>
			    <input type="text" class="form-control" value="<?=$id_alamat;?>" disabled>			  
				</div>
				<div class="form-group">
                  <label for="exampleInputFile">Tanggal</label>
                  <input type="text" class="form-control" id="datepicker" name="tglsurvey" value="<?if($_GET['t'] == "l") { echo $wektuiki;} else {echo $tglsurvey;}?>" readonly>
                </div>
				<div class="bootstrap-timepicker">
				<div class="form-group">
                  <label for="exampleInputFile">Waktu</label>
                  <input type="text" class="form-control timepicker" name="jamsurvey" value="<?=$jamsurvey;?>" <?if($_GET['s'] == "d") {echo "disabled";} else {echo "required";}?>>
                </div>	
				</div>
				
				<div class="form-group">
				<label for="exampleInputFile">Petugas</label>
				<select class="form-control select2" id="id_tb_user" name="id_tb_user[]" multiple="multiple"  data-placeholder="Ketik nama user atau jabatan" style="width: 100%;"  <?if($_GET['s'] == "d") {echo "disabled";} else {echo "required";}?>> 				
				<?
				$id_usere = explode(',',$row['id_tb_user']);
				foreach($id_usere as $idu) {
				$sqld = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$idu'"));	?>
				<option value="<?=$sqld['id_tb_user'];?>" selected><?=$sqld['nm_user'];?> <?if(empty($sqld['jabatan'])) {;} else {echo "-";}?> <?=$sqld['jabatan'];?></option>
				<?}?>
				</select>
				</div>
				
				<div class="form-group">
                  <label for="exampleInputFile">Hasil</label>
                 <select class="form-control select2" id="rencana" name="st_survey" data-placeholder="pilih hasil survey" style="width: 100%;" <?if($_GET['s'] == "d") {echo "disabled";} else {echo "required";}?>> 
				 <?if($_GET['s'] == "b") {?>
				 <option value="">--- Pilih Hasil ---</option>
				 <?}?>
				 <option value="1" <?if($row['st_survey'] == 1) {echo "selected";}?> >Direview</option>
				 <option value="2" <?if($row['st_survey'] == 2) {echo "selected";}?> >Disetujui</option>
				 <option value="3" <?if($row['st_survey'] == 3) {echo "selected";}?> >Ditolak</option>
				</select>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Keterangan/alasan</label>
				  <? if($_GET['s']=='d') {?>
                  <textarea type="textarea" class="form-control" id="keterangan" name="ket_survey" disabled placeholder="-"><?=$row['ket_survey'];?></textarea>
				  <?} else if($_GET['s']=='e') {?>
				  <textarea type="textarea" class="form-control" id="keterangan" name="ket_survey" placeholder="Masukkan keterangan" ><?=$row['ket_survey'];?></textarea>
				  <?} else {?> 
				  <textarea type="textarea" class="form-control" id="keterangan" name="ket_survey" placeholder="Masukkan keterangan" ></textarea>
                  <?}?>
				</div>
				
				<div class="form-group">
				<label for="exampleInputFile"></label>
				<?
				if($_GET['s']=='d') {} else {
				?>
				  <input type="checkbox" name="kirimnotif" value="1" style="margin-left: 0px;"><span> Kirim notifikasi ke pelanggan</span>
				<?}?>
				</div>
    <!--            <div class="form-group">
                  <label for="exampleInputFile">Gambar</label>
                  <input type="file" id="exampleInputFile" name="gbr_tiang">

                  <p class="help-block">File format jpg, jpeg atau png.</p>
                </div>      -->         
              </div>
              <!-- /.box-body -->
			  <?
			  if($_GET['v']=='a') { } else {?>
              <div class="box-footer" id="olaole">
				 <? if($_GET['s']=='d' and $_GET['t']!='u') {?>
				 <a href="survey_add.php?id=<?=maxiline($id_tb_survey, 'e');?>&s=e" class="btn btn-primary">Revisi</a> 	 
				 <?} else if($_GET['s']=='d' and $_GET['t']=='u') {?>
				 <a href="survey_add.php?id=<?=maxiline($id_tb_survey, 'e');?>&s=e&t=u" class="btn btn-primary">Revisi</a> 
				 <?} else if($_GET['s']=='e' and $_GET['t']=='u') {?>
				 <button type="submit" class="btn btn-primary">Submit</button>	 
				 <?} else {?>
				 <button type="submit" class="btn btn-primary">Submit</button>	 
				 <?}?>
				
				<?if($_GET['t']=='u') {?>
				<button type="reset" class="btn btn-default" style="margin-left:5px;" onclick="window.location.href='survey_v.php'">Cancel</button>
				<?} else {?>
				<button type="reset" class="btn btn-default" style="margin-left:5px;"  onclick="window.location.href='pendaftaran_dtl.php?id=<?=maxiline($id_tb_pendaftaran, 'e');?>'" >Cancel</button>
				<?}?>
			  </div>
			  <?}?>
            </form>
          </div>
          <!-- /.box -->      
		 </section>	
        <!-- right col -->
		<?
		if($_GET['s'] == "d" or $_GET['s'] == "e") {
		?>
		<section class="col-lg-4">
		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Laporan</h3>
			  <div class="box-tools">
				<?php if ($row['sts_survey'] != 2) {?>
						<a href="laporan_survey_add.php?id=<?=maxiline($id_tb_survey, 'e');?>&s=b&token=<?echo $kodeaman;?>" class="btn btn-sm btn-primary" style="margin-top:1px;"> 
						<i class="fa fa-plus-square"><span class="tombole"> Input Laporan</span> </i>
						</a>
				<?php } else {}?>				
			</div>
            </div>
			<?
			$sqlx = "select * from tb_laporan where id_jns_laporan = '$id_tb_survey' and jns_laporan = '1'";
			$resx = $db->query($sqlx);
			$query_jmlx = $db->queryNumRows($sqlx);
			$jmlhx = $db->getNumRows($query_jmlx);
			?>
			<div class="box-body">
			<?
			if(empty($jmlhx)) {?>
			<div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <li class="item">
				  Belum ada laporan
				  </li>
                </ul>
			</div>	
			<?} else {?>
				<div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
				<?
				while($rowx = $db->fetchArray($resx)) {
				$sqlz = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowx[id_tb_user]'"));	
				?>
					<li class="item">
						<div class="product-img">
						  <img src="dist/img/icon.jpg" alt="Laporan Image" class="img-size-50">
						</div>
						<div class="product-info">
						  <a href="laporan_survey_add.php?id=<?=maxiline($rowx['id_tb_laporan'], 'e');?>&s=e&t=u" class="product-title"><?=$sqlz['nm_user']?>
							 <span class="badge badge-info float-right"><?=date_format($rowx['tgl_laporan'], 'd-m-Y');?></span></a>
						  <span class="product-description">
							<?=$rowx['judul_laporan'];?>
						  </span>
						</div>
					</li>
				<?}?>
				</ul>
				</div>
			<?}
			?>	
			</div>
		</div>	
		</section>
		<?}?>
      </div>
      <!-- /.row (main row) -->
	  
	  <!-- modal upload image -->
	  <div class="modal fade" id="imageUploadModal" tabindex="-1" role="dialog">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <form id="imageUploadForm" enctype="multipart/form-data">
		  <input type="hidden" name="j" value="a">
	      <input type="hidden" name="id_tb_proses" value="999">
		  <input type="hidden" name="st_proses" value="1">
		  <input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran?>">
			<div class="modal-header">
			  <h4 class="modal-title">Upload Image</h4>
			</div>
			<div class="modal-body">
			<div class="form-group">  
			<input type="file" name="imagenya" id="imageFile" required />
			</div>
			
			<div class="form-group">
			<label>Keterangan</label>
			<textarea name="ket_gbr" id="ket_gbr" class="form-control" placeholder="Masukkan keterangan file"></textarea>
			</div>			
			  
			</div>		
			<div class="modal-footer">
			  <div id="laporanx" style="color:red;float:left;" ></div>
			  <button type="submit" class="btn btn-primary">Upload</button>
			  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		  </form>		  
		</div>
	  </div>
	</div>
	<!-- end modal upload image -->   

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
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
$(function () {

	
 //Timepicker
    $('.timepicker').timepicker({
		showInputs: false,
		showMeridian: false     
    })
  })
</script>
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
<?
if($_GET['h']=='y') {?>
<script>
$('#olaole').remove();
</script>
<?}?>
</body>
</html>

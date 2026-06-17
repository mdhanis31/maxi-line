<? 
$page= "verifikasi";
$pages= "aktivasi";


include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$wektuiki = $now->format("Y-m-d H:m:s");
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");

$kodeaman = $_SESSION['token'];

if($_GET['s'] == "d" or $_GET['s'] == "e") { 

	$id_tb_laporan = maxiline(SafeSQL($_GET['id']), 'd');
	
	$sqlu = "select * from tb_laporan where id_tb_laporan = '$id_tb_laporan'";
	$resu = $db->query($sqlu);
	$rowu = $db->fetchArray($resu);
	$id_tb_aktivasi = $rowu['id_jns_laporan'];
	
	$sql = "select * from tb_aktivasi where id_tb_aktivasi = '$id_tb_aktivasi'";
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	$id_tb_pendaftaran = $row['id_tb_pendaftaran'];

	$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
	$resa = $db->query($sqla);
	$rowa = $db->fetchArray($resa);

	$tgllaporan = date_format($rowu['tgl_laporan'], 'd-m-Y');

} else if($_GET['s'] == "b") {

	$id_tb_aktivasi = maxiline(SafeSQL($_GET['id']), 'd');
	$sql = "select * from tb_aktivasi where id_tb_aktivasi = '$id_tb_aktivasi'";
	$res = $db->query($sql);
	$row = $db->fetchArray($res);
	$id_tb_pendaftaran = $row['id_tb_pendaftaran'];

	$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
	$resa = $db->query($sqla);
	$rowa = $db->fetchArray($resa);

	$tgllaporan = date_format($row['tgl_aktivasi'], 'd-m-Y');	

} else {
	?>
	<script>alert('Data tidak ditemukan!')</script>;
	<script>document.location.href="index.php"</script>
	<?php 
	exit();  	
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
	 <? if($_GET['s']=='d') {echo "Detil Laporan";} else if($_GET['s']=='e') {echo "Revisi Laporan";} else {echo "Input Laporan";}?>
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pengaktifan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col --> 
		<section class="col-lg-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Laporan Pengaktifan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="aktivasi_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="token" value="<?=$kodeaman?>" />			
			<? if($_GET['s']=='e') {?>
			<input type="hidden" name="l" value="e" />
			<input type="hidden" name="id_tb_laporan" value="<?=$id_tb_laporan;?>" />
			<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran;?>" />			
			<input type="hidden" name="id_tb_aktivasi" value="<?=$id_tb_aktivasi;?>" />
			<?} else if($_GET['s']=='b') {?>
			<input type="hidden" name="l" value="b" />
			<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran;?>" />
			<input type="hidden" name="id_tb_aktivasi" value="<?=$id_tb_aktivasi;?>" />
			<?}?>
				<div class="col-md-6">
				<div class="box-body">
                <div class="form-group">
                  <label for="exampleInputtext">Kode Pendaftaran</label>
				  <input type="text" class="form-control" value="<?=$rowa['kode_daftar'];?>" disabled placeholder="-">
                </div>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="box-body">		
				<div class="form-group">
                  <label for="exampleInputtext">Nama Pendaftaran</label>
				  <input type="text" class="form-control" value="<?=$rowa['nama'];?>" disabled placeholder="-">
                </div>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="box-body">
				<div class="form-group">
				<?
				$sqlb = "select * from tb_lokasi where id_tb_lokasi = '$rowa[id_tb_lokasi]'";
				$resb = $db->query($sqlb);
				$rowb = $db->fetchArray($resb);
				?>				
                  <label for="exampleInputtext">Alamat</label>
                  <textarea type="textarea" class="form-control" disabled placeholder="-"><?=$rowb['nama_area'].PHP_EOL;?><?=$rowb['alamat_tiang'];?></textarea>				 
                </div>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="box-body">
				<div class="form-group">
				<label for="exampleInputFile">Petugas</label>
				<?
				$id_usere = explode(',',$row['id_tb_user']);
				foreach($id_usere as $idu) {
				$sqld = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$idu'"));
				if(empty($sqld['jabatan'])) {$jabatan = '';} else {$jabatan = "(".$sqld['jabatan'].")";}
				$namae[] = $sqld['nm_user']." ".$jabatan;
				}
				$namenya = implode('<br>',$namae); 
				?>
				<textarea type="textarea" class="form-control" disabled placeholder="-"> <?=$namenya;?></textarea>
				</div>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="box-body">
				<div class="form-group">
                  <label for="exampleInputFile">Tanggal</label>
                  <input type="text" class="form-control" id="datepicker" required name="tgl_laporan" value="<?=$tgllaporan;?>">
                </div>	
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="box-body">
				<div class="form-group">
                  <label for="exampleInputFile">Hasil</label>
                 <select class="form-control select2" data-placeholder="pilih hasil aktivasi" style="width: 100%;" disabled> 
				 <?if($_GET['s'] == "b") {?>
				 <option value="">--- Pilih Hasil ---</option>
				 <?}?>
				 <option value="1" <?if($row['st_aktivasi'] == 1) {echo "selected";}?> >Direview</option>
				 <option value="2" <?if($row['st_aktivasi'] == 2) {echo "selected";}?> >Disetujui</option>
				 <option value="3" <?if($row['st_aktivasi'] == 3) {echo "selected";}?> >Ditolak</option>
				</select>
                </div>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="box-body">
				<div class="form-group">
                  <label for="exampleInputtext">Judul Laporan</label>
				  <input type="text" class="form-control" name="judul_laporan" value="<?=$rowu['judul_laporan'];?>" required placeholder="Tulis judul laporan">
                </div>
				</div>
				</div>
				
				<div class="col-md-6">
				<div class="box-body">
				<div class="form-group">
                  <label for="exampleInputtext">Pembuat Laporan</label>
				  <?
				  $sqlo = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowu[id_tb_user]'"));
				  ?>
				  <input type="text" class="form-control" value="<?=$sqlo['nm_user'];?>" disabled placeholder="-">
                </div>
				</div>
				</div>
				
				<div class="col-md-12">
				<div class="box-body">
				<div class="form-group">
                  <label for="exampleInputtext">Laporan</label>
				    <? if($_GET['s']=='d') {?>
                  <textarea type="textarea" class="form-control" id="keterangan" name="isi_laporan" disabled placeholder="-"><?=$rowu['isi_laporan'];?></textarea>
				  <?} else if($_GET['s']=='e') {?>
				  <textarea type="textarea" class="form-control" id="keterangan" name="isi_laporan" <? if ($row['sts_aktivasi'] != 2) {echo "required";} else {echo "disabled";}?> placeholder="Masukkan laporan" ><?=$rowu['isi_laporan'];?></textarea>
				  <?} else {?> 
				  <textarea type="textarea" class="form-control" id="keterangan" name="isi_laporan" required placeholder="Masukkan laporan" ></textarea>
                  <?}?>
				</div>
				
				<div class="form-group">
				<label for="exampleInputFile"></label>				
				  <input type="checkbox" name="kirimnotif" value="2"><span style="margin-left: 1px;"> Kirim notifikasi ke OPS</span>
				</div>     
              </div>
			  </div>
              <!-- /.box-body -->			  
			 
              <div class="box-footer">
			  <div class="col-md-12">
				<?
				if($row['sts_aktivasi'] == 1) {
					if($_GET['s']=='e') {
						if($rowu['id_tb_user']== $_SESSION['id_tb_user']) {?>
						 <button type="submit" class="btn btn-primary" id="olaole">Simpan</button>	
						<?}
					} else {	
					?>
					 <button type="submit" class="btn btn-primary" id="olaole">Simpan</button>
					<?} 
				}?>
				 <button type="reset" class="btn btn-default" style="margin-left:5px;"  onclick="history.back()" >Cancel</button>
			  </div>
			  </div>
            </form>
          </div>
          <!-- /.box -->      
		 </section>	
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
	  
	  <!-- modal upload image -->
	  <div class="modal fade" id="imageUploadModal" tabindex="-1" role="dialog">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <form id="imageUploadForm" enctype="multipart/form-data">
		  <input type="hidden" name="j" value="a">
	      <input type="hidden" name="id_tb_proses" value="<?=$id_tb_aktivasi?>">
		  <input type="hidden" name="st_proses" value="3">
		  <input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran?>">
			<div class="modal-header">
			  <h4 class="modal-title">Upload Image</h4>
			</div>
			<div class="modal-body">
			<div class="form-group">  
			<input type="file" name="imagenya" id="imageFile" required />
			<p class="help-block" style="color:red;">File format jpg, png, jpeg, pdf atau PDF. Max size 15MB. Max resolusi 3000x3000px </p>
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
		$('#datepicker').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		})
		
		//Timepicker
		$('.timepicker').timepicker({
			showInputs: false,
			showMeridian: false     
		})
	});
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
<script>
$(document).ready(function() {
    //Add text editor
	$('#keterangan').wysihtml5({
		  toolbar: { 
		  fa: true, 
		  "font-styles": false,
		  link: false, // Button to insert a link.
		  image: false, // Button to insert an image.
		  html: true	  
		  }
	});	
	
	<?if($_GET['h']=='y') {?>
		$('.wysihtml5-toolbar').remove();
		$('#keterangan').prop('disabled', true);		
	<?} else {
		if ($row['sts_ajtivasi'] != 2) {?>
			$('.wysihtml5-toolbar').append('<li><div class="btn-group"><a class="btn btn-default" id="custom-insert-image"><span class="fa fa-file-image-o"></span></a></div></li>');
		<?}
	  }?> 
	
	 $('#custom-insert-image').on('click', function () {
		$('#imageUploadModal').modal('show');
	  });
	 
 
 
})  

</script>
 
<?
if($_GET['h']=='y') {?>
<script>
$('#olaole').remove();
$('#custom-insert-image').remove();
</script>
<?}?>

<script>
$('#imageUploadForm').on('submit', function (e) {
  e.preventDefault();

  var formData = new FormData(this);

  $.ajax({
    url: 'upload_image.php', // Your backend image upload handler
    type: 'POST',
    data: formData,
	dataType: 'json',
    success: function (data) {
	  if (data.status == 1) {
		  $('#imageUploadModal').modal('hide');

		  // Insert image into editor
		  var imageHtml = "http://" + data.imageUrl;
		  //$('#keterangan').data("wysihtml5").composer.commands.exec("insertHTML", imageHtml);
		  
		  // Insert image into WYSIHTML5
		  var iframe = $('#keterangan').siblings('.wysihtml5-sandbox')[0];
		  var editor = iframe.contentWindow;
		  editor.document.execCommand('insertImage', false, imageHtml);
		  
		  
		  //const imageHtml = '<img src="' + data.imageUrl + '" alt="Inserted Image">';

		  // Insert image into WYSIHTML5
		  //const iframe = $('#keterangan').siblings('.wysihtml5-sandbox')[0];
		  //const editor = iframe.contentWindow;
		  //editor.document.execCommand('insertHTML', false, imageHtml);
		  
		  
	  } else {
		  $("#laporanx").text(data.error);  
	  }
    },
	
    cache: false,
    contentType: false,
    processData: false
  });
 }); 
</script>
</body>
</html>

<? 
$page= "verifikasi";
$pages= "aktivasi";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");

$kodeaman = $_SESSION['token'];

$id_tb_aktivasi = maxiline(SafeSQL($_GET['id']), 'd');
$sql = "select * from tb_aktivasi where id_tb_aktivasi = '$id_tb_aktivasi'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$id_tb_pendaftaran = $row['id_tb_pendaftaran'];

$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$tglaktivasi = date_format($row['tgl_aktivasi'], 'd-m-Y');	
$jamaktivasi = date_format($row['tgl_aktivasi'], 'H:i');

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

.select2-container .select2-selection--single {
	height: max-content;
}
</style> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	 <? if($_GET['s']=='d') {echo "Detil Data";} else if($_GET['s']=='e') {echo "Revisi Data";} else {echo "Input Data";}?>
	
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
		<section class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pengaktifan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form>
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
                  <input type="text" class="form-control" id="datepicker" name="tglaktivasi" value="<?=$tglaktivasi;?>" readonly>
                </div>
				<div class="bootstrap-timepicker">
				<div class="form-group">
                  <label for="exampleInputFile">Waktu</label>
                  <input type="text" class="form-control timepicker" name="jamaktivasi" value="<?=$jamaktivasi;?>" <?if($_GET['s'] == "d") {echo "disabled";} else {echo "required";}?>>
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
                 <select class="form-control select2" id="rencana" name="st_aktivasi" data-placeholder="pilih hasil pengaktifan" style="width: 100%;" <?if($_GET['s'] == "d") {echo "disabled";} else {echo "required";}?>> 
				 <?if($_GET['s'] == "b") {?>
				 <option value="">--- Pilih Hasil ---</option>
				 <?}?>
				 <option value="1" <?if($row['st_aktivasi'] == 1) {echo "selected";}?> >Pending</option>
				 <option value="2" <?if($row['st_aktivasi'] == 2) {echo "selected";}?> >Selesai</option>
				 <option value="3" <?if($row['st_aktivasi'] == 3) {echo "selected";}?> >Gagal</option>
				</select>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Keterangan/alasan</label>
				  <? if($_GET['s']=='d') {?>
                  <textarea type="textarea" class="form-control" id="keterangan" name="ket_aktivasi" disabled placeholder="-"><?=$row['ket_aktivasi'];?></textarea>
				  <?} else if($_GET['s']=='e') {?>
				  <textarea type="textarea" class="form-control" id="keterangan" name="ket_aktivasi" placeholder="Masukkan keterangan" required><?=$row['ket_aktivasi'];?></textarea>
				  <?} else {?> 
				  <textarea type="textarea" class="form-control" id="keterangan" name="ket_aktivasi" placeholder="Masukkan keterangan" required></textarea>
                  <?}?>
				</div>  
	
              </div>
              <!-- /.box-body -->
			 <div class="box-footer" > 
				<button type="reset" class="btn btn-default" style="margin-left:5px;" onclick="history.back()">Cancel</button>
			 </div>
            </form>
          </div>
          <!-- /.box -->      
		 </section>	
        <!-- right col -->
		
		 <?
		if($_GET['s'] == "d" and $_SESSION['level_user'] != 5) {
		?>
		<section class="col-lg-4">
		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Laporan</h3>
			  <div class="box-tools">
							
			  </div>
            </div>
			<?
			$sqlx = "select * from tb_laporan where id_jns_laporan = '$id_tb_aktivasi' and jns_laporan = '3'";
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
						  <a href="laporan_aktivasi_add.php?id=<?=maxiline($rowx['id_tb_laporan'], 'e');?>&s=e&h=y" class="product-title"><?=$sqlz['nm_user']?>
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

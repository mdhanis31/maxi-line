<? 
$page= "verifikasi";
$pages= "pemutusan";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");

$kodeaman = $_SESSION['token'];

if($_GET['s'] == "d" or $_GET['s'] == "e") { 
$id_tb_pemutusan = maxiline(SafeSQL($_GET['id']), 'd');
$sql = "select * from tb_pemutusan where id_tb_pemutusan = '$id_tb_pemutusan'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$id_tb_pendaftaran = $row['id_tb_pendaftaran'];

$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$tglpemutusan = date_format($row['tgl_pemutusan'], 'd-m-Y');	
$jampemutusan = date_format($row['tgl_pemutusan'], 'H:i');

} else {
$id_tb_pendaftaran = maxiline(SafeSQL($_GET['id']), 'd');
$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$sql = "select top 1 * from tb_rencana where id_tb_pendaftaran = '$id_tb_pendaftaran' and rencana = '5' and st_rencana = '1' order by tgl_rencana asc";
$res = $db->query($sql);
$row = $db->fetchArray($res);
if(empty($row['tgl_rencana'])){
$tglsurvey = "";
$jamsurvey = "";
} else {
$tglpemutusan = date_format($row['tgl_rencana'], 'd-m-Y');	
$jampemutusan = date_format($row['tgl_rencana'], 'H:i');
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
        <li class="active">Pemutusan</li>
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
              <h3 class="box-title">Pemutusan / Penarikan Perangkat</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="pemutusan_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="token" value="<?=$kodeaman?>" />
			<input type="hidden" name="id_tb_pendaftaran" value="<?=$id_tb_pendaftaran;?>" />
			<input type="hidden" name="id_tb_rencana" value="<?=$row['id_tb_rencana'];?>" />			
			<? if($_GET['s']=='e' and $_GET['t']!='u') {?>
			<input type="hidden" name="f" value="e" />
			<input type="hidden" name="id_tb_pemutusan" value="<?=$id_tb_pemutusan;?>" />
			<?} else if($_GET['t']=='u') {?>
			<input type="hidden" name="f" value="e" />
			<input type="hidden" name="t" value="u" />
			<input type="hidden" name="id_tb_pemutusan" value="<?=$id_tb_pemutusan;?>" />
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
                  <input type="text" class="form-control" id="datepicker" name="tglpemutusan" value="<?=$tglpemutusan;?>" readonly>
                </div>
				<div class="bootstrap-timepicker">
				<div class="form-group">
                  <label for="exampleInputFile">Waktu</label>
                  <input type="text" class="form-control timepicker" name="jampemutusan" value="<?=$jampemutusan;?>" <?if($_GET['s'] == "d") {echo "disabled";} else {echo "required";}?>>
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
                 <select class="form-control select2" id="rencana" name="st_pemutusan" data-placeholder="pilih hasil pemutusan / perbaikan" style="width: 100%;" <?if($_GET['s'] == "d") {echo "disabled";} else {echo "required";}?>> 
				 <?if($_GET['s'] == "b") {?>
				 <option value="">--- Pilih Hasil ---</option>
				 <?}?>
				 <option value="1" <?if($row['st_pemutusan'] == 1) {echo "selected";}?> >Pending</option>
				 <option value="2" <?if($row['st_pemutusan'] == 2) {echo "selected";}?> >Selesai</option>
				 <option value="3" <?if($row['st_pemutusan'] == 3) {echo "selected";}?> >Gagal</option>
				</select>
                </div>
				<div class="form-group">
                  <label for="exampleInputtext">Keterangan/alasan</label>
				  <? if($_GET['s']=='d') {?>
                  <textarea type="textarea" class="form-control" id="keterangan" name="ket_pemutusan" disabled placeholder="-"><?=$row['ket_pemutusan'];?></textarea>
				  <?} else if($_GET['s']=='e') {?>
				  <textarea type="textarea" class="form-control" id="keterangan" name="ket_pemutusan" placeholder="Masukkan keterangan" required><?=$row['ket_pemutusan'];?></textarea>
				  <?} else {?> 
				  <textarea type="textarea" class="form-control" id="keterangan" name="ket_pemutusan" placeholder="Masukkan keterangan" required></textarea>
                  <?}?>
				</div>
    
				<div class="form-group">
				<label for="exampleInputFile"></label>
				<?
				if($_GET['s']=='d') {} else {?>
				  <input type="checkbox" name="kirimnotif" value="1" style="margin-left: 0px;"><span> Kirim notifikasi ke pelanggan</span>
				<?}?>
				</div>
	
              </div>
              <!-- /.box-body -->
			 
			 <div class="box-footer">
				<?
				if($_GET['s']=='b') {
					if(in_array($_SESSION['id_tb_user'], $id_usere) or $_SESSION['level_user'] == '1') {?>
					<button type="submit" id="olaole" class="btn btn-primary">Submit</button>				  
					<?}
				} else if($_GET['s']=='d' and $_GET['t']=='u') {
					if($row['sts_pemutusan'] == 1){?>
					<a href="pemutusan_add.php?id=<?=maxiline($id_tb_pemutusan, 'e');?>&s=e&t=u" id="olaole" class="btn btn-primary">Revisi</a>
					<?}
				} if($_GET['s']=='d') {
					if($row['sts_pemutusan'] == 1){?>
					<a href="pemutusan_add.php?id=<?=maxiline($id_tb_pemutusan, 'e');?>&s=e" id="olaole" class="btn btn-primary">Revisi</a> 	
					<?}
				} else if($_GET['s']=='e') {
					if($row['sts_pemutusan'] == 1){
						if(in_array($_SESSION['id_tb_user'], $id_usere) or $_SESSION['level_user'] == '1') {?>
						<button type="submit" id="olaole" class="btn btn-primary">Submit</button>				  
						<?}
					}	
				}
				 
				if($_GET['t']=='u') {?>
				<button type="reset" class="btn btn-default" style="margin-left:5px;" onclick="window.location.href='pemutusan_v.php'">Cancel</button>
				<?} else {?>
				<button type="reset" class="btn btn-default" style="margin-left:5px;" onclick="window.location.href='pendaftaran_dtl.php?id=<?=maxiline($id_tb_pendaftaran, 'e');?>'">Cancel</button>
				<?}?>
			 </div>
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
				<?php if ($row['sts_pemutusan'] != 2) {?>
						<a href="laporan_pemutusan_add.php?id=<?=maxiline($id_tb_pemutusan, 'e');?>&s=b&token=<?echo $kodeaman;?>" class="btn btn-sm btn-primary" style="margin-top:1px;"> 
						<i class="fa fa-plus-square"><span class="tombole"> Input Laporan</span> </i>
						</a>
				<?php } else {}?>				
			</div>
            </div>
			<?
			$sqlx = "select * from tb_laporan where id_jns_laporan = '$id_tb_pemutusan' and jns_laporan = '5'";
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
						  <a href="laporan_pemutusan_add.php?id=<?=maxiline($rowx['id_tb_laporan'], 'e');?>&s=e&t=u" class="product-title"><?=$sqlz['nm_user']?>
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

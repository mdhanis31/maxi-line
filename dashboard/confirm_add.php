<? 
$page= "invoice";
$pages= "confirm";


include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$wektuiki = $now->format("Y-m-d H:m:s");
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");

$kodeaman = $_SESSION['token'];


$id_tr_confirm = maxiline(SafeSQL($_GET['id']), 'd');
$sql = "select * from tr_confirm where id_tr_confirm = '$id_tr_confirm'";
$res = $db->query($sql);
$row = $db->fetchArray($res);

if($_GET['id'] and empty($row['id_tr_confirm'])) {
?>
<script>alert('Data tidak ditemukan!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit(); 
}

if($_GET['id']) { 
$tglconfirm = date_format($row['tgl_transfer'], 'd-m-Y');	
} else {
$tglconfirm = date_format($now, 'd-m-Y');
}

$sqlb = $db->fetchArray($db->query("select DISTINCT(id_tb_pendaftaran) from tb_pendaftaran where no_invoice = '$row[kode_invoice]'"));

if(in_array($_SESSION['level_user'], 5) and $_SESSION['id_tb_pendaftaran'] != $sqlb['id_tb_pendaftaran']) {
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
	 <? if($_GET['id']) {echo "Detail Konfirmasi Pembayaran";} else {echo "Tambah Konfirmasi Pembayaran";}?>
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Konfirmasi Pembayaran</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col --> 
		<section class="col-lg-10">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="confirm_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="token" value="<?=$kodeaman?>" />		
			<input type="hidden" name="j" value="a" />			
			
              <div class="box-body">
               <div class="form-group">
                  <label for="exampleInputtext">Nama Pemilik Rekening</label>
				  <input type="text" class="form-control" id="nama" value="<?=$row['nama'];?>" <?if($_GET['id']) {echo "readonly";} else {echo "required";}?> placeholder="Nama">
                </div>
				
				<div class="form-group">
                  <label for="exampleInputtext">Kode Invoice</label>
				   <?if($_GET['id']) {?>
				   <input type="text" class="form-control" name="kode_invoice" value="<?=$row['kode_invoice'];?>" readonly placeholder="Kode invoice"><?} else {?>
					<select class="form-control" id="kdinvo"  name="kode_invoice" required placeholder="Kode invoice">
					<option value="">--pilih invoice--</option>
					<?
					$sqq = "select * from tr_invoice where id_tb_pendaftaran = '$_SESSION[id_tb_pendaftaran]' and sts_lunas != '2' and sts_invoice = '2'";
					$req = $db->query($sqq);
					while($roq = $db->fetchArray($req)){
					?>
					<option value="<?=$roq['no_invoice'];?>" ida="<?=$roq['tot_tagih'];?>" ><?=$roq['no_invoice'];?></option>
					<?}?>
					</select>
				   <?}?>
                </div>
                <div class="form-group">
                  <label for="exampleInputtext">Tanggal Pembayaran</label>
                  <input type="text" class="form-control datepicker" placeholder="Tanggal Pembayaran" name="tgl_transfer" <?if($_GET['id']) {echo "readonly";} else {echo "required";}?> value="<?=$tglconfirm;?>">				 
                </div>
				<div class="form-group">
                <label for="exampleInputtext">Bank</label>
			    <input type="text" class="form-control datepicker" placeholder="Bank" name="asal_bank" <?if($_GET['id']) {echo "readonly";} else {echo "required";}?> value="<?=$row['asal_bank'];?>">					  
				</div>
				<div class="form-group">
                  <label for="exampleInputFile">No. Rekening</label>
                   <input type="text" class="form-control" placeholder="No. Rekening" name="asal_norek" <?if($_GET['id']) {echo "readonly";} else {echo "required";}?> value="<?=$row['asal_norek'];?>">
                </div>
			
				<div class="form-group">
                  <label for="exampleInputFile">Nominal (Rp.)</label>
                   <input type="number" id="nomina" class="form-control" placeholder="Nominal" name="jml_transfer" readonly required value="<?=$row['jml_transfer'];?>"> 
                </div>	
				
				
				<div class="form-group">
				<label for="exampleInputFile">Keterangan Transfer</label>
				 <textarea class="form-control" placeholder="Keterangan" name="ket_transfer" required <?if($_GET['id']) {echo "readonly";} else {echo "required";}?>><?=$row['ket_transfer'];?></textarea>
				</div>
				
				<div class="form-group">
                 <label for="exampleInputFile">Status</label>
                 <input type="text" class="form-control" placeholder="Status"  value="<?if($row['st_confirm'] == 1) {echo "Menunggu konfirmasi";} else if($row['st_confirm'] == 2) {echo "Diterima";} else if($row['st_confirm'] == 3) {echo "Ditolak";}?>" readonly> 
                </div>

				<?if($row['st_confirm'] == 3) {?>
				<div class="form-group">
                 <label for="exampleInputFile">Alasan Penolakan</label>
                 <input type="text" class="form-control" placeholder="Status"  value="<?=$row['alasan_tolak'];?>" readonly> 
                </div>				
				<?}?> 
				
				<div class="form-group has-feedback">
				  <label for="exampleInputFile">Upload bukti transfer</label>
				  <input type="file" id="exampleInputFile" name="file_confirm" required>
				  <p class="help-block">File format jpg, jpeg, png</p>
			   </div>
			   
              </div>
              <!-- /.box-body -->
			   <div class="box-footer">	
			  <?
			  if(!$_GET['id']) {?>             	
				 <button type="submit" class="btn btn-primary">Submit</button>	 
				 <?}?>
				
				<button type="reset" class="btn btn-default" style="margin-left:5px;"  onclick="history.back()">Cancel</button>
				
			  </div>
            </form>
          </div>
          <!-- /.box -->      
		 </section>	
		 
		<? 
		$sqlc = $db->fetchArray($db->query("select * from tb_file_confirm where id_tr_confirm = '$row[id_tr_confirm]'"));				  

		if(!empty($sqlc['link_gbr'])){		
					 
		$permissible_extension = array("pdf", "PDF");
		$ext = pathinfo($sqlc['link_gbr'], PATHINFO_EXTENSION);

		if (!in_array($ext, $permissible_extension)) {
		$filenye = "dist/file_confirm/".$sqlc['link_gbr'];	  
		} else {
		$filenye = "dist/img/pdf_icon.png";	  
		}
			
		?>
		<div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
			<label for="exampleInputFile">Bukti Pembayaran</label>			
			<a href="./dist/file_confirm/<?=$sqlc['link_gbr'];?>" class="" target="_BLANK">
			<img style="max-width: 200px;" src="<?=$filenye?>" alt="User Image" >
			</a>
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
<script>
$('#kdinvo').on('change', function (e) {
	var nominal = $('option:selected', this).attr('ida');
	//alert('nilai '+ nominal);
	
    $("#nomina").val(nominal);
});
</script>
</body>
</html>

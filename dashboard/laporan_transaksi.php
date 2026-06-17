<? 
$page= "laptransaksi";
include ("head.php"); 
include ("nav.php"); 

$kodeaman = $_SESSION['token'];

$now = new DateTime();
$besok = new DateTime('tomorrow');
$tgl1 = $now->format("d-m-Y");
$tgl2 = $besok->format("d-m-Y");
$blnsaiki = $now->format("m-Y");
$wektuiki = date("Y-m-d", strtotime($tgl1));


if ($_POST['j'] == 'a') { 

$bulan = SafeSQL($_POST['bulan']);
$tahun = SafeSQL($_POST['tahun']);
$st_waktu = SafeSQL($_POST['st_waktu']);


if ($_POST['andi'] == 'ganteng') {
$date1 = date("Y-m-d", strtotime($_POST['date1']));
} else if ($_POST['andi'] == 'cakep') {
$blth   = "01-".$_POST['date1'];
$date1 = date("Y-m-d", strtotime($blth));
} else if ($_POST['andi'] == 'bagus') {
$newth = str_replace(" ","",$_POST['date1']);	
$tglblth   = "01-01-".$newth;
$date1 = date("Y-m-d", strtotime($tglblth));
}

if ($_POST['andi'] == 'ganteng') {$date2 = date("Y-m-d", strtotime($_POST['date2']))." 23:59:59";}
else if ($_POST['andi'] == 'cakep') {
$dat2 = date("Y-m-d", strtotime("+$bulan months", strtotime($date1)));
$date2 = date("Y-m-d", strtotime("-1 days", strtotime($dat2)))." 23:59:59";
} else if ($_POST['andi'] == 'bagus') {
$thnya = $_POST['date1'] + $tahun - 1;	
$thnyalkp = "31-12-".$thnya." 23:59:59";
$date2 = date("Y-m-d", strtotime($thnyalkp));
}


} else {
$date1 = date('Y-m-01'); // hard-coded '01' for first day
$date2  = date('Y-m-t')." 23:59:59";
}

$time1 = date("Y-m-d", strtotime($date1));
$time3 = date("m-Y", strtotime($date1));	
$time2 = date("Y-m-d", strtotime($date2));	
$time4 = date("Y", strtotime($date1));

$sqq = $db->fetchArray($db->query("SELECT count(id_tr_invoice) as jml FROM tr_invoice where (tgl_invoice >= '$date1' and tgl_invoice < '$date2' and sts_lunas = '2')"));
$sqw = $db->fetchArray($db->query("SELECT sum(tot_tagih) as income FROM tr_invoice where (tgl_invoice >= '$date1' and tgl_invoice < '$date2' and sts_lunas = '2')"));

//print_r($_POST)."<br>";
//echo $date1."<br>";
//echo $date2."<br>";
//print_r($_POST)."<br>";
//exit();


if($_SESSION['level_user'] == 5 ) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}
?>
	<link rel="stylesheet" href="plugins/select2/select2.css">
	 <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	 <!-- Bootstrap time Picker -->
	<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
	<style>
	.dataTables_wrapper .dataTables_paginate .paginate_button {
		padding: 0!important;
		border: none !important;
	}

	div.dt-buttons {
		margin-left: 350px;
	}

	.dropdown-menu {
		min-width: 120px;
	}

	div.dataTables_wrapper div.dataTables_paginate {   
		padding-top: 30px;
	}

	div.dataTables_wrapper div.dataTables_info {
		padding-top: 30px;
	}
	</style> 
	
	<link rel="stylesheet" href="resources/dataTables.searchHighlight.css" type="text/css"/> 

	<script type="text/javascript" language="javascript" src="resources/jquery-1.12.4.js">
	</script>

	<script type="text/javascript" language="javascript" src="resources/jquery.dataTables.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/dataTables.searchHighlight.min.js">	
	</script>
	<script type="text/javascript" language="javascript" src="resources/jquery.highlight.js">	
	</script>

	<script type="text/javascript" class="init">
	$(document).ready(function() {
		$.fn.DataTable.ext.pager.numbers_length = 6;
		var dataTable = $('#tablelaporan').DataTable( {
						"processing": true,
						"serverSide": true,
						"ajax":{
							url :"json_transaksi_lap.php", //
							type: "post",  // method  , by default get
							data: {
							"date1": '<?=$date1;?>',
							"date2": '<?=$date2;?>'
							}						
						},
			"columns": [ { "data": 0 },
						{ "data": 1 },
						{ "data": 2 },
						{ "data": 3 },
						{ "data": 4 },
						{ "data": 5 },
						{ "data": 6,					
						  "render" :  $.fn.dataTable.render.number( '.', ',', 2, 'Rp. ' ),
						},
						{ "data": 7 }
						],
			"order": [[ 7, "desc" ]],
			"lengthMenu": [[30, 60, 90], [30, 60, 90]],
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": [0,7]
			},
		
			{
					"targets": [ 7 ],
					"visible": false
			}
			 
			],
	//		dom: 'Blfrtip',		
			searchHighlight: true,
		   
		});	
		
		 dataTable.on('draw.dt', function () {
		var info = dataTable.page.info();
		dataTable.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1 + info.start;
			});
		});
		
		$('#tableindex_filter input').unbind();
	   $('#tableindex_filter input').bind('keyup', function(e) {
		   if(e.keyCode == 13) {
			dataTable.search(this.value).draw();  
		}
	   });
		
	});
	</script>     
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Laporan Transaksi
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">UI</a></li>
        <li class="active">General</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">       
      <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="<?if($_POST['st_waktu'] == 0) {echo "active";} else if($_POST['st_waktu'] == 1) {echo "active";};?>"><a href="#harian" data-toggle="tab">Hari</a></li>
              <li><a class="<?if($_POST['st_waktu'] == 2) {echo "active";};?>" href="#bulanan" data-toggle="tab">Bulan</a></li>
              <li><a class="nav-link <?if($_POST['st_waktu'] == 3) {echo "active";};?>" href="#tahunan" data-toggle="tab">Tahun</a></li>              
            </ul>
            <div class="tab-content">
              <div class="tab-pane <?if($_POST['st_waktu'] == 0) {echo "active";} else if($_POST['st_waktu'] == 1) {echo "active";};?>" id="harian">
				<div class="box-body border-radius-none">
					<div class="box-body col-md-6">
						<form action="laporan_transaksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
						<input type="hidden" name="j" value="a" />
						<input type="hidden" name="st_waktu" value="1" />
						<input type="hidden" name="id_tb_customer" value="<?=$id_tb_customer;?>" />								 
						 
						<fieldset>	
							<div class="form-group">
							 <label>Tanggal awal</label>                                         
								<input type="text" class="form-control datepicker home mama" id="dpd1" name="date1" data-date-format="dd-mm-yyyy" value="<?if ($_POST['j'] == 'a') {if ($_POST['st_waktu'] == '2') {echo date("d-m-Y", strtotime($date1));} else {echo "$_POST[date1]";};} else {echo "$tgl1";}?>"  placeholder="Masukkan tanggal awal" required>                                          
							</div>
										
							 <div class="form-group">
							  <label>Tanggal akhir</label>                                         
								<input type="text" class="form-control datepicker home mama" id="dpd2" name="date2" data-date-format="dd-mm-yyyy" value="<?if ($_POST['j'] == 'a') {echo "$_POST[date2]";} else {echo "$tgl2";}?>"  placeholder="Masukkan tanggal akhir" required>
							</div>										
																	
							<div class="form-actions">											
							  <button type="submit" name="andi" value="ganteng" class="btn btn-large btn-block btn-danger">Check</button> 
							</div>
						 </fieldset>		
						</form>
					</div>
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane <?if($_POST['st_waktu'] == 2) {echo "active";};?>" id="bulanan">
				<div class="box-body border-radius-none">
					<div class="card-body col-md-6">
						<form action="laporan_transaksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
						<input type="hidden" name="j" value="a" />
						<input type="hidden" name="st_waktu" value="2" />
						<input type="hidden" name="id_tb_customer" value="<?=$id_tb_customer;?>" />
						 
						<fieldset>								     
							<div class="form-group">
							  <label>Bulan awal</label>                                         
								<input type="text" class="form-control datepicker home papa" id="date01" name="date1" data-date-format="mm-yyyy" data-date-viewmode="months" data-date-minviewmode="months" value="<?if ($_POST['j'] == 'a') {echo "$time3";} else {echo "$blnsaiki";}?>" placeholder="Masukkan tanggal awal" required>                                          
							</div>
							<div class="form-group">
							  <label>Jumlah Bulan</label>                                         
							   <select class="form-control guest-input" name="bulan" required>
									<option value="">-- Pilih --</option>
									<option <?if($_POST['bulan']==1) {echo "selected";}?> value="1">1</option>
									<option <?if($_POST['bulan']==2) {echo "selected";}?> value="2">2</option>
									<option <?if($_POST['bulan']==3) {echo "selected";}?> value="3">3</option>
									<option <?if($_POST['bulan']==4) {echo "selected";}?> value="4">4</option>
									<option <?if($_POST['bulan']==5) {echo "selected";}?> value="5">5</option>
									<option <?if($_POST['bulan']==6) {echo "selected";}?> value="6">6</option>
									<option <?if($_POST['bulan']==7) {echo "selected";}?> value="7">7</option>
									<option <?if($_POST['bulan']==8) {echo "selected";}?> value="8">8</option>
									<option <?if($_POST['bulan']==9) {echo "selected";}?> value="9">9</option>
									<option <?if($_POST['bulan']==10) {echo "selected";}?> value="10">10</option>
									<option <?if($_POST['bulan']==11) {echo "selected";}?> value="11">11</option>
									<option <?if($_POST['bulan']==11) {echo "selected";}?> value="12">12</option>
							   </select>
							</div>										
																
							<div class="form-actions">										
							  <button type="submit" name="andi" value="cakep" class="btn btn-large btn-block btn-danger">Check</button> 
							</div>							
						</fieldset>
						</form>
					</div>					
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane <?if($_POST['st_waktu'] == 3) {echo "active";};?>" id="tahunan">
				<div class="box-body border-radius-none">
					<div class="card-body col-md-6">
						<form action="laporan_transaksi.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
						<input type="hidden" name="j" value="a" />
						<input type="hidden" name="st_waktu" value="3" />
						<input type="hidden" name="id_tb_customer" value="<?=$id_tb_customer;?>" />
						 
							<fieldset>								     
								<div class="form-group">
								  <label>Tahun awal</label>                                         
									<input type="text" class="form-control datepicker home mama" id="date02" name="date1" data-date-format="yyyy"  data-date-viewmode="years" data-date-minviewmode="years" value="<?if ($_POST['j'] == 'a') {echo "$time4";} else { echo $now->format("Y");}?>" required>                                          
								 </div>
							
								<div class="form-group">
								 <label>Jumlah Tahun</label>                                         
									<input type="text" class="form-control guest-input" id="typeahead" name="tahun" placeholder="Masukkan jumlah tahun" required value="<?if ($_POST['j'] == 'a') {echo "$_POST[tahun]";}?>"> 
								</div>
								<div class="control-group aksi">										
								<div class="form-actions">										
								  <button type="submit" name="andi" value="bagus" class="btn btn-large btn-block btn-danger">Check</button>  
								</div>
								</div>
							</fieldset>
						</form>									
					</div>
				</div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- END CUSTOM TABS -->
  
      <div class="row">
         <section class="col-lg-12">
          <!-- solid sales graph -->
          <div class="box box-primary">
            <div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Transaksi <?=tglindo($time1);?> s/d <?=tglindo($time2);?></h3>
					<div class="card-body">
						
						<div class="box-tools">	
							<br>
							<div class="btn-group">
							<button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<span style="margin-right:3px;"> <i class="fa fa-print"></i> Cetak</span>
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
							</button>
								<ul class="dropdown-menu dropdown-menu-left" role="menu">		  
									<li><a class="dropdown-item" target="_blank" href="transaksi_pdf.php?d1=<?=$date1;?>&d2=<?=$date2;?>&token=<?echo $kodeaman;?>">pdf</a></li>
									<li><a class="dropdown-item" target="_blank" href="transaksi_excel.php?d1=<?=$date1;?>&d2=<?=$date2;?>&token=<?echo $kodeaman;?>">excel</a></li>
								</ul>
							</div>
							<br>
						</div>
						<br>
					 <div class="table-responsive">
					  <table id="example2" class="table table-bordered table-striped no-footer" width="100%">
						<thead>
						<tr>
						   <th width="25%">Jumlah Invoice</th>
						   <th><?				  
						   echo "$sqq[jml]";
						   ?></th>
					   </tr>
						<tr>
						   <th>Income</th>
						   <th><?				  
						   echo "Rp. ".number_format($sqw['income'],0,',','.').",00";
						   ?>
						   </th>
					   </tr>
						</thead>                             
					  </table>
					 </div>
					 <div class="table-responsive">
					  <table id="tablelaporan" class="table table-bordered table-striped no-footer">
						<thead>
						<tr>
						  <th>#</th>
						  <th>Nama Pelanggan</th>				  			  
						  <th>Invoice</th>                  
						  <th>Tanggal</th>
						  <th>Dibayar</th>
						  <th>Layanan</th>	
						  <th>Tagihan</th>
						  <th>waktu</th>
						</tr>
						</thead>
							
					  </table>
					 </div>
					</div>
            
            </div>
		  </div>
		 </section>
      </div>

      </div>
      <!-- /.row -->
      <!-- END PROGRESS BARS -->

    </section>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->
   <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; <?=$th;?> Maxi-Line.</strong> All rights
    reserved.
  </footer>
  <!-- End #footer -->
  
</div>
<!-- ./wrapper -->
				
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap datepicker css -->
<link href="bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" media="screen">
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->


<script>
$(function () {
 $('#dpd1').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
    }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#dpd2').datepicker('setStartDate', minDate);
	$('#dpd2').datepicker('clearDates');
});

	$('#dpd2').datepicker({
	  autoclose: true,
	  format: 'dd-mm-yyyy'
	});	
	
	$('#date01').datepicker({
	  autoclose: true,
	  viewMode: 'months', 
	  minViewMode: 'months',
	  format: 'mm-yyyy'
	});	
	
	$('#date02').datepicker({
	  autoclose: true,
	  viewMode: 'years', 
	  minViewMode: 'years',
	  format: 'yyyy'
	});
	
});
</script>
</body>
</html>
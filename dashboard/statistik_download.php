<? 
$page= "download";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");

if (!isset($_SESSION['username'])) {
?>
<script>
alert('Anda harus login terlebih dahulu!');
document.location.href="login.php"</script>
<?php 
exit();
} 

$color = array("#ccff33", "#ffcc00", "#ff9933","#ff33cc","#0099ff","#00ffcc","#00cc00","#0000cc","#6b616b","#a8a36b","#c5a6ff","#ccff33", "#ffcc00");
$bulan = array (1 =>   'Januari', 'Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
foreach($_GET as $kuncinya => $nilainya)
{
    $filter[$kuncinya] = $db->escaped(stripslashes(strip_tags(htmlspecialchars($nilainya, ENT_QUOTES))));    
}

if($_GET['id']) {
$idd = $filter['id'];
$ide = dirpk( $idd, 'd' );	
} else {
$ide = "2020";	
}	

?>
	
	<link rel="stylesheet" href="resources/dataTables.searchHighlight.css" type="text/css"/> 
	
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
	</script>
<script type="text/javascript" class="init">
$(document).ready(function() {
	$.fn.DataTable.ext.pager.numbers_length = 6;
	var dataTable = $('#usaha').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :"json_datatable.php", // json datasource
						type: "post",  // method  , by default get
						data: {
						"ide": "<?=$ide;?>"
						}						
					},
    	 "order": [[ 1, "desc" ]],
		"lengthMenu": [[10, 20, 30], [10, 20, 30]],
		
//		dom: 'Blfrtip',		
		searchHighlight: true,
		dom: 'lBfrtip',
	    buttons: [
		'excel', 'csv', 'pdf'
	   ],       
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
</script> 
<style>
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0!important;
    border: none !important;
}

div.dt-buttons {
    margin-left: 350px;
}
</style> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	  Download
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Download</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
			<?
			$sql = "SELECT YEAR(tanggal_tanda_tangan) AS tahun, COUNT(DISTINCT nomor_izin_usaha) AS jml FROM v_portal_stats_pusat_siup_aktif where YEAR(tanggal_tanda_tangan) = '$th' ORDER BY jml DESC";
			$res = $db->query($sql);
			$row = $db->fetchArray($res);
			?>
              <h3><?=$row['jml']?></h3>

              <p>SIUP Terbit <?=$th;?></p>
            </div>
            <div class="icon">
              <i class="ion ion-document"></i>
            </div>
            <a href="#usaha" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
            <?
			$sql1 = "SELECT COUNT(nama_pemilik) as jml1 FROM (SELECT * FROM v_portal_stats_pusat_siup_aktif GROUP BY nama_pemilik)a";
			$res1 = $db->query($sql1);
			$row1 = $db->fetchArray($res1);
			?>
              <h3><?=$row1['jml1']?></h3>

              <p>Pemilik SIUP</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#pemilikperprovinsi" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
            <?
			$sql2 = "SELECT COUNT(nomor_izin_usaha) as jml2 FROM v_portal_stats_pusat_siup_aktif";
			$res2 = $db->query($sql2);
			$row2 = $db->fetchArray($res2);
			?>
              <h3><?=$row2['jml2']?></h3>
              <p>Jumlah Ijin Usaha</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#ijinusahaperprovinsi" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
            <?
			$sql3 = "SELECT COUNT(DISTINCT provinsi_ok) AS jml3 FROM v_portal_stats_pusat_siup_aktif ORDER BY jml3 DESC ";
			$res3 = $db->query($sql3);
			$row3 = $db->fetchArray($res3);
			?>
              <h3><?=$row3['jml3']?></h3>
              <p>Propinsi Sebaran SIUP </p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#siupperprovinsi" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
			<?
			$sql = "SELECT YEAR(tanggal_tanda_tangan) AS tahun, COUNT(DISTINCT nomor_izin_usaha) AS jml FROM v_portal_stats_pusat_siup_aktif where YEAR(tanggal_tanda_tangan) = '$th' ORDER BY jml DESC";
			$res = $db->query($sql);
			$row = $db->fetchArray($res);
			?>
              <h3><?=$row['jml']?></h3>

              <p>SIUP Terbit <?=$th;?></p>
            </div>
            <div class="icon">
              <i class="ion ion-document"></i>
            </div>
            <a href="#c_usaha_aktif_jenisa" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
            <?
			$sql1 = "SELECT COUNT(nama_pemilik) as jml1 FROM (SELECT * FROM v_portal_stats_pusat_siup_aktif GROUP BY nama_pemilik)a";
			$res1 = $db->query($sql1);
			$row1 = $db->fetchArray($res1);
			?>
              <h3><?=$row1['jml1']?></h3>

              <p>Pemilik SIUP</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#pemilikperprovinsi" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
            <?
			$sql2 = "SELECT COUNT(nomor_izin_usaha) as jml2 FROM v_portal_stats_pusat_siup_aktif";
			$res2 = $db->query($sql2);
			$row2 = $db->fetchArray($res2);
			?>
              <h3><?=$row2['jml2']?></h3>
              <p>Jumlah Ijin Usaha</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#ijinusahaperprovinsi" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
            <?
			$sql3 = "SELECT COUNT(DISTINCT provinsi_ok) AS jml3 FROM v_portal_stats_pusat_siup_aktif ORDER BY jml3 DESC ";
			$res3 = $db->query($sql3);
			$row3 = $db->fetchArray($res3);
			?>
              <h3><?=$row3['jml3']?></h3>
              <p>Propinsi Sebaran SIUP </p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#siupperprovinsi" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->     
		  <section class="col-lg-12 connectedSortable">
          <!-- solid sales graph -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title">SIUP Aktif Per Provinsi <?=$th;?></h3>

             
            </div>
            <div class="box-body border-radius-none">
              <div class="box-body">
              <table id="usaha" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Provinsi</th>
                  <th>Jumlah</th>
                </tr>
                </thead>                         
              </table>
            </div>
            </div>
            <!-- /.box-body -->
        
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2020 Direktorat Perizinan dan Kenelayanan.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- DataTables -->
<script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!--<script>
  $(function () {
    $('#example2').DataTable()
    $('#example1').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false
    })
  })
</script> -->
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
</body>
</html>

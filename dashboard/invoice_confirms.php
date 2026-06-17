<? 
$page= "confirm";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

$status = array(8,9);
$ids = join("','",$status);   
$sql = "select 
DISTINCT(id_tr_confirm) as idconfirm, * from (
SELECT trc.*, tri.id_tb_pendaftaran FROM tr_confirm trc left join tr_invoice tri on trc.kode_invoice = tri.no_invoice 
)a where id_tb_pendaftaran = '$_SESSION[id_tb_pendaftaran]'
";
$res = $db->query($sql);

?>
	
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
 <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.css">
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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	  List Konfirmasi Pembayaran
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
		  <section class="col-lg-12">
          <!-- solid sales graph -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title"></h3>
				<div class="box-tools">
					<a href="confirm_add.php" class="btn btn-sm btn-primary">
					<i class="fa fa-plus"> </i> Konfirmasi Pembayaran
					</a>         
				</div>
            
            </div> 
            <div class="box-body border-radius-none">
              <div class="box-body">
			   <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                   <th></th>
				  <th>Nama</th>
			<!--	  <th>Kode Pelanggan</th> -->
				  <th>Kode Invoice</th>
				  <th>Tanggal Pembayaran</th>
				  <th>Asal Bank</th>
				  <th>No. Rek</th>
				  <th>Jumlah</th>
				  <th>Status</th>
				  <th>Keterangan</th>
				  <th></th>
                </tr>
                </thead> 
				<?
				
				while($row = $db->fetchArray($res)) {				
				$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$row[id_tb_pendaftaran]'"));
				
								
				$n++;?>	
				 <tr>
                  <td><?=$n;?></td>
                  <td><?=$row['nama'];?></td>
			<!--	  <td><?=$sqlb['kode_daftar'];?></td> -->
				  <td><?=$row['kode_invoice'];?></td>
				  <td><?=tglindo(date_format($row['tgl_transfer'], 'Y-m-d'));?> <?=date_format($row['tgl_transfer'], 'H:i');?></td>				  
				  <td><?=$row['asal_bank'];?></td>
				  <td><?=$row['asal_norek'];?></td>				 
				  <td>Rp. <?=number_format($row['jml_transfer'],0,',','.').",00";?></td>
				  <td><?=$st_confirm[$row['st_confirm']];?></td>
				  <td><?=$row['ket_transfer'];?></td>	
				  <td>
				   <div class="btn-group">
				   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
				 <ul class="dropdown-menu dropdown-menu-right" role="menu">
				 <li><a href="confirm_add.php?id=<?=maxiline($row['id_tr_confirm'], 'e');?>&e=y">Detail</a></li>									
				</ul>
				</div>
				  </td>
                </tr>
				<?}?>
              </table>			 
			  </div>
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
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; <?=$th;?> Maxi-Line.</strong> All rights
    reserved.
  </footer><!-- End #footer -->

</div>
<!-- ./wrapper -->

			
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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
<script>
  $(function () {
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })
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
</body>
</html>

<? 
$page= "invoice";
$pages= "pending";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

$status = array(8,9);
$ids = join("','",$status);   

if($_SESSION['level_user'] == 5) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}
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

/* spinner */

#spinner {
	display: none; 
/*	display: block; */
	top: 10%;
    left: 50%;
	z-index: 100;
    position: absolute;
}

.loading {
	border: 20px solid #ccc;
    width: 17%;
    height: 22%;
    border-radius: 50%;
	border-top-color: #1ecd97;
	border-left-color: #1ecd97;
	animation: spin 1s infinite ease-in;	
}

@keyframes spin {
	0% {
		transform: rotate(0deg);
	}
	100% {
		transform: rotate(360deg);
	}
}

#backfull {
	display: none;
	width: 100%;
    height: 100%;
    background-color: white;
	z-index: 99;
    position: absolute;
	opacity: 0.8;
}

/* spinner */
</style>
	<div id="backfull">
	<div id="spinner" class="loading">
	</div>
	</div>
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	  List Invoice Belum Dibayar
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Invoice</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->    
		<section class="col-lg-12">
		 <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#lembur" data-toggle="tab" aria-expanded="true">Layanan Aktif</a></li>
    <!--          <li><a href="#makan" data-toggle="tab">Uang Makan</a></li> -->
              <li class=""><a href="#dinas" data-toggle="tab" aria-expanded="false">Layanan Selesai</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="lembur">
                <!-- Post -->
				<div class="box-header">										
				</div>
            <div class="box-body border-radius-none">
              <div class="box-body">
                <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th>
				  <th>Nama Pelanggan</th>
				  <th>Kode Invoice</th>
				  <th>Tanggal</th>
				  <th>Jenis Invoice</th>
				  <th>Status</th>
				  <th>Tagihan</th>
				  <th></th>
                </tr>
                </thead> 
				<?
				$sql = "SELECT * FROM tb_pendaftaran WHERE st_layanan = '10' or st_layanan = '8'";
				$res = $db->query($sql);
				while($row = $db->fetchArray($res)) {
					$sql1 = "SELECT * FROM tr_invoice WHERE sts_invoice = '2' and sts_lunas = '3' and id_tb_pendaftaran = '$row[id_tb_pendaftaran]' order by tgl_invoice desc";
					$res1 = $db->query($sql1);
					while($row1 = $db->fetchArray($res1)) {					
									
					$n++;?>	
					 <tr>
					  <td><?=$n;?></td>
					  <td><?=$row['nama'];?></td>
					  <td><?=$row1['no_invoice'];?></td>
					  <td><?=tglindo(date_format($row1['tgl_invoice'], 'Y-m-d'));?></td>				  
					  <td><?=$jns_invoice[$row1['jns_invoice']];?></td>
					  <td><?=$sts_lunas[$row1['sts_lunas']];?></td>
					  <td>Rp. <?=number_format($row1['tot_tagih'],0,',','.').",00";?></td>
					  <td>
					   <div class="btn-group">
					   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span style="margin-right:3px;">Aksi </span>
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu dropdown-menu-right" role="menu">
					 <li>
					 <?
					 if($_SESSION['level_user'] == 4 or $_SESSION['level_user'] == 2){
					 ?>
					 <a href="invoice_admin.php?id=<?=maxiline($row1['id_tb_pendaftaran'], 'e');?>&i=<?=maxiline($row1['id_tr_invoice'], 'e');?>&j=1">Lihat Invoice</a>
					 <?} else {?>
					 <a href="invoice_add.php?id=<?=maxiline($row1['id_tb_pendaftaran'], 'e');?>&i=<?=maxiline($row1['id_tr_invoice'], 'e');?>&j=1">Lihat Invoice</a>
					 <?}?>
					 </li>
					 <li><a id="confirm" href="invoice_.php?id=<?=maxiline($row1['id_tr_invoice'], 'e');?>&l=y&st=<?=$row1['jns_invoice'];?>" onclick="return confirmnya()">Confirm dibayar</a></li>
					</ul>
					</div>
					  </td>
					</tr>
					<?}
				}?>
              </table>
			  </div>
			</div>
           </div>
            <!-- /.card-body -->
          </div>         
          <!-- /.post -->  

		  <div class="tab-pane" id="dinas">
					  <!-- Post -->
			<div class="box-header">									
			</div>
            <div class="box-body border-radius-none">
              <div class="box-body">
              <div class="table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th>
				  <th>Nama Pelanggan</th>
				  <th>Kode Invoice</th>
				  <th>Tanggal</th>
				  <th>Jenis Invoice</th>
				  <th>Status</th>
				  <th>Tagihan</th>
				  <th></th>
                </tr>
                </thead> 
				<?
				$sql2 = "SELECT * FROM tb_pendaftaran WHERE st_layanan = '9'";
				$res2 = $db->query($sql2);
				while($row2 = $db->fetchArray($res2)) {
					
					$sql3 = "SELECT * FROM tr_invoice WHERE sts_invoice = '2' and sts_lunas = '3' and id_tb_pendaftaran = '$row2[id_tb_pendaftaran]' order by tgl_invoice desc";
					$res3 = $db->query($sql3);
					while($row3 = $db->fetchArray($res3)) {
									
					$n1++;?>	
					 <tr>
					  <td><?=$n1;?></td>
					  <td><?=$row2['nama'];?></td>
					  <td><?=$row3['no_invoice'];?></td>
					  <td><?=tglindo(date_format($row3['tgl_invoice'], 'Y-m-d'));?></td>				  
					  <td><?=$jns_invoice[$row3['jns_invoice']];?></td>
					  <td><?=$sts_lunas[$row3['sts_lunas']];?></td>
					  <td>Rp. <?=number_format($row3['tot_tagih'],0,',','.').",00";?></td>
					  <td>
					   <div class="btn-group">
					   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span style="margin-right:3px;">Aksi </span>
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu dropdown-menu-right" role="menu">
					 <li>
					 <?
					 if($_SESSION['level_user'] == 4 or $_SESSION['level_user'] == 2){
					 ?>
					 <a href="invoice_admin.php?id=<?=maxiline($row3['id_tb_pendaftaran'], 'e');?>&i=<?=maxiline($row3['id_tr_invoice'], 'e');?>&j=1">Lihat Invoice</a>
					 <?} else {?>
					 <a href="invoice_add.php?id=<?=maxiline($row3['id_tb_pendaftaran'], 'e');?>&i=<?=maxiline($row3['id_tr_invoice'], 'e');?>&j=1">Lihat Invoice</a>
					 <?}?>
					 </li>
					 <li><a id="confirm" href="invoice_.php?id=<?=maxiline($row3['id_tr_invoice'], 'e');?>&l=y&st=<?=$row3['jns_invoice'];?>" onclick="return confirmnya()">Confirm dibayar</a></li>
					</ul>
					</div>
					  </td>
					</tr>
					<?}
				}?>
              </table>
			  </div>
			</div>
           </div>
            <!-- /.card-body --> 
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->         
  
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

<!-- Modal Default Size -->
	<div class="modal fade" id="vermodal1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">			
			<div class="modal-content" id="data">
			
			</div>
		</div>
	</div>
	<!-- // Modal Default Size -->
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
$(document).on('click','.open_modale',function()
	{
		var id=$(this).attr("id");
		$.ajax({
			url:"add_rencana.php",
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

  $(function () {
    $('#example1, #example2').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })

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



 
 function confirmnya() {
  if (confirm("Confirm dibayar?")) {
      $("#backfull").css("display","block");
	  $("#spinner").css("display","block");
  } 
}
 
</script>
</body>
</html>

<? 
$page= "pelaporan";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

if($_SESSION['level_user'] == 4) {
	$sql = "SELECT * FROM tr_pelaporan WHERE jns_laporan = '1' order by tgl_data desc";
} else if($_SESSION['level_user'] == 2 or $_SESSION['level_user'] == 3) {
	$sql = "SELECT * FROM tr_pelaporan WHERE jns_laporan = '2' order by tgl_data desc";	
} else if($_SESSION['level_user'] == 5) {
	$sqla = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
	$sql  = "SELECT * FROM tr_pelaporan WHERE id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]' order by tgl_data desc";	
} else {
	$sql = "SELECT * FROM tr_pelaporan order by tgl_data desc";	
}

$res = $db->query($sql);
?>
	
<!-- <link rel="stylesheet" href="resources/dataTables.searchHighlight.css" type="text/css"/> 
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
<!-- <script type="text/javascript" class="init">
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
		
		// dom: 'Blfrtip',		
		searchHighlight: true,		       
    });	
	
	/* dataTable.on('draw.dt', function () {
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
		List Pelaporan
		<!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pelaporan</li>
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
						<h3 class="box-title">Pelaporan</h3>
						<div class="box-tools">
							<?// if($_SESSION['level_user'] == 5) {?>
								<a href="pelaporan_add.php" class="btn btn-sm btn-danger">
									<i class="fa fa-edit"></i> Buat Laporan Baru
								</a>
							<?//}?>
						</div>
					</div> 
					<div class="box-body border-radius-none">
						<div class="box-body">
							<div class="table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th></th>
											<th>Kode Pelanggan</th>
											<th>Nama </th>
											<th>Tanggal </th>
											<th>Close </th>
											<th>Subyek</th>
											<th>Jenis</th>				  
											<th>Status </th>
											<?php if($_SESSION['level_user'] != 5) {?>
												<th>Hitungan waktu</th>
											<?php } ?>
											<th></th>
										</tr>
									</thead>
									<?php
										while($row = $db->fetchArray($res)) {				
											$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$row[id_tb_pendaftaran]'"));
											$n++;
									?>
										<tr>
											<td><?=$n;?></td>
											<td><?=$sqlb['kode_daftar'];?></td>
											<td><?=$sqlb['nama'];?></td>
											<td><?=tglindo(date_format($row['tgl_data'], 'Y-m-d'));?></td>
											<td><? if(empty($row['tgl_close'])) {echo "-";} else {echo tglindo(date_format($row['tgl_close'], 'Y-m-d'));}?></td>
											<td><?=$row['subyek_laporan'];?></td>				  
											<td><?=$jns_laporan[intval("0".$row['jns_laporan'])]?></td>
											<td>
												<div class="label label-<? if($row['sts_laporan'] == 1) {echo "danger";} else if($row['sts_laporan'] == 2) {echo "warning";} else if($row['sts_laporan'] == 3) {echo "success";}?>">
													<?=$sts_laporan[intval("0".$row['sts_laporan'])]?>
												</div>
											</td>
											<?php if($_SESSION['level_user'] != 5) { ?>
												<td>
													<?php
														if(empty($row['tgl_close'])) {
															$datetime1 = $row['tgl_data'];
															$datetime2 = $now;

															$difference = $datetime1->diff($datetime2);
															$jeda = $difference->days;
															if($jeda < 1) {echo $difference->h." Jam";} else {echo $difference->days." Hari";}
														} else {
															$datetime1 = $row['tgl_data'];
															$datetime2 = $row['tgl_close'];

															$difference = $datetime1->diff($datetime2);
															$jeda = $difference->days;
															if($jeda < 1) {echo $difference->h." Jam";} else {echo $difference->days." Hari";}
														}
													?>
												</td>
											<?php } ?>
											<td>
												<a class="btn btn-info btn-sm" href="pelaporan_add.php?id=<?=maxiline($row['id_tr_pelaporan'], 'e');?>&i=<?=maxiline($row['code_pelaporan'],'e')?>">Detail</a>
											</td>
										</tr>
									<?php } ?>
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
    <strong>Copyright &copy; <?=$th;?> Maxi-Line.</strong> All rights reserved.
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
	$(document).on('click','.open_modale',function() {
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
		$('#example1').DataTable({
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
</script>
</body>
</html>

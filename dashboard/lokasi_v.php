<? 
$page= "zonasi";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");

$sql = "select * from tb_lokasi";
$res = $db->query($sql);

$userlvl = array(1,2,3);
if(!in_array($_SESSION['level_user'], $userlvl)) {
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

.table-responsive {
    min-height: 300px;
    overflow-x: auto;
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
	  Daftar Coverage
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Coverage</li>
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
              <h3 class="box-title">Coverage</h3>			 
				<div class="box-tools pull-left">
					<a href="lokasi_add.php" class="btn btn-sm btn-primary">
					<i class="fa fa-edit"></i> Tambah Coverage
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
                  <th>Nama </th>
				  <th>STO</th>
				  <th>Coverage Desa / Kelurahan</th>
				  <th>Kuota Tarikan </th>
				  <th>latitude </th>
				  <th>Longitude </th>
				  <th>Keterangan </th>
				  <th></th>
                </tr>
                </thead> 
				<?
				while($row = $db->fetchArray($res)) {				
				$n++;?>	
				 <tr>
                  <td><?=$n;?></td>
                  <td><?=$row['nama_tiang'];?></td>
				  <td><?=$row['nama_area'];?></td>
				  <td>
				  <?
				  $desa = explode(',',$row['id_v_alamat']);
				  $alamate = array();
				  				 
				  foreach($desa as $ndesa){
				  $rowa = $db->fetchArray($db->query("select * from v_alamat where id_data_kd_pos ='$ndesa'"));				 
				  $alamate[] = $rowa['kelurahan_desa'].','.$rowa['kecamatan'].','.$rowa['kabupaten_kota'];
				  }
				  //print_r($alamate);
				   /*
				  
					// 1. Ambil baris pertama dan pecah untuk judul
					$first_parts = explode(',', $alamate[0]);
					$kecamatan = ucfirst(strtolower($first_parts[1]));
					$kota = ucfirst(strtolower($first_parts[2]));

					// 2. Tampilkan Judul
					echo "<strong>Kec. "."$kecamatan ($kota)</strong><br>";

					// 3. Loop untuk menampilkan setiap kelurahan
					foreach ($alamate as $alamat) {
						$parts = explode(',', $alamat);
						$deso = ucfirst(strtolower($parts[0]));
						echo $deso.", ";
					}
					*/					
					
					$grouped = [];

					// 1. Kelompokkan data ke dalam array multidimensi
					foreach ($alamate as $val) {
						$parts = explode(',', $val);
						$kelurahan = $parts[0];
						$kec_kota  = "Kec. ".ucfirst(strtolower($parts[1])) . " (" . ucfirst(strtolower($parts[2])) . ")"; // Contoh: TEMBALANG (SEMARANG)
						
						// Masukkan kelurahan ke dalam grup kecamatan yang sesuai
						$grouped[$kec_kota][] = $kelurahan;
					}

					// 2. Loop hasil pengelompokan untuk ditampilkan
					foreach ($grouped as $header => $list_kelurahan) {
						echo "<strong>$header</strong><br>"; // Cetak Nama Kecamatan (Kota)
						
						foreach ($list_kelurahan as $kel) {
							$kelu = ucfirst(strtolower($kel));
							echo $kelu . "<br>"; // Cetak Daftar Kelurahan di bawahnya
						}
						
						echo "<br>"; // Beri jarak antar kelompok (opsional)
					}
					?>
				  
				  </td>
				  <td><?=$row['kuota_tarikan'];?></td>
				  <td><?=$row['latitude'];?></td>
				  <td><?=$row['longitude'];?></td>
				  <td><?=$row['keterangan_tiang'];?></td>
				  <td>
				  <div class="btn-group">
                 
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li><a href="lokasi_add.php?id=<?=maxiline($row['id_tb_lokasi'], 'e');?>&f=d">Detil</a></li>
                    <li><a href="lokasi_add.php?id=<?=maxiline($row['id_tb_lokasi'], 'e');?>&f=e">Edit</a></li>
					<?
					if($_SESSION['level_user'] == 1) {?>
                    <li class="divider"></li>
                    <li><a href="lokasi_.php?id=<?=maxiline($row['id_tb_lokasi'], 'e');?>&f=h&token=<?=$kodeaman;?>" onclick="return confirm('Hapus data?')">Hapus</a></li>
					<?}?>
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

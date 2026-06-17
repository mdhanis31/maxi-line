<? 
$page= "langganan";
$pages= "paket";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$th = $now->format("Y");

$sql = "SELECT * FROM tb_paket where sts_delete = '1' order by nama_paket asc";
$res = $db->query($sql);

$userlvl = array(1,4);
if(!in_array($_SESSION['level_user'], $userlvl)) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}
?>
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

.select2-container .select2-selection--single {
	height: max-content;
}

div.dataTables_wrapper div.dataTables_paginate {   
    padding-top: 30px;
}

div.dataTables_wrapper div.dataTables_info {
	padding-top: 30px;
}

ul.fitur-list {
    list-style-position: outside;
    margin: 0;
    padding: 0 0 0 15px;
}

ul.fitur-list li {
    white-space: normal;
}
</style> 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
            <h1 class="m-0 text-dark">Paket</h1>          
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Paket</li>
            </ol>
		</section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
		<div class="row">
        <!-- Left col -->     
		  <section class="col-lg-12">
          <!-- solid sales graph -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title">Jenis Paket</h3>
				<div class="box-tools">
					<a href="paket_add.php" class="btn btn-sm btn-primary">
					<i class="fa fa-edit"></i> Tambah Paket
					</a>         
				</div>
            
            </div>
            <div class="box-body border-radius-none">
              <div class="box-body">
			  <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
		<!--		  <th>Kode </th> -->
                  <th>Nama</th>                  
				  <th>Harga</th>
				  <th style="width:30%;">Coverage</th>
				  <th>Fitur</th>				  
				  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
				<?
				while($row = $db->fetchArray($res)) {					
				$n++;?>				
                <tr>
                  <td><?=$n?></td>
				  <td><?=$row['nama_paket'];?></td>
				  <td>Rp. <?=number_format($row['harga_paket'],0,',','.').",00";?></td>
				  <td>
				  <div id="coverage_display" style="padding: 10px; min-height: 100px;"><?
				  $lokasi = explode(',',$row['id_tb_lokasi']);
				  foreach($lokasi as $nlokasi){
				  $sqlb = "select * from tb_lokasi where id_tb_lokasi = '$nlokasi'";
				  $resb = $db->query($sqlb);
				  $rowb = $db->fetchArray($resb);
				  
				  echo "<strong>$rowb[nama_tiang]</strong><br><strong>$rowb[nama_area]</strong><br>"; // Cetak Nama Kecamatan (Kota)
				  
				  $desa = explode(',',$rowb['id_v_alamat']);
				  $alamate = array();
				  foreach($desa as $ndesa){
				  $rowc = $db->fetchArray($db->query("select * from v_alamat where id_data_kd_pos ='$ndesa'"));				 
				  $alamate[] = $rowc['kelurahan_desa'].','.$rowc['kecamatan'].','.$rowc['kabupaten_kota'].','.$rowc['kd_pos'];				  
				  }
				  
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
					 // Beri jarak antar kelompok (opsional)
				  }
				  ?>
				  <div style="width:40%;">-----------</div><br>
				  <?
				}  
				?>
				</div>
				  </td>
				  <td><ul class="fitur-list"><?=html_entity_decode($row['isi_paket']);?></ul></td>
				  <td>
				   <div class="btn-group">
				   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">					  
                      <li><a class="dropdown-item" href="paket_add.php?i=<?=maxiline($row['id_tb_paket'], 'e');?>&a=b">Edit</a></li>
					   <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item"  href="paket_.php?id=<?=maxiline($row['id_tb_paket'], 'e');?>&b=c&token=<?=$kodeaman?>" onclick="return confirm('Hapus data?')">Hapus</a></li>                     
					   <?}?>
					</ul>
                  </div>
				  </td>
                </tr>
                <?}?>
                </tbody>               
              </table>
			 </div>
			 </div>
            </div>
            <!-- /.card-body -->
          </div>
  
      </section><!-- /.container-fluid -->
	  
    </div>
    <!-- /.content -->
  </section>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
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
<!-- page script -->
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
</body>
</html>
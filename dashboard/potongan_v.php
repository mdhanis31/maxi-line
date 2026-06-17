<? 
$page = "adcharge";
if ($_GET['idx'] == "bFZHcDF0alhzWTYvZGtYZTc1ZyswUT09") {
$pages = "pasang";
} else if ($_GET['idx'] == "Vy80RENjZGxxTTNDOHN5VkRJSGlJUT09") {
$pages = "bulanan";
} else if ($_GET['idx'] == "WEEvaENpL3VyK3BxTER3cTFYYVJqUT09") {
$pages = "layanan";
} else if ($_GET['idx'] == "ckxmWHJMNXpJeTNxbWlXdzQ5TjRKUT09") {
$pages = "pemutusan";
} else {
?>
<script>alert('Halaman tidak ditemukan!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();
}	
	
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$th = $now->format("Y");

$id = maxiline($_GET['idx'], 'd');

if ($id == 1) {
$sql = "SELECT * FROM tb_potongan where jns_potongan = '1'";
} else if ($id == 2) {
$sql = "SELECT * FROM tb_potongan where jns_potongan = '2'";
} else if ($id == 3) {
$sql = "SELECT * FROM tb_potongan where jns_potongan = '3'";
} else if ($id == 4) {
$sql = "SELECT * FROM tb_potongan where jns_potongan = '4'";
}
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
</style>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
            <h1 class="m-0 text-dark">Biaya Tambahan</h1>          
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Biaya Tambahan</li>
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

              <h3 class="box-title">Biaya Tambahan <?if ($id == 1) {echo "Pemasangan";} else if ($id == 2) {echo "Bulanan";} else if ($id == 3) {echo "Layanan";} else if ($id == 4) {echo "Pemutusan";}?>
			  </h3>
				<div class="box-tools">
					<a href="potongan_add.php?idx=<?=$_GET['idx'];?>" class="btn btn-sm btn-primary">
					<i class="fa fa-edit"></i> Tambah Biaya
					</a>         
				</div>
            
            </div>
            <div class="box-body border-radius-none">
              <div class="box-body">
			  <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No. </th>
                  <th>Nama</th>
				  <th>Jumlah</th>
				  <th>Status</th>
				  <th>Keterangan</th>				  
				  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
				<?
				while($row = $db->fetchArray($res)) {					
				$n++;?>				
                <tr>
                  <td><?=$n?></td>
				  <td><?=$row['nama_potongan'];?></td>
				   <td><?if($row['satuan'] == '%') {echo $row['jumlah']." %";} else {echo "Rp. ".number_format($row['jumlah'],0,',','.').",00";}?></td>
				   <td><?
				   if($row['status']==1) {echo "Aktif";} else if($row['status']==2) {echo "Tidak Aktif";}
				   ?></td>
				    <td><?=$row['ket_potongan']?></td>
				  <td>
				   <div class="btn-group">
				   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">				  
                      <li><a class="dropdown-item" href="potongan_add.php?idx=<?=$_GET['idx'];?>&i=<?=maxiline($row['id_tb_potongan'], 'e');?>&a=b">Edit</a></li>
					   <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item"  href="potongan_.php?idx=<?=$_GET['idx'];?>&id=<?=$row['id_tb_potongan']?>&b=c&token=<?=$kodeaman?>" onclick="return confirm('Hapus data?')">Hapus</a></li>                     
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
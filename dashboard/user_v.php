<?
$page = "user";
include ("head.php"); 
include ("nav.php"); 

//cek apakah login admin
if ($_SESSION['level_user'] != 1) {
?>
<script>document.location.href="user_add.php?i=<?=$_SESSION['id_tb_user'];?>&a=b"</script>
<?php 
exit();  
}

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$sqla = "SELECT * FROM tb_user where level_user != '5' and sts_delete='1'";
$resa = $db->query($sqla);

if($_SESSION['level_user'] != 1) {
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
      <h1>
	  List User Maxi-Line
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">User Maxi-Line</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
       <div class="row">
		
	    <section class="col-lg-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-th"></i>
              <h3 class="box-title">User Maxi-Line</h3>
			   <div class="box-tools">
				<?  if ($_SESSION['level_user'] == '1') {?>
				 <a href="user_add.php" class="btn btn-primary btn-sm">Tambah User <i class="fa fa-plus"></i></a> 
				<?}?>
				</div>
            </div>
            <!-- /.card-header -->
            <div class="box-body border-radius-none">
              <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No. </th>
                  <th>Nama</th>	
				  <th>No. Telp.</th>
				  <?  if ($_SESSION['level_user'] == '1') {?>
				  <th>Last Login</th>
				  <?}?>
                  <th>Email</th>	
				  <th>Jenis Akun</th>
				  <th>Aksi</th>	
                </tr>
                </thead>
                <tbody>
				<?
				while($rowa = $db->fetchArray($resa)) {					
				$n++;?>				
                <tr>
                  <td><?=$n?></td>
				  <td><?=$rowa['nm_user']?></td>                  
				  <td><?=$rowa['telp']?></td>
				   <?  if ($_SESSION['level_user'] == '1') {?>
				   <td><?=tglindo(date_format($rowa['current_login'], 'Y-m-d'));?> - <?=date_format($rowa['current_login'], 'H:i:s');?></td>
				   <?}?>
                  <td><?=$rowa['email']?></td>
				  <td><?=$st_level[intval("0".$rowa['level_user'])]?></td>
				  <td>
				  <?  if ($_SESSION['level_user'] == '1') {?>
				  <div class="btn-group">
					<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">				  
                      <li><a class="dropdown-item" href="user_add.php?i=<?=maxiline($rowa['id_tb_user'], 'e');?>&a=b">Edit</a></li>				
					  <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item" href="user_.php?i=<?=maxiline($rowa['id_tb_user'], 'e');?>&j=c&token=<?=$kodeaman;?>" onclick="return confirm('Hapus data?')">Hapus</a></li>                     
					   <?}?>
					</ul>
                  </div>
				  <?}?>
				  </td>
                </tr>
                <?}?>
                </tbody>               
              </table>
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
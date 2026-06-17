<?
$page = "tos";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$sqla = "SELECT * FROM tb_tos";
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
	  Term of Services
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Term of Services</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
       <div class="row">
		
	    <section class="col-lg-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-th"></i>
              <h3 class="box-title">Term of Services</h3>
			   <div class="box-tools">
				
				</div>
            </div>
            <!-- /.card-header -->
            <div class="box-body border-radius-none">
              <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Judul</th>
                  <th>Isi</th>				 
				  <th>Aksi</th>	
                </tr>
                </thead>
                <tbody>
				<?
				while($rowa = $db->fetchArray($resa)) {					
				$n++;?>				
                <tr>
                  <td><?=$rowa['judul'];?></td>
				  <td><?=substr($rowa['note'], 0, 200);?> ... </td>
				  <td>
				  <?  if ($_SESSION['level_user'] == '1') {?>
				  <div class="btn-group">
					<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">				  
                      <li><a class="dropdown-item" href="tos_add.php?i=<?=maxiline($rowa['id_tb_tos'], 'e');?>&a=b">Edit</a></li>				
					  <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item" href="tos_.php?i=<?=maxiline($rowa['id_tb_tos'], 'e');?>&j=c&token=<?=$kodeaman;?>" onclick="return confirm('Hapus data?')">Hapus</a></li>                     
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
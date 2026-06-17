<? 
$page= "invoice";
$pages= "note";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$sql = "SELECT * FROM tb_note";
$res = $db->query($sql);

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
            <h1 class="m-0 text-dark">Note Invoice</h1>          
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Note Invoice</li>
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
			<h3 class="box-title">Daftar Note</h3>			
			 <div class="box-tools">	
             <a href="note_add.php" class="btn btn-primary btn-sm">Tambah Note <i class="fa fa-plus"> </i></a> 			
            </div>
            </div>
            <!-- /.card-header -->
           <div class="box-body border-radius-none">
			 <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No. </th>
				  <th>Judul</th>
                  <th>Note</th>				  
				  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
				<?
				while($row = $db->fetchArray($res)) {					
				$n++;?>				
                <tr>
                  <td><?=$n?></td>
				  <td><?=$row['judul'];?></td>  
				  <td><?=$row['note'];?></td>  
				  <td>
				  <div class="btn-group">
                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">					  
                      <li><a class="dropdown-item" href="note_add.php?i=<?=maxiline($row['id_tb_note'], 'e');?>&a=b">Edit</a></li>
					   <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item"  href="note_.php?id=<?=maxiline($row['id_tb_note'], 'e');?>&b=c&token=<?=$kodeaman?>" onclick="return confirm('Hapus data?')">Hapus</a></li>                     
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
            <!-- /.card-body -->
          </div>
  
      </div><!-- /.container-fluid -->
	</section>	  
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
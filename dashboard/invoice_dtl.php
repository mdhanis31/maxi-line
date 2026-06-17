<? 
$page= "biayatambahan";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$th = $now->format("Y");

$id_tb_pendaftaran = SafeSQL($_GET['id']);
$sql = "SELECT * FROM tr_transaksi where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$res = $db->query($sql);

$sqla = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

$sqlb = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$rowa[id_tb_paket]'"));

if($_SESSION['level_user'] == 4 or $_SESSION['level_user'] == 5 or $_SESSION['level_user'] == 2) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
            <h1 class="m-0 text-dark">Invoice</h1>          
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Invoice</li>
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

              <h3 class="box-title">Invoice</h3>
				<div class="box-tools">				       
				</div>
            
            </div>
			    <!-- SELECT2 EXAMPLE --> 
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Kode Customer
                  </div>				
				<input class="form-control" value="<?=$rowa['kode_daftar'];?>" disabled>				
				</div>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <div class="form-group">
				<div class="input-group">
                  <div class="input-group-addon">
                  Nama
                  </div>				
				<input class="form-control" value="<?=$rowa['nama'];?>" disabled>				
				</div>
              </div>
              </div>
			   <div class="form-group">
                <div class="form-group">
				<div class="input-group">
                  <div class="input-group-addon">
                  Alamat
                  </div>				
				<input class="form-control" value="<?=$rowa['alamat'];?>" disabled>				
				</div>
              </div>
              </div>
			   <div class="form-group">
                <div class="form-group">
				<div class="input-group">
                  <div class="input-group-addon">
                  Tanggal Pendaftaran
                  </div>				
				<input class="form-control" value="<?=tglindo(date_format($rowa['tgl_data'], 'Y-m-d'));?>" disabled>				
				</div>
              </div>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
             <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Paket
                  </div>				
				<input class="form-control" value="<?=$sqlb['nama_paket'];?>" disabled>				
				</div>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Status
                  </div>				
				<input class="form-control" value="<?=$st_layanan[$rowa['st_layanan']];?>" disabled>				
				</div>
              </div>
			    <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Email
                  </div>				
				<input class="form-control" value="<?=$rowa['email'];?>" disabled>				
				</div>
              </div>
			    <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                   Telp
                  </div>				
				<input class="form-control" value="<?=$rowa['telp'];?>" disabled>				
				</div>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
	  
	  
      <!-- /.box -->
            <div class="box-body border-radius-none">
              <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>  
				  <th>No. Invoice</th>
				  <th>Nominal (Rp.)</th>
				  <th>Tanggal</th>
				  <th>Tipe</th>				  
				  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
				<?
				while($row = $db->fetchArray($res)) {					
				$n++;?>				
                <tr>
                  <td><?=$n?></td>
				  <td><?=$row['harga_transaksi'];?></td>
				  <td><?=$row['jumlah'];?></td>				  
				  <td><?=$row['tipe_transaksi']?></td>
				  <td>
				   <div class="btn-group">
				   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu" role="menu">				  
                      <li><a class="dropdown-item" href="potongan_add.php?i=<?=$row['id_tb_potongan']?>&a=b">Edit</a></li>
					   <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item"  href="potongan_aksi.php?id=<?=$row['id_tb_potongan']?>&b=c&token=<?=$kodeaman?>" onclick="return confirm('Hapus data?')">Hapus</a></li>                     
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
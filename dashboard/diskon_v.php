<? 
$page = "diskon";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$sql = "SELECT * FROM tb_voucher order by tgl_data desc";
$res = $db->query($sql);

if($_SESSION['level_user'] == 4 or $_SESSION['level_user'] == 5 or $_SESSION['level_user'] == 2) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}

function base64url_encode($plainText)
{
    return strtr(base64_encode($plainText), '+/=', '-_,');
}
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Voucher Diskon
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Diskon</li>
      </ol>
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
         <section class="col-lg-12">
		 <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-th"></i>
              <h3 class="box-title">Voucher Diskon Terbit</h3>
				<div class="box-tools">
				 <a href="diskon_add.php" class="btn btn-primary btn-sm">Tambah Diskon <i class="fa fa-plus"> </i></a> 	
				</div>
            </div>
          <!-- solid sales graph -->
          <div class="box-body border-radius-none">
              <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No. </th>
                  <th>Kode</th>
				  <th>Tipe</th>
				  <th>Start</th>
				  <th>Expired</th>
				  <th>Nominal</th>
				  <th>Jumlah</th>
				  <th>Sisa</th>
				  <th>Keterangan</th>
				  <th>Status</th>				  
				  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
				<?
				while($row = $db->fetchArray($res)) {					
				$n++;				
				?>				
                <tr>
                  <td><?=$n?></td>
				  <td><?=$row['nama_voucher']?></td>
				  <td><?if($row['tipenya'] == 1) {echo "Promo";} else {echo "Voucher";}?></td> 
				  <td><?=tglindo(date_format($row['tglawal'], 'Y-m-d'));?></td>
				  <td><?=tglindo(date_format($row['tglakhir'], 'Y-m-d'));?></td>
				  <td><?if($row['satuan'] == '%') {echo $row['nominal']." %";} else {echo "Rp. ".number_format($row['nominal'],0,',','.').",00";}?></td>
				  <td><?=$row['jumlah'];?></td>				  
				  <td><?=$row['sisa'];?></td>				  
				  <td><?=$row['ket_voucher']?></td>
				  <td><?if($row['status_voucher'] == 1) {echo "Aktif";} else if($row['status_voucher'] == 2) {echo "Dipakai";} else if($row['status_voucher'] == 3) {echo "Expired";}?></td>
				  <td>
				  <? 
				  if($row['tipenya'] == 2) {
				  if($row['status_voucher'] == 1) {?>
				  <div class="btn-group">
                   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">		  
                      <li><a class="dropdown-item" href="diskon_edt.php?i=<?=maxiline($row['id_tb_voucher'],'e');?>">Edit</a></li>
					   <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item"  href="diskon_aksi.php?id=<?=maxiline($row['id_tb_voucher'],'e');?>&b=c&token=<?=$kodeaman?>" onclick="return confirm('Expired-kan Voucher? Voucher akan hangus & proses ini tidak bisa di undo!')">Expired</a></li>                     
					   <?}?>
					    <li><a class="dropdown-item" href="history_voucher.php?i=<?=maxiline($row['id_tb_voucher'],'e');?>">History</a></li>
					</ul>
                  </div>
				  <?} else {?>
				  <a class="btn-info btn-sm" href="history_voucher.php?i=<?=maxiline($row['id_tb_voucher'],'e');?>">History</a>
				  <?}
				  } else {
					if($row['status_voucher'] == 1) {
					$sql1 = "SELECT count(id_tr_voucher) as jmlterpake FROM tr_voucher where id_tb_voucher = '$row[id_tb_voucher]'";
					$res1 = $db->query($sql1);  
					$row1 = $db->fetchArray($res1);
					if($row['status_voucher'] !=  1) {} else {?>
					
					<div class="btn-group">
					<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">		  
                      <li><a class="dropdown-item" href="diskon_edt.php?i=<?=maxiline($row['id_tb_voucher'],'e');?>">Edit</a></li>
					   <?if($_SESSION['level_user'] == 1) {?>
                      <li><a class="dropdown-item"  href="diskon_aksi.php?id=<?=maxiline($row['id_tb_voucher'],'e');?>&b=c&token=<?=$kodeaman?>" onclick="return confirm('Expired-kan Promo? Promo akan hangus & proses ini tidak bisa di undo!')">Expired</a></li>                     
					   <?}?>
					  <li><a class="dropdown-item" href="history_voucher.php?i=<?=maxiline($row['id_tb_voucher'],'e');?>">History</a></li>
					</ul>
					</div>				  
				  <?}
				  } else {?>
				   <a class="btn-info btn-sm" href="history_voucher.php?i=<?=maxiline($row['id_tb_voucher'],'e');?>">History</a>
				  <?}
				  }?>
				  </td>
                </tr>
                <?}?>
                </tbody>               
              </table>
			 </div>
            </div>
			</div>
			</section>
            <!-- /.card-body -->
          </div>
  
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
  </footer>
</div>

				
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
</body>
</html>
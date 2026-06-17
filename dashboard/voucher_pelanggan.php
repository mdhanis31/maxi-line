<? 
$page = "voucher";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$sqla = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
								
$sqlb = "SELECT * FROM tr_voucher where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]' order by tgl_data desc";
$resb = $db->query($sqlb);

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
              <h3 class="box-title"></h3>
				<div class="box-tools">
				 <button type="button" data-toggle="modal" data-target="#vermodal1" data-target="#vermodal1" class="btn btn-primary btn-sm">Tambah Voucher <i class="fa fa-plus"> </i></button> 	
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
				  <th>Sisa</th>
				  <th>Keterangan</th>
				  <th>Status</th>				  
				  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
				<?
				while($rowb = $db->fetchArray($resb)) {	
				$row = $db->fetchArray($db->query("SELECT * from tb_voucher where id_tb_voucher = '$rowb[id_tb_voucher]'"));
				if($row['satuan'] == 'Rp') {
				$sqlb = $db->fetchArray($db->query("  select sum(dipake) as dipake from (SELECT id_tr_invoice, id_tr_voucher, 
				dipake from tr_diskon where id_tb_voucher = '$rowb[id_tb_voucher]')a left join (select sts_invoice, id_tr_invoice from tr_invoice)b
				on a.id_tr_invoice = b.id_tr_invoice where sts_invoice != '1'"));
				$sisae = $row['nominal'] - $sqlb['dipake'];
				} else {
				$sisae = $rowb['sisa'];	
				}
				$n++;				
				?>				
                <tr>
                  <td><?=$n?></td>
				  <td><?=$row['nama_voucher']?></td>
				  <td><?if($row['tipenya'] == 1) {echo "Promo";} else {echo "Voucher";}?></td> 
				  <td><?=tglindo(date_format($row['tglawal'], 'Y-m-d'));?></td>
				  <td><?=tglindo(date_format($row['tglakhir'], 'Y-m-d'));?></td>
				  <td><?if($row['satuan'] == '%') {echo $row['nominal']." %";} else {echo "Rp. ".number_format($row['nominal'],0,',','.').",00";}?></td>
				  <td><?if($row['satuan'] == '%') {echo $sisae;} else {echo "Rp. ".number_format($sisae,0,',','.').",00";}?></td>				  
				  <td><?=$row['ket_voucher']?></td>
				  <td><?if($rowb['st_tr_voucher'] == 1) {echo "Aktif";} else if($rowb['st_tr_voucher'] == 2) {echo "Dipakai";} else if($rowb['st_tr_voucher'] == 3) {echo "Expired";}?></td>
				  <td>
				 
				  <div class="btn-group">
                   <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span style="margin-right:3px;">Aksi </span>
					<span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">		  
                      <li><a class="dropdown-item" href="voucher_dtl.php?i=<?=maxiline($rowb['id_tr_voucher'],'e');?>">History</a></li>					
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
			</section>
            <!-- /.card-body -->
          </div>
			
				 <!-- Modal Default Size -->	
	<div class="modal fade" id="vermodal1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tambah Voucher</h4>
              </div>
              <div class="modal-body">
               <div class="col-md-12">
				<form class="form-horizontal" action="diskon_exe.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
				
				<input type="hidden" name="token" value="<?=$kodeaman?>" />	
				<input type="hidden" name="id_tb_pendaftaran" value="<?=$sqla['id_tb_pendaftaran'];?>" />
				<input type="hidden" name="cek" value="voucher" />
				
				      <div class="form-group"> 
                        <div class="col-sm-12">	
                          <input type="text" class="form-control" id="code" name="kode" placeholder="Masukkan kode voucher" required>
						</div>
					   </div>	
				 <div style="margin-top:50px;">
				   <button type="submit" class="btn btn-info" style="margin-right:5px;">Tambahkan</button>
				   <button type="reset" class="btn btn-default" data-dismiss="modal">Batal</button>
				 </div>				
				</form>
				</div>
              </div>
              <div class="modal-footer">
              
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
		
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
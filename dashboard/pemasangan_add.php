<? 
$page= "langganan";
$pages= "pemasangan";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();

$kodeaman = $_SESSION['token'];
$id = SafeSQL($_GET['i']);

$now = new DateTime();
$th = $now->format("Y");

$sql = "select * from tb_biaya_pasang";
$res = $db->query($sql);
$row = $db->fetchArray($res);
$jml = $db->queryNumRows($sql);
$total = $db->getNumRows($jml);

?>

      
<style>
.wysihtml5-sandbox {
resize: vertical;
}
</style>  
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	 <? if($total=='0') {echo "Input Biaya Pemasangan";} else {echo "Edit Biaya Pemasangan";}?>
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Biaya Pemasangan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	  <div class="row">
			<!-- Left col --> 		
			  <section class="col-lg-10">
			  <div class="box box-primary">
				<div class="box-header with-border">
				  <h3 class="box-title"> Biaya Pemasangan</h3>
				</div>
			
			<form role="form" action="pemasangan_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<?if ($total=='0') {?>
			 <input type="hidden" name="j" value="a" />
			 <?} else {?>
			  <input type="hidden" name="j" value="b" />
			 <?}?>
			 <input type="hidden" name="id_tb_biaya_pasang" value="<?php echo $row['id_tb_biaya_pasang'];?>">						
			 <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
				 
			<div class="box-body">
			<div class="form-group">
				 <div class="input-group">
                  <div class="input-group-addon">
                   Rp.
                  </div>				
				<input type="number" class="form-control" name="biaya_pasang" value="<?=$row['biaya_pasang'];?>" required placeholder="Masukkan biaya pemasangan">				
				</div>		
			</div>
			
		    <div style="margin-top:50px;">
			<div style="margin-top:50px;">		
			  <button type="submit" name="kirim" value="b" class="btn btn-info" style="margin-right:5px;"><? if($total=='0') {echo "Simpan";} else {echo "Update";}?></button>
			  <button type="reset" class="btn btn-default" onclick="window.location.href='potongan_v.php'">Batal</button>
			</div>
			</div>
		   </form>
		   </div>
			 
            </div>
			</section>
            <!-- /.card-body -->
      </div><!-- /.container-fluid -->
    </section>
	
   <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- /.content-wrapper -->
    <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; <?=$th;?> Maxi-Line.</strong> All rights
    reserved.
  </footer>
</div>
<!-- ./wrapper -->


<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
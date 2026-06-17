<? 
$page= "invoice";
$pages= "note";
include ("head.php"); 
include ("nav.php"); 

$db = new DbConnector();

$kodeaman = $_SESSION['token'];
$id = maxiline(SafeSQL($_GET['i']), 'd');

$sql = "select * from tb_note where id_tb_note='$id'";
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
      <h1>Note Invoice
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Note</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     <div class="row">
        <!-- Left col --> 		
		  <section class="col-lg-10">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> <?if ($_GET['a']=='b') { echo "Edit Note";} else if ($total=='0') { echo "Input Note";} else { echo "Detail Note";}?></h3>
            </div>
            <!-- /.card-header -->            
			<form action="note_.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form" onsubmit="return validateSearch()">
			 <?if ($_GET['a']=='b') {?>
			 <input type="hidden" name="j" value="b" />
			 <?} else if($total=='0') {?> 
			 <input type="hidden" name="j" value="a" />
			 <?}?>
			 <input type="hidden" name="id_tb_note" value="<?php echo $row['id_tb_note'];?>">						
			 <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
			
			<div class="box-body">
			<div class="form-group">
				<label>Judul</label>
				<? if ($_GET['a']=='b') {?>
				<input type="text" id="nama" name="judul" class="form-control" value="<?php echo $row['judul'];?>" required>
				 <?} else if ($total=='0') {?>
				   <input type="text" id="nama" name="judul" class="form-control" placeholder="Masukkan judul" required>
				<?} else {?>
				<input type="text" id="nama" class="form-control" value="<?php echo $row['judul'];?>" readonly>
				<?} ?>
            </div>	  
			
			<div class="form-group">
              <label>Isi Note</label>   
			  <? if ($_GET['a']=='b') {?>
				<textarea style="height:100px;" name="note" id="note" class="form-control textarea" required><?=$row['note'];?></textarea>   
				 <?} else if ($total=='0') {?>
				<textarea style="height:100px;" name="note" id="alamat" class="form-control textarea" required placeholder="Masukkan isi note"></textarea>
				<?} else {?>
				<textarea style="height:100px;" id="alamat" class="form-control" readonly><?=$row['note'];?></textarea>  
				<?} ?>
                           
            </div>			
		   
			<div style="margin-top:50px;">			
			  <button type="submit" name="kirim" value="b" class="btn btn-info" style="margin-right:5px;">Simpan</button>
			  <button type="reset" class="btn btn-default" onclick="window.location.href='note_v.php'">Batal</button>
			</div>
			</form>
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
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- page script -->
<script>
  var xs = jQuery.noConflict();
  xs(function () {  

    // bootstrap WYSIHTML5 - text editor

    xs('.textarea').wysihtml5({
      toolbar: { fa: true, 
	  "font-styles": false,
	  "emphasis": false, //Italics, bold, etc.
	  link: false, // Button to insert a link.
      image: false, // Button to insert an image.
	  html: true	  
	  }
    })
  })
</script>
</body>
</html>
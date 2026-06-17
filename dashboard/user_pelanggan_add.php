<? 
$page = "user_pelanggan";

include ("head.php"); 
include ("nav.php"); 

$kodeaman = $_SESSION['token'];
if ($_GET['a']=='b') {
$id = maxiline(SafeSQL($_GET['i']), 'd');

$sqla = "select * from tb_user where id_tb_user = '$id'";
$resa = $db->query($sqla);
$rowa = $db->fetchArray($resa);

if(empty($rowa['nm_user'])) {
?>
<script>alert('Data tidak ditemukan!')</script>;
<script>document.location.href="user_v.php"</script>
<?php 
exit();
}	
}  

if($_SESSION['level_user'] != 1 and $_SESSION['id_tb_user'] != $id ) {
?>
<script>alert('Level anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();  
}
?>
<style>
.float-sm-right {
    float: right!important;
}
</style>

   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>  Data User Pelanggan
	
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">User Pelanggan</li>
      </ol>
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
	  
		<div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><? if ($_GET['a']=='b') {echo "Edit";} else {echo "Tambah";}?> User Pelanggan</h3>
				<div class="box-tools" style="margin-top:4px;">
					<a data-toggle="modal" href="#" data-target="#defaultModal" class="btn-sm btn-primary">
					<i class="fa fa-edit"></i> Rubah username / password
					</a>         
				</div>
            </div>
			
			<form action="user_pelanggan_.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
			<?if($_GET['a'] == 'b') {?>
			 <input type="hidden" name="j" value="a" />
			 <input type="hidden" name="id_tb_user" value="<?=$rowa['id_tb_user'];?>" />
			 <?} else {?>
			 <input type="hidden" name="j" value="b" />					 
			 <?}?>
			<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />		
			<div class="box-body">
					<div class="form-group">
					  <label>Nama *</label> 
						<? if($_GET['a'] == 'b') {?>
						<input type="text" name="nm_user" id="nm_user" class="form-control" value="<?=$rowa['nm_user']?>" required> 
						<?} else {?>
						<input type="text" name="nm_user" id="nm_user" class="form-control" placeholder="Masukkan Nama" required> 
						<?}?>
					</div>				
					<div class="form-group">
					  <label>No. Telp</label>             
						<? if($_GET['a'] == 'b') {?>
						<input type="text" name="telp" id="telp" class="form-control" value="<?=$rowa['telp']?>" > 
						<?} else {?>
						<input type="text" name="telp" id="telp" class="form-control" placeholder="Masukkan no. telepon" > 
						<?}?>             
					</div>
					
					<div class="form-group">
					  <label>Email</label>             
						<? if($_GET['a'] == 'b') {?>
						<input type="email" name="email" id="email" class="form-control" value="<?=$rowa['email']?>" required> 
						<?} else {?>
						<input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required> 
						<?}?>              
					</div>
					
					<div class="form-group">
					  <label>Jabatan</label>             
						<? if($_GET['a'] == 'b') {?>
						<input type="text" name="jabatan" id="jabatan" class="form-control" value="<?=$rowa['jabatan']?>" > 
						<?} else {?>
						<input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Masukkan jabatan" > 
						<?}?>             
					</div>
					
					<div class="form-group">
					  <label>Alamat</label>             
						<? if($_GET['a'] == 'b') {?>
						<textarea style="height:100px;" name="alamat" id="alamat" class="form-control" placeholder="Masukkan alamat"><?=$rowa['alamat'];?></textarea>  
						<?} else {?>
						 <textarea style="height:100px;" name="alamat" id="alamat" class="form-control" placeholder="Masukkan alamat"></textarea>  
						<?}?>             
					</div>					
					 <input type="hidden" name="level_user" value="<?=$rowa['level_user'];?>" />
					<div class="form-group">
					 <label>Username *</label>             
						<? if($_GET['a'] == 'b') {?>
						<input type="text" name="username" id="nm_unit" class="form-control" value="<?=$rowa['username']?>" required> 
						<?} else {?>
						<input type="text" name="username" id="nm_unit" class="form-control" placeholder="Masukkan username" required> 
						<?}?>            
					</div>
										
					<div class="form-group" style="<? if ($_GET['a']=='b') {?>display: none;<?}?>">
					<label for="password">Password Aplikasi *</label>
					<div class="form-group">
						<div class="form-line">
							<input type="password" id="password1" class="form-control" name="passwd1" placeholder="Masukkan password aplikasi" <?if (!isset($_GET[i])) {echo"required";}?>>
							<p style="color:red;">Password minimal 5 karakter & maksimal 12 karakter. Harus terdapat huruf besar, huruf kecil, angka & spesial karakter</p>
						</div>
					</div>
					<div class="form-group">
						<div class="form-line">
							<input type="password" id="password2" class="form-control" name="passwd2" placeholder="Masukkan ulang password aplikasi" <?if (!isset($_GET[i])) {echo"required";}?>>
						</div>
					</div>
					</div>
					
					<div class="form-group col-8" style="padding-left: 0px;<? if ($_GET['a']=='b') {?>display: none;<?}?>;">
					 <label for="exampleInputFile">Foto</label>
					 <div class="input-group">
					  <div class="custom-file">
						<input type="file" class="custom-file-input" id="exampleInputFile"  name="pasfoto">
						<label class="custom-file-label" for="exampleInputFile">Pilih file</label>
					  </div>				 
					 </div>
					 <span class="athhar">Tipe file jpg/png, besar file maksimal 500kb, size file maksimal 400 x 400px.</span>
					</div>
			
					<div style="margin-top:45px;">
					<?if($_GET['a']=='b') {?>
					  <button type="submit" name="draft" value="a" class="btn btn-info">Update</button>
					  <button type="reset" class="btn btn-default" onclick="window.location.href='user_pelanggan_v.php'">Batal</button>					 
					<?} else {?>
					  <button type="submit" name="draft" value="a" class="btn btn-info" style="margin-right:5px;">Simpan</button>
					  <button type="reset" class="btn btn-default" onclick="window.location.href='user_pelanggan_v.php'">Batal</button>		
					<?}?>
					</div>
			  </div>
			</form>
			 </div>
            <!-- /.card-body -->
		</div>
			
			
			
		<? if($_GET['a'] == 'b') {?>
		<div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
			<?
			if(empty($rowa['pasfoto'])){$foto = "icon.jpg";} else {$foto = $rowa['pasfoto'];}
			?>
			<img class="profile-user-img img-responsive img-circle" src="dist/foto_profil/<?=$foto?>" alt="User Image" >
			<div style="text-align:center;">
			<a data-toggle="modal" href="#" data-target="#defaultModal1" class="btn btn-primary" style="margin-top:25px;">Rubah Foto</a>
			</div>
			</div>
			</div>
		</div>
		<?}?>
	 </div>		
    </section>
	
   <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
	   <!-- Modal Default Size -->
              <div class="modal fade" id="defaultModal" role="dialog">
               <div class="modal-dialog" role="document" >			
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title"><b>Rubah Password</b></h4>
					</div>
					
					<div class="modal-body">							
					 
					 <!-- form start -->
						<form action="user_pelanggan_.php" method="post" enctype="multipart/form-data" name="form" id="form2" role="form">
						  <input type="hidden" name="j" value="d" />
						  <input type="hidden" name="id_tb_user" value="<?=$id;?>" />
						  <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
						
							<div class="form-group">
							  <input type="password" class="form-control" name="passwd1" placeholder="Password" required>
								<p style="color:red;">Password minimal 5 karakter & maksimal 12 karakter. Harus terdapat huruf besar, huruf kecil, angka & spesial karakter</p>
							</div> 
							<div class="form-group">
							  <input type="password" class="form-control" name="passwd2" placeholder="Confirm Password" required>
								
							</div> 
							<br>
						  <button type="submit" class="btn btn-primary">Simpan</button>
						  <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left:2px;">Batal</button>			 
					</form>
					<!-- /.form -->					
					
					</div>                   
					</div>
                </div>
            </div>
            <!-- #END# Modal Default Size -->
			
				   <!-- Modal Default Size -->
          <div class="modal fade" id="defaultModal1" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
				  	<div class="modal-header">
						<h4 class="modal-title"><b>Rubah Foto</b></h4>
					</div>
					<div class="modal-body">									
					 
					 <!-- form start -->
						<form action="user_pelanggan_.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
						  <input type="hidden" name="r" value="f" />
						  <input type="hidden" name="id_tb_user" value="<?=$id;?>" />
						  <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
						
							 <div class="input-group">							 
								<input type="file" class="custom-file-input" id="exampleInputFile"  name="pasfoto" required>
							 </div>
							 <p class="margin">Tipe file jpg/png, besar file maksimal 500kb, size file maksimal 400 x 400px.</p>
					  <br>
					  <button type="submit" class="btn btn-primary">Simpan</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left:2px;">Batal</button>		 
					</form>
					<!-- /.form -->					
					
					</div>                   
					</div>
                </div>
            </div>
            <!-- #END# Modal Default Size -->


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
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
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

<script>
  var xs = jQuery.noConflict();
  xs(function () {  

    // bootstrap WYSIHTML5 - text editor

    xs('.textarea').wysihtml5({
      toolbar: { fa: true, 
	  "font-styles": false,
	  link: false, // Button to insert a link.
      image: false, // Button to insert an image.
	  html: true
	  
	  }
    })
  })

  xs(".custom-file-input").on("change", function() {
	var fileName = xs(this).val().split("\\").pop();
	xs(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	}); 
</script>
</body>
</html>
<?php
//start session
@session_start();
//include config
include "include/config.php";
include "include/DbConnector.php";

$db = new DbConnector();
//Create token
$_SESSION['token'] = md5(uniqid(rand(), TRUE));
$kodeaman = $_SESSION['token'];
//Create reg code
$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$codereg = "";
for ($i = 0; $i < 10; $i++) {
    $codereg .= $chars[mt_rand(0, strlen($chars)-1)];
}

//print_r($joss);
?>
    

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>MAXI-LINE | Redefine Internet Services</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
	<!-- Site Icons -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="plugins/iCheck/square/blue.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="plugins/select2/select2.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<style>
	.register-page {
	   background : #1e385f;
	}
</style>
<body class="hold-transition register-page" style="background-image: url(dist/img/banner.png); background-repeat: no-repeat; background-position: center;background-size: cover;">
<div class="register-box">
	<div class="register-logo">
		<a href="index.php"><img src="dist/img/MaxiLine.png" style="width: 80%;"><p class="azmi"><b></b></p></a>
	</div>
	<!-- /.login-logo -->
	<div class="register-box-body">
		<!-- general form elements --> 
		<p class="login-box-msg" style="padding: 0 20px 0px 20px;"><b>Registrasi Maxi-Line</b></p>  
		<p class="text-sm mb-0" style="color:red;text-align:center;">Harap mengisi semua inputan dibawah ini.</p> 
		
		<!-- <p class="login-box-msg" style="padding: 0 20px 0px 20px;"><b>Cek jangkauan layanan</b></p>  
		<p class="text-sm mb-0" style="color:red;text-align:center;"></p> -->
		<!-- /.card-header -->
		<!-- form start -->
		<!-- <form class="form-horizontal" action="daftar.php" method="post" enctype="multipart/form-data" name="form" id="form" role="form">
			<input type="hidden" name="j" value="b" />				  
			<div class="input-group margin" style="margin:20px 0px 10px 0px;">
				<input type="text" class="form-control" name="lokasi" placeholder="Masukkan nama jalan lokasi rumah anda">
				<span class="input-group-btn">
					<button type="button" class="btn btn-info btn-flat">Cek</button>
				</span>
			</div>
		</form> -->
				
		<form action="daftar_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form" oninput='pass2.setCustomValidity(pass2.value != pass1.value ? "Passwords do not match." : "")'>		
			<input type="hidden" name="reg_code" value="<?=$codereg?>" />
			<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
			<input type="hidden" name="st_layanan" value="1" />
				
			<div class="form-group has-feedback">
				<select class="form-control select2" id="id_tb_lokasi" name="id_tb_lokasi" data-placeholder="Pilih lokasi" style="width: 100%;" required> 
				</select>
			</div>
				
			<!-- <div class="form-group has-feedback">
				<textarea class="form-control" name="alamat" placeholder="Ketik alamat" required style="resize: vertical;"></textarea>						
				<span class="form-control-feedback"><i class="fa fa-home"></i></span>
			</div>
				
			<div class="form-group has-feedback">
				<select class="form-control select2" name="id_tb_paket" data-placeholder="Pilih paket terlebih dahulu" id="paketnya" disabled required>						
				</select>
				<p class="text-sm mb-0" style="color:red;">* Harga paket adalah bulanan dan belum termasuk PPN </p>
			</div> -->

			<div class="form-group has-feedback">
				<select class="form-control select2" name="id_tb_paket" data-placeholder="Pilih paket terlebih dahulu" id="paketnya" disabled required>					
				</select>
			</div>

			<div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" class="form-control" name="harga_paket" id="harga_paket" placeholder="Biaya langganan per bulan" disabled required>
                <span class="input-group-addon">.00</span>
			</div>
			<p class="text-sm mb-0" style="color:red;">* Harga paket adalah bulanan dan belum termasuk PPN </p>
				
			<div class="form-group has-feedback">
				<select class="form-control" name="tipe_identitas" placeholder="Pilih identitas" required>
				<option style="color:#555;" value="">-- Pilih identitas --</option>
				<option style="color:#555;" value="1">KTP</option>
				<option style="color:#555;" value="2">SIM C</option>
				<option style="color:#555;" value="3">SIM A</option>
				</select>
			</div>
				
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="no_identitas" placeholder="Masukkan nomor identitas (angka saja)" required>
				<span class="form-control-feedback"><i class="fa fa-address-book"></i></span>
			</div>
				
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="nama" placeholder="Nama lengkap" required>
				<span class="form-control-feedback"><i class="fa fa-male"></i></span>
			</div>

			<div class="form-group has-feedback">
				<textarea class="form-control" name="alamat" placeholder="Ketik alamat" required style="resize: vertical;"></textarea>						
				<span class="form-control-feedback"><i class="fa fa-home"></i></span>
			</div>
			
			<div class="form-group has-feedback">
				<input type="email" class="form-control" name="email" placeholder="Email" required>
				<span class="form-control-feedback"><i class="fa fa-envelope"></i></span>
			</div>
				
			<div class="form-group has-feedback">
				<input type="number" class="form-control" name="telp" placeholder="Telepon" required>
				<span class="form-control-feedback"><i class="fa fa-phone"></i></span>
			</div>
				
			<div class="form-row">
				<div class="form-group has-feedback">		
					<img id="vimg" src="verificationimage.php?<?php echo rand(1000,9999); ?>" style="border:1px dotted #333; width: 30%;" alt="Kode verifikasi" align="absbottom" onClick="this.src='verificationimage.php?rand='+Math.random();"/>
				</div>
				<div class="form-group form-group has-feedback">
					<input type="text" class="form-control" id="capjay" name="capjay" required="required" placeholder="Isikan angka yg tertera diatas">
				</div>
			</div>

			<div class="checkbox">
				<label>
					<input type="checkbox" required> Setuju dengan syarat & aturan yang berlaku, dapat dilihat melalui link berikut <a href="../syaratdanaturan.php" target="_BLANK">Syarat & Aturan</a>
				</label>
			</div>
				
			<!-- /.card-body -->
			<div class="card-footer" style="padding: .75rem 0rem;">
				<button type="submit" class="btn btn-primary">Daftar</button>
				<button style="float:right;" type="reset" class="btn btn-default" onclick="window.location.href='../index.php'">Kembali</button>
			</div>				 
		</form>
	</div>
	<!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
	$(document).ready(function(){
		$("#id_tb_lokasi").select2({
			ajax: { 
				url: "daftar_lokasi.php",
				type: "post",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						searchTerm: params.term // search term
					};
				},
				processResults: function (response) {
					return {
						results: response
					};
				},
				cache: true
			}
		});
	
		$("#id_tb_lokasi").on('select2:select', function (e) {
			$("#paketnya").empty();
			var $newOption = $("<option selected='selected'></option>")
			$("#paketnya").append($newOption).trigger('change');
			$("#paketnya").prop("disabled", false);
			$("#harga_paket").val('');
		});
	});
	
	$(document).ready(function(){
		$("#paketnya").select2({
			ajax: { 
				url: "daftar_paket.php",
				type: "post",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						id : $('#id_tb_lokasi').val(),	
						searchTerm: params.term // search term
					};
				},
				processResults: function (response) {
					return {
						results: response
					};
				},
				cache: true
			}
		});

		// Event ketika dropdown berubah
		$('#paketnya').on('select2:select', function (e) {
			var data = e.params.data;
		
			if (data.harga) {
				$('#harga_paket').val(data.harga);
			} else {
				$('#harga_paket').val(data.text); // Contoh jika ingin menampilkan teks pilihan
			}
		});
	});
</script>
</body>
</html>


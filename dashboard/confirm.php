<?
@session_start();


include "include/config.php";
include "include/daftar_fungsi.php";
//include "include/session.php";
//include "include/timeout.php";
include "include/DbConnector.php";


if (empty($_SESSION['token'])) {
    $_SESSION['token'] = md5(uniqid(rand(), TRUE));
}
$kodeaman = $_SESSION['token'];

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
.azmi {	
		font-weight: 500;
		font-size: 20px;
		line-height: 1;
		margin-top:15px;		
	  }
.login-page {
	   background : #1e385f;
/*	   background : #272c5d; */
	  }
.dropdown-menu {
    top: 97% !important;
	  }
</style>
<body class="hold-transition login-page" style="background-image: url(dist/img/banner.png); background-repeat: no-repeat; background-position: center;background-size: cover;">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><img src="dist/img/MaxiLine.png" style="width: 80%;"><p class="azmi"><b></b></p></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Konfirmasi Pembayaran</p>

    <form action="confirm_.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />
	<input type="hidden" name="j" value="a" />
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Nama" name="nama" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Kode Invoice" name="kode_invoice">
        <span class="glyphicon glyphicon-text-color form-control-feedback"></span>
      </div>
	  <div class="form-group has-feedback">
        <input type="text" class="form-control datepicker" placeholder="Tanggal Pembayaran" name="tgl_transfer" required>
        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
      </div>
	  <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Bank" name="asal_bank" required>
        <span class="glyphicon glyphicon-home form-control-feedback"></span>
      </div>
	  <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="No. Rekening" name="asal_norek" required>
        <span class="glyphicon glyphicon-sort-by-alphabet form-control-feedback"></span>
      </div>
	  <div class="form-group has-feedback">
        <input type="number" class="form-control" placeholder="Nominal" name="jml_transfer" required>
        <span class="glyphicon glyphicon-usd form-control-feedback"></span>
      </div>
	  <div class="form-group has-feedback">
        <textarea class="form-control" placeholder="Keterangan" name="ket_transfer"></textarea>
        <span class="glyphicon glyphicon-text-size form-control-feedback"></span>		
      </div>
	  <div class="form-group has-feedback">
		  <label for="exampleInputFile">Upload bukti transfer</label>
		  <input type="file" id="exampleInputFile" name="file_confirm" required>
		  <p class="help-block">File format jpg, jpeg, png</p>
      </div>
	   <input type="hidden" class="form-control" name="jebakan">
      <div class="row">
       
        <!-- /.col -->
        <div class="col-xs-4" style="float: right;">
          <button type="submit" class="btn btn-primary btn-block btn-flat float-right" >Kirim</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
$(".datepicker").datepicker({
dateFormat: 'dd-mm-yyyy',
format: 'dd-mm-yyyy',
startDate: '-1m',
});
</script>
</body>
</html>


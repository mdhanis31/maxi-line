<?php
@session_start();

if(empty($_SESSION['token'])){
$_SESSION['token'] = md5(uniqid(rand(), TRUE));
}
$kodeaman = $_SESSION['token'];
//print_r($_SESSION);
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
.login-page {
	   background : #1e385f;
	  }
</style>
<body class="hold-transition login-page" style="background-image: url(dist/img/banner.png); background-repeat: no-repeat; background-position: center;background-size: cover;">
<div class="login-box">
  <div class="login-logo">
   <a href="index.php"><img src="dist/img/MaxiLine.png" style="width: 80%;"><p class="azmi"><b></b></p></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">   
		<p class="login-box-msg"><b>Lupa Password</b></p>
    <form method="post" action="login_.php" role="form">
      <input type="hidden" name="j" value="b" />
      <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />	
                
      <div class="form-group has-feedback">
        <div class="input-group-prepend">
          <span class="form-control-feedback"><i class="fa fa-envelope"></i></span>
        </div>
        <input type="email" class="form-control" name="email" placeholder="Masukkan email yang terdaftar" required autofocus>                        
      </div>
        
      <div class="form-row">
        <div class="form-group has-feedback">		
          <img id="vimg" src="verificationimage.php?<?php echo rand(1000,9999); ?>" style="border:1px dotted #333; width: 30%;" alt="Kode verifikasi" align="absbottom" onClick="this.src='verificationimage.php?rand='+Math.random();"/>
        </div>
        <div class="form-group form-group has-feedback">
          <input type="text" class="form-control" id="capjay" name="capjay" required="required" placeholder="Isikan angka yg tertera diatas">
        </div>
      </div>
                  
      <div class="form-group has-feedback">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button style="float:right;" type="reset" class="btn btn-default" onclick="window.location.href='index.php'">Kembali</button>
        <!-- /.col -->
      </div>
    </form>
    <!-- <p class="mb-1">
      <a href="index.php" class="text-center">Kembali</a>
    </p>	-->
  </div>
</div>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/sign-in.js"></script>
</body>

</html>
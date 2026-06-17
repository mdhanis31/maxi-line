<?php
@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";

$db = new DbConnector();

if(empty($_SESSION['token'])){
$_SESSION['token'] = md5(uniqid(rand(), TRUE));
}

$kodeaman = $_SESSION['token'];
//print_r($_SESSION);

$maskcdauth = SafeSQL($_GET[i]);
$cdauth = maxiline( $maskcdauth, 'd' );
list($id_tb_user, $email, $waktu) = explode (",", $cdauth);

$sekarang = date("Y-m-d H:i:s");
$nanti = date("Y-m-d H:i:s", strtotime ("+1 hour", strtotime($waktu)));

//echo $id_tb_user."<br>";
//echo $email."<br>";
//echo $waktu."<br>";
//echo $sekarang."<br>";
//echo $nanti."<br>";
//exit;

$sqlb = "SELECT * FROM tb_user WHERE id_tb_user = '$id_tb_user' and email = '$email'";	
$resb = $db->query($sqlb);
$rowb = $db->fetchArray($resb);

if(empty($rowb['id_tb_user'])) {
 ?><script>
      alert('Akun tidak ditemukan!');
	  location.href='daftar.php';
      </script><?php
	  exit();	
} else if($nanti < $sekarang) {
 ?><script>
      alert('Kode reset sudah expired, silahkan mengulangi proses reset password anda!');
	  location.href='lupa_pass.php';
      </script><?php
	  exit();	
}

//echo $id_tb_user."<br>";
//echo $email."<br>";
//echo $waktu."<br>";
//exit;

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
</style>
<body class="hold-transition login-page" style="background-image: url(dist/img/banner.png); background-repeat: no-repeat; background-position: center;background-size: cover;">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><img src="dist/img/MaxiLine.png" style="width: 80%;"><p class="azmi"><b></b></p></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><b>Reset Password</b></p>
    <form id="sign_in" method="post" action="user_aksi.php" role="form">
      <input type="hidden" name="j" value="d">
      <input type="hidden" name="id_tb_user" value="<?php echo $id_tb_user;?>">
      <input type="hidden" name="token" value="<?php echo $kodeaman; ?>" />	
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="passwd1" placeholder="Masukkan password baru" required autofocus>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
					
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="passwd2" placeholder="Masukkan ulang password baru" required autofocus>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
					
      <div class="form-row">
        <div class="form-group has-feedback">		
          <img id="vimg" src="verificationimage.php?<?php echo rand(1000,9999); ?>" style="border:1px dotted #333; width: 30%;" alt="Kode verifikasi" align="absbottom" onClick="this.src='verificationimage.php?rand='+Math.random();"/>
        </div>
        <div class="form-group form-group has-feedback">
          <input type="text" class="form-control" id="capjay" name="capjay" required="required" placeholder="Isikan angka yg tertera diatas">
        </div>
      </div>
                   
      <div class="row">
        <div class="col-8">
        </div>
        <!-- /.col -->
        <div class="col-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Reset</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
</div>
   
	
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
</body>

</html>
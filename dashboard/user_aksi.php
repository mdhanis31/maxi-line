<?php
@session_start();
include "include/config.php";
include "include/DbConnector.php";
$db = new DbConnector();
//include "../include/config.php";

$kodeaman = $_SESSION['token'];
$id_user = $_SESSION['id_tb_user'];

$id_tb_user = SafeSQL($_POST['id_tb_user']);
$nm_user = SafeSQL($_POST['nm_user']);
$telp = SafeSQL($_POST['telp']);
$email = SafeSQL($_POST['email']);
$level_user = SafeSQL($_POST['level_user']);
$username = SafeSQL($_POST['username']);
$passwd1 = SafeSQL($_POST['passwd1']);
$passwd2 = SafeSQL($_POST['passwd2']);
$passe = md5($_POST['passwd1']);
$pass1 = md5($_POST['passwd2']);
$alamat = SafeSQL($_POST['alamat']);
$jabatan = SafeSQL($_POST['jabatan']);
$id_tb_pelanggan = $_SESSION['id_tb_pelanggan'];
//print_r($_POST)."<br>";
//echo $passe."<br>" ;
//echo $pass1;
//exit();


$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$kd_book = "";
for ($i = 0; $i < 5; $i++) {
	$kd_book .= $chars[mt_rand(0, strlen($chars)-1)];
}

if($_POST['j']=='d') {
	
	if(md5($_POST['capjay']).'a4xn' != $_COOKIE['maxilinecookie']) {
	?><script>
	  alert('Captcha Salah');
	  history.back();
	  </script><?php
	  exit();
	}

	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {
	
	if($passwd1!=$passwd2) {
    ?><script>
      alert('Konfirmasi password tidak sama!');
      history.back();
      </script><?php
	  exit();
	} ELSE if($passwd1==$passwd2 && strlen($passwd1) < 5) {
		?><script>
		alert('password minimal 6 karakter!');
		history.back();
		</script><?php
		exit();
	} else if($passwd1==$passwd2 && strlen($passwd1) > 12 ) {
		?><script>
		alert('password maksimal 12 karakter!');
		history.back();
		</script><?php
		exit();
	} else {
		
	$sql = $db->query("update tb_user set password='$passe' where id_tb_user='$id_tb_user'");

	if ($sql) {
		echo " <script>alert('Update password berhasil!');</script>
		<script>location.href='login.php';</script>";
		exit();	   	
	} else { 
	echo " <script>alert('Update password gagal!');</script>
	 <script>history.back();</script>";
	exit();
	 }
	}	 
   } else {
   // log potential CSRF attack.
	echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	<script>history.back();</script>";
	exit();
	} 
} 
?>
	


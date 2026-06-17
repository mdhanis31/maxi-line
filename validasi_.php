<?php

@session_start();
//print_r($_SESSION);
include "app/inc/config.php";
include "app/inc/DbConnector.php";
include "app/inc/funct.php";

//exit();
$db = new DbConnector();

$reg_code = SafeSQL($_POST['reg_code']);
$email = SafeSQL($_POST['email']);

//print_r($_POST);
//exit();

if ($_POST[j] == 'a') {
//---------------------------------	
	if(empty($email)) {
    ?><script>
      alert('Kode harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if((!empty($email)) && (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
    ?><script>
      alert('email tidak valid!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($reg_code)) {
    ?><script>
      alert('Code Registrasi harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	$sql = "SELECT * FROM tb_pelanggan where reg_code = '$reg_code' and email = '$email'";
	$res = $db->query($sql);
	$jml = $db->hasRows($res);
	//echo $jml;
	//exit();
	if ($jml == 0) {
    ?><script>
      alert('kode registrasi tidak ditemukan!');
	  history.back();
      </script><?php
	  exit();
	} else {
	if ($row['status_reg'] == 2) {
    ?><script>
      alert('Registrasi anda sudah tervalidasi!');
	  location.href='app/admin/login.php';
      </script><?php
	  exit();
	} else if ($row['status_reg'] == 3) {
    ?><script>
      alert('Kode registrasi tidak ditemukan!');
	  history.back();
      </script><?php
	  exit();
	} else {
	
	// insert database
	$sqla = "UPDATE tb_pelanggan SET status_reg = '2' WHERE reg_code = '$reg_code' and email = '$email'";
	$resa = $db->query($sqla);
	 
	$sqlb = "SELECT * FROM tb_pelanggan WHERE reg_code = '$reg_code' and email = '$email'";	
	$resb = $db->query($sqlb);
	$rowb = $db->fetchArray($resb);
	 
	$sqlc = "insert into tb_user (id_tb_pelanggan, username, password, nm_user, alamat, email, telp, level_user) values ('$rowb[id_tb_pelanggan]', '$rowb[username]', '$rowb[passwd]', '$rowb[nm_user]', '$rowb[alamat]', '$rowb[email]', '$rowb[telp]', '1')";
	$resc = $db->query($sqlc);
	 
	 if ($resc) {
	 echo " <script>alert('Registrasi berhasil, silahkan login!');</script>
			<script>location.href='app/login.php';</script>";		
			exit();
			} else { 
	 echo " <script>alert('registrasi gagal silahkan menghubungi customer service kami!');</script>
			<script>history.back();</script>";
			exit();
			}	
	// insert database
	}
	 }
	  } 
	   } else {
	// update via email
	$maskcdauth = SafeSQL($_GET[i]);
	$cdauth = maksiaunth( $maskcdauth, 'd' );
	list($reg_code, $email) = explode (",", $cdauth);
//	echo $reg_code."<br>";
//	echo $email;
//	exit;
	
	// insert database	
	
	$sqlb = "SELECT * FROM tb_pelanggan WHERE reg_code = '$reg_code' and email = '$email'";	
	$resb = $db->query($sqlb);
	$rowb = $db->fetchArray($resb);
	
/* 	echo $rowb[status_reg]."<br>";
	echo $rowb[id_tb_pelanggan]."<br>";
	echo $rowb[username]."<br>";
	echo $rowb[passwd]."<br>";
	echo $rowb[nama_user]."<br>";
	echo $rowb[alamat]."<br>";
	echo $rowb[email]."<br>";
	echo $rowb[telepon]."<br>";
	exit; */
	
	if ($rowb['status_reg'] == 2) {
   echo " <script>alert('Registrasi anda sudah tervalidasi!');</script>
		  <script>location.href='app/login.php';</script>";				  
	  exit();
	} else {
		
	$sqla = "UPDATE tb_pelanggan SET status_reg = '2' WHERE reg_code = '$reg_code' and email = '$email'";	
	$resa = $db->query($sqla);
	
	$sqlc = "insert into tb_user (id_tb_pelanggan, username, password, nm_user, alamat, email, telp, level_user) values ('$rowb[id_tb_pelanggan]', '$rowb[username]', '$rowb[passwd]', '$rowb[nama_user]', '$rowb[alamat]', '$rowb[email]', '$rowb[telepon]', '1')";
	$resc = $db->query($sqlc);
	}
	
	if ($resc) {
	echo " <script>alert('Registrasi berhasil, silahkan login!');</script>
		   <script>location.href='app/login.php';</script>";		
		   exit();
		   } else { 
	echo " <script>alert('registrasi gagal silahkan menghubungi customer service kami!');</script>
	       <script>history.back();</script>";
		   exit();
		   }	
	// insert database
	// update via email
  }
?>   
<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");

$biaya_pasang = SafeSQL($_POST['biaya_pasang']);
$id_tb_biaya_pasang = SafeSQL($_POST['id_tb_biaya_pasang']);

//print_r($_POST)."<br>";
//exit();
if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
//---------------------------------	
	if(empty($biaya_pasang)) {
    ?><script>
      alert('Nominal biaya pemasangan harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	$sqla = $db->query ("insert into tb_biaya_pasang (biaya_pasang) values ('$biaya_pasang')");
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Data berhasil disimpan!');</script>
			  <script>location.href='pemasangan_add.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Data gagal disimpan!');</script>
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
   } else if ($_POST['j'] == 'b') {
   
    unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {  
	
//---------------------------------	
if(empty($biaya_pasang)) {
    ?><script>
      alert('Nominal biaya pemasangan  harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
		
	$sqla = $db->query ("update tb_biaya_pasang set biaya_pasang='$biaya_pasang'");
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Data berhasil diupdate!');</script>
			  <script>location.href='pemasangan_add.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Data gagal diupdate!');</script>
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
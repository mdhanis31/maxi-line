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

$nama_paket = SafeSQL($_POST['nama_paket']);
$harga_paket = SafeSQL($_POST['harga_paket']);
$isi_paket = SafeSQL($_POST['isi_paket']);
$paket_promo = SafeSQL($_POST['paket_promo']);
$is_hidden = SafeSQL($_POST['is_hidden']);
$id_paket_utama = SafeSQL($_POST['id_paket_utama']);
$id_tb_paket = SafeSQL($_POST['id_tb_paket']);

// print_r($_POST)."<br>";
// exit();

if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();

if ($kodeaman && $_POST['token']==$kodeaman) {
	
	//---------------------------------	
	if(empty($nama_paket)) {
    ?><script>
      alert('Nama paket harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($harga_paket)) {
    ?><script>
      alert('Harga harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($isi_paket)) {
    ?><script>
      alert('fitur/isi paket tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
		
	if($paket_promo == 2){$id_utama = $id_paket_utama;} else {$id_utama = "0";}
	
	$sqla ="insert into tb_paket (nama_paket, harga_paket, isi_paket, paket_promo, id_paket_utama, is_hidden) values ('$nama_paket', '$harga_paket', '$isi_paket', '$paket_promo', '$id_utama', '$is_hidden')";
	$resa =  $db->query($sqla);
	
	// print_r($sqla);
	// exit();

		// insert database
		if ($resa) {	 
			echo "<script>alert('Data berhasil disimpan!');</script>
				<script>location.href='paket_v.php';</script>";
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
	if(empty($nama_paket)) {
    ?><script>
      alert('Nama paket harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($harga_paket)) {
    ?><script>
      alert('Harga harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($isi_paket)) {
    ?><script>
      alert('fitur/isi paket tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
		
	if($paket_promo == 2){$id_utama = $id_paket_utama;} else {$id_utama = "0"; }	
		
	$sqla = $db->query ("update tb_paket set nama_paket='$nama_paket', harga_paket='$harga_paket', isi_paket='$isi_paket', paket_promo='$paket_promo', id_paket_utama='$id_utama', is_hidden='$is_hidden' where id_tb_paket='$id_tb_paket'");
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Data berhasil diupdate!');</script>
			  <script>location.href='paket_v.php';</script>";
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
} else if ($_GET['b'] == 'c') {

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_GET['token']==$kodeaman) {

	$id_tb_paket = maxiline(SafeSQL($_GET['id']),'d');
	$sqld = "Update tb_paket set sts_delete = '2' where id_tb_paket='$id_tb_paket'";
    $resd = $db->query($sqld);	
	
    if ($sqld) {	 
		echo "<script>alert('Data berhasil dihapus!');</script>
			  <script>location.href='paket_v.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Data gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 

 } else {
// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
 } 
}
?>   
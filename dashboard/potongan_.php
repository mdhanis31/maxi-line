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

$nama_potongan = SafeSQL($_POST['nama_potongan']);
$satuan = SafeSQL($_POST['satuan']);
$jumlah = SafeSQL($_POST['jumlah']);
$status = SafeSQL($_POST['status']);
$jns_potongan = SafeSQL($_POST['jns_potongan']);
$ket_potongan = SafeSQL($_POST['ket_potongan']);

$id_tb_potongan = SafeSQL($_POST['id_tb_potongan']);
$jns_potongans = maxiline($jns_potongan, 'e');
//print_r($_POST)."<br>";
//echo $jns_potongans;
//exit();
if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
//---------------------------------	
	if(empty($nama_potongan)) {
    ?><script>
      alert('Nama harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($satuan)) {
    ?><script>
      alert('Satuan harus dipilih!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($jumlah)) {
    ?><script>
      alert('Jumlah potongan harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	$sqla = $db->query ("insert into tb_potongan (nama_potongan, satuan, jumlah, status, ket_potongan, id_tb_pelanggan, jns_potongan) values ('$nama_potongan', '$satuan', '$jumlah', '$status', '$ket_potongan', '$id_tb_pelanggan', '$jns_potongan')");
	// insert database
	if ($sqla) {
	$jns_potongans = maxiline($jns_potongan, 'e');
		echo "<script>alert('Data berhasil disimpan!');</script>
			  <script>location.href='potongan_v.php?idx=$jns_potongans';</script>";
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
	if(empty($nama_potongan)) {
    ?><script>
      alert('Nama harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($satuan)) {
    ?><script>
      alert('Satuan harus dipilih!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($jumlah)) {
    ?><script>
      alert('Jumlah potongan harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
		
	$sqla = $db->query ("update tb_potongan set nama_potongan='$nama_potongan', satuan='$satuan', jumlah='$jumlah', status='$status', ket_potongan='$ket_potongan' where id_tb_potongan='$id_tb_potongan'");
	// insert database
	if ($sqla) {
	$jns_potongans = maxiline($jns_potongan, 'e');
		echo "<script>alert('Data berhasil diupdate!');</script>
			  <script>location.href='potongan_v.php?idx=$jns_potongans';</script>";
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

	$id_tb_potongan = SafeSQL($_GET['id']);
	$sqld = "delete from tb_potongan where id_tb_potongan='$id_tb_potongan'";
    $resd = $db->query($sqld);
    if ($sqld) {
		echo "<script>alert('Data berhasil dihapus!');</script>
			  <script>location.href='potongan_v.php?idx=$_GET[idx]';</script>";
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
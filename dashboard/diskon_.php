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

$tipenya = SafeSQL($_POST['tipenya']);
$tglaw = SafeSQL($_POST['tglawal']);
$tglak = SafeSQL($_POST['tglakhir']);
$jumlah = SafeSQL($_POST['nominal']);
$satuan = SafeSQL($_POST['satuan']);
$banyaknya = SafeSQL($_POST['jumlah']);
$ket_voucher = SafeSQL($_POST['ket_voucher']);
$id_tb_voucher = SafeSQL($_POST['id_tb_voucher']);

$id_tb_pelanggan = $_SESSION['id_tb_pelanggan'];

$tglawal = date("Y-m-d", strtotime($tglaw));
$tglakhir = date("Y-m-d", strtotime($tglak));
$nama_voucher = SafeSQL($_POST['nama_voucher']);
$kodenya = SafeSQL($_POST['kodenya']);
$taon = SafeSQL($_POST['taon']);
$noe = SafeSQL($_POST['noe']);
$jmlsisa = SafeSQL($_POST['jmlsisa']);
$jmlall = SafeSQL($_POST['jmlall']);

//print_r($_POST)."<br>";
//exit();

if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
//---------------------------------	
	if(empty($nama_voucher) and $tipenya == 1) {
    ?><script>
      alert('Nama voucher harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($kodenya) and $tipenya == 2) {
    ?><script>
      alert('Kode voucher harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($tglawal)) {
    ?><script>
      alert('Tanggal berlaku harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($tglakhir)) {
    ?><script>
      alert('Tanggal expired harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	if ($_POST['d'] == 'p') {
	for ($i = 0; $i < $banyaknya; $i++){
    $sqla = $db->query ("insert into tb_voucher (nama_voucher, tipenya, ket_voucher, tglawal, tglakhir, jumlah, satuan, id_tb_pelanggan) values ('$nama_voucher', '$tipenya', '$ket_voucher', '$tglawal', '$tglakhir', '$jumlah', '$satuan', '$id_tb_pelanggan')");
	 }	
	} else if ($_POST['d'] == 'v') {
	for ($i = 0; $i < $banyaknya; $i++){
	$lastcode = $noe + $i;
	$noreg = sprintf('%03d', $lastcode);
	$nmcode = $kodenya.$taon.$noreg;
    $sqla = $db->query ("insert into tb_voucher (nama_voucher, tipenya, ket_voucher, tglawal, tglakhir, jumlah, satuan, id_tb_pelanggan) values ('$nmcode', '$tipenya', '$ket_voucher', '$tglawal', '$tglakhir', '$jumlah', '$satuan', '$id_tb_pelanggan')");
	 }	
	}
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Data berhasil disimpan!');</script>
			  <script>location.href='diskon_v.php';</script>";
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
	if(empty($nama_voucher) and $tipenya == 1) {
    ?><script>
      alert('Nama voucher harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($kodenya) and $tipenya == 2) {
    ?><script>
      alert('Kode voucher harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($tglawal)) {
    ?><script>
      alert('Tanggal berlaku harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($tglakhir)) {
    ?><script>
      alert('Tanggal expired harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	if ($_POST['d'] == 'p') {
	if($jmlsisa == $jumlah) {$jmlvoucher = $jumlah;}
	else if($jmlsisa > $jumlah) {$jmlvou = $jmlsisa - $jumlah; $jmlvoucher =  $jmlall -  $jmlvou;}		
	else if($jmlsisa < $jumlah) {$jmlvou = $jumlah - $jmlsisa; $jmlvoucher =  $jmlall +  $jmlvou;}		
	
	$sqla = $db->query("update tb_voucher set nama_voucher = '$nama_voucher', ket_voucher = '$ket_voucher', tglawal = '$tglawal', tglakhir = '$tglakhir', jumlah = '$jmlvoucher', nominal = '$nominal', satuan = '$satuan' where id_tb_voucher = '$id_tb_voucher'");
	} else if ($_POST['d'] == 'v') {
	$sqla = $db->query("update tb_voucher set ket_voucher = '$ket_voucher', tglawal = '$tglawal', tglakhir = '$tglakhir', nominal = '$nominal', satuan = '$satuan' where id_tb_voucher = '$id_tb_voucher'");
    }
	
	if ($sqla) {	 
		echo "<script>alert('Data berhasil diupdate!');</script>
			  <script>location.href='diskon_v.php';</script>";
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

	$id_tb_voucher = SafeSQL($_GET['id']);
	$sqld = "delete from tb_voucher where id_tb_voucher='$id_tb_voucher'";
    $resd = $db->query($sqld);
    if ($sqld) {	 
		echo "<script>alert('Data berhasil dihapus!');</script>
			  <script>location.href='diskon_v.php';</script>";
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
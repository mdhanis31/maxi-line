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

$judul = SafeSQL($_POST['judul']);
$note = $_POST['note'];
$id_tb_note = SafeSQL($_POST['id_tb_note']);


//print_r($_POST)."<br>";
//exit();
if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
//---------------------------------	
	if(empty($judul)) {
    ?><script>
      alert('Judul harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($note)) {
    ?><script>
      alert('Isi note tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	$sqla = $db->query ("insert into tb_note (judul, note) values ('$judul', '$note')");
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Data berhasil disimpan!');</script>
			  <script>location.href='note_v.php';</script>";
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
	if(empty($judul)) {
    ?><script>
      alert('Judul harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($note)) {
    ?><script>
      alert('Isi note tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
	$sqla = $db->query ("update tb_note set judul='$judul', note='$note' where id_tb_note='$id_tb_note'");
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Data berhasil diupdate!');</script>
			  <script>location.href='note_v.php';</script>";
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

	$id_tb_note = maxiline(SafeSQL($_GET['id']), 'd');
	$sqld = "delete from tb_note where id_tb_note='$id_tb_note'";
    $resd = $db->query($sqld);
    if ($sqld) {	 
		echo "<script>alert('Data berhasil dihapus!');</script>
			  <script>location.href='note_v.php';</script>";
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
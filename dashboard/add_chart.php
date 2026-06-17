<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");

$id_tr_invoice = maxiline(SafeSQL($_GET['id']), 'd');
$id_tr_keranjang = maxiline(SafeSQL($_GET['id']), 'd');

//print_r($_POST)."<br>";
//exit();
if ($_GET['j'] == 'a') {
//---------------------------------	
	
	$sqla ="insert into tr_keranjang (id_tr_invoice, id_tb_user) values ('$id_tr_invoice', '$_SESSION[id_tb_user]')";
	$resa =  $db->query($sqla);
	
	//print_r($sqla)."<br>";
	//exit();
	// insert database
	if ($resa) {	 
		echo "<script>alert('Berhasil ditambahkan ke keranjang belanja!');</script>
			  <script>location.href='online_pay.php?#total';</script>";
			  exit();
		} else { 
		echo " <script>alert('Gagal ditambahkan ke keranjang belanja!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
   } else if ($_GET['j'] == 'b') {
//---------------------------------	
	
	$sqla ="update tr_keranjang set id_tb_user = '$_SESSION[id_tb_user]', st_keranjang = '2' where id_tr_keranjang = '$id_tr_keranjang' ";
	$resa =  $db->query($sqla);
	
	//print_r($sqla)."<br>";
	//exit();
	// insert database
	if ($resa) {	 
		echo "<script>alert('Berhasil ditambahkan ke keranjang belanja!');</script>
			  <script>location.href='online_pay.php?#total';</script>";
			  exit();
		} else { 
		echo " <script>alert('Gagal ditambahkan ke keranjang belanja!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
   } else if ($_GET['j'] == 'c') {
	$sqla ="update tr_keranjang set id_tb_user = '$_SESSION[id_tb_user]', st_keranjang = '1' where id_tr_keranjang = '$id_tr_keranjang' ";
	$resa =  $db->query($sqla);
	
	//print_r($sqla)."<br>";
	//exit();
	// insert database
	if ($resa) {	 
		echo "<script>alert('Berhasil dihilangkan dari keranjang belanja!');</script>
			  <script>location.href='online_pay.php?#total';</script>";
			  exit();
		} else { 
		echo " <script>alert('Gagal dihilangkan dari keranjang belanja!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
	} else {
		echo " <script>alert('Transaksi gagal!!');</script>
		<script>history.back();</script>";
		exit();	
	}	
?>   
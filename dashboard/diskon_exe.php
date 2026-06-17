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

$kode = SafeSQL($_POST['kode']);
$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);

//print_r($_POST)."<br>";
//exit();

if ($_POST['cek'] == 'voucher') {

//print_r($_POST)."<br>";
//exit;
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
if(empty($kode)) {
    ?><script>
      alert('Kode Voucher tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
		
	$sql2 = $db->fetchArray($db->query("select * from tb_voucher where nama_voucher ='$kode' and tipenya = '2' and status_voucher != '3'"));
	if(!empty($sql2['id_tb_voucher'])) {
	$sql3 = $db->fetchArray($db->query("select * from tr_voucher where id_tb_voucher = '$sql2[id_tb_voucher]'"));
		if(!empty($sql3['id_tr_voucher'])) {
		 ?><script>
		  alert('Kode Voucher sudah dipakai!');
		  history.back();
		  </script><?php
		  exit();	
		} else {
		 $sql4 = $db->fetchArray($db->query("select * from tb_voucher where id_tb_voucher ='$sql2[id_tb_voucher]'")); 
		 if($sql4['satuan'] == "%"){ $sisa = 1;} else { $sisa = $sql4['nominal'];}
		 $sql5 = $db->query("insert into tr_voucher (id_tb_pendaftaran, id_tb_voucher, nama_voucher, jumlah, satuan, sisa) values ('$id_tb_pendaftaran', '$sql2[id_tb_voucher]', '$sql4[nama_voucher]','$sql4[nominal]','$sql4[satuan]', '$sisa')");
		 
			if ($sql5) {	 
			echo "<script>alert('Voucher telah ditambahkan!');</script>
			<script>location.href='voucher_pelanggan.php';</script>";
			exit();
			} else { 
			echo "<script>alert('Voucher gagal ditambahkan, hubungi admin maxi-cloud!');</script>
			<script>history.back();</script>";
			exit();
			} 
		}	
	} else {
	 ?><script>
      alert('Kode Voucher tidak ditemukan, cek kembali kode voucher anda!');
      history.back();
      </script><?php
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
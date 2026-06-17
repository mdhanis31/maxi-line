<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
// include "include/session.php";
include "include/DbConnector.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");
$waktu = $now->format("H:i:s");

$nama = SafeSQL($_POST['nama']);
$kode_invoice = SafeSQL($_POST['kode_invoice']);
$tgl_transfers = SafeSQL($_POST['tgl_transfer']);
$asal_bank = SafeSQL($_POST['asal_bank']);
$asal_norek = SafeSQL($_POST['asal_norek']);
$jml_transfer = SafeSQL($_POST['jml_transfer']);
$ket_transfer = SafeSQL($_POST['ket_transfer']);
$jebakan = SafeSQL($_POST['jebakan']);

$tgl_transfer = date("Y-m-d", strtotime($tgl_transfers))." ".$waktu;
//print_r($_POST)."<br>";
//exit();

if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	$sqlb = $db->fetchArray($db->query("select * from tr_invoice where no_invoice = '$kode_invoice' and sts_invoice = '2' and sts_lunas = '1'"));
	
	if (empty($sqlb['id_tr_invoice'])) {	 
		echo "<script>alert('Nomor invoice tidak ditemukan / sudah expired!');</script>
			  <script>history.back();</script>";
			  exit();
	} 
	
	//---------------------------------
	if(empty($nama)) {
    ?><script>
      alert('Nama harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(!empty($jebakan)) {
    ?><script>
      alert('Periksa kembali inputan anda!');
      history.back();
      </script><?php
	  exit();
	} else {
		
		
	$namepp = $_FILES['file_confirm']['name'];
	$temp = $_FILES['file_confirm']['tmp_name'];    
    $type = $_FILES['file_confirm']['type'];
    $size = $_FILES['file_confirm']['size'];
    	
	$permissible_extension = array("jpg", "png", "jpeg", "pdf", "PDF");
	$ext = pathinfo($namepp, PATHINFO_EXTENSION);
	$test = getimagesize($temp);
    $width = $test[0];
    $height = $test[1];
	
	if (empty($_FILES)) {	
	?><script>
	alert('Pilih gambar yg akan diupload!');
	history.back();
	</script><?php
	exit();
	} else if (!in_array($ext, $permissible_extension)) {	
	?><script>
	alert('File harus dalam format jpg/png/jpeg/pdf!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/file_confirm/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
	exit();
	}		
		
	$sqla = $db->query ("insert into tr_confirm (nama, kode_invoice, tgl_transfer, asal_bank, asal_norek, jml_transfer, ket_transfer) values ('$nama', '$kode_invoice', '$tgl_transfer', '$asal_bank', '$asal_norek', '$jml_transfer', '$ket_transfer')");
	
	$sqlb = "SELECT SCOPE_IDENTITY() as lastdaftar from tr_confirm";
	$resb = $db->query($sqlb);
	$rowb = $db->fetchArray($resb);	
	
	$new_nama_file = date('Ymd'). '_' . $namepp;
	rename("dist/file_confirm/$namepp", "dist/file_confirm/$new_nama_file");
	
	$sqlb = $db->query ("insert into tb_file_confirm (link_gbr, id_tr_confirm) values ('$new_nama_file', '$rowb[lastdaftar]')");
			
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Konfirmasi berhasil dikirim!');</script>
			  <script>location.href='../index.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Konfirmasi gagal dikirim!');</script>
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
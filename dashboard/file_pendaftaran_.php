<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";
//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$id_tb_user = $_SESSION['id_tb_user'];

$id_tb_proses = SafeSQL($_POST['id_tb_proses']);
$st_proses = SafeSQL($_POST['st_proses']);
$ket_gbr = SafeSQL($_POST['ket_gbr']);
$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);

$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$kd_dok = "";
for ($i = 0; $i < 3; $i++) {
    $kd_dok .= $chars[mt_rand(0, strlen($chars)-1)];
}
//print_r($_POST)."<br>";
//exit();
if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
//---------------------------------	
	
	if(isset($_FILES['file_pendaftaran']['tmp_name'])) {
	//echo "upload";
	//exit;
	$fileCount = count($_FILES['file_pendaftaran']['tmp_name']);
	//echo "$fileCount";
	//exit;		
	for ($i = 0; $i < $fileCount; $i++) {
	
	$namepp = $_FILES['file_pendaftaran']['name'][$i];
	$temp = $_FILES['file_pendaftaran']['tmp_name'][$i];    
    $type = $_FILES['file_pendaftaran']['type'][$i];
    $size = $_FILES['file_pendaftaran']['size'][$i];
    	
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
	alert('File harus dalam format jpg, png, jpeg, pdf atau PDF!');
	history.back();
	</script><?php
	exit();
	} else  if ($size >= 15000000) {	
	?><script>
	alert('Size file dokumen tidak boleh lebih dari 15Mb!');
	history.back();
	</script><?php
	exit();
	} else if ($width > 3000 || $height > 3000) {
	?><script>
	alert('Ukuran file tidak boleh lebih dari 3000x3000 pixels!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/file_pendaftaran/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
	exit();
	} else {
	$namepps = substr("$namepp", 0, -4);
	$ext = substr("$namepp", -4);
	$newnamepp = $namepps.$kd_dok.$ext;
	rename("dist/file_pendaftaran/$namepp","dist/file_pendaftaran/$newnamepp");
	
	
	//---------- input db
	$sqlb = $db->query ("insert into tb_file_pendaftaran (id_tb_proses, st_proses, link_gbr, ket_gbr, id_tb_user) values ('$id_tb_proses', '$st_proses', 'dist/file_pendaftaran/$newnamepp','$ket_gbr', '$id_tb_user')");
	//echo "$sqlb";
	//exit;
	}
   }
	if ($sqlb) {
		$idne = maxiline($id_tb_pendaftaran, 'e');
		echo "<script>alert('File berhasil diupload!');</script>
			  <script>location.href='pendaftaran_dtl.php?id=$idne';</script>";
			  exit();
		} else { 
		echo " <script>alert('File gagal diupload!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
  } else {
		echo " <script>alert('Pilih file yang akan diupload!');</script>
			   <script>history.back();</script>";
				exit();
	  }
	} else {
   // log potential CSRF attack.
	  echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	  <script>history.back();</script>";
	  exit();
	}	
   } else if ($_GET['g'] == 'h') {

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_GET['token']==$kodeaman) {

	$id_tb_file_pendaftaran = maxiline(SafeSQL($_GET['id']), 'd');
	$id_tb_pendaftaran = maxiline(SafeSQL($_GET['i']), 'd');
	
	//echo $id_tb_file_pendaftaran."<br>";
	//echo $id_tb_pendaftaran."<br>";
	//exit;
	
	$sqla = $db->fetchArray($db->query("SELECT * from tb_file_pendaftaran where id_tb_file_pendaftaran = '$id_tb_file_pendaftaran'"));
	unlink("$sqla[link_gbr]");
	$sqlb = "delete from tb_file_pendaftaran where id_tb_file_pendaftaran='$id_tb_file_pendaftaran'";
    $resb = $db->query($sqlb);
    if ($sqlb) {
		$idne = maxiline($id_tb_pendaftaran, 'e');
		echo "<script>alert('File berhasil dihapus!');</script>
			  <script>location.href='pendaftaran_dtl.php?id=$idne';</script>";
			  exit();
		} else { 
		echo " <script>alert('File gagal dihapus!');</script>
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
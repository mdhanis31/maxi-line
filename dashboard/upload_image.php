<?php
@session_start();

include "include/session.php";
include "include/DbConnector.php";
include "include/config.php";
$db = new DbConnector();

$id_tb_proses = SafeSQL($_POST['id_tb_proses']);
$st_proses = SafeSQL($_POST['st_proses']);
$ket_gbr = SafeSQL($_POST['ket_gbr']);
$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);

$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$kd_dok = "";
for ($i = 0; $i < 3; $i++) {
    $kd_dok .= $chars[mt_rand(0, strlen($chars)-1)];
}

if(isset($_FILES['imagenya']['tmp_name'])) {
	
	$namepp = $_FILES['imagenya']['name'];
	$temp = $_FILES['imagenya']['tmp_name'];    
    $type = $_FILES['imagenya']['type'];
    $size = $_FILES['imagenya']['size'];
    	
	$permissible_extension = array("jpg", "png", "jpeg", "pdf", "PDF");
	$ext = pathinfo($namepp, PATHINFO_EXTENSION);
	$test = getimagesize($temp);
    $width = $test[0];
    $height = $test[1];
	
	if (empty($_FILES)) {
    echo json_encode(['status' => '2', 'error' => 'File harus dipilih']);
	exit();
	} else if (!in_array($ext, $permissible_extension)) {
    echo json_encode(['status' => '2', 'error' => 'File harus dalam format jpg, jpeg, png, pdf atau PDF!']);	
	exit();
	} else  if ($size >= 15000000) {
    echo json_encode(['status' => '2', 'error' => 'Size file dokumen tidak boleh lebih dari 15MB!']);	
	exit();
	} else if ($width > 3000 || $height > 3000) {
    echo json_encode(['status' => '2', 'error' => 'Ukuran file tidak boleh lebih dari 3000x3000 pixels!']);
	exit();
	} else if (!move_uploaded_file($temp, "dist/file_pendaftaran/" . $namepp)) {
    echo json_encode(['status' => '2', 'error' => 'File tidak bisa diupload!']);	
	exit();
	} else {	
	$newnamepp = $kd_dok.$namepp;
	rename("dist/file_pendaftaran/$namepp","dist/file_pendaftaran/$newnamepp");
	
	
	//---------- input db
	$sqlb = $db->query ("insert into tb_file_pendaftaran (id_tb_proses, st_proses, link_gbr, ket_gbr, id_tb_user) values ('$id_tb_proses', '$st_proses', 'dist/file_pendaftaran/$newnamepp','$ket_gbr', '$_SESSION[id_tb_user]')");
	//echo "$sqlb";
	//exit;
	
		if ($sqlb) { 
		echo json_encode(['status' => '1', 'imageUrl' => 'localhost:8080/maxi-line/dashboard/dist/file_pendaftaran/'.$newnamepp.'']);
		exit();
		} else {
		echo json_encode(['status' => '2', 'error' => 'Gagal menyimpan data ke database!']);	
		exit();	
		}	
	
	}   
} else {
    echo json_encode(['status' => '2', 'error' => 'Invalid file']);
}
?>
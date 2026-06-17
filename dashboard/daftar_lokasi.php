<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";


//exit();
$db = new DbConnector();

if(!isset($_POST['searchTerm'])){ 
	$sql = $db->query("SELECT TOP (50) A.*, B.id_tb_lokasi FROM v_alamat A JOIN tb_lokasi B ON ',' + B.id_v_alamat + ',' LIKE '%,' + CAST(A.id_data_kd_pos AS VARCHAR) + ',%' order by A.kelurahan_desa asc ");	 
} else { 
	$carinya = $_POST['searchTerm'];   
	$sql = $db->query("SELECT A.*, B.id_tb_lokasi FROM v_alamat A JOIN tb_lokasi B ON ',' + B.id_v_alamat + ',' LIKE '%,' + CAST(A.id_data_kd_pos AS VARCHAR) + ',%' 
	where A.kelurahan_desa like '%$carinya%' or A.kecamatan like '%$carinya%' 
	or A.kabupaten_kota like '%$carinya%' or A.nama_prop like '%$carinya%' 
	or A.kd_pos like '%$carinya%' 
	order by A.kelurahan_desa asc ");
} 

$data = array();
while ($row = $db->fetchArray($sql)) {  
$id_alamat = $row['kelurahan_desa'].' - '.$row['kecamatan'].' - '.$row['kabupaten_kota'].' - '.$row['nama_prop'].' - '.$row['kd_pos'] ; 
$data[] = array('id'=>$row['id_data_kd_pos'], 'text'=>$id_alamat, 'lokasi'=>$row['id_tb_lokasi'] );
}
echo json_encode($data);

?>   
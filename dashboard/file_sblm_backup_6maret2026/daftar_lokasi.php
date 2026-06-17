<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";


//exit();
$db = new DbConnector();

if(!isset($_POST['searchTerm'])){ 
  $sql = $db->query("select top 100 * from tb_lokasi a left join v_alamat b on a.id_v_alamat = b.id_data_kd_pos");   
} else { 
  $search = $_POST['searchTerm'];   
  $sql = $db->query("select * from tb_lokasi a left join v_alamat b on a.id_v_alamat = b.id_data_kd_pos where a.alamat_tiang like '%$search%' or a.nama_area like '%$search%'");
} 

$data = array();
while ($row = $db->fetchArray($sql)) {  
$id_alamat = $row['nama_area'].', '.$row['alamat_tiang'].', '.$row['kelurahan_desa'].', '.$row['kecamatan'].', '.$row['kabupaten_kota'] ; 
$data[] = array('id'=>$row['id_tb_lokasi'], 'text'=>$id_alamat);
}
echo json_encode($data);

?>   
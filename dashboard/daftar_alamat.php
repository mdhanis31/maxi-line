<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";


//exit();
$db = new DbConnector();

if(!isset($_POST['searchTerm'])){ 
  $sql = $db->query("select top 100 * from v_alamat");   
}else{ 
  $search = $_POST['searchTerm'];   
  $sql = $db->query("select * from v_alamat where kelurahan_desa like '%$search%' or kecamatan like '%$search%' or kabupaten_kota like '%$search%' or kd_pos like '%$search%'");
} 

$data = array();
while ($row = $db->fetchArray($sql)) {  
$id_alamat = $row['kelurahan_desa'].' - '.$row['kecamatan'].' - '.$row['kabupaten_kota'].' - '.$row['kd_pos'] ; 
$data[] = array('id'=>$row['id_data_kd_pos'], 'text'=>$id_alamat);
}
echo json_encode($data);

?>   
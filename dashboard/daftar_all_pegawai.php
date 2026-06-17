<?php

@session_start();
//print_r($_SESSION);
include "include/DbConnector.php";
include "include/config.php";

//exit();

$db = new DbConnector();

$id_tb_user = maxiline($_GET['id'], 'd');

if(!isset($_POST['searchTerm'])){ 
  $sql = $db->query("select top 100 * from tb_user where id_tb_pendaftaran is null");   
} else { 
  $search = $_POST['searchTerm'];   
  $sql = $db->query("select * from tb_user where id_tb_pendaftaran is null and nm_user like '%$search%' or jabatan like '%$search%'");
} 

$data = array();
while ($row = $db->fetchArray($sql)) {
if(!empty($row['jabatan'])){
$jabatan = $row['nm_user']." - ".$row['jabatan'];
} else {
$sqld = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$row[id_tb_pendaftaran]'"));	
$jabatan = $row['nm_user']." - ".$sqld['kode_daftar'];
}	
$data[] = array('id'=>$row['id_tb_user'], 'text'=>$jabatan);
}
echo json_encode($data);

?>   
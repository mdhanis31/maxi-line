<?php

@session_start();
//print_r($_SESSION);
include "include/DbConnector.php";
include "include/config.php";

//exit();

$db = new DbConnector();

$id_tb_user = maxiline($_GET['id'], 'd');

if(!isset($_POST['searchTerm'])){ 
  $sql = $db->query("select top 100 * from tb_user where id_tb_user != '$_SESSION[id_tb_user]'");   
} else { 
  $search = $_POST['searchTerm'];   
  $sql = $db->query("select a.*,b.kode_daftar from tb_user a left join tb_pendaftaran b on a.id_tb_pendaftaran = b.id_tb_pendaftaran where id_tb_user != '$_SESSION[id_tb_user]' and nm_user like '%$search%' or kode_daftar like '%$search%' or jabatan like '%$search%'");
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
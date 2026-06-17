<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";


//exit();
$db = new DbConnector();

if(!isset($_POST['searchTerm'])){ 
  $sql = $db->query("  select top 50 * from (
  select tbu.id_tb_user, tbu.username, tbp.* from tb_user tbu left join tb_pendaftaran tbp on tbu.id_tb_pendaftaran = tbp.id_tb_pendaftaran 
  where tbu.level_user = '5' and tbp.st_layanan = '8')a");   
}else{ 
  $search = $_POST['searchTerm'];   
  $sql = $db->query("select * from (select tbu.id_tb_user, tbu.username, tbp.* from tb_user tbu left join tb_pendaftaran tbp on tbu.id_tb_pendaftaran = tbp.id_tb_pendaftaran 
where tbu.level_user = '5' and tbp.st_layanan = '8')a where nama like '%$search%' or username like '%$search%' or kode_daftar = '%$search%'");
} 

$data = array();
while ($row = $db->fetchArray($sql)) {  
$data[] = array('id'=>$row['id_tb_pendaftaran'], 'text'=>$row['kode_daftar']." - ".$row['nama']);
}
echo json_encode($data);

?>   
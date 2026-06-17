<?php
@session_start();
//print_r($_SESSION);
include "include/DbConnector.php";
include "include/config.php";

//exit();

$db = new DbConnector();

if(!isset($_POST['searchTerm'])){ 
  $sql = $db->query("SELECT a.* FROM (SELECT * FROM tb_user WHERE id_tb_pendaftaran IS NULL AND sts_delete = '1')a WHERE a.level_user = '1' OR a.level_user = '2' OR a.level_user = '3'");
}else{
  $search = $_POST['searchTerm'];
  $sql = $db->query("SELECT * FROM tb_user WHERE id_tb_pendaftaran IS NULL AND (level_user = '1' or level_user = '2' OR level_user = '3') AND (nm_user LIKE '%$search%' OR jabatan LIKE '%$search%') AND sts_delete = '1'");
}

$data = array();
while ($row = $db->fetchArray($sql)) {
  if(!empty($row['jabatan'])){
    $jabatan = $row['nm_user']." - ".$row['jabatan'];
  } else {
    $jabatan = $row['nm_user'];
  }

  $data[] = array('id'=>$row['id_tb_user'], 'text'=>$jabatan);
}
echo json_encode($data);

?>
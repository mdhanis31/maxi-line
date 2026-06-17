<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";


$id_lokasi = SafeSQL($_POST['id']);
$db = new DbConnector();
if(!isset($_POST['searchTerm'])){	

	if($id_lokasi == 4) {
		$sql = $db->query("select top 50 * from tb_paket where paket_promo = '1' and is_hidden = '1' and nama_paket like '%starlink%' "); 
	} else {
		$sql = $db->query("select top 50 * from tb_paket where paket_promo = '1' and is_hidden = '1' and nama_paket not like '%starlink%' "); 
	}	
    
} else {
	$carinya = SafeSQL($_POST['searchTerm']);
	
	if($id_lokasi == 4) {
		$sql = $db->query("select * from tb_paket where paket_promo = '1' and is_hidden = '1' and nama_paket like '%starlink%' and nama_paket like '%carinya%' "); 
	} else {
		$sql = $db->query("select * from tb_paket where paket_promo = '1' and is_hidden = '1' and nama_paket not like '%starlink%' and nama_paket like '%carinya%'"); 
	}

} 

$res = $db->query($sql);
$data = array();

while ($row = $db->fetchArray($sql)) {  
	$data[] = array('id'=>$row['id_tb_paket'], 'text'=>$row['nama_paket'], 'harga' => number_format($row['harga_paket'],0,',','.'));
}

echo json_encode($data);

?>
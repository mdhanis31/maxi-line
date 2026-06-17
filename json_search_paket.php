<?php
@session_start();

include "dashboard/include/config.php";
include "dashboard/include/DbConnector.php";

$db = new DbConnector();

// array select
$id = isset($_POST['id']) ? SafeSQL($_POST['id']) : '';
$id1 = isset($_POST['id1']) ? SafeSQL($_POST['id1']) : '';

if(!isset($_POST['searchTerm'])){ 

	$area = array();
	$sqla = "Select * from tb_lokasi";
	$resa = $db->query($sqla);

	while ($rowa = $db->fetchArray($resa)) {
		$area[] = explode(',', $rowa['id_v_alamat']);
	}	

	$merged = array_unique(array_merge(...$area));
	
	if($id == 1){	
		
		$provinsi = array();

		foreach($merged as $merge){
			$sqlb = "Select * from v_alamat where id_data_kd_pos = '$merge'";
			$resb = $db->query($sqlb);
			$rowb = $db->fetchArray($resb); 
			$provinsi[] = $rowb['nama_prop'];
		}	
				
		$datae = array_slice(array_unique($provinsi), 0, 20);
		
		//print_r($data);
		//echo "<br><br>";
		//exit();
		
	    //$sql= "SELECT DISTINCT TOP (20) id_data_propinsi, nama_prop FROM v_alamat ORDER BY nama_prop ASC ";	
	} else if($id == 2){
	    //$sql= "SELECT DISTINCT TOP (20) kabupaten_kota FROM v_alamat WHERE id_data_propinsi = '$id1' ORDER BY kabupaten_kota ASC";

		$kabupaten_kota = array();
		foreach($merged as $merge){
			$sqlb = "Select * from v_alamat where nama_prop = '$id1' and id_data_kd_pos = '$merge'";
			$resb = $db->query($sqlb);
			$rowb = $db->fetchArray($resb); 
			$kabupaten_kota[] = $rowb['kabupaten_kota'];
		}	
		//print_r($kabupaten_kota);
		//exit();
		$datae = array_slice(array_unique($kabupaten_kota), 0, 20);
		
	} else if($id == 3){
	    //$sql= "SELECT DISTINCT TOP (20) id_data_kd_pos, kecamatan, kelurahan_desa, kd_pos FROM v_alamat WHERE kabupaten_kota = '$id1' ORDER BY kecamatan ASC";

		$datao = array();
		foreach($merged as $merge){
		$sqlb = "Select id_data_kd_pos, kecamatan, kelurahan_desa, kd_pos from v_alamat where kabupaten_kota = '$id1' and id_data_kd_pos = '$merge'";
		$resb = $db->query($sqlb);
		$rowb = $db->fetchArray($resb);
			if(!empty($rowb['id_data_kd_pos'])){
				$datao[] = array('id'=>$rowb['id_data_kd_pos'], 'text'=>$rowb['kecamatan']." - ".$rowb['kelurahan_desa']." - ".$rowb['kd_pos']);
			}
		}
		
		$unique_data = array_combine(array_column($datao, 'id'), $datao);

		// 2. Reset index agar menjadi numerik (0, 1, 2...)
		$unique_data = array_values($unique_data);

		// 3. Ambil 20 data terdepan
		$datae = array_slice($unique_data, 0, 20);
		
	}

	foreach($datae as $datax) {
		if($id == 1){
			$data[] = array('id'=>$datax, 'text'=>$datax);
		} else if($id == 2){
			$data[] = array('id'=>$datax, 'text'=>$datax);
		} else if($id == 3){
			$data[] = array('id'=>$datax['id'], 'text'=>$datax['text']);
		} 
	}

	echo json_encode($data);
	
} else {
	$search = SafeSQL($_POST['searchTerm']);   
	if($id == 1){
	    $sql= "SELECT DISTINCT TOP (20) id_data_propinsi, nama_prop FROM v_alamat WHERE nama_prop LIKE '%$search%' ORDER BY nama_prop ASC";	
	} else if($id == 2){
	    $sql= "SELECT DISTINCT TOP (20) kabupaten_kota FROM v_alamat WHERE id_data_propinsi = '$id1' AND kabupaten_kota LIKE '%$search%' ORDER BY kabupaten_kota ASC";	
	} else if($id == 3){
	    $sql= "SELECT DISTINCT TOP (20) id_data_kd_pos, kecamatan, kelurahan_desa, kd_pos FROM v_alamat WHERE kabupaten_kota = '$id1' AND (kecamatan LIKE '%$search%' OR kelurahan_desa LIKE '%$search%' OR kd_pos LIKE '%$search%') ORDER BY kecamatan ASC";	
	} 
	
	$res = $db->query($sql);
	$data = array();
	if ($res) {
		while ($row = $db->fetchArray($res)) {
			if($id == 1){
				$data[] = array('id'=>$row['id_data_propinsi'], 'text'=>$row['nama_prop']);
			} else if($id == 2){
				$data[] = array('id'=>$row['kabupaten_kota'], 'text'=>$row['kabupaten_kota']);
			} else if($id == 3){
				$data[] = array('id'=>$row['id_data_kd_pos'], 'text'=>$row['kecamatan']." - ".$row['kelurahan_desa']." - ".$row['kd_pos']);
			} 
		}
	}
	echo json_encode($data);
	
}	



?>
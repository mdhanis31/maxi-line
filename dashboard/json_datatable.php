<?php
@session_start();
include('include/DbConnector.php');
include('include/config.php');
$db = new DbConnector();

$id = $_POST['ide'];

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 => 'provinsi_ok',
	1 => 'jml',		
);


// getting total number records without any search
$sql = "SELECT COUNT(a.provinsi_ok) AS jmlok ";
$sql.=" FROM (SELECT provinsi_ok, COUNT(DISTINCT nomor_izin_usaha) AS jml FROM v_portal_stats_pusat_siup_aktif WHERE YEAR(tanggal_tanda_tangan)='$id' GROUP BY provinsi_ok ORDER BY jml DESC)a";
$query = $db->query($sql);
$row = $db->fetchArray($query);
$totalData = $row["jmlok"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT * ";
$sql.=" FROM (SELECT provinsi_ok, COUNT(DISTINCT nomor_izin_usaha) AS jml FROM v_portal_stats_pusat_siup_aktif WHERE YEAR(tanggal_tanda_tangan)='$id' GROUP BY provinsi_ok)a ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" WHERE a.provinsi_ok LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR a.jml LIKE '%".$requestData['search']['value']."%' ";	
} 

$query_jml = $db->query($sql);
$totalFiltered = $db->getNumRows($query_jml); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query = $db->query($sql);
$data = array();
while( $row=$db->fetchArray($query) ) {
$id = dirpk( $row['provinsi_ok'], 'e' );
	// preparing an array
	$nestedData=array(); 
	$nestedData[] = '<a href="index.php?id='.$id.'&e=y&#c_usaha_aktif_jenisa">'.$row["provinsi_ok"].'</a>';
	$nestedData[] = $row["jml"];
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format
?>
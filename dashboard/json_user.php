<?php
@session_start();
include "include/DbConnector.php";
include "include/config.php";
$db = new DbConnector();

$now = new DateTime();
$tgl = $now->format("Y-m-d");

$kodeaman = $_SESSION['token'];
/* Database connection start 
$servername = "localhost";
$username = "sa";
$password = "andi";
$dbname = "siki_new";

$connInfo = array("Database"=>$dbname, "UID"=>$username, "PWD"=>$password);
$conn = sqlsrv_connect($host, $connInfo) or die("Connection failed: " . sqlsrv_errors());

 Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 => 'null',
	1 => 'nip',
	2 => 'nm_user',
	3 => 'jabatan',
	4 => 'kd_upt',
	5 => 'nmsingkat_upt',
	6 => 'detil',
);


// getting total number records without any search
$sql = "SELECT count(id_tb_pegawai) as jml";
if ($_SESSION['level_user'] == '2') {
$sql.=" FROM tb_pegawai where kd_upt = '$_SESSION[kd_upt]' ";
} else if ($_SESSION['level_user'] == '3') {
$sql.=" FROM tb_pegawai ";
}
//$query=sqlsrv_query($conn, $sql) or die;
//$row=sqlsrv_fetch_array($query);
$query = $db->query($sql);
$row = $db->fetchArray($query);
$totalData = $row["jml"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT a.*, b.nmsingkat_upt ";
if ($_SESSION['level_user'] == '2') {
$sql.=" FROM tb_pegawai a left join tb_unit b on a.kd_upt=b.kd_upt where a.kd_upt = '$_SESSION[kd_upt]' ";
if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" and (a.nip LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR a.nm_user LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR a.jabatan LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR a.kd_upt LIKE '%".$requestData['search']['value']."%' ";	
	$sql.=" OR b.nmsingkat_upt LIKE '%".$requestData['search']['value']."%') ";
}
} else if ($_SESSION['level_user'] == '3') {
$sql.=" FROM tb_pegawai a left join tb_unit b on a.kd_upt=b.kd_upt  ";
if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" where a.nip LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR a.nm_user LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR a.jabatan LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR a.kd_upt LIKE '%".$requestData['search']['value']."%' ";	
	$sql.=" OR b.nmsingkat_upt LIKE '%".$requestData['search']['value']."%' ";
}
}

//$params = array();
//$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
//$query=sqlsrv_query($conn, $sql, $params, $options) or die;
//$totalFiltered = sqlsrv_num_rows( $query );

$query_jml = $db->queryNumRows($sql);
$totalFiltered = $db->getNumRows($query_jml);

$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  offset ".$requestData['start']." rows FETCH NEXT ".$requestData['length']." ROWS ONLY";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//$query=sqlsrv_query($conn, $sql) or die;
$query = $db->query($sql);
$data = array();
//while( $row=sqlsrv_fetch_array($query) ) {
while( $row=$db->fetchArray($query) ) {
// preparing an array
	$nestedData=array(); 

	$nestedData[] = null;
	$nestedData[] = $row["nip"];
	$nestedData[] = $row["nm_user"];
	$nestedData[] = $row["jabatan"];	
	$nestedData[] = $row["kd_upt"];
	$nestedData[] = $row["nmsingkat_upt"];
	$nestedData[] = '<a class="btn btn-sm btn-info" href="pegawai_add.php?i='.$row["id_tb_pegawai"].'&c=b">Detail</a>
					';	
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

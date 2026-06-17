<?php
@session_start();
include "include/config.php";
include "include/DbConnector.php";

$db = new DbConnector();

$now = new DateTime();
$tgl = $now->format("Y-m-d");

$kodeaman = $_SESSION['token'];

/* Database connection end */
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 => 'null',
	1 => 'nama',
	2 => 'no_invoice',
	3 => 'tahunnya',
	4 => 'tahunbayar',
	5 => 'nama_paket',
	6 => 'tot_tagih',
	7 => 'tgl_invoice',
);

// getting total number records without any search
$sql = "SELECT count(id_tr_invoice) as jml";
$sql.=" FROM tr_invoice WHERE tgl_invoice >= '$date1' and tgl_invoice < '$date2' and sts_lunas = '2'";

$query = $db->query($sql);
$row = $db->fetchArray($query);
$totalData = $row['jml'];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select tri.*, tbp.nama, tbk.nama_paket,
CONCAT(DAY(tgl_invoice),' ', CASE MONTH(tgl_invoice) 
    WHEN 1 THEN 'Januari' 
    WHEN 2 THEN 'Februari' 
    WHEN 3 THEN 'Maret' 
    WHEN 4 THEN 'April' 
    WHEN 5 THEN 'Mei' 
    WHEN 6 THEN 'Juni' 
    WHEN 7 THEN 'Juli' 
    WHEN 8 THEN 'Agustus' 
    WHEN 9 THEN 'September'
    WHEN 10 THEN 'Oktober' 
    WHEN 11 THEN 'November' 
    WHEN 12 THEN 'Desember' END,' ',YEAR(tgl_invoice)) AS tahunnya, 
	CONCAT(DAY(tgl_dibayar),' ', CASE MONTH(tgl_dibayar) 
    WHEN 1 THEN 'Januari' 
    WHEN 2 THEN 'Februari' 
    WHEN 3 THEN 'Maret' 
    WHEN 4 THEN 'April' 
    WHEN 5 THEN 'Mei' 
    WHEN 6 THEN 'Juni' 
    WHEN 7 THEN 'Juli' 
    WHEN 8 THEN 'Agustus' 
    WHEN 9 THEN 'September'
    WHEN 10 THEN 'Oktober' 
    WHEN 11 THEN 'November' 
    WHEN 12 THEN 'Desember' END,' ',YEAR(tgl_dibayar)) AS tahunbayar,	
	CONCAT(FORMAT(tgl_dibayar,'H:mm'),' ','WIB') as waktubayar  ";
$sql.=" from tr_invoice tri LEFT JOIN tb_pendaftaran tbp on tbp.id_tb_pendaftaran = tri.id_tb_pendaftaran
LEFT JOIN tb_paket tbk on tbp.id_tb_paket = tbk.id_tb_paket where sts_lunas = '2' and (tgl_invoice >= '$date1' and tgl_invoice < '$date2') ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" and tahunnya LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR waktu LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR tahunbayar LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR nama LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR no_invoice LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR nama_paket LIKE '%".$requestData['search']['value']."%' ";		
	$sql.=" OR tot_tagih LIKE '%".$requestData['search']['value']."%' ";	
	
}

$query_jml = $db->queryNumRows($sql);
$totalFiltered = $db->getNumRows($query_jml);

$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  offset ".$requestData['start']." rows FETCH NEXT ".$requestData['length']." ROWS ONLY";

$query = $db->query($sql);
$data = array();

while( $row=$db->fetchArray($query) ) {
//$nilai = "Rp. ".number_format($row['tot_tagih'],0,',','.').",00";
//$tglinvoice = $row['tgl_invoice']->format('d-m-Y H:i');
// preparing an array
	$nestedData=array(); 
	
	$nestedData[] = null;
	$nestedData[] = $row['nama'];	
	$nestedData[] = $row['no_invoice'];	
	$nestedData[] = $row['tahunnya'];
	$nestedData[] = $row['tahunbayar'];	
	$nestedData[] = $row['nama_paket'];	
	$nestedData[] = $row['tot_tagih'];
	$nestedData[] = $row['tgl_invoice']->format('Y-m-d H:i:s');
		
	$data[] = $nestedData;
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format
?>

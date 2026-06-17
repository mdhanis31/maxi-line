<?php
$serverName = '103.162.60.212,8198\MANUNGGALDB';
$username = 'manunggaldb';
$password = 'M4nun994l01*';
$dbname = 'maxiline';

$connInfo = array("Database"=>$dbname, "UID"=>$username, "PWD"=>$password, "TrustServerCertificate" => "yes");
$conn = sqlsrv_connect( $serverName, $connInfo);

if( $conn === false ) {
     die( print_r( sqlsrv_errors(), true));
}

$sql= "SELECT top 1 * FROM tb_user order by nm_user asc";	
$query=sqlsrv_query($conn, $sql) or die;

while($row=sqlsrv_fetch_array($query)) {
echo $row['nm_user']."<br>";	
}	
?>
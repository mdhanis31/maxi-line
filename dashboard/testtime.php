<?

$now = new DateTime();
$th = $now->format("Y");
$bl = $now->format("m");
$Th = $now->format("y");
$tgl = $now->format("j");
$tgle = $now->format("Y-m-05");
$newdate = date("Y-m-d", strtotime('+1 month' , strtotime($tgle))) ;

$tgle = $now->format("05-m-Y");
$newdate = date("d-m-Y", strtotime('+1 month' , strtotime($tgle))) ;

echo $tgl;
?>
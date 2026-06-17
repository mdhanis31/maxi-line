<?php
session_start();
// set timeout period in seconds
if (!isset($_SESSION['username'])) {
} else {
$inactive = 3800;
// check to see if $_SESSION['timeout'] is set
if(isset($_SESSION['timeout']) ) {
	$session_life = time() - $_SESSION['timeout'];
	if($session_life > $inactive)
        { session_start();
		session_destroy(); 
		?><script>alert('Sesi anda sudah habis');
		location.href='login.php';</script><?
	   exit(); }
}
$_SESSION['timeout'] = time();
}
?>

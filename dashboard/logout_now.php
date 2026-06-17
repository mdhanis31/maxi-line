<?php
@session_start();
include "inc/config.php";
include "inc/DbConnector.php";
include "inc/funct.php";

$db = new DbConnector();
//$kodeaman = $_SESSION['token'];
$myusername = SafeSQL($_GET[id]);
 
//if ($kodeaman && $_GET['token']==$kodeaman) {
unset($_SESSION[$myusername]);
session_destroy();
//setcookie('PHPSESSID','',time()-3600,'/','',0);
	?>
<script>document.location.href="login.php"</script>
<?php
exit(); 
//} else {
   // log potential CSRF attack.
//  echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
//		<script>history.back();</script>";
//		exit();
//} 
?>

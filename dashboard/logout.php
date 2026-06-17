<?php
session_start();
include "include/config.php";
include "include/DbConnector.php";
$db = new DbConnector();

$id = maxiline($_GET['id'], 'd');
$sql = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$id'"));	
if(empty($sql['id_tb_user'])) {
?>
<script>alert('Anda tidak memiliki hak akses!')</script>;
<script>document.location.href="index.php"</script>
<?php 
exit();	
}	
	
unset($_SESSION['username']);
unset($_SESSION['level_user']);
unset($_SESSION['id_tb_user']);
session_destroy();
//setcookie('PHPSESSID','',time()-3600,'/','',0);
	?>
<script>document.location.href="index.php"</script>
<?php
exit(); 

?>

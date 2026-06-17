<?php

@session_start();
//print_r($_SESSION);
include "inc/config.php";
include "inc/DbConnector.php";
include "inc/funct.php";
//memanggil library phpmailer

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

if ($_GET['q'] == '2') {
	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_GET['token']==$kodeaman) {

		$id_tr_user = SafeSQL($_GET['id']);
		$sqla = "update tr_user set sts_data='2' where id_tr_user='$id_tr_user'";
		$resa = $db->query($sqla);

		if ($sqla) {
			echo "<script>alert('User telah dinonaktifkan!');</script>
			<script>location.href='user_internal_v.php';</script>";
			exit();
		} else {
			echo " <script>alert('User gagal dinonaktifkan!');</script>
			<script>history.back();</script>";
			exit();
	   }
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
} elseif ($_GET['q'] == '1') {
	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_GET['token']==$kodeaman) {

		$id_tr_user = SafeSQL($_GET['id']);
		$sqla = "update tr_user set sts_data='1' where id_tr_user='$id_tr_user'";
		$resa = $db->query($sqla);

		if ($sqla) {
			echo "<script>alert('User telah diaktifkan!');</script>
			<script>location.href='user_internal_v.php';</script>";
			exit();
		} else { 
			echo " <script>alert('User gagal diaktifkan!');</script>
			<script>history.back();</script>";
			exit();
		}
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
} elseif ($_GET['t'] == '2') {
	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_GET['token']==$kodeaman) {

		$id_tr_user = SafeSQL($_GET['id']);
		$sqla = "update tr_user set sts_data='2' where id_tr_user='$id_tr_user'";
		$resa = $db->query($sqla);

		if ($sqla) {	 
			echo "<script>alert('User telah dinonaktifkan!');</script>
			<script>location.href='user_usaha_v.php';</script>";
			exit();
		} else { 
			echo " <script>alert('User gagal dinonaktifkan!');</script>
			<script>history.back();</script>";
			exit();
		}

	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
} else if ($_GET['t'] == '1') {

	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_GET['token']==$kodeaman) {

		$id_tr_user = SafeSQL($_GET['id']);
		$sqla = "update tr_user set sts_data='1' where id_tr_user='$id_tr_user'";
		$resa = $db->query($sqla);

		if ($sqla) {
			echo "<script>alert('User telah diaktifkan!');</script>
			<script>location.href='user_usaha_v.php';</script>";
			exit();
		} else {
			echo " <script>alert('User gagal diaktifkan!');</script>
			<script>history.back();</script>";
			exit();
		}
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
}
?>
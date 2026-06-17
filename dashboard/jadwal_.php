<?php

@session_start();
include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";
$db = new DbConnector();

$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");
//echo $tgle."<br>";
//echo $tgl;
//exit;
$tgl_rencana = date("Y-m-d", strtotime($_POST['tgl_rencana']))." ".$_POST['waktu_rencana'];
$rencana = SafeSQL($_POST['rencana']);
$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
$id_tb_rencana = SafeSQL($_POST['id_tb_rencana']);
// $id_tb_user = SafeSQL($_POST['id_tb_user']);
$id_tb_user = implode(",", $_POST['id_tb_user']);
$url = maxiline($_POST['req'], 'd');
// print_r($_POST);
//echo $tglpasang;
//exit();


if ($_POST['u'] == 'f'){
	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_POST['token']==$kodeaman) {

		if(empty($tgl_rencana)) {
		?><script>
			alert('Tanggal rencana harus diisi!');
			history.back();
			</script><?php
			exit();
		} else if(empty($rencana)) {
		?><script>
			alert('Rencana harus dipilih!');
			history.back();
			</script><?php
			exit();
		} else {

			$sqla = $db->query ("insert into tb_rencana (id_tb_user, id_tb_pendaftaran, rencana, tgl_rencana)
			values ('$id_tb_user', '$id_tb_pendaftaran', '$rencana', '$tgl_rencana')");
			$id_daftar = maxiline($id_tb_pendaftaran, 'e');

			if ($sqla) {
				if($_POST['kirimnotif'] == 1) {
					$sqlb = $db->fetchArray($db->query("SELECT SCOPE_IDENTITY() as lastid from tb_rencana"));
					$id_rencana = maxiline($sqlb['lastid'], 'e');
					$tipenya = maxiline("jadwal", 'e');

					// echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
					exit();
				} else {
					echo " <script>alert('Jadwal berhasil ditambahkan');</script>
					<script>location.href='$url?id=$id_daftar';</script>";
					exit();
				}
			} else { 
				echo " <script>alert('Jadwal gagal ditambahkan!');</script>
				<script>history.back();</script>";
				exit();
			}
		}
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}

} elseif($_POST['f'] == 'e') {
	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_POST['token']==$kodeaman) {
		$sqla = $db->query ("update tb_rencana set id_tb_user='$id_tb_user', rencana='$rencana', tgl_rencana='$tgl_rencana' where id_tb_rencana='$id_tb_rencana'");
		$id_daftar = maxiline($id_tb_pendaftaran, 'e');
		if ($sqla) {
			if($_POST['kirimnotif'] == 1) {
				$id_rencana = maxiline($id_tb_rencana, 'e');
				$tipenya = maxiline("jadwal", 'e');
				// echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
				exit();	
			} else {
				echo " <script>alert('Data berhasil diupdate');</script>
				<script>location.href='$url?id=$id_daftar';</script>";
				exit();
			}
		} else { 
			echo " <script>alert('Data gagal diupdate!');</script>
			<script>history.back();</script>";
			exit();
	   }
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
} elseif($_GET['g'] == 'h') {
	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_GET['token']==$kodeaman) {
		$id_tb_rencana = maxiline(SafeSQL($_GET['id']), 'd');
		$sqla = $db->fetchArray($db->query ("Select * from tb_rencana where id_tb_rencana='$id_tb_rencana'"));
		//echo "$sqla[id_tb_pendaftaran]";
		//exit;

		if($sqla['rencana'] == 1) {$status = 1;} elseif($sqla['rencana'] == 2) {$status = 4;} elseif($sqla['rencana'] == 3) {$status = 6;}

		$sqlc = $db->query ("update tb_pendaftaran set st_layanan = '$status' where id_tb_pendaftaran='$sqla[id_tb_pendaftaran]'");
		$sqlb = $db->query ("Delete from tb_rencana where id_tb_rencana='$id_tb_rencana'");
		$id_daftar = maxiline($sqla['id_tb_pendaftaran'], 'e');

		if ($sqlb) {
			echo " <script>alert('Data berhasil dihapus');</script>
			<script>location.href='pendaftaran_dtl.php?id=$id_daftar]';</script>";
			exit();
		} else {
			echo " <script>alert('Data gagal dihapus!');</script>
			<script>history.back();</script>";
			exit();
		}
	} else {
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
	
}
?>
<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/session.php";
include "include/DbConnector.php";

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");

$pesan = SafeSQL($_POST['pesan']);
$id_user_tujuan = $_POST['id_user_tujuan'];
$id_user_pengirim = SafeSQL($_POST['id_user_pengirim']);
$subyek = SafeSQL($_POST['subyek']);

$tujuke = implode(",",$id_user_tujuan);

//print_r($_POST)."<br>";
//echo $tujuke."<br>";
//exit();

if ($_POST['j'] == 'a') {
	
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
	foreach ($id_user_tujuan as $x=>$idnya) {
	$id_tujuan = SafeSQL($idnya);
	
	if(empty($id_tujuan)) {
    ?><script>
      alert('Tujuan harus diisi!');
      history.back();
      </script><?php
	  exit();
	}
	}
	
//---------------------------------	
	if(empty($subyek)) {
    ?><script>
      alert('Subyek harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($pesan)) {
    ?><script>
      alert('Pesan tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {
	
	$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codepesan = "";
	for ($i = 0; $i < 10; $i++) {
		$codepesan .= $chars[mt_rand(0, strlen($chars)-1)];
	}
	
	$id_tr_pesan_new = array();
	foreach ($id_user_tujuan as $x=>$idnya) {
	$id_tujuan = SafeSQL($idnya);
	
	if($_POST['draft'] == 2){
	$sqla ="insert into tr_pesan (id_user_tujuan, id_user_pengirim, subyek, pesan, st_pesan, codepesan) values ('$id_tujuan', '$id_user_pengirim', '$subyek', '$pesan', '2', '$codepesan')";
	} else {
	$sqla ="insert into tr_pesan (id_user_tujuan, id_user_pengirim, subyek, pesan, codepesan) values ('$id_tujuan', '$id_user_pengirim', '$subyek', '$pesan', '$codepesan')";	
	}
	//echo $sqla."<br>";
	//exit();	
	$resa =  $db->query($sqla);
	$akhir = $db->fetchArray($db->query("select SCOPE_IDENTITY() as lastid from tr_pesan"));
	$id_tr_pesan_new[] = $akhir['lastid'];
	}
	//echo $sqla."<br>";
	//exit();
	// insert database
	if ($resa) {

	// insert file 
	if(!empty($_FILES['file_pesan']['name'][0])) {
	
	$fileCount = count($_FILES['file_pesan']['tmp_name']);
	//echo "$fileCount";
	//exit;		
	for ($i = 0; $i < $fileCount; $i++) {
	
	$namepp = $_FILES['file_pesan']['name'][$i];
	$temp = $_FILES['file_pesan']['tmp_name'][$i];    
    $type = $_FILES['file_pesan']['type'][$i];
    $size = $_FILES['file_pesan']['size'][$i];
    	
	$test = getimagesize($temp);
	
	if (empty($_FILES)) {
	foreach ($id_tr_pesan_new as $x=>$idtrpesan) {
	//---------- input db
	$sqlb = $db->query ("update tr_pesan set st_pesan = '2' where id_tr_pesan = '$idtrpesan'");
	//echo "$sqlb";
	//exit;
	 }	
	$seki =  maxiline($idtrpesan, 'e' );
	echo "<script>alert('Pilih file yg akan diupload!, draft pesan disimpan');</script>
	<script>location.href='pesan_baca.php?id=$seki&p=d';</script>";
	exit();	
	} else if ($size >= 5000000) {
	foreach ($id_tr_pesan_new as $x=>$idtrpesan) {
	//---------- input db
	$sqlb = $db->query ("update tr_pesan set st_pesan = '2' where id_tr_pesan = '$idtrpesan'");
	//echo "$sqlb";
	//exit;
	 }
	$seki =  maxiline($idtrpesan, 'e' );
	echo "<script>alert('Size file dokumen tidak boleh lebih dari 5Mb!, draft pesan disimpan');</script>
	<script>location.href='pesan_baca.php?id=$seki&p=d';</script>";
	exit();	
	} else if (!move_uploaded_file($temp, "dist/file_pesan/" . $namepp)) {
	foreach ($id_tr_pesan_new as $x=>$idtrpesan) {
	//---------- input db
	$sqlb = $db->query ("update tr_pesan set st_pesan = '2' where id_tr_pesan = '$idtrpesan'");
	//echo "$sqlb";
	//exit;
	 }
	 $seki =  maxiline($idtrpesan, 'e' );
	echo "<script>alert('File tidak bisa diupload!, draft pesan disimpan');</script>
	<script>location.href='pesan_baca.php?id=$seki&p=d';</script>";
	exit();	
	} else {
	
	foreach ($id_tr_pesan_new as $x=>$idtrpesan) {
	//---------- input db
	$sqlb = $db->query ("insert into tr_file_pesan (id_tr_pesan, link_file) values ('$idtrpesan', '$namepp')");
	//echo "$sqlb";
	//exit;
	 }
	}
   }
	if ($sqlb) {
			if($_POST['draft'] == 2){
			echo "<script>alert('Draft berhasil dibuar!');</script>
				  <script>location.href='pesan_draft.php';</script>";
			exit();
			} else {
			$sub = maxiline($subyek, 'e');
			$pesa = maxiline($pesan, 'e');
			echo "<script>location.href='pesanmail_.php?id=$tujuke&i=$id_user_pengirim&s=$sub&p=$pesa';</script>";
			exit();	
			}
		} else { 
		echo " <script>alert('Pesan gagal dikirim!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
  } else {
			if($_POST['draft'] == 2){
			echo "<script>alert('Draft berhasil dibuar!');</script>
				  <script>location.href='pesan_draft.php';</script>";
			exit();
			} else {
			$sub = maxiline($subyek, 'e');
			$pesa = maxiline($pesan, 'e');
			echo "<script>location.href='pesanmail_.php?id=$tujuke&i=$id_user_pengirim&s=$sub&p=$pesa';</script>";
			exit();	
			}	  
		}
	//end insert file			
		} else { 
		if($_POST['draft'] == 2){
			echo "<script>alert('Draft gagal dibuar!');</script>
				  <script>location.href='pesan_draft.php';</script>";
			exit();
			} else {
			echo "<script>alert('Pesan gagal dikirim!');</script>
				  <script>location.href='pesan_v.php';</script>";
			exit();	
			}
	   } 	
	 } 	
	} else {
   // log potential CSRF attack.
	  echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	  <script>history.back();</script>";
	  exit();
	}	
   } else if ($_POST['h'] == 'y'){
	if (empty($_POST['opsi'])) {	
	?><script>
	alert('Pilih pesan yang akan dihapus!');
	history.back();
	</script><?php
	exit();
	} else {
		
	$id_tr_pesan = $_POST['id_tr_pesan'];
	$opsi = $_POST['opsi'];
	
	$result = array_intersect($id_tr_pesan, $opsi);
	//print_r($result);
	//exit;
	foreach ($result as $x=>$pesan) {
	$id_pesan = SafeSQL($pesan);
	$sqlb = $db->query ("update tr_pesan set st_hapus_tujuan='2' where id_tr_pesan='$id_pesan'");
	//echo "$sqlb";
	//exit;
	}   
		if ($sqlb) {	 
		echo "<script>location.href='pesan_v.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Pesan gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
    }
   } else if ($_POST['h'] == 'ys'){
	if (empty($_POST['opsi'])) {	
	?><script>
	alert('Pilih pesan yang akan dihapus!');
	history.back();
	</script><?php
	exit();
	} else {
		
	$codepesan = $_POST['codepesan'];
	$opsi = $_POST['opsi'];
	
	$result = array_intersect($codepesan, $opsi);
	//print_r($result);
	//exit;
	foreach ($result as $x=>$pesan) {
	$codepesan = SafeSQL($pesan);
	$sqlb = $db->query ("update tr_pesan set st_hapus_pengirim='2' where codepesan='$codepesan'");
	//echo "$sqlb";
	//exit;
	}   
		if ($sqlb) {	 
		echo "<script>location.href='pesan_sent.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Pesan gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
    }
   } else if ($_POST['h'] == 'yd'){
	if (empty($_POST['opsi'])) {	
	?><script>
	alert('Pilih pesan yang akan dihapus!');
	history.back();
	</script><?php
	exit();
	} else {
		
	$codepesan = $_POST['codepesan'];
	$opsi = $_POST['opsi'];
	
	$result = array_intersect($codepesan, $opsi);
	//print_r($result);
	//exit;
	foreach ($result as $x=>$pesan) {
	$codepesan = SafeSQL($pesan);
	$sqlb = $db->query("update tr_pesan set st_hapus_pengirim='2' where codepesan='$codepesan'");
	//echo "$sqlb";
	//exit;
	}   
		if ($sqlb) {	 
		echo "<script>location.href='pesan_draft.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Pesan gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
    }
   } else if ($_POST['b'] == 'y') {
	$sql1 = $db->fetchArray($db->query("select * from tr_pesan where id_tr_pesan = '$_POST[id]'"));	   
	if($sql1['st_bintang'] == 1) {
	$sql2 = $db->query("update tr_pesan set st_bintang = '2' where id_tr_pesan = '$_POST[id]'");	
	} else {
	$sql2 = $db->query("update tr_pesan set st_bintang = '1' where id_tr_pesan = '$_POST[id]'");	
	}
   } else if ($_POST['r'] == 'y'){
	if (empty($_POST['restore'])) {	
	?><script>
	alert('Pilih pesan yang akan direstore!');
	history.back();
	</script><?php
	exit();
	} else {
		
	$codepesan = $_POST['codepesan'];
	$restore = $_POST['restore'];
	
	$result = array_intersect($codepesan, $restore);
	//print_r($result);
	//exit;
	foreach ($result as $x=>$pesan) {
	$codepesan = SafeSQL($pesan);
	$sqla = $db->fetchArray($db->query("select * from tr_pesan where codepesan='$codepesan'"));
	//echo $sqla['st_hapus_tujuan'];
	//exit;
	if($sqla['st_hapus_tujuan'] == 2 and $sqla['id_user_tujuan'] == $_SESSION['id_tb_user']) {
	$sqlb = $db->query("update tr_pesan set st_hapus_tujuan='1' where codepesan='$sqla[codepesan]'");
	} else if($sqla['st_hapus_pengirim'] == 2 and $sqla['id_user_pengirim'] == $_SESSION['id_tb_user']) {
	$sqlb = $db->query("update tr_pesan set st_hapus_pengirim='1' where codepesan='$sqla[codepesan]'");
	}
	//echo "$sqlb";
	//exit;
	}   
		if ($sqlb) {	 
		echo "<script>location.href='pesan_hapus.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Pesan gagal direstore!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
    }
   } else if ($_POST['pb'] == 'h'){
	if (empty($_POST['id_tr_pesan'])) {	
	?><script>
	alert('Pilih pesan yang akan dihapus!');
	history.back();
	</script><?php
	exit();
	} else {
		
	$id_tr_pesan = SafeSQL($_POST['id_tr_pesan']);
	$sqla = $db->fetchArray($db->query("select * from tr_pesan where id_tr_pesan='$id_tr_pesan'"));
	//echo $sqla['st_hapus_tujuan'];
	//exit;
	if($_POST['p'] == "i") {
	$sqlb = $db->query("update tr_pesan set st_hapus_tujuan='2' where id_tr_pesan='$sqla[id_tr_pesan]'");
	} else if($_POST['p'] == "s" or $_POST['p'] == "d") {
	$sqlb = $db->query("update tr_pesan set st_hapus_pengirim='2' where codepesan='$sqla[codepesan]'");
	}
	//echo "$sqlb";
	//exit;
	  
		if ($sqlb) {
		 if($_POST['p'] == "i") {
				echo "<script>location.href='pesan_v.php';</script>";
				exit();
			  } else if($_POST['p'] == "s") {
				echo "<script>location.href='pesan_sent.php';</script>";
				exit();
			  } else if($_POST['p'] == "d") {
				echo "<script>location.href='pesan_draft.php';</script>";
				exit();
			  } 	
		} else { 
		echo " <script>alert('Pesan gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
    }
   } else if(!empty($_GET['i'])){
	$codepesan = maxiline($_GET['id'], 'd'); 
	$confirm = maxiline($_GET['i'], 'd');
	$nmfile = maxiline($_GET['nmfile'], 'd');
	
	//echo $codepesan;
	//exit;
	$codepesam = SafeSQL($codepesan);
	$sqla = "select * from tr_pesan where codepesan='$codepesam'";
	$jml = $db->queryNumRows($sqla);
	$total = $db->getNumRows($jml);
	//echo $sqla['id_tr_file_pesan'];
	//exit;
	//echo $sqla;
	//exit;
	
	if($confirm != "e") {	
	?><script>
	alert('Pilih file yang akan dihapus!');
	history.back();
	</script><?php
	exit();
	} else if(empty($total)) {	
	?><script>
	alert('File tidak ditemukan!');
	history.back();
	</script><?php
	exit();
	} else {
	$sql1 = $db->query("select max(id_tr_pesan) as id_tr_pesan from tr_pesan where codepesan = '$codepesam'");	
	while($row1 = $db->fetchArray($sql1)){
	//echo $letakfile;
	//exit;
	$sqlc = $db->query("delete from tr_file_pesan where id_tr_pesan='$row1[id_tr_pesan]' and link_file='$nmfile'");
	}
	//unlink("$letakfile");
	//echo $sqla['st_hapus_tujuan'];
	//exit;
	//echo "$sqlb";
	//exit;
	  
		if ($sqlc) {
		$idnya = maxiline($sql1['id_tr_pesan'], 'e');
		$inya = maxiline("e", 'e');
		echo "<script>location.href='pesan_tulis.php?id=$idnya&i=$inya';</script>";
			  exit();
		} else { 
		echo " <script>alert('File tidak bisa dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
    }
   }     
?>   
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
$codepesan = SafeSQL($_POST['codepesan']);
$filegbr = $_POST['filegbr'];

//print_r($_POST)."<br>";
//exit();
$tujuke = implode(",",$id_user_tujuan);

if ($_POST['b'] == 'e') {
	
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
	$hitungan = count($id_user_tujuan);	
	
	$sql = "select * from tr_pesan where codepesan='$codepesan'";
	$jml = $db->queryNumRows($sql);
	$total = $db->getNumRows($jml);		
		
	if($hitungan < $total) {
	$selisih = 	$total - $hitungan;
		
	$sqla = $db->query("select top($selisih) * from tr_pesan where codepesan = '$codepesan'");
	while($rowa = $db->fetchArray($sqla)) {
	$sqlb = $db->query("delete from tr_pesan where id_tr_pesan='$rowa[id_tr_pesan]'");	
	$sqlc = $db->query("delete from tr_file_pesan where id_tr_pesan='$rowa[id_tr_pesan]'");
	}
	}
	
	$sqlz = $db->query("select link_file from (select max(id_tr_pesan) as id_tr_pesan from tr_pesan where codepesan = '$codepesan')a left join tr_file_pesan tfp on a.id_tr_pesan = tfp.id_tr_pesan");
	
	$sqld = $db->query("select * from tr_pesan where codepesan = '$codepesan'");
	$id_pesan = array();
	while($rowd = $db->fetchArray($sqld)){
	$id_pesan[] = $rowd['id_tr_pesan'];	
	}
	
	foreach ($id_user_tujuan as $x=>$idnya) {
	$id_tujuan = SafeSQL($idnya);
	$id_tr_psn = $id_pesan[$x];
	
	if(!empty($id_tr_psn)) {	
	if($_POST['draft'] == 2){
	$sqla ="update tr_pesan set id_user_tujuan = '$id_tujuan', id_user_pengirim = '$id_user_pengirim', subyek = '$subyek', pesan = '$pesan', st_pesan = '2' where id_tr_pesan = '$id_tr_psn'";
	} else {
	$sqla ="update tr_pesan set id_user_tujuan = '$id_tujuan', id_user_pengirim = '$id_user_pengirim', subyek = '$subyek', pesan = '$pesan', st_pesan = '1' where id_tr_pesan = '$id_tr_psn'";	
	}	
	$resa =  $db->query($sqla);
	$id_tr_pesan_new[] = $id_tr_psn;
	} 
	
	if(empty($id_tr_psn)) {
	if($_POST['draft'] == 2){
	$sqla ="insert into tr_pesan (id_user_tujuan, id_user_pengirim, subyek, pesan, st_pesan, codepesan) values ('$id_tujuan', '$id_user_pengirim', '$subyek', '$pesan', '2', '$codepesan')";
	} else {
	$sqla ="insert into tr_pesan (id_user_tujuan, id_user_pengirim, subyek, pesan, codepesan) values ('$id_tujuan', '$id_user_pengirim', '$subyek', '$pesan', '$codepesan')";	
	}	
	$resaa =  $db->query($sqla);
	$akhir = $db->fetchArray($db->query("select SCOPE_IDENTITY() as lastid from tr_pesan"));	
	while($rowz = $db->fetchArray($sqlz)){
	$resa = $db->query("insert into tr_file_pesan (id_tr_pesan, link_file) values ('$akhir[lastid]', '$rowz[link_file]')");
	}
	$id_tr_pesan_new[] = $akhir['lastid'];
	}	
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
	?><script>
	alert('Pilih file yg akan diupload!');
	history.back();
	</script><?php
	exit();
	} else if ($size >= 5000000) {	
	?><script>
	alert('Size file dokumen tidak boleh lebih dari 5Mb!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/file_pesan/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
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
  }	else {
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
	   } else { 
		echo " <script>alert('Pesan gagal dikirim!');</script>
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
   } else if ($_POST['j'] == 'a') {
	
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
	$resa =  $db->query($sqla);
	$akhir = $db->fetchArray($db->query("select SCOPE_IDENTITY() as lastid from tr_pesan"));
	$id_tr_pesan_new[] = $akhir['lastid'];
	}
	//echo $sqla."<br>";
	//exit();
	// insert database
	if ($resa) {
	foreach ($id_tr_pesan_new as $x=>$idtrpesan) {
	foreach ($filegbr as $a=>$filee) {
	$sqlb = $db->query ("insert into tr_file_pesan (id_tr_pesan, link_file) values ('$idtrpesan', '$filee')");
	}}
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
	?><script>
	alert('Pilih file yg akan diupload!');
	history.back();
	</script><?php
	exit();
	} else if ($size >= 5000000) {	
	?><script>
	alert('Size file dokumen tidak boleh lebih dari 5Mb!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/file_pesan/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
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
   }
?>   
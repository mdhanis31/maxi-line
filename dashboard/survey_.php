<?
@session_start();

include "include/session.php";
include "include/DbConnector.php";
include "include/config.php";
$db = new DbConnector();

$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");
//echo $tgle."<br>";
//echo $tgl;
//exit;
$tgl_survey = date("Y-m-d", strtotime($_POST['tglsurvey']))." ".$_POST['jamsurvey'];
$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);

$st_survey = SafeSQL($_POST['st_survey']);
$ket_survey = SafeSQL($_POST['ket_survey']);
$id_tb_rencana = SafeSQL($_POST['id_tb_rencana']);
$id_tb_survey = SafeSQL($_POST['id_tb_survey']);

$id_tb_usere = implode(',',$_POST['id_tb_user']);

//print_r($_POST)."<br>";
//echo $tgl_survey;
//exit();


if ($_POST['f'] == 'n'){
	
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {

	if(empty($_POST['tglsurvey'])) {
    ?><script>
      alert('Tanggal survey tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($_POST['jamsurvey'])) {
    ?><script>
      alert('Jam survey tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($st_survey)) {
    ?><script>
      alert('Hasil survey harus dipilih!');
      history.back();
      </script><?php
	  exit();
	} else {
		
		$sqld = $db->query("Update tb_survey set sts_survey = '2' where id_tb_pendaftaran ='$id_tb_pendaftaran'");
		
		$sqla = $db->query ("insert into tb_survey (id_tb_pendaftaran, id_tb_user, st_survey, ket_survey, tgl_survey, id_tb_rencana)
		values ('$id_tb_pendaftaran', '$id_tb_usere', '$st_survey', '$ket_survey', '$tgl_survey', '$id_tb_rencana')");		
		
		$sqlb = $db->query ("update tb_rencana set st_rencana = '2' where id_tb_rencana = '$id_tb_rencana'");
		
		if($st_survey == 1) {$st_layanan = "3";} else if($st_survey == 2) {$st_layanan = "4";} else if($st_survey == 3) {$st_layanan = "2";}
		$sqlc = $db->query ("update tb_pendaftaran set st_layanan = '$st_layanan' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		if ($sqla) {
		if($_POST['kirimnotif'] == 1) {
		$sqld = $db->fetchArray($db->query("SELECT SCOPE_IDENTITY() as lastid from tb_survey"));
		$id_rencana = maxiline($sqld['lastid'], 'e');
		$tipenya = maxiline("survey", 'e');	
		echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
		exit();	
		} else {			
		$id_daftar = maxiline($id_tb_pendaftaran, 'e');	
		echo " <script>alert('Survey berhasil ditambahkan');</script>
			  <script>location.href='pendaftaran_dtl.php?id=$id_daftar';</script>";
			  exit();
		}} else { 
		echo " <script>alert('Survey gagal ditambahkan!');</script>
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
} else if($_POST['f'] == 'e') {
	
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {
		
	$sqla = $db->query ("update tb_survey set id_tb_user='$id_tb_usere', st_survey='$st_survey', ket_survey='$ket_survey', tgl_survey='$tgl_survey' where id_tb_survey='$id_tb_survey'");
	if($st_survey == 1) {$st_layanan = "3";} else if($st_survey == 2) {$st_layanan = "4";} else if($st_survey == 3) {$st_layanan = "2";}
	$sqlc = $db->query ("update tb_pendaftaran set st_layanan = '$st_layanan' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		if ($sqla) {
			if($_POST['kirimnotif'] == 1) {			
			$id_rencana = maxiline($id_tb_survey, 'e');
			$tipenya = maxiline("survey", 'e');	
			echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
			  exit();	
		} else {			
			if($_POST['t']=='u') {
			echo " <script>alert('Survey berhasil diupdate');</script>
				  <script>location.href='survey_v.php';</script>";
				  exit();
			} else {
			$id_daftar = maxiline($id_tb_pendaftaran, 'e');	
			echo " <script>alert('Data berhasil diupdate');</script>
			  <script>location.href='pendaftaran_dtl.php?id=$id_daftar';</script>";
			  exit();
			}			
		}} else { 
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
} else if($_GET['s'] == 'h') {
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_GET['token']==$kodeaman) {
	
	$id_tb_survey = maxiline(SafeSQL($_GET['id']), 'd');
	$sqla = $db->fetchArray($db->query ("Select * from tb_survey where id_tb_survey='$id_tb_survey'"));
	//echo "$id_tb_survey";
	//exit;
	$sqlc = $db->query ("Update tb_rencana set st_rencana = '1' where id_tb_rencana = '$sqla[id_tb_rencana]'");
	$sqld = $db->query ("Update tb_survey set sts_survey = '1' where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'");
	$sqlb = $db->query ("Delete from tb_survey where id_tb_survey='$id_tb_survey'");
	
	$sql = "SELECT * FROM tb_file_pendaftaran WHERE id_tb_proses = '$id_tb_survey' and st_proses = '1'";
	$res = $db->query($sql);
	while($row = $db->fetchArray($res)) {
	unlink("$row[link_gbr]");	
	}
	$sqle = $db->query ("Delete from tb_file_pendaftaran where id_tb_proses='$id_tb_survey' and st_proses = '1'");
	
	if ($sqle) {
	$id_daftar = maxiline($sqla['id_tb_pendaftaran'], 'e');		
		echo " <script>alert('Data berhasil dihapus');</script>
		  <script>location.href='pendaftaran_dtl.php?id=$id_daftar';</script>";
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
	
} else if($_POST['l'] == 'b') {
	
	$judul_laporan = SafeSQL($_POST['judul_laporan']);
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_jns_laporan = SafeSQL($_POST['id_tb_survey']);
	$tgl_laporan = date("Y-m-d", strtotime($_POST['tgl_laporan']));
	$isi_laporan = SafeSQL($_POST['isi_laporan']);
	
	unset($_SESSION['token']);
	session_write_close();
	//if ($kodeaman && $_POST['token']==$kodeaman) {

		if(empty($_POST['tgl_laporan'])) {
		?><script>
		  alert('Tanggal tidak boleh kosong!');
		  history.back();
		  </script><?php
		  exit();
		} else if(empty($_POST['judul_laporan'])) {
		?><script>
		  alert('Judul laporan tidak boleh kosong!');
		  history.back();
		  </script><?php
		  exit();
		} else if(empty($isi_laporan)) {
		?><script>
		  alert('Isi laporan tidak boleh kosong!');
		  history.back();
		  </script><?php
		  exit();
		} else {
		
		$sqla = "insert into tb_laporan (judul_laporan, id_tb_pendaftaran, id_tb_user, jns_laporan, id_jns_laporan, tgl_laporan, isi_laporan)
		values ('$judul_laporan', '$id_tb_pendaftaran', '$_SESSION[id_tb_user]', '1', '$id_jns_laporan', '$tgl_laporan', '$isi_laporan')";	
		$resa = $db->query($sqla);
		//echo $sqla;
		//exit;
		
			if ($resa) {				
				if($_POST['kirimnotif'] == 2) {
					$sqld = $db->fetchArray($db->query("SELECT SCOPE_IDENTITY() as lastid from tb_laporan"));
					$id_rencana = maxiline($sqld['lastid'], 'e');
					$tipenya = maxiline("laporan", 'e');	
					echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
				exit();	
				} else {				
					$id_survey = maxiline($id_jns_laporan, 'e');		
					echo " <script>alert('Data berhasil diinput');</script>
					<script>location.href='survey_add.php?id=$id_survey&s=d';</script>";
					exit();
				}
			} else { 
				echo " <script>alert('Data gagal diinput!');</script>
				<script>history.back();</script>";
				exit();
			}	
		
		}
		
	//} else {
	//echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     //<script>history.back();</script>";
		//exit();		
	//}

} else if($_POST['l'] == 'e') {
	
	$judul_laporan = SafeSQL($_POST['judul_laporan']);
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_jns_laporan = SafeSQL($_POST['id_tb_survey']);
	$tgl_laporan = date("Y-m-d", strtotime($_POST['tgl_laporan']));
	$isi_laporan = SafeSQL($_POST['isi_laporan']);
	$id_tb_laporan = SafeSQL($_POST['id_tb_laporan']);
	
	//print_r($_POST);
	//exit;
	
	unset($_SESSION['token']);
	session_write_close();
	//if ($kodeaman && $_POST['token']==$kodeaman) {

		if(empty($_POST['tgl_laporan'])) {
		?><script>
		  alert('Tanggal tidak boleh kosong!');
		  history.back();
		  </script><?php
		  exit();
		} else if(empty($_POST['judul_laporan'])) {
		?><script>
		  alert('Judul laporan tidak boleh kosong!');
		  history.back();
		  </script><?php
		  exit();
		} else if(empty($isi_laporan)) {
		?><script>
		  alert('Isi laporan tidak boleh kosong!');
		  history.back();
		  </script><?php
		  exit();
		} else {
		
		$sqla = "update tb_laporan set judul_laporan = '$judul_laporan', id_tb_pendaftaran = '$id_tb_pendaftaran', id_tb_user = '$_SESSION[id_tb_user]', jns_laporan = '1', id_jns_laporan = '$id_jns_laporan', tgl_laporan = '$tgl_laporan', isi_laporan = '$isi_laporan' where id_tb_laporan = '$id_tb_laporan' ";	
		$resa = $db->query($sqla);
		//echo $sqla;
		//exit;
		
			if ($resa) {				
				if($_POST['kirimnotif'] == 2) {
					$id_rencana = maxiline($id_tb_laporan, 'e');
					$tipenya = maxiline("laporan", 'e');	
					echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
					exit();	
				} else {				
					$id_survey = maxiline($id_jns_laporan, 'e');		
					echo " <script>alert('Data berhasil diinput');</script>
					<script>location.href='survey_add.php?id=$id_survey&s=d';</script>";
					exit();
				}
			} else { 
				echo " <script>alert('Data gagal diinput!');</script>
				<script>history.back();</script>";
				exit();
			}	
		
		}	
}		
?>
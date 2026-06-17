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
$tgl_pemutusan = date("Y-m-d", strtotime($_POST['tglpemutusan']))." ".$_POST['jampemutusan'];
$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);

$st_pemutusan = SafeSQL($_POST['st_pemutusan']);
$ket_pemutusan = SafeSQL($_POST['ket_pemutusan']);
$id_tb_rencana = SafeSQL($_POST['id_tb_rencana']);
$id_tb_pemutusan = SafeSQL($_POST['id_tb_pemutusan']);

$id_tb_usere = implode(',',$_POST['id_tb_user']);

//print_r($_POST)."<br>";
//echo $tgl_survey;
//exit();
$id_daftar = maxiline($id_tb_pendaftaran, 'e');

if ($_POST['f'] == 'n'){
	
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {

	if(empty($_POST['tglpemutusan'])) {
    ?><script>
      alert('Tanggal tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($_POST['jampemutusan'])) {
    ?><script>
      alert('Jam tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($st_pemutusan)) {
    ?><script>
      alert('Hasil harus dipilih!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($ket_pemutusan)) {
    ?><script>
      alert('Keterangan harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else {
		
		$sql = $db->query("Update tb_perawatan set sts_perawatan = '2' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		$sqld = $db->query("Update tb_pemutusan set sts_pemutusan = '2' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		$sqla = $db->query ("insert into tb_pemutusan (id_tb_pendaftaran, id_tb_user, st_pemutusan, ket_pemutusan, tgl_pemutusan, id_tb_rencana)
		values ('$id_tb_pendaftaran', '$id_tb_usere', '$st_pemutusan', '$ket_pemutusan', '$tgl_pemutusan', '$id_tb_rencana')");		
		
		$sqlb = $db->query ("update tb_rencana set st_rencana = '2' where id_tb_rencana = '$id_tb_rencana'");
				
		if ($sqla) {
		if($_POST['kirimnotif'] == 1) {
		$sqld = $db->fetchArray($db->query("SELECT SCOPE_IDENTITY() as lastid from tb_pemutusan"));
		$id_rencana = maxiline($sqld['lastid'], 'e');
		$tipenya = maxiline("pemutusan", 'e');	
		echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
			  exit();	
		} else {		
		echo " <script>alert('Data berhasil ditambahkan');</script>
			  <script>location.href='pendaftaran_dtl.php?id=$id_daftar ';</script>";
			  exit();
		}} else { 
		echo " <script>alert('Data gagal ditambahkan!');</script>
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
		
	$sqla = $db->query ("update tb_pemutusan set id_tb_user='$id_tb_usere', st_pemutusan='$st_pemutusan', ket_pemutusan='$ket_pemutusan', tgl_pemutusan='$tgl_pemutusan' where id_tb_pemutusan='$id_tb_pemutusan'");	
		
		if ($sqla) {
			if($_POST['kirimnotif'] == 1) {			
			$id_rencana = maxiline($id_tb_pemutusan, 'e');
			$tipenya = maxiline("pemutusan", 'e');	
			echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
			exit();	
		} else {		
			if($_POST['t']=='u') {
			echo " <script>alert('Data berhasil diupdate');</script>
				  <script>location.href='pemutusan_v.php';</script>";
				  exit();
			} else {					
			echo " <script>alert('Data berhasil diupdate');</script>
			  <script>location.href='pendaftaran_dtl.php?id=$id_daftar ';</script>";
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
	
	$id_tb_pemutusan = maxiline(SafeSQL($_GET['id']),'d');
	$sqla = $db->fetchArray($db->query ("Select * from tb_pemutusan where id_tb_pemutusan='$id_tb_pemutusan'"));
	//echo "$sqla[id_tb_pendaftaran]";
	//exit;
	$sqlc = $db->query ("Update tb_rencana set st_rencana = '1' where id_tb_rencana = '$sqla[id_tb_rencana]'");
	$sqld = $db->query ("Update tb_pemutusan set sts_pemutusan = '1' where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'");
	$sqlb = $db->query ("Delete from tb_pemutusan where id_tb_pemutusan='$id_tb_pemutusan'");
	$sql = "SELECT * FROM tb_file_pendaftaran WHERE id_tb_proses = '$id_tb_pemutusan' and st_proses = '4'";
	$res = $db->query($sql);
	while($row = $db->fetchArray($res)) {
	unlink("$row[link_gbr]");	
	}
	$sqle = $db->query ("Delete from tb_file_pendaftaran where id_tb_proses='$id_tb_pemutusan' and st_proses = '4'");
	
	if ($sqle) {
		$id_daf = maxiline($sqla['id_tb_pendaftaran'], 'e');
		echo " <script>alert('Data berhasil dihapus');</script>
		  <script>location.href='pendaftaran_dtl.php?id=$id_daf';</script>";
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
	
}	else if($_POST['l'] == 'b') {
	
	$judul_laporan = SafeSQL($_POST['judul_laporan']);
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_jns_laporan = SafeSQL($_POST['id_tb_pemutusan']);
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
		values ('$judul_laporan', '$id_tb_pendaftaran', '$_SESSION[id_tb_user]', '5', '$id_jns_laporan', '$tgl_laporan', '$isi_laporan')";	
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
					$id_pemutusan = maxiline($id_jns_laporan, 'e');		
					echo " <script>alert('Data berhasil diinput');</script>
					<script>location.href='pemutusan_add.php?id=$id_pemutusan&s=d';</script>";
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
	$id_jns_laporan = SafeSQL($_POST['id_tb_pemutusan']);
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
		
		$sqla = "update tb_laporan set judul_laporan = '$judul_laporan', id_tb_pendaftaran = '$id_tb_pendaftaran', id_tb_user = '$_SESSION[id_tb_user]', jns_laporan = '5', id_jns_laporan = '$id_jns_laporan', tgl_laporan = '$tgl_laporan', isi_laporan = '$isi_laporan' where id_tb_laporan = '$id_tb_laporan' ";	
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
					$id_pemutusan = maxiline($id_jns_laporan, 'e');		
					echo " <script>alert('Data berhasil diinput');</script>
					<script>location.href='pemutusan_add.php?id=$id_pemutusan&s=d';</script>";
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
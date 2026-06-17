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
$tgl_aktivasi = date("Y-m-d", strtotime($_POST['tglaktivasi']))." ".$_POST['jamaktivasi'];
$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);

$st_aktivasi = SafeSQL($_POST['st_aktivasi']);
$ket_aktivasi = SafeSQL($_POST['ket_aktivasi']);
$id_tb_rencana = SafeSQL($_POST['id_tb_rencana']);
$id_tb_aktivasi = SafeSQL($_POST['id_tb_aktivasi']);

$id_tb_usere = implode(',',$_POST['id_tb_user']);

//print_r($_POST)."<br>";
//echo $tgl_survey;
//exit();
$id_daftar = maxiline($id_tb_pendaftaran, 'e');

if ($_POST['f'] == 'n'){
	
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {

	if(empty($_POST['tglaktivasi'])) {
    ?><script>
      alert('Tanggal pengaktifan tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($_POST['jamaktivasi'])) {
    ?><script>
      alert('Jam Pengaktifan tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($st_aktivasi)) {
    ?><script>
      alert('Hasil pengaktifan harus dipilih!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($ket_aktivasi)) {
    ?><script>
      alert('Keterangan/alasan hasil pengaktifan harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else {
		
		$sql1 = $db->query("Update tb_instalasi set sts_instalasi = '2' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		$sqld = $db->query("Update tb_aktivasi set sts_aktivasi = '2' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		$sqla = $db->query ("insert into tb_aktivasi (id_tb_pendaftaran, id_tb_user, st_aktivasi, ket_aktivasi, tgl_aktivasi, id_tb_rencana)
		values ('$id_tb_pendaftaran', '$id_tb_usere', '$st_aktivasi', '$ket_aktivasi', '$tgl_aktivasi', '$id_tb_rencana')");		
		
		$sqlb = $db->query ("update tb_rencana set st_rencana = '2' where id_tb_rencana = '$id_tb_rencana'");
		
		if($st_aktivasi == 1) {$st_layanan = "6";} else if($st_aktivasi == 2) {$st_layanan = "7";} else if($st_aktivasi == 3) {$st_layanan = "2";}
		$sqlc = $db->query ("update tb_pendaftaran set st_layanan = '$st_layanan' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		if ($sqla) {
		if($_POST['kirimnotif'] == 1) {
		$sqld = $db->fetchArray($db->query("SELECT SCOPE_IDENTITY() as lastid from tb_aktivasi"));
		$id_rencana = maxiline($sqld['lastid'], 'e');
		$tipenya = maxiline("aktivasi", 'e');	
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
		
	$sqla = $db->query ("update tb_aktivasi set id_tb_user='$id_tb_usere', st_aktivasi='$st_aktivasi', ket_aktivasi='$ket_aktivasi', tgl_aktivasi='$tgl_aktivasi' where id_tb_aktivasi='$id_tb_aktivasi'");
	if($st_aktivasi == 1) {$st_layanan = "6";} else if($st_aktivasi == 2) {$st_layanan = "7";} else if($st_aktivasi == 3) {$st_layanan = "2";}
	$sqlc = $db->query ("update tb_pendaftaran set st_layanan = '$st_layanan' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
		
		if ($sqla) {
			if($_POST['kirimnotif'] == 1) {			
			$id_rencana = maxiline($id_tb_aktivasi, 'e');
			$tipenya = maxiline("aktivasi", 'e');	
			echo "<script>location.href='mailnotif_.php?id=$id_rencana&j=$tipenya';</script>";
			exit();	
		} else {		
			if($_POST['t']=='u') {
			echo " <script>alert('Data berhasil diupdate');</script>
				  <script>location.href='aktivasi_v.php';</script>";
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
	
	$id_tb_aktivasi = maxiline(SafeSQL($_GET['id']),'d');
	$sqla = $db->fetchArray($db->query ("Select * from tb_aktivasi where id_tb_aktivasi='$id_tb_aktivasi'"));
	//echo "$sqla[id_tb_pendaftaran]";
	//exit;
	$sqlc = $db->query ("Update tb_rencana set st_rencana = '1' where id_tb_rencana = '$sqla[id_tb_rencana]'");
	$sqld = $db->query ("Update tb_aktivasi set sts_aktivasi = '1' where id_tb_pendaftaran = '$sqla[id_tb_pendaftaran]'");
	$sqlb = $db->query ("Delete from tb_aktivasi where id_tb_aktivasi='$id_tb_aktivasi'");
	$sql = "SELECT * FROM tb_file_pendaftaran WHERE id_tb_proses = '$id_tb_aktivasi' and st_proses = '3'";
	$res = $db->query($sql);
	while($row = $db->fetchArray($res)) {
	unlink("$row[link_gbr]");	
	}
	$sqle = $db->query ("Delete from tb_file_pendaftaran where id_tb_proses='$id_tb_aktivasi' and st_proses = '3'");
	
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
	
} else if($_POST['l'] == 'b') {
	
	$judul_laporan = SafeSQL($_POST['judul_laporan']);
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_jns_laporan = SafeSQL($_POST['id_tb_aktivasi']);
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
		values ('$judul_laporan', '$id_tb_pendaftaran', '$_SESSION[id_tb_user]', '3', '$id_jns_laporan', '$tgl_laporan', '$isi_laporan')";	
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
					$id_aktivasi = maxiline($id_jns_laporan, 'e');		
					echo " <script>alert('Data berhasil diinput');</script>
					<script>location.href='aktivasi_add.php?id=$id_aktivasi&s=d';</script>";
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
	$id_jns_laporan = SafeSQL($_POST['id_tb_instalasi']);
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
		
		$sqla = "update tb_laporan set judul_laporan = '$judul_laporan', id_tb_pendaftaran = '$id_tb_pendaftaran', id_tb_user = '$_SESSION[id_tb_user]', jns_laporan = '3', id_jns_laporan = '$id_jns_laporan', tgl_laporan = '$tgl_laporan', isi_laporan = '$isi_laporan' where id_tb_laporan = '$id_tb_laporan' ";	
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
					$id_aktivasi = maxiline($id_jns_laporan, 'e');		
					echo " <script>alert('Data berhasil diinput');</script>
					<script>location.href='instalasi_add.php?id=$id_aktivasi&s=d';</script>";
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
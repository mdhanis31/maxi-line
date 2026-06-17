<?
@session_start();
include "include/DbConnector.php";
include "include/config.php";
$db = new DbConnector();

$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d");
//echo $tgle."<br>";
//echo $tgl;
//exit;
$nama = SafeSQL($_POST['nama']);
$nama_area = SafeSQL($_POST['nama_area']);
$alamat = SafeSQL($_POST['alamat']);
$id_alamat = $_POST['id_alamat'];
$id_tb_user = $_SESSION['id_tb_user'];
$kuota = SafeSQL($_POST['kuota']);
$tglpasang = date("Y-m-d", strtotime($_POST['tgl_pasang']));
$latitude = SafeSQL($_POST['latitude']);
$longitude = SafeSQL($_POST['longitude']);
$keterangan_tiang = SafeSQL($_POST['keterangan']);

$id_tb_lokasi = SafeSQL($_POST['id_tb_lokasi']);
$id_tb_gbr_lokasi = SafeSQL($_POST['id_tb_gbr_lokasi']);

$nama_gbr = SafeSQL($_POST['nama_tiang']);
$ket_gbr = SafeSQL($_POST['keterangan_tiang']);

//print_r($_POST)."<br>";
//echo $tglpasang;
//exit();

if ($_GET['f'] == 'h') {

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_GET['token']==$kodeaman) {

	$id_tb_lokasi = SafeSQL(maxinile($_GET['id'], 'e'));
	$sqla = "delete from tb_lokasi where id_tb_lokasi = '$id_tb_lokasi'";
    $resa = $db->query($sqla);
	
	$sqlb = "select * from tb_gbr_lokasi where id_tb_lokasi = '$id_tb_lokasi'";
	$resb = $db->query($sqlb);
	while($rowb = $db->fetchArray($resb)) {	
	unlink("$rowb[link_gbr]");
	$sqlc = "delete from tb_gbr_lokasi where id_tb_gbr_lokasi = '$rowb[tb_gbr_lokasi]'";
    $resc = $db->query($sqlc);
	}

    if ($sqla) {	 
		echo "<script>alert('Data berhasil dihapus!');</script>
			  <script>location.href='lokasi_v.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Data gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 

 } else {
// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
 } 
} else if ($_GET['g'] == 'h') {

unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_GET['token']==$kodeaman) {

	$id_tb_gbr_lokasi = SafeSQL(maxiline($_GET['id'], 'd'));
	$sqla = "select * from tb_gbr_lokasi where id_tb_gbr_lokasi = '$id_tb_gbr_lokasi'";
    $resa = $db->query($sqla);
	$rowa = $db->fetchArray($resa);
	unlink("$rowa[link_gbr]");
	
	$sqlb = "delete from tb_gbr_lokasi where id_tb_gbr_lokasi = '$id_tb_gbr_lokasi'";
	$resb = $db->query($sqlb);
	
    if ($sqlb) {	 
		echo "<script>alert('Foto berhasil dihapus!');</script>
			   <script>location.href='lokasi_add.php?id=".maxiline($rowa['id_tb_lokasi'], 'e')."&f=d';</script>";
			  exit();
		} else { 
		echo " <script>alert('Foto gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 

 } else {
// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
 } 
} else if($_POST['u'] == 'f') {
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
//---------------------------------	
	if(empty($nama_gbr)) {
    ?><script>
      alert('Judul foto harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
	if (is_uploaded_file($_FILES['foto_tiang']['tmp_name'])) {
	$namepp = $_FILES['foto_tiang']['name'];
	$temp = $_FILES['foto_tiang']['tmp_name'];    
    $type = $_FILES['foto_tiang']['type'];
    $size = $_FILES['foto_tiang']['size'];
    	
	$permissible_extension = array("jpg", "png", "jpeg");
	$ext = pathinfo($namepp, PATHINFO_EXTENSION);
	$test = getimagesize($temp);
    $width = $test[0];
    $height = $test[1];
	
	if (empty($_FILES)) {	
	?><script>
	alert('Pilih gambar yg akan diupload!');
	history.back();
	</script><?php
	exit();
	} else if (!in_array($ext, $permissible_extension)) {	
	?><script>
	alert('File harus dalam format jpg/png/jpeg!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/foto_tiang/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
	exit();
	} else {
	
	//---------- input db
	$sqla = $db->query ("insert into tb_gbr_lokasi (id_tb_lokasi, nama_gbr, link_gbr, ket_gbr) values ('$id_tb_lokasi', '$nama_gbr', 'dist/foto_tiang/$namepp', '$ket_gbr')");
	// insert database
	if ($sqla) {	 
		echo "<script>alert('Foto berhasil disimpan!');</script>
			  <script>location.href='lokasi_add.php?id=".maxiline($id_tb_lokasi, 'e')."&f=d';</script>";
			  exit();
		} else { 
		echo " <script>alert('Foto gagal disimpan!');</script>
			   <script>history.back();</script>";
				exit();
	   } 	
	 } 
	} else {
		echo " <script>alert('Proses upload gambar gagal!');</script>
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
} else if($_POST['r'] == 'f') {
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {
	
//---------------------------------	
	if(empty($nama_gbr)) {
    ?><script>
      alert('Judul foto harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else {
	if (is_uploaded_file($_FILES['foto_tiang']['tmp_name'])) {
	$namepp = $_FILES['foto_tiang']['name'];
	$temp = $_FILES['foto_tiang']['tmp_name'];    
    $type = $_FILES['foto_tiang']['type'];
    $size = $_FILES['foto_tiang']['size'];
    	
	$permissible_extension = array("jpg", "png", "jpeg");
	$ext = pathinfo($namepp, PATHINFO_EXTENSION);
	$test = getimagesize($temp);
    $width = $test[0];
    $height = $test[1];
	
	if (empty($_FILES)) {	
	?><script>
	alert('Pilih gambar yg akan diupload!');
	history.back();
	</script><?php
	exit();
	} else if (!in_array($ext, $permissible_extension)) {	
	?><script>
	alert('File harus dalam format jpg/png/jpeg!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/foto_tiang/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
	exit();
	} else {
	
	//---------- input db
	$sqlb = "select * from tb_gbr_lokasi where id_tb_gbr_lokasi = '$id_tb_gbr_lokasi'";
    $resb = $db->query($sqlb);
	$rowb = $db->fetchArray($resb);
	unlink("$rowb[link_gbr]");
	
	$sqla = $db->query ("update tb_gbr_lokasi set nama_gbr = '$nama_gbr', link_gbr = 'dist/foto_tiang/$namepp', ket_gbr = '$ket_gbr' where id_tb_gbr_lokasi = '$id_tb_gbr_lokasi'");
	// insert database	
	
	if ($sqla) {	 
		echo "<script>alert('Foto berhasil diganti!');</script>
			  <script>location.href='lokasi_add.php?id=".maxiline($rowb['id_tb_lokasi'], 'e')."&f=d';</script>";
			  exit();
		} else { 
		echo " <script>alert('Foto gagal diganti!');</script>
			   <script>history.back();</script>";
				exit();
	   } 	
	 } 
	} else {
		$sqla = $db->query ("update tb_gbr_lokasi set nama_gbr = '$nama_gbr', ket_gbr = '$ket_gbr' where id_tb_gbr_lokasi = '$id_tb_gbr_lokasi'");
		
		$sqlb = "select * from tb_gbr_lokasi where id_tb_gbr_lokasi = '$id_tb_gbr_lokasi'";
		$resb = $db->query($sqlb);
		$rowb = $db->fetchArray($resb);
		
		if ($sqla) {	 
		echo "<script>alert('Data berhasil diupdate!');</script>
			  <script>location.href='lokasi_add.php?id=".maxiline($rowb['id_tb_lokasi'], 'e')."&f=d';</script>";
			  exit();
		} else { 
		echo " <script>alert('Data gagal diupdate!');</script>
			   <script>history.back();</script>";
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
} else {
unset($_SESSION['token']);
session_write_close();
if ($kodeaman && $_POST['token']==$kodeaman) {

if ($_POST['f'] == 'n'){

	if(empty($nama)) {
    ?><script>
      alert('Nama harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(empty($nama_area)) {
    ?><script>
      alert('Nama STO harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else if(empty($alamat)) {
    ?><script>
      alert('Alamat STO harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else if(empty($id_alamat)) {
    ?><script>
      alert('kecamatan, kota atau desa harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else if(empty($kuota)) {
    ?><script>
      alert('Kuota harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else if(empty($latitude)) {
    ?><script>
      alert('Latitude harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else if(empty($longitude)) {
    ?><script>
      alert('Longitude harus diisi!');
      history.back();
      </script><?php
	  exit();  
	} else {
		
		$desa = implode(',',$id_alamat);

		$sqla = $db->query ("insert into tb_lokasi (nama_tiang, nama_area, alamat_tiang, id_v_alamat, id_tb_user, kuota_tarikan, tgl_pemasangan, latitude, longitude, keterangan_tiang)
		values ('$nama', '$nama_area', '$alamat', '$desa', '$id_tb_user', '$kuota', '$tglpasang', '$latitude', '$longitude', '$keterangan_tiang')");
					
		if ($sqla) {	 
		echo " <script>alert('Data berhasil ditambahkan');</script>
			  <script>location.href='lokasi_v.php';</script>";
			  exit();
		} else { 
		echo " <script>alert('Data gagal ditambahkan!');</script>
			   <script>history.back();</script>";
				exit();
	   } 
	}				
} else if($_POST['f'] == 'e') {

		$desa = implode(',',$id_alamat);	
			
		$sqla = $db->query ("update tb_lokasi set nama_tiang='$nama', nama_area='$nama_area', alamat_tiang='$alamat', id_v_alamat='$desa', id_tb_user='$id_tb_user', 
							kuota_tarikan='$kuota', tgl_pemasangan='$tglpasang', latitude='$latitude', longitude='$longitude', keterangan_tiang='$keterangan_tiang'
							where id_tb_lokasi='$id_tb_lokasi'");
		
		if ($sqla) {	 
			echo " <script>alert('Data berhasil diupdate');</script>
			  <script>location.href='lokasi_v.php';</script>";
			  exit();
			} else { 
			echo " <script>alert('Data gagal diupdate!');</script>
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
}
?>
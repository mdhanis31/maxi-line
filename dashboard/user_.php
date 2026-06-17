<?php
@session_start();
include "include/config.php";
include "include/DbConnector.php";
include "include/ReferralCodeGenerator.php";


$db = new DbConnector();
$gen  = new ReferralCodeGenerator();

$kodeaman = $_SESSION['token'];
$id_user = $_SESSION['id_tb_user'];

$id_tb_user = SafeSQL($_POST['id_tb_user']);		
$nm_user = SafeSQL($_POST['nm_user']);
$telp = SafeSQL($_POST['telp']);
$email = SafeSQL($_POST['email']);
$level_user = SafeSQL($_POST['level_user']);
$username = SafeSQL($_POST['username']);
$passwd1 = SafeSQL($_POST['passwd1']);
$passwd2 = SafeSQL($_POST['passwd2']);
$passe = md5($_POST['passwd1']);
$pass1 = md5($_POST['passwd2']);
$alamat = SafeSQL($_POST['alamat']);
$jabatan = SafeSQL($_POST['jabatan']);
$referral = '';

// print_r($_POST)."<br>";
// echo $passe."<br>" ;
// echo $pass1;
// echo $referral;
// exit();


$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$kd_book = "";

for ($i = 0; $i < 5; $i++) {
	$kd_book .= $chars[mt_rand(0, strlen($chars)-1)];
}

if($_POST['j']=='b') {

	unset($_SESSION['token']);
	session_write_close();

	if ($kodeaman && $_POST['token'] == $kodeaman) {
		$sqla = "select * from tb_user where username='$username'";
		$resa = $db->query($sqla);
		$rowa = $db->fetchArray($resa);

	
		if(empty($rowa['id_tb_user'])) {
			$sqlb = "select * from tb_user where nm_user='$nm_user'";
			$resb = $db->query($sqlb);
			$rowb = $db->fetchArray($resb);	
	
			if(empty($rowa['id_tb_user'])) {
				//echo $sqlz['jmluser']."<br>";
				//echo $id_tb_pelanggan."<br>";
				//echo $slq['paket_langganan'];
				//exit;

				if(empty($nm_user)) { ?>
					<script>
						alert('Nama harus diisi!');
						history.back();
					</script>
					<?php exit();
				} elseif(!preg_match('/^[a-zA-Z ]*$/',$nm_user)) { ?>
					<script>
						alert('Nama harus berisi huruf!');
						history.back();
					</script>
					<?php exit();	  
				} elseif(empty($username)) { ?>
					<script>
						alert('Username tidak boleh kosong!');
						history.back();
					</script>
					<?php exit();
				} elseif($passwd1!=$passwd2) {?>
					<script>
						alert('Konfirmasi password tidak sama!');
						history.back();
					</script>
					<?php exit();
				} elseif($passwd1==$passwd2 && strlen($passwd1) < 5) { ?>
					<script>
						alert('password minimal 6 karakter, maksimal 12 karakter!');
						history.back();
					</script>
					<?php exit();
				} elseif($passwd1==$passwd2 && strlen($passwd1) > 14 ) { ?>
					<script>
						alert('password minimal 6 karakter, maksimal 14 karakter!');
						history.back();
					</script>
					<?php exit();
				} elseif(!preg_match("#[0-9]+#",$passwd1)) { ?>
					<script>
						alert('password harus terdapat angka!');
						history.back();
					</script>
					<?php exit();
				} elseif(!preg_match("#[A-Z]+#",$passwd1)) { ?>
					<script>
						alert('password harus terdapat huruf besar!');
						history.back();
					</script>
					<?php exit();
				} elseif(!preg_match("#[a-z]+#",$passwd1)) { ?>
					<script>
						alert('password harus terdapat huruf kecil!');
						history.back();
					</script>
					<?php exit();
				} else {
					// Generate new referral code for reseller!
					if ($level_user == 6 && empty($rowa['referral'])) {
						$referral = SafeSQL($gen->generate());

						if($referral == $rowa['referral']) {
							echo " <script>alert('Kode referral sudah pernah digunakan!');</script>
							<script>location.href='user_v.php';</script>";
							exit();
						}
					}

					if (is_uploaded_file($_FILES['pasfoto']['tmp_name'])) {
						$namepp = $_FILES['pasfoto']['name'];
						$temp = $_FILES['pasfoto']['tmp_name'];    
						$type = $_FILES['pasfoto']['type'];
						$size = $_FILES['pasfoto']['size'];
    	
						$permissible_extension = array("jpg", "png");
						$ext = pathinfo($namepp, PATHINFO_EXTENSION);
						$test = getimagesize($temp);
						$width = $test[0];
						$height = $test[1];
	
						if (empty($_FILES)) { ?>
							<script>
								alert('Pilih file yg akan diupload!');
								history.back();
							</script>
							<?php exit();
						} elseif(!in_array($ext, $permissible_extension)) {	?>
							<script>
								alert('File harus dalam format jpg / png!');
								history.back();
							</script>
							<?php exit();
						} elseif($size >= 500000) { ?>
							<script>
								alert('Size file dokumen tidak boleh lebih dari 500Kb!');
								history.back();
							</script>
							<?php exit();
						} elseif($width > 400 || $height > 400) { ?>
							<script>
								alert('Ukuran file tidak boleh lebih dari 400x400 pixels!');
								history.back();
							</script>
							<?php exit();
						} elseif(!move_uploaded_file($temp, "dist/foto_profil/" . $namepp)) { ?>
							<script>
								alert('File tidak bisa diupload!');
								history.back();
							</script>
							<?php exit();
						} else {	
							$newnamepp = $kd_book.$namepp;
							rename("dist/foto_profil/$namepp","dist/foto_profil/$newnamepp");

							$sql = $db->query("insert into tb_user (nm_user, email, telp, level_user, username, password, alamat, jabatan, pasfoto, referral) values ('$nm_user', '$email', '$telp', '$level_user', '$username', '$passe', '$alamat', '$jabatan', '$newnamepp', '$referral')");
						}
					} else {
						$sql = $db->query("insert into tb_user (nm_user, email, telp, level_user, username, password, alamat, jabatan, referral) values ('$nm_user', '$email', '$telp', '$level_user', '$username', '$passe', '$alamat', '$jabatan', '$referral')");	 
					}

					if ($sql) { 
						echo " <script>alert('User berhasil dibuat!');</script>
						<script>location.href='user_v.php';</script>";
						exit();
					} else { 
						echo " <script>alert('User gagal dibuat!');</script>
						<script>history.back();</script>";
						exit();
					}
				}
			} else {
				echo " <script>alert('Nama user sudah terdaftar!');</script>
				<script>location.href='user_v.php';</script>";
				exit();
			}
		} else {
			echo " <script>alert('Username sudah terdaftar!');</script>
			<script>location.href='user_v.php';</script>";
			exit();
		}
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
} else if($_POST['j']=='a') {

	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {
	
	$sqla = "select * from tb_user where username='$username' and id_tb_user != '$id_tb_user'";
	$resa = $db->query($sqla);
	$rowa = $db->fetchArray($resa);
		
	if(empty($rowa['id_tb_user'])) {
	$sqlb = "select * from tb_user where nm_user='$nm_user' and id_tb_user != '$id_tb_user'";
	$resb = $db->query($sqlb);
	$rowb = $db->fetchArray($resb);	
	$jmlb = $db->queryNumRows($sqlb);
	$totalb = $db->getNumRows($jmlb);
	
	if(empty($rowa['id_tb_user'])) {
	
	if(empty($nm_user)) {
    ?><script>
      alert('Nama harus diisi!');
      history.back();
      </script><?php
	  exit();
	} else if(!preg_match('/^[a-zA-Z ]*$/',$nm_user)) {
	 ?><script>
      alert('Nama harus berisi huruf!');
      history.back();
      </script><?php
	  exit();	  
	} else if(empty($username)) {
    ?><script>
      alert('Username tidak boleh kosong!');
      history.back();
      </script><?php
	  exit();
	} else {

	$sql = $db->query("update tb_user set nm_user='$nm_user', email='$email', telp='$telp', level_user='$level_user', username='$username', jabatan='$jabatan', alamat='$alamat' where id_tb_user= '$id_tb_user'");
	if ($sql) {
	if ($_SESSION['id_tb_user'] == '$id_tb_user') {
	$sqlb = $db->fetchArray($db->query ("select * from tb_user where id_tb_user='$id_tb_user'"));
	if ($_SESSION['username'] == '$username') {
	echo " <script>alert('Update user berhasil!');</script>
	<script>location.href='user_v.php';</script>";
	exit();
	} else {
	$idsx = maxiline($id_tb_user, 'e');
	echo " <script>alert('Update user berhasil, username anda telah berubah silahkan login ulang!');</script>
	<script>location.href='logout.php?id=$idsx';</script>";
	exit();
	}
	  } else {
	  echo " <script>alert('Update user berhasil!');</script>
	  <script>location.href='user_v.php';</script>";
	  exit();
	  }		
		} else { 
		echo " <script>alert('Update user gagal!');</script>
		 <script>history.back();</script>";
		exit();
		}
	}
	 } else {
		echo " <script>alert('Nama user sudah terdaftar!');</script>
		 <script>history.back();</script>";
		exit();
	 }
	} else {
		echo " <script>alert('Username sudah terdaftar!');</script>
		 <script>history.back();</script>";
		exit();
	}
   } else {
   // log potential CSRF attack.
	echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	<script>history.back();</script>";
	exit();
	} 
} else if($_POST['j']=='d') {

	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {
	
	if($passwd1!=$passwd2) {
    ?><script>
      alert('Konfirmasi password tidak sama!');
      history.back();
      </script><?php
	  exit();
	} ELSE if($passwd1==$passwd2 && strlen($passwd1) < 5) {
		?><script>
		alert('password minimal 6 karakter, maksimal 12 karakter!');
		history.back();
		</script><?php
		exit();
	} else if($passwd1==$passwd2 && strlen($passwd1) > 14 ) {
		?><script>
		alert('password minimal 6 karakter, maksimal 14 karakter!');
		history.back();
		</script><?php
		exit();
	} ELSE if(!preg_match("#[0-9]+#",$passwd1)) {
		?><script>
		alert('password harus terdapat angka!');
		history.back();
		</script><?php
		exit();
	} ELSE if(!preg_match("#[A-Z]+#",$passwd1)) {
		?><script>
		alert('password harus terdapat huruf besar!');
		history.back();
		</script><?php
		exit();
	} ELSE if(!preg_match("#[a-z]+#",$passwd1)) {
		?><script>
		alert('password harus terdapat huruf kecil!');
		history.back();
		</script><?php
		exit();
	} else {
		
	$sql = $db->query("update tb_user set password='$passe' where id_tb_user='$id_tb_user'");
	if ($sql) { 
	$idsx = maxiline($id_tb_user, 'e');
	if ($_SESSION['id_tb_user'] == $id_tb_user) {		
		echo " <script>alert('Password berhasil dirubah, silahkan login ulang!');</script>
		<script>location.href='logout.php?id=$idsx';</script>";
		exit();			
		} else { 
		echo " <script>alert('Update password berhasil!');</script>
		<script>location.href='user_add.php?i=$idsx&a=b';</script>";
		exit();
	   }	
	} else { 
	echo " <script>alert('Update password gagal!');</script>
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
} else if ($_GET['j'] == 'c') {
	
	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_GET['token']==$kodeaman) {
	
		//---------------------------------	
		$id_tb_user = maxiline(SafeSQL($_GET['i']), 'd');
		$sqlb = $db->query("update tb_user set sts_delete = '2' where id_tb_user='$id_tb_user'");
		if ($sqlb) {
			$idsx = maxiline($id_tb_user, 'e');
			if ($_SESSION['id_tb_user'] == '$id_tb_user') {		
				echo " <script>alert('User anda telah dihapus!');</script>
				<script>location.href='logout.php?id=$idsx';</script>";
				exit();			
			} else { 
				echo "<script>alert('User berhasil dihapus!');</script>
				<script>location.href='user_v.php';</script>";
				exit();
			}
		} else {
			echo " <script>alert('User gagal dihapus!');</script>
			<script>history.back();</script>";
			exit();
		}
	} else {
		// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
		<script>history.back();</script>";
		exit();
	}
} if($_POST['r']=='f') {

	unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {
	
	if (is_uploaded_file($_FILES['pasfoto']['tmp_name'])) {
	$namepp = $_FILES['pasfoto']['name'];
	$temp = $_FILES['pasfoto']['tmp_name'];    
    $type = $_FILES['pasfoto']['type'];
    $size = $_FILES['pasfoto']['size'];
    	
    $permissible_extension = array("jpg", "png");
	$ext = pathinfo($namepp, PATHINFO_EXTENSION);
	$test = getimagesize($temp);
    $width = $test[0];
    $height = $test[1];
	
	if (empty($_FILES)) {	
	?><script>
	alert('Pilih file yg akan diupload!');
	history.back();
	</script><?php
	exit();
	} else if (!in_array($ext, $permissible_extension)) {	
	?><script>
	alert('File harus dalam format jpg / png!');
	history.back();
	</script><?php
	exit();
	} else  if ($size >= 500000) {	
	?><script>
	alert('Size file dokumen tidak boleh lebih dari 500Kb!');
	history.back();
	</script><?php
	exit();
	} else if ($width > 400 || $height > 400) {
	?><script>
	alert('Ukuran file tidak boleh lebih dari 400x400 pixels!');
	history.back();
	</script><?php
	exit();
	} else if (!move_uploaded_file($temp, "dist/foto_profil/" . $namepp)) {	
	?><script>
	alert('File tidak bisa diupload!');
	history.back();
	</script><?php
	exit();
	} else {	

	$newnamepp = $kd_book.$namepp;
	rename("dist/foto_profil/$namepp","dist/foto_profil/$newnamepp");
	$sql = $db->query("update tb_user set pasfoto='$newnamepp' where id_tb_user = '$id_tb_user'");
	
	if ($sql) { 
	echo " <script>alert('Foto profil berhasil diupdate!');</script>
	<script>location.href='user_v.php';</script>";
	exit();
	} else { 
	echo " <script>alert('Foto profil gagal diupdate!');</script>
	 <script>history.back();</script>";
	exit();
	 }	
	} 
	 } else {
	echo " <script>alert('Foto gagal diupload!');</script>
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
	


<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";

require_once 'plugins/PHPmailer/class.phpmailer.php';

//exit();
$db = new DbConnector();
$kodeaman = $_SESSION['token'];

$now = new DateTime();
$tgl = $now->format("Y-m-d H:i:s");

$a = explode('-',$_POST["tgl_invoice"]);
$b = explode('-',$_POST["tgl_expired"]);
$c = explode('-',$_POST["tgl_putus"]);
$tanggal = $a[2].'-'.$a[1].'-'.$a[0];
$tanggel = $b[2].'-'.$b[1].'-'.$b[0];
$tanggil = $c[2].'-'.$c[1].'-'.$c[0];
$waktue = $now->format("H:i:s");
$waktu = $tanggal." ".$waktue;
$expired = $tanggel." ".$waktue;
$tglputus = $tanggil." ".$waktue;
$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
$id_tb_user = $_SESSION['id_tb_user'];

//echo $_POST['$tgl_invoice']."<br>";
//echo $expired."<br>";
//echo $waktu."<br>";
//print_r($_POST)."<br>";
//echo $tglputus;
//print_r($_POST['nama_transaksi'])."<br>";
//exit;

if ($_POST['status'] == 'awal') {
	
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	
	if (empty($id_tb_pendaftaran)) {	 
		echo " <script>alert('Pendaftaran tidak ditemukan!');</script>
	   <script>location.href='index.php';</script>";
		exit();
	}
	
	$nama_transaksi = SafeSQL($_POST['nama_transaksi']);
	$harga_transaksi = SafeSQL($_POST['harga_transaksi']);
	$jumlah = SafeSQL($_POST['jumlah']);
	$satuan = SafeSQL($_POST['satuan']);
	$sub_total = $jumlah * $harga_transaksi;
	$no_invoice = SafeSQL($_POST['no_invoice']);
	//echo $id_tb_pendaftaran;
	//exit;
	
	$sql = "insert into tr_invoice (id_tb_pendaftaran, no_invoice, tgl_invoice, tgl_expired, jns_invoice, id_tb_user_create) values ('$id_tb_pendaftaran', '$no_invoice', '$waktu', '$expired', '4', '$id_tb_user')";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	
	// insert database
	if ($res) {	
		$sqla = "SELECT SCOPE_IDENTITY() as lastid from tr_invoice";
		$resa = $db->query($sqla);
		$rowa = $db->fetchArray($resa);	

			
		$sql = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total) values ('$id_tb_pendaftaran', '$rowa[lastid]', '$nama_transaksi', '$harga_transaksi', '$jumlah', '$satuan', '$sub_total')";
		//echo $sql;
		//exit;	
		
		$res = $db->query($sql);
		$id_tb_pendaftarans = maxiline($id_tb_pendaftaran, 'e');
		$id_tr_invoices = maxiline($rowa['lastid'], 'e');
		if ($sql) {	 
			echo "<script>location.href='invoice_add4.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=4';</script>";
			exit();
		} else { 
			echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
			   <script>history.back();</script>";
			exit();
		} 
	} else { 
			echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
			   <script>history.back();</script>";
			exit();
		}
		
} else if ($_POST['input'] == "y") {
	
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
	$nama_transaksi = SafeSQL($_POST['nama_transaksi']);
	$harga_transaksi = SafeSQL($_POST['harga_transaksi']);
	$jumlah = SafeSQL($_POST['jumlah']);
	$satuan = SafeSQL($_POST['satuan']);
	$sub_total = $jumlah * $harga_transaksi;
	//echo $id_tb_pendaftaran;
	//exit;
	
	$sql = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total) values ('$id_tb_pendaftaran', '$id_tr_invoice', '$nama_transaksi', '$harga_transaksi', '$jumlah', '$satuan', '$sub_total')";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	$id_tb_pendaftarans = maxiline($id_tb_pendaftaran, 'e');
	$id_tr_invoices = maxiline($id_tr_invoice, 'e');
	if ($sql) {	 
		echo "<script>location.href='invoice_add4.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=3';</script>";
		exit();
	} else { 
		echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
		   <script>history.back();</script>";
		exit();
	} 	
} else if ($_GET['k'] == 'h') {
   
    unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_GET['token']==$kodeaman) {  
	
//---------------------------------	
	$id_tr_transaksi = SafeSQL($_GET['i']);
	
	$sqla = $db->query("delete from tr_transaksi where id_tr_transaksi='$id_tr_transaksi'");
	// insert database
	if ($sqla) {	 
		echo "<script>history.back();</script>";
			  exit();
		} else { 
		echo " <script>alert('Item gagal dihapus!');</script>
			   <script>history.back();</script>";
				exit();
	   } 		  
	} else {
// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
 } 
} else if ($_POST['edit'] == "n") {
	
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
	$nama_transaksi = SafeSQL($_POST['nama_transaksi']);
	$harga_transaksi = SafeSQL($_POST['harga_transaksi']);
	$id_tr_transaksi = SafeSQL($_POST['id_tr_transaksi']);
	$jumlah = SafeSQL($_POST['jumlah']);
	$satuan = SafeSQL($_POST['satuan']);
	$sub_total = $jumlah * $harga_transaksi;
	//echo $id_tb_pendaftaran;
	//exit;
	
	$sql = "update tr_transaksi set nama_transaksi = '$nama_transaksi', harga_transaksi = '$harga_transaksi', jumlah = '$jumlah', satuan = '$satuan', sub_total = '$sub_total' where id_tr_transaksi = '$id_tr_transaksi'";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	$id_tb_pendaftarans = maxiline($id_tb_pendaftaran, 'e');
	$id_tr_invoices = maxiline($id_tr_invoice, 'e');
	if ($sql) {	 
		echo "<script>location.href='invoice_add4.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=3';</script>";
		exit();
	} else { 
		echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
		   <script>history.back();</script>";
		exit();
	} 	
} else if ($_POST['terbit'] == 'ya') {
	
	if(empty($_POST['id_tr_invoice'])){
	echo " <script>alert('Biaya tambahan tidak boleh kosong!');</script>
	   <script>history.back();</script>";
		exit();
	}
   
    unset($_SESSION['token']);
	session_write_close();
	if ($kodeaman && $_POST['token']==$kodeaman) {  
	
//---------------------------------	
	$nama_transaksi1 = explode(',', $_POST['nama_transaksi1']);
	$harga_transaksi1 = explode(',', $_POST['harga_transaksi1']);
	
	$nama_transaksi2 = explode(',', $_POST['nama_transaksi2']);
	$harga_transaksi2 = explode(',', $_POST['harga_transaksi2']);
	$sub_total2 = explode(',', $_POST['sub_total2']);
	
	$nama_transaksi = $_POST['nama_transaksi'];
	$harga_transaksi = $_POST['harga_transaksi'];
	$sub_total = $_POST['sub_total'];
	$jumlah = $_POST['jumlah'];
	$satuan = $_POST['satuan'];
	
	$total = $_POST['total'];
	$id_tr_invoice = $_POST['id_tr_invoice'];
	
	$delall = $db->query("delete from tr_transaksi where id_tr_invoice = '$id_tr_invoice'");
	
	$sqlb = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
	
	$sqlg = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transaksi', '$harga_transaksi', '$jumlah', 'Rp', '$sub_total', '3')";
	//echo $sql;
	//exit;	
	$resg = $db->query($sqlg);
	
	$sqla = "update tr_invoice set tgl_data = '$tglputus', tot_tagih = '$total', jns_invoice = '4' where id_tr_invoice = '$id_tr_invoice'";
	//echo $sqla;
	//exit;	
	$resa = $db->query($sqla);	
	
	if(!empty($_POST['nama_transaksi1'])){
	foreach ($nama_transaksi1 as $a=>$nama_transak1) { 
	$nama_transak1 = $nama_transak1;
	$harga_transak1 = $harga_transaksi1[$a];
	
	$sqlf = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transak1', '$harga_transak1', '1', 'Rp', '$harga_transak1', '2')";
	//echo $sqle;
	//exit;	
	$resf = $db->query($sqlf);
	}
	}

	if(!empty($_POST['nama_transaksi2'])){
	foreach ($nama_transaksi2 as $b=>$nama_transak2) { 
	$nama_transak2 = $nama_transak2;
	$harga_transak2 = $harga_transaksi2[$b];
	$sub_tot2 = $sub_total2[$b];
	
	$sqlf = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transak2', '$sub_tot2', '$harga_transak2', '%', '$sub_tot2', '2')";
	//echo $sql;
	//exit;	
	$resf = $db->query($sqlf);
	}
	}
	
	if ($resf) {	 
	echo " <script>alert('Draft invoice disimpan, akan diterbitkan tanggal 5');</script>
		  <script>location.href='invoice_draft.php';</script>";
		  exit();
	} else { 
	echo " <script>alert('Draft invoice gagal diupdate!');</script>
		   <script>history.back();</script>";
			exit();
    }  		  
	} else {
// log potential CSRF attack.
		echo " <script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
	     <script>history.back();</script>";
		exit();
 } 
} else if ($_GET['h'] == 'y') {
   $id_tr_invoice = maxiline(SafeSQL($_GET['id']),'d');
   $sql = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
   
   if(empty($sql['id_tr_invoice'])){
		echo " <script>alert('Invoice tidak ditemukan!');</script>
		<script>history.back();</script>";
		exit();   
   } else {
	$sqla = $db->query("delete from tr_invoice where id_tr_invoice='$id_tr_invoice'");
	if ($sqla) {	 
	echo " <script>alert('Draft invoice berhasil dihapus');</script>
		  <script>location.href='invoice_draft.php';</script>";
		  exit();
	} else { 
	echo " <script>alert('Draft invoice gagal dihapus!');</script>
		   <script>history.back();</script>";
			exit();
   } 
  }
} else if ($_POST['invoice'] == "simpan") {
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	
	if (empty($id_tb_pendaftaran)) {	 
		echo " <script>alert('Pendaftaran tidak ditemukan!');</script>
	   <script>location.href='index.php';</script>";
		exit();
	}
	
	$no_invoice = SafeSQL($_POST['no_invoice']);
		
	$sql = "update tr_invoice set tgl_data = '$tglputus', jns_invoice = '4' where id_tr_invoice = '$id_tr_invoice'";
	$res = $db->query($sql);
		
	$ucap = "diupdate";
	
	$sql1 = "SELECT * FROM tr_transaksi where id_tr_invoice = '$id_tr_invoice' and jns_transaksi = '3'";
	$res1 = $db->query($sql1);
	$row1 = $db->fetchArray($res1);
	
	$tglmundur = date('Y-m-d 00:00:00', strtotime('-1 month', strtotime($waktu)));
	
	$awale = new DateTime($tglmundur);
	$akhire = new DateTime($tglputus);	
	
	$difference = $awale->diff($akhire);
	$jeda = $difference->days;

	$harganya = $row1['harga_transaksi'];
	$ratabulan = 30;	
	$biayahari = round($harganya / $ratabulan, -2);
	$haribiaya = $biayahari * $jeda;
	
	//echo "Jumlah hari = ".$jeda."<br>"."tagihan = ".$haribiaya."<br>".$tglmundur;
	//exit;
	
	$sql2 = "update tr_transaksi set jumlah = '$jeda', sub_total = '$haribiaya' where id_tr_transaksi = '$row1[id_tr_transaksi]'";
	$res2 = $db->query($sql2);
	
	
	//echo $sql;
	//exit;	
	
	if ($res2) {	 
	echo " <script>alert('Draft invoice berhasil $ucap');</script>
		  <script>location.href='invoice_draft.php';</script>";
		  exit();
	} else { 
	echo " <script>alert('Draft invoice gagal diupdate!');</script>
		   <script>history.back();</script>";
			exit();
   } 
} 
?>   
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
$tanggal = $a[2].'-'.$a[1].'-'.$a[0];
$tanggel = $b[2].'-'.$b[1].'-'.$b[0];
$waktue = $now->format("H:i:s");
$waktu = $tanggal." ".$waktue;
$expired = $tanggel." ".$waktue;
$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
$id_tb_user = $_SESSION['id_tb_user'];

//echo $_POST['$tgl_invoice']."<br>";
//echo $expired."<br>";
//echo $waktu."<br>";
//print_r($_POST)."<br>";
//print_r($_POST['nama_transaksi'])."<br>";
//exit;

if ($_POST['status'] == 'awal') {
	
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);
	
	if (empty($id_tb_pendaftaran)) {	 
		echo " <script>alert('Pendaftaran tidak ditemukan!');</script>
	   <script>location.href='index.php';</script>";
		exit();
	}
	
	$sq = "SELECT * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
	$re = $db->query($sq);
	$ro = $db->fetchArray($re);
	
	$nama_transaksi = SafeSQL($_POST['nama_transaksi']);
	$harga_transaksi = SafeSQL($_POST['harga_transaksi']);
	$jumlah = SafeSQL($_POST['jumlah']);
	$satuan = SafeSQL($_POST['satuan']);
	$sub_total = $jumlah * $harga_transaksi;
	$no_invoice = SafeSQL($_POST['no_invoice']);
	//echo $id_tb_pendaftaran;
	//exit;
	
	$sql = "insert into tr_invoice (id_tb_pendaftaran, id_tb_paket, no_invoice, tgl_invoice, tgl_expired, jns_invoice, id_tb_user_create) values ('$id_tb_pendaftaran','$ro[id_tb_paket]', '$no_invoice', '$waktu', '$expired', '3', '$id_tb_user')";
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
			echo "<script>location.href='invoice_add3.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=3';</script>";
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
		echo "<script>location.href='invoice_add3.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=3';</script>";
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
		echo "<script>location.href='invoice_add3.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=3';</script>";
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
	
	$total = $_POST['total'];
	
	$sqlg = "update tr_invoice set tgl_invoice = '$waktu', tgl_expired = '$expired', tot_tagih = '$total', sts_invoice = '2' where id_tr_invoice = '$id_tr_invoice'";
	//echo $sqlg;
	//exit;	
	$resg = $db->query($sqlg);
	
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
	//echo $sqlb['id_tb_pendaftaran']."<br>";
	//echo $id_tr_invoice;
	//exit;
	
			$id_invo = maxiline($id_tr_invoice, 'e');
			echo "<script>location.href='mailinvoice_.php?id=$id_invo';</script>";
			exit();	
 	} else { 
		echo " <script>alert('Invoice gagal diterbitkan!');</script>
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
	if(empty($_POST['id_tr_invoice'])){
		echo " <script>alert('Biaya tambahan tidak boleh kosong!');</script>
		   <script>history.back();</script>";
			exit();
		}
	$sql = "update tr_invoice set tgl_invoice = '$waktu', tgl_expired = '$expired' where id_tr_invoice = '$id_tr_invoice'";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	if ($res) {	 
	echo " <script>alert('Draft invoice berhasil diupdate');</script>
		  <script>location.href='invoice_draft.php';</script>";
		  exit();
	} else { 
	echo " <script>alert('Draft invoice gagal diupdate!');</script>
		   <script>history.back();</script>";
			exit();
   } 
} 
?>   
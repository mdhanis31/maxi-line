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
$tanggal = $a[2].'-'.$a[1].'-'.$a[0];
$waktue = $now->format("H:i:s");
$waktu = $tanggal." ".$waktue;
$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);
$id_tb_user = $_SESSION['id_tb_user'];

//echo $_POST['$tgl_invoice']."<br>";
//echo $tanggal."<br>";
//echo $waktu."<br>";
//print_r($_POST)."<br>";
//print_r($_POST['nama_transaksi'])."<br>";
//exit;

if ($_GET['n'] == 'y') {
	
	$id_tb_pendaftaran = maxiline(SafeSQL($_GET['id']),'d');
	
	if (empty($id_tb_pendaftaran)) {	 
		echo " <script>alert('Pendaftaran tidak ditemukan!');</script>
	   <script>location.href='index.php';</script>";
		exit();
	}
	
	$sq = "SELECT * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
	$re = $db->query($sq);
	$ro = $db->fetchArray($re);	
	
	$no_invoice = SafeSQL($_GET['kd']);
	$jns_invoice = SafeSQL($_GET['j']);
	
	$now = new DateTime();
	$th = $now->format("Y");
	$bl = $now->format("m");
	$Th = $now->format("y");
	$tg = $now->format("j");
	
	if($tg >= 5){
	$tgle = $now->format("Y-m-05");
	$tglinvoice = date("Y-m-d", strtotime('+1 month' , strtotime($tgle)))." ".$waktue ;
	$tglexpired = date("Y-m-d H:i:s", strtotime('+1 week' , strtotime($tglinvoice)));
	} else {
	$tglinvoice = $now->format("Y-m-05")." ".$waktue;
	$tglexpired = date("Y-m-d H:i:s", strtotime('+1 week' , strtotime($tglinvoice)));
	}
	
	// search invoice dg date terbit sama
	$sqlx = "SELECT * from tr_invoice where CONVERT(date, tgl_invoice) = CONVERT(date, '$tglinvoice') and id_tb_pendaftaran = '$id_tb_pendaftaran'";
	$resx = $db->query($sqlx);
	$rowx = $db->fetchArray($resx);

	if(!empty($rowx['id_tr_invoice'])) {
	echo "<script>location.href='invoice_draft.php';</script>";
	exit();	
	}	
	
	$sql = "insert into tr_invoice (id_tb_pendaftaran, id_tb_paket, no_invoice, tgl_invoice, tgl_expired, jns_invoice, id_tb_user_create) values ('$id_tb_pendaftaran','$ro[id_tb_paket]', '$no_invoice', '$tglinvoice', '$tglexpired', '2', '$id_tb_user')";
	//echo $sql;
	//exit;	
	$res = $db->query($sql);
	
	// insert database
	if ($res) {	
		$sqla = "SELECT SCOPE_IDENTITY() as lastid from tr_invoice";
		$resa = $db->query($sqla);
		$rowa = $db->fetchArray($resa);		
		
		$id_invoice = maxiline($rowa['lastid'], 'e');
		$id_daftar = maxiline($id_tb_pendaftaran, 'e');
		if($_GET['b']=="t"){
		echo "<script>location.href='invoice_pending.php?id=2';</script>";
		exit();
		} else {
		echo "<script>alert('Draft invoice baru telah dibuat!');</script>
		<script>location.href='invoice_add2.php?id=$id_daftar&i=$id_invoice&j=2';</script>";
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
		echo "<script>location.href='invoice_add2.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=1';</script>";
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
		echo "<script>location.href='invoice_add2.php?id=$id_tb_pendaftarans&i=$id_tr_invoices&j=1';</script>";
		exit();
	} else { 
		echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
		   <script>history.back();</script>";
		exit();
	} 	
} else if ($_POST['terbit'] == 'ya') {
   
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
		
		$total = round($_POST['total'],0,PHP_ROUND_HALF_DOWN);;
		$id_tr_invoice = $_POST['id_tr_invoice'];
	
		//print_r($nama_transaksi1);
		//exit;
		$sqlb = $db->fetchArray($db->query("SELECT * from tr_invoice where id_tr_invoice='$id_tr_invoice'"));
		
		$sqlg = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transaksi', '$harga_transaksi', '1', 'Rp', '$sub_total', '3')";
		//echo $sqlg;
		//exit;	
		$resg = $db->query($sqlg);
	
		$sqla = $db->query("update tr_invoice set tot_tagih = '$total' where id_tr_invoice='$id_tr_invoice'");	
	
		if(!empty($_POST['nama_transaksi1'])){
			foreach ($nama_transaksi1 as $a=>$nama_transak1) { 
				$nama_transak1 = $nama_transak1;
				$harga_transak1 = $harga_transaksi1[$a];
				
				$sqlf = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transak1', '$harga_transak1', '1', 'Rp', '$harga_transak1', '2')";
				// echo $sqle;
				// exit;	
				$resf = $db->query($sqlf);
			}
		}

		if(!empty($_POST['nama_transaksi2'])){
			foreach ($nama_transaksi2 as $b=>$nama_transak2) { 
				$nama_transak2 = $nama_transak2;
				$harga_transak2 = $harga_transaksi2[$b];
				$sub_tot2 = round($sub_total2[$b],0,PHP_ROUND_HALF_DOWN);
				
				$sqlf = "insert into tr_transaksi (id_tb_pendaftaran, id_tr_invoice, nama_transaksi, harga_transaksi, jumlah, satuan, sub_total, jns_transaksi) values ('$sqlb[id_tb_pendaftaran]', '$id_tr_invoice', '$nama_transak2', '$sub_tot2', '$harga_transak2', '%', '$sub_tot2', '2')";
				// echo $sqlf;
				// exit;	
				$resf = $db->query($sqlf);
			}
		}
	
		// insert database
		if ($resf) {
			//echo $sqlb['id_tb_pendaftaran']."<br>";
			//echo $id_tr_invoice;
			//exit;
			
			$id_daf = maxiline($sqlb['id_tb_pendaftaran'], 'e');
			$id_invo = maxiline($id_tr_invoice, 'e');
			
			echo " <script>alert('Invoice disimpan dan akan diterbitkan tanggal 5!');</script>
			<script>location.href='invoice_add2.php?id=$id_daf&i=$id_invo&j=2';</script>";
			exit();
		} else { 
			echo " <script>alert('Invoice gagal disimpan!');</script>
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
	$sql = "update tr_invoice set tgl_invoice = '$waktu' where id_tr_invoice = '$id_tr_invoice'";
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
} else if ($_GET['l'] == 'y') {
	$id_tr_invoices = maxiline(SafeSQL($_GET['id']),'d');
	$sql = "update tr_invoice set sts_lunas = '2', tgl_dibayar = '$tgl ' where id_tr_invoice = '$id_tr_invoice'";
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
} else if ($_POST['rubah_paket'] == 'ya') {
	$id_tb_pendaftaran = SafeSQL($_POST['id_tb_pendaftaran']);	
	$id_tb_paket = SafeSQL($_POST['id_tb_paket']);
	$id_tr_invoice = SafeSQL($_POST['id_tr_invoice']);	
	
	$sqle = $db->query("update tb_pendaftaran set id_tb_paket = '$id_tb_paket' where id_tb_pendaftaran = '$id_tb_pendaftaran'");
	$sqlf =  $db->fetchArray($db->query("SELECT TOP (1) * FROM tr_invoice where id_tb_pendaftaran = '$id_tb_pendaftaran' order by id_tr_invoice desc"));
	$sqle = $db->query("update tr_invoice set id_tb_paket = '$id_tb_paket' where id_tr_invoice = '$sqlf[id_tr_invoice]'");
	
	if($sqle) {
		
	$id_invoice = maxiline($id_tr_invoice, 'e');
	$id_daftar = maxiline($id_tb_pendaftaran, 'e');
		 
	echo "<script>location.href='invoice_add2.php?id=$id_daftar&i=$id_invoice&j=2';</script>";
	exit();
		
	} else {
		echo " <script>alert('Mohon maaf terjadi kesalahan system!');</script>
		   <script>history.back();</script>";
		exit();
	}	
}
?>   
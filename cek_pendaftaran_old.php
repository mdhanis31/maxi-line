<?php
include "dashboard/include/config.php";
include "dashboard/include/DbConnector.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>MAXI LINE</title>
  <meta content="Internet cepat dengan biaya ekonomis" name="description">
  <meta content="internet, wifi, fo, isp, provider, internet murah, internet cepat" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Maundy - v2.1.0
  * Template URL: https://bootstrapmade.com/maundy-free-coming-soon-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
.loading {
    color: black;
	margin-bottom: 15px;
}

.yoga {
    background: #ff6600;
    border: 0;
    padding: 10px 34px;
    color: #fff;
    transition: 0.4s;
    border-radius: 50px;	
}	

a.yoga:hover {
	background: #ff822f !important;	
	color: #fff !important;
}

.yogas {
    background: #ff6600;
    border: 0;
    padding: 10px 24px;
    color: #fff;
    transition: 0.4s;
    border-radius: 50px;	
}
</style>
<body>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex flex-column align-items-center">

      <h1><img src="images/MaxiLine.png" alt="" style="width:40%;display: block;margin-left: auto; margin-right: auto;"></h1>
      <h2>Jaringan internet cepat dan murah untuk area tertentu</h2>     
    </div>
  </header><!-- End #header -->

  <main id="main">

    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact" style="padding: 10px 0;">
      <div class="container">
		<?
		 if ($_POST['j'] == 'a') {		
		  
			$db = new DbConnector();
			$sql = "select * from tb_pendaftaran where email = '$_POST[email]' and kode_daftar = '$_POST[kode]'";
			$res = $db->query($sql);
			$row = $db->fetchArray($res);
			if(empty($row['id_tb_pendaftaran'])){?>
			<div class="section-title" style="margin-top: -60px;">
			  <h2><b>Data tidak ditemukan, hubungi kami untuk memastikan pendaftaran anda.</b></h2>
			</div>

			 <div class="mb-3" style="margin-bottom:5rem!important;">                
              <div class="text-center"><a class="yoga" href="index.php">Kembali</a></div>
			</div>
			<?}	else {
			$sqld = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$row[id_tb_paket]'"));	
			?>
			<div class="section-title" style="margin-top: -60px;">
			  <h2><b>Detail Pendaftaran</b></h2>
			</div>
			<div class="form-row">
			<div class="form-group col-md-6">
			<table class="table table-bordered" style="color:white;">
			<tr>
			<td>Kode Pendaftaran</td>
			<td>:</td>
			<td><?=$row['kode_daftar'];?></td>
			</tr>
			<tr>
			<td>Paket</td>
			<td>:</td>
			<td><?=$sqld['nama_paket'];?></td>
			</tr>
			<tr>
			<td>Tanggal Pendaftaran</td>
			<td>:</td>
			<td><?=tglindo(date_format($row['tgl_data'], 'Y-m-d'));?></td>
			</tr>
			<tr>
			<td>Status</td>
			<td>:</td>
			<td><?=$st_layanan[$row['st_layanan']];?></td>
			</tr>
			</table>
			</div>
			<div class="form-group col-md-6">
			<?
			$sqlb = "select * from tb_rencana where id_tb_pendaftaran = '$row[id_tb_pendaftaran]'";
			$resb = $db->query($sqlb);
			$query_jml = $db->queryNumRows($sqlb);
			$jmlh = $db->getNumRows($query_jml);			
			?>
			<table class="table table-bordered" style="color:white;">
						
			<?if(empty($jmlh)) {
				echo "<tr><td>Belum ada jadwal</td></tr>";	
				} else {?>
			<tr>
			<td>Tanggal</td>
			<td>Jadwal</td>
			</tr>
			<? $allrencana = array();
				$rencananya = array();
				while($rowb = $db->fetchArray($resb)) {?>
				<tr>
				<td><?=date_format($rowb['tgl_rencana'], 'd-m-Y H:i:s');?></td>
				<td><?=$st_rencana[intval("0".$rowb['rencana'])];?></td>				
				<?}
				}?>						
			</table>
			</div>
			</div>
			 <div class="mb-3" style="margin-bottom:5rem!important;margin-top:5rem!important;">                
              <div class="text-center"><a class="yoga" href="index.php">Kembali</a></div>
			</div>
			<?}			 
		 } else {?>
        <div class="section-title">
          <h2>Cek Pendaftaran Anda</h2>
        </div>
		
        <div class="row">
          <div class="col-lg-12 mt-5 mt-lg-0 d-flex align-items-stretch">
            <form action="cek_pendaftaran.php" method="post" role="form" class="php-email-form" enctype="multipart/form-data" name="form" id="form" role="form">
			<input type="hidden" name="j" value="a" />	
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Kode Pendaftaran</label>
                  <input type="text" name="kode" class="form-control" id="name" data-rule="minlen:4" placeholder="Masukkan kode pendaftaran" required/>
                  <div class="validate"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Email Terdaftar</label>
                  <input type="email" class="form-control" name="email" id="email" data-rule="email" placeholder="Masukkan email terdaftar" required/>
                  <div class="validate"></div>
                </div>
              </div>              
			  <input name="nama_lengkap" data-provide="typeahead" type="text" style="display: none;" >
              <div class="mb-3">
                <div class="loading">Waiting</div>
    <!--            <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div> -->
              <div class="text-center"><button type="submit" id="kirim">Submit</button></div>
            </form>
			</div>
        </div>
      </div>
	  <?}?>
	   
    </section><!-- End Contact Us Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Manunggal Sistem Sejahtera</span></strong>. All Rights Reserved
      </div>
      <div class="credits">       
       
      </div>
    </div>
  </footer><!-- End #footer -->

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
<!--  <script src="assets/vendor/php-email-form/validate.js"></script> -->
  <script src="assets/vendor/jquery-countdown/jquery.countdown.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
 
  <script>
  $('#form').submit(function() {
 //   event.preventDefault();
    $('.loading').show(); // show animation
	$('#kirim').prop('disabled', true);
    return true; // allow regular form submission
  });  
  </script>
</body>

</html>
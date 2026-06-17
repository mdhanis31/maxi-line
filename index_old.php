<?php
@session_start();

//print_r($_SESSION);

include "dashboard/include/config.php";
include "dashboard/include/DbConnector.php";

if (empty($_SESSION['token'])) {
  $_SESSION['token'] = md5(uniqid(rand(), TRUE));
}
$token = $_SESSION['token'];
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

a {
  color: #ffffff;
}
</style>
<body>

<!-- ======= Header ======= -->
<header id="header" class="d-flex align-items-center">
  <div class="container d-flex flex-column align-items-center">

    <h1><img src="images/MaxiLine.png" alt="" style="width:40%;display: block;margin-left: auto; margin-right: auto;"></h1>
    <h2>Jaringan internet cepat dan murah untuk area tertentu</h2>
    <div class="container" style="text-align: center; margin: 25px 0 50px 0;">
      <a href="dashboard/daftar.php" class="yoga">Daftar</a>
      <a href="dashboard/login.php" class="yoga" style="margin-left:5px;">Login</a>
    </div>
    <!-- <div class="countdown d-flex justify-content-center" data-count="2020/12/3">
      <div>
        <h3>%D</h3>
        <h4>Days</h4>
      </div>
      <div>
        <h3>%H</h3>
        <h4>Hours</h4>
      </div>
      <div>
        <h3>%M</h3>
        <h4>Minutes</h4>
      </div>
      <div>
        <h3>%S</h3>
        <h4>Seconds</h4>
      </div>
    </div> -->

    <!-- <div class="subscribe">
      <h4>Subscribe untuk mengikuti update informasi dari kami!</h4>
      <form action="forms/notify.php" method="post" role="form" class="php-email-form">
        <div class="subscribe-form">
          <input type="email" name="email"><input type="submit" value="Subscribe">
        </div>
        <div class="mt-2">
          <div class="loading">Loading</div>
          <div class="error-message"></div>
          <div class="sent-message">Your notification request was sent. Thank you!</div>
        </div>
      </form>
    </div> -->
    <div class="social-links text-center" style="margin-top:20px;">
      <a href="https://wa.me/+6281567693648" class="whatsapp" target="_blank"><i class="icofont-whatsapp"></i></a>
      <a href="mailto:maxisupport@maxi-line.net" class="mail" target="_blank"><i class="icofont-letter"></i></a>
    </div>

  </div>
</header>
<!-- End #header -->

<main id="main">
	
	<!-- ======= About Us Section ======= -->
  <section id="about" class="about">
    <div class="container">

      <div class="section-title">
        <h2>About Us</h2>
        <p>Kami bergerak di sektor informasi teknologi dengan fokus utama pada bidang infrastruktur jaringan,<br>data center, virtual & cloud, software dan aplikasi.</p>
      </div>

      <div class="row mt-2">
        <div class="col-lg-4 col-md-6 icon-box">
          <div class="icon"><i class="icofont-network"></i></div>
          <h4 class="title"><a href="">Infrastruktur Jaringan</a></h4>
          <p class="description">Desain, implementasi dan maintenance networking berbasis kabel maupun Wireless. Provider jaringan internet.</p>
        </div>
        <div class="col-lg-4 col-md-6 icon-box">
          <div class="icon"><i class="icofont-server"></i></div>
          <h4 class="title"><a href="">data center, virtual & cloud</a></h4>
          <p class="description">Desain, implementasi dan maintenance data center. Virtualisasi server maupun PC, privat cloud service.</p>
        </div>
        <div class="col-lg-4 col-md-6 icon-box">
          <div class="icon"><i class="icofont-computer"></i></div>
          <h4 class="title"><a href="">software dan aplikasi</a></h4>
          <p class="description">Software dan aplikasi guna menunjang performa kegiatan usaha. Berbasis cloud office maupun database.</p>
        </div>
      </div>

    </div>
  </section><!-- End About Us Section -->

  <!-- ======= Paket ======= -->
  <section id="about" class="about">
    <div class="container">

      <div class="section-title">
        <h2>Paket Berlangganan</h2>
        <p>Harga yang tertera belum termasuk pajak & biaya pemasangan, hubungi kami untuk mendapatkan informasi harga keseluruhan maupun penawaran spesial yang sedang berlaku.</p>
      </div>
		
      <div class="row mt-2">
        <?php
          $db = new DbConnector();
          $sql = "select * from tb_paket where paket_promo = '1' ";
          $res = $db->query($sql);
          while($row = $db->fetchArray($res)) {
        ?>
          <div class="col-lg-4 col-md-6 icon-box">
            <div class="icon"><i class="icofont-network"></i></div>
            <h4 class="title"><a href=""><?= $row['nama_paket']; ?></a></h4>
            <p class="description" style="text-align:left;"><?= html_entity_decode(strip_tags($row['isi_paket'])); ?></p>
            <p style="font-size: 1.0rem;font-style: italic;">Rp. <?= number_format($row['harga_paket'],0,',','.').",00"; ?></p>
          </div>
        <?php } ?>
      </div>

    </div>
  </section><!-- Paket -->
	
  <!-- ======= Contact Us Section ======= -->
  <section id="contact" class="contact">
    <div class="container">

      <div class="section-title">
        <h2>Contact Us</h2>
      </div>

      <div class="row">

        <div class="col-lg-5 d-flex align-items-stretch">

          <div class="info">
            <div class="address">
              <i class="icofont-google-map"></i>
              <h4>Location:</h4>
              <p>Villa Krista no.G16,<br>
                Kelurahan Gedawang, Banyumanik,<br>
                Semarang, Jawa Tengah</p>
            </div>

            <div class="email">
              <i class="icofont-envelope"></i>
              <h4>Email:</h4>
              <p>cs@maxi-line.net</p>
            </div>

            <div class="phone">
              <i class="icofont-phone"></i>
              <h4>Call:</h4>
              <p>(024) 76405322</p>
            </div>

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1979.6483533094133!2d110.42697825802234!3d-7.0915645987196605!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708f9e3538f599%3A0x5bc157c707dae8b!2sManunggal%20Sistem%20Sejahtera!5e0!3m2!1sen!2sid!4v1597042625102!5m2!1sen!2sid" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
          </div>

        </div>

        <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
          <form action="kirim_aksi.php" method="post" role="form" class="php-email-form" enctype="multipart/form-data" name="form" id="form" role="form">
            <input type="hidden" name="token" value="<?php echo $token; ?>" />
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="name">Your Name</label>
                <input type="text" name="name" class="form-control" id="name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                <div class="validate"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="name">Your Email</label>
                <input type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="Please enter a valid email" required/>
                <div class="validate"></div>
              </div>
            </div>
            <div class="form-group">
              <label for="name">Subject</label>
              <input type="text" class="form-control" name="subject" id="subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" required/>
              <div class="validate"></div>
            </div>
            <div class="form-group">
              <label for="name">Message</label>
              <textarea class="form-control" name="message" rows="10" data-rule="required" data-msg="Please write something for us" required></textarea>
              <div class="validate"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <div class="form-group" style="margin-bottom:15px;text-align: left;">
                  <img id="vimg" src="verificationimage.php?<?php echo rand(1000,9999); ?>" style="border:1px dotted #333; width: 75%;" alt="Kode verifikasi, Ketikkan pada kotak isian dikanan" align="absbottom"/>
                </div>
              </div>
              <div class="form-group col-md-9">
                <input type="text" class="form-control" id="capjay" name="capjay" required="required" placeholder="Isikan angka yg tertera disebelah kiri">
              </div>
            </div>
            <input name="nama_lengkap" data-provide="typeahead" type="text" style="display: none;" >
            <div class="mb-3">
              <div class="loading">Waiting</div>
              <!-- <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div> -->
              <div class="text-center"><button type="submit" id="kirim">Send Message</button></div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </section><!-- End Contact Us Section -->

</main>
<!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer">
  <div class="container">
    <div class="copyright">
      <a href="syaratdanaturan.php">&#8226; Syarat & Ketentuan</a> <a href="kebijakanprivasi.php"><span style="margin-left:10px;">&#8226;</span> Kebijakan Privasi</a> <a href="#about"><span style="margin-left:10px;">&#8226;</span> About Us</a>
    </div>
    <div class="credits">
      &copy; Copyright <strong><span>Manunggal Sistem Sejahtera</span></strong>. All Rights Reserved
    </div>
  </div>
</footer>
<!-- End #footer -->

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
    // event.preventDefault();
    $('.loading').show(); // show animation
    $('#kirim').prop('disabled', true);
    return true; // allow regular form submission
  });
</script>
<script>
  function refreshImage(v) {
    v.src = 'verificationimage.php?' + Math.random();
  }
</script>
</body>

</html>
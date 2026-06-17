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

.disable {
	color : #24b7a4;
	pointer-events: none;
}
</style>
<body>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center" style="padding: 25px 0;">
    <div class="container d-flex flex-column align-items-center">

      <h1><a href="index.php"><img src="images/MaxiLine.png" alt="" style="width:40%;display: block;margin-left: auto; margin-right: auto;"></a></h1>
     
 <!--      <div class="countdown d-flex justify-content-center" data-count="2020/12/3">
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

 <!--     <div class="subscribe">
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
    </div>
  </header><!-- End #header -->

  <main id="main">
	
    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact" style="padding: 20px 0;">
      <div class="container">

    <?
		$db = new DbConnector();
		$sql = "select * from tb_tos where id_tb_tos = '2' ";
		$res = $db->query($sql);
		while($row = $db->fetchArray($res)) {
		?>
        <div class="section-title">
          <h2><?=$row['judul'];?></h2>
        </div>

        <div class="row">
          <div class="col-lg-12 mt-5 mt-lg-0 d-flex align-items-stretch">
		  <div class="php-email-form">
            <p><?=$row['note'];?></p>
          </div>
		  </div>

        </div>
		<?}?>

      </div>
    </section><!-- End Contact Us Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
	   <a href="#" class="disable">&#8226; Syarat & Ketentuan</a> <a href="kebijakanprivasi.php"><span style="margin-left:10px;">&#8226;</span> Kebijakan Privasi</a> <a href="index.php#about"><span style="margin-left:10px;">&#8226;</span> About Us</a>
      </div>
      <div class="credits">
		&copy; Copyright <strong><span>Manunggal Sistem Sejahtera</span></strong>. All Rights Reserved
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
  <script>
 function refreshImage(v) {
        v.src = 'verificationimage.php?' + Math.random();
    }
</script>
</body>

</html>
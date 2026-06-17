<?php
@session_start();

include "dashboard/include/config.php";
include "dashboard/include/DbConnector.php";

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = md5(uniqid(rand(), TRUE));
}
$token = $_SESSION['token'];

$saiki = date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>MAXI LINE - Redefine Internet Services</title>
<!-- Stylesheets -->
<link href="resources/css/bootstrap.css" rel="stylesheet">
<link href="resources/css/style.css" rel="stylesheet">
<link href="resources/css/responsive.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
<link rel="icon" href="assets/img/favicon.png" type="image/x-icon">

<!-- Responsive -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
.facility-section .blocks-column .facility-block:nth-child(2n + 0) {
    transform: translateY(0px);
}

.facility-section .image-column .inner-column {
    position: relative;
    padding-left: var(--padding-top-60);
    padding-top: var(--padding-top-0);
}

.featured-section {
    background-color: rgb(251 227 172);
}

body {
zoom: 95%;
}

.list-style-two li {
    font-size: 1.1em;
}	
</style>
</head>

<body class="hidden-bar-wrapper">

<div class="page-wrapper">
 	
    <!-- Preloader -->
    <div class="preloader">
		<span></span>
	</div>
 	
 	<!-- Main Header -->
    <header class="main-header">
    	
		<!-- Header Top -->
		<div class="header-top">
			<div class="auto-container clearfix">
				
				<div class="pull-left">
					<ul class="info">
						<li><a href="#"><span class="icon fa fa-clock-o"></span>Jam Kerja: 08.30 - 16.30 WIB</a></li>
						<li><a href="https://wa.me/+6281545928181"><span class="icon fa fa-whatsapp"></span>WA: +6281-5459-28181</a></li>
						<li><a href="mailto:cs@maxi-line.net"><span class="icon fa fa-envelope-o"></span>E-mail: cs@maxi-line.net</a></li>
					</ul>
				</div>
				
				<div class="pull-right clearfix">
					<!-- Social Box -->
					<ul class="social-box">
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
				
			</div>
		</div>
		
        <!-- Header Lower -->
        <div class="header-lower">
            
			<div class="auto-container clearfix">
				<div class="inner-container clearfix">
					
					<div class="pull-left logo-box" style="padding: 20px 0px;">
						<div class="logo"><a href="index.php"><img src="resources/images/logo.png" alt="" title=""></a></div>
					</div>
					<div class="nav-outer clearfix">
						
						<!-- Mobile Navigation Toggler -->
						<div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>
						<!-- Main Menu -->
						<nav class="main-menu show navbar-expand-md">
							<div class="navbar-header">
								<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							
							<div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
								<ul class="navigation clearfix">
									<li class="current"><a class="scroll-to-target" href="index.php" target="_self">Home</a></li>
                                    <li><a class="scroll-to-target" data-target="#about" href="index.php?#about">Tentang Kami</a></li>
                                    <li><a class="scroll-to-target" data-target="#services" href="index.php?#services">Layanan</a></li>
                                    <li><a class="scroll-to-target" data-target="#pricing" href="index.php?#pricing">Pricing</a></li>
                                    <li><a class="scroll-to-target" data-target="#contact" href="index.php?#contact">Kontak</a></li>
									<li><a class="scroll-to-target" data-target="#faq" href="index.php?#faq">FAQ</a></li>
                                    <li><a href="dashboard/login.php"><span class="fa fa-sign-in" aria-hidden="true"></span> Login</a></li>
								</ul>
							</div>
							
						</nav>
						<!-- Main Menu End-->
						
						<!-- Outer Box -->
						<div class="outer-box clearfix">
							<!-- Nav Btn -->
							<div class="nav-btn navSidebar-button">
								<span class="icon flaticon-dots-menu"></span>
							</div>
						</div>
						<!-- End Outer Box -->
						
					</div>
				</div>
				
			</div>
        </div>
        <!-- End Header Lower -->
        
		<!-- Sticky Header  -->
        <div class="sticky-header">
            <div class="auto-container clearfix">
                <!-- Logo -->
                <div class="logo pull-left">
                    <a href="index.html" title=""><img src="resources/images/logo-small.png" alt="" title=""></a>
                </div>
				
                <!--Right Col-->
                <div class="pull-right">
                    <!-- Main Menu -->
                    <nav class="main-menu">
                        <!--Keep This Empty / Menu will come through Javascript-->
                    </nav><!-- Main Menu End-->
					
					<!-- Outer Box -->
					<div class="outer-box clearfix">
						
						<!-- Nav Btn -->
						<div class="nav-btn navSidebar-button"><span class="icon flaticon-dots-menu"></span></div>
						
					</div>
					<!-- End Outer Box -->
					
                </div>
            </div>
        </div><!-- End Sticky Menu -->
    
		<!-- Mobile Menu  -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <div class="close-btn"><span class="icon flaticon-multiply"></span></div>
            
            <nav class="menu-box">
                <div class="nav-logo"><a href="index.php"><img src="resources/images/logo.png" alt="" title=""></a></div>
                <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
            </nav>
        </div><!-- End Mobile Menu -->
	
    </header>
    <!-- End Main Header -->
	
	<!-- Sidebar Cart Item -->
	<div class="xs-sidebar-group info-group">
		<div class="xs-overlay xs-bg-black"></div>
		<div class="xs-sidebar-widget">
			<div class="sidebar-widget-container">
				<div class="widget-heading">
					<a href="#" class="close-side-widget">
						X
					</a>
				</div>
				<div class="sidebar-textwidget">
					
					<!-- Sidebar Info Content -->
					<div class="sidebar-info-contents">
						<div class="content-inner">
							<div class="logo">
								<a href="index.php"><img src="resources/images/logo-2.png" alt="" /></a>
							</div>
							<div class="content-box">
								<h5>Tentang Kami</h5>
								<p class="text">
                                    Maxi-Line merupakan perusahaan yang menyediakan layanan Internet Services Provider, Data Center, Virtual & Cloud, Software dan Aplikasi.
                                </p>
								<!-- <a href="contact.html" class="theme-btn btn-style-one"><span class="txt">Consultation</span></a> -->
							</div>
							<div class="contact-info">
								<h5>Kontak Info</h5>
								<ul class="list-style-one">
									<li>
                                        <span class="icon fa fa-location-arrow"></span>
                                        Villa Krista no.G17,
                                        Kelurahan Gedawang, Banyumanik,
                                        Semarang, Jawa Tengah
                                    </li>
									<li><span class="icon fa fa-phone"></span>+6281-5459-28181</li>
									<li><span class="icon fa fa-envelope"></span>cs@maxi-line.net</li>
									<li><span class="icon fa fa-clock-o"></span>Jam Kerja: 08.30 to 17.00 Sabtu-Minggu: Tutup</li>
								</ul>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<!-- END sidebar widget item -->
	
	<!-- Page Title -->
    <section class="page-title" style="background-image: url(images/background/10.jpg)">
        <div class="auto-container">
			<h2>Kebijakan Privasi</h2>			
			<ul class="bread-crumb clearfix">
				<li><a href="index.php">Home</a></li>
				<li>Frequently Always Question</li>
			</ul> 
        </div>
    </section>
    <!-- End Page Title -->
	
	<!-- Sidebar Page Container -->
    <section class="sidebar-page-container">
    	<div class="auto-container">
        	<div class="row clearfix">
				
				<!-- Content Side -->
                <div class="content-side col-lg-8 col-md-12 col-sm-12">
                	<div class="service-detail">
						<div class="inner-box">
							<!--
							<div class="image">
								<img src="images/resource/service-2.jpg" alt="" />
							</div>
							-->
							<div class="lower-content">
								<?
								$db = new DbConnector();
								$sql = "select * from tb_tos where id_tb_tos = '1' ";
								$res = $db->query($sql);
								while($row = $db->fetchArray($res)) {
								?>
								<h4><?=$row['judul'];?></h4>								
								<p><?=$row['note'];?></p>
								<?}?>	
							</div>
						</div>
					</div>
				</div>
				
				<!-- Sidebar Side -->
                <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
                	<aside class="sidebar sticky-top">
						
						<!-- Contact Widget -->
						<div class="sidebar-widget contact-widget">
							<div class="widget-content">
								<div class="sidebar-title">
									<h5>Contact Us</h5>
								</div>
								<ul class="contact-info-widget">
									<li>
										<span class="icon flaticon-map"></span>
										Villa Krista no.G17,
                                        Kelurahan Gedawang, Banyumanik,
                                        Semarang, Jawa Tengah
									</li>
									<li>
										<span class="icon flaticon-call"></span>										
										<a href="tel:+9684-32-45-789" style="margin-top: 0.7em;">+6281-5459-28181</a>
									</li>
									<li>
										<span class="icon flaticon-email-1"></span>
										<a href="mailto:cs@maxi-line.net" style="margin-top: 0.7em;">cs@maxi-line.net</a>
									</li>
								</ul>
							</div>
						</div>
						
					</aside>
				</div>
				
			</div>
		</div>
	</section>
	<!-- End Sidebar Page Container -->
	
	<!-- Main Footer -->
    <footer class="main-footer">		
		<!-- Footer Bottom -->
		<div class="footer-bottom">
			<div class="auto-container">
                <ul class="term-condition">
                    <li><a href="syaratdanaturan.php" >Syarat & Ketentuan</a></li>
                    <li><a style="color:rgb(255 209 178);">Kebijakan Privasi</a></li>
                    <li><a href="https://karir.manunggalgroup.com">Karir</a></li>
                </ul>
				<div class="copyright">&copy; <?= date('Y')?> Manunggal Sistem Sejahtera. All Rights Reserved. Designed By <a href="https://themeforest.net/user/themexriver">Themexriver</a></div>
			</div>
		</div>
	</footer>
	<!-- End Main Footer -->
	
</div>
<!--End pagewrapper-->

<!-- Scroll To Top -->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>

<script src="resources/js/jquery.js"></script>
<script src="resources/js/popper.min.js"></script>
<script src="resources/js/bootstrap.min.js"></script>
<script src="resources/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="resources/js/jquery.fancybox.js"></script>
<script src="resources/js/appear.js"></script>
<script src="resources/js/parallax.min.js"></script>
<script src="resources/js/tilt.jquery.min.js"></script>
<script src="resources/js/jquery.paroller.min.js"></script>
<script src="resources/js/owl.js"></script>
<script src="resources/js/wow.js"></script>
<script src="resources/js/validate.js"></script>
<script src="resources/js/nav-tool.js"></script>
<script src="resources/js/jquery-ui.js"></script>
<script src="resources/js/script.js"></script>

<!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
<!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->

<script>
    $(document).ready(function() {
        $('.navigation .scroll-to-target').on('click', function(e) {
            e.preventDefault(); // Prevent the default link behavior
            
            // Remove 'current' class from all items
            $('.navigation li').removeClass('current');
            
            // Add 'current' class to the clicked item
            $(this).closest('li').addClass('current');
            
            // Optionally scroll to the target if needed
            var target = $(this).data('target');
            if (target) {
                $('html, body').animate({
                    scrollTop: $(target).offset().top
                }, 1000); // Adjust the duration (1000 ms) as needed
            }
        });
    });
</script>
</body>
</html>
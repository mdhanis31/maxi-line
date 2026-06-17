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
									<li class="current"><a class="scroll-to-target" data-target="html">Home</a></li>
                                    <li><a class="scroll-to-target" data-target="#about">Tentang Kami</a></li>
                                    <li><a class="scroll-to-target" data-target="#services">Layanan</a></li>
                                    <li><a class="scroll-to-target" data-target="#pricing">Pricing</a></li>
                                    <li><a class="scroll-to-target" data-target="#contact">Kontak</a></li>
									<li><a class="scroll-to-target" data-target="#faq">FAQ</a></li>
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
	
	<!-- Main Section -->
    <section class="main-slider">
		
		<div class="main-slider-carousel owl-carousel owl-theme">
            
            <div class="slide" style="background-image: url(resources/images/main-slider/slider_1.png)">
				<div class="pattern-layer" style="background-image: url(resources/images/main-slider/pattern-layer.png)"></div>
				
				<div class="auto-container">
					
					<!-- Content Boxed -->
					<div class="content-boxed">
						<div class="inner-box">
							<div class="title">Maxi-Line Internet Provider</div>
							<h1>Redefine Internet Services</h1>
							<div class="btns-box">
								<a href="dashboard/daftar.php" class="theme-btn btn-style-two"><span class="txt">Daftar Sekarang <i class="lnr lnr-arrow-right"></i></span></a>
								<a href="https://wa.me/+6281545928181" class="theme-btn btn-style-three"><span class="txt">Kontak Kami <i class="lnr lnr-arrow-right"></i></span></a>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="slide" style="background-image: url(resources/images/main-slider/slider_2.png)">
				<div class="pattern-layer" style="background-image: url(resources/images/main-slider/pattern-layer.png)"></div>

				<div class="auto-container">
					
					<!-- Content Boxed -->
					<div class="content-boxed">
						<div class="inner-box">
							<div class="title">Maxi-Line Internet Provider</div>
							<h1>Enjoy Connected With Us</h1>
							<div class="btns-box">
								<a href="dashboard/daftar.php" class="theme-btn btn-style-two"><span class="txt">Daftar Sekarang<i class="lnr lnr-arrow-right"></i></span></a>
								<a href="https://wa.me/+6281545928181" class="theme-btn btn-style-three"><span class="txt">Kontak Kami <i class="lnr lnr-arrow-right"></i></span></a>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			
		</div>
		
	</section>
	<!-- End Main Section -->
	
	<!-- Appointment Section -->
	<section class="appointment-section" style="background-image: url(resources/images/background/pattern-1.png)">
		<div class="auto-container">
			<div class="row clearfix">
				
				<!-- Title Column -->
				<div class="title-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<h3>Bring The Internet to Your Home With Wireless Internet from Maxi-Line</h3>
					</div>
				</div>
				
				<!-- Form Column -->
				<!-- <div class="form-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="appointment-form">
							<form method="post" action="contact.html">
								<div class="form-group">
									<input type="email" name="email" value="" placeholder="Enter street address & apartment" required="">
									<button type="submit" class="theme-btn btn-style-two"><span class="txt">Check Availability <i class="lnr lnr-arrow-right"></i></span></button>
								</div>
							</form>
						</div>
					</div>
				</div> -->
				
			</div>
		</div>
	</section>
	<!-- End Appointment Section -->

    <!-- Services Section -->
	<section class="services-section" id="about" style="background-image: url(resources/images/background/pattern-2.png)">
		<div class="auto-container">
			
			<!-- Lower Section -->
			<div class="lower-section">
				<div class="row clearfix">
					
					<!-- Image Column -->
					<div class="image-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner-column titlt" data-tilt data-tilt-max="3">
							<div class="color-layer"></div>
							<div class="border-layer"></div>
							<div class="image">
								<img src="resources/images/resource/service-1.png" alt="" />
							</div>
						</div>
					</div>
					
					<!-- Content Column -->
					<div class="content-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner-column">
							<!-- Sec Title -->
							<div class="sec-title">
								<div class="title">Tentang Kami</div>
								<h2>Konektivitas Cepat Tanpa Batas</h2>
							</div>
							<div class="bold-text">Kami bergerak di sektor informasi teknologi dengan fokus utama pada bidang infrastruktur jaringan,
							data center, virtual & cloud, software dan aplikasi.</div>
							<div class="text">
								Di PT Manunggal Sistem Sejahtera, kami bangga menghadirkan solusi internet berkualitas tinggi dengan teknologi terbaru.
								Dengan infrastruktur jaringan canggih dan dukungan pelanggan yang ramah, kami memastikan setiap pelanggan mendapatkan pengalaman online yang optimal.
								Kami menawarkan berbagai paket layanan yang dirancang untuk memenuhi kebutuhan berbeda, mulai dari kecepatan tinggi untuk streaming dan gaming, hingga opsi hemat biaya untuk penggunaan sehari-hari.
							</div>
							<!-- <a href="about.html" class="theme-btn btn-style-four"><span class="txt">Read More <i class="lnr lnr-arrow-right"></i></span></a> -->
						</div>
					</div>
					
				</div>
			</div>
			<!-- End Lower Section -->
			
		</div>
	</section>
	<!-- End Services Section -->

    <!-- Services Section Two -->
	<section class="services-page-section" id="services" style="background-image: url(resources/images/background/pattern-7.png)">
		<div class="auto-container">
			
			<!-- Sec Title -->
			<div class="sec-title centered">
				<div class="separator"></div>
				<h2>Layanan Kami</h2>
			</div>
			<!-- End Sec Title -->
			
			<div class="four-item-carousel-two owl-carousel owl-theme">
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<div class="color-layer"></div>
						<div class="icon-layer-one" style="background-image: url(resources/images/background/pattern-19.png)"></div>
						<div class="icon-layer-two" style="background-image: url(resources/images/background/pattern-20.png)"></div>
						<div class="icon"><img src="resources/images/icons/service-4.png" alt="" /></div>
						<h4><a href="#">Infrastruktur Jaringan</a></h4>
						<div class="text">Desain, implementasi dan maintenance networking berbasis kabel maupun Wireless.</div>
					</div>
				</div>
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<div class="color-layer"></div>
						<div class="icon-layer-one" style="background-image: url(resources/images/background/pattern-19.png)"></div>
						<div class="icon-layer-two" style="background-image: url(resources/images/background/pattern-20.png)"></div>
						<div class="icon"><img src="resources/images/icons/service-5.png" alt="" /></div>
						<h4><a href="#">Server / Data Center</a></h4>
						<div class="text">Kami menyediakan jasa membangun, optimasi, upgrade dan maintenance data center.</div>
					</div>
				</div>
				
				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<div class="color-layer"></div>
						<div class="icon-layer-one" style="background-image: url(resources/images/background/pattern-19.png)"></div>
						<div class="icon-layer-two" style="background-image: url(resources/images/background/pattern-20.png)"></div>
						<div class="icon"><img src="resources/images/icons/service-6.png" alt="" /></div>
						<h4><a href="#">Software dan Aplikasi</a></h4>
						<div class="text">Software dan aplikasi yang berguna menunjang performa kegiatan usaha anda.</div>
					</div>
				</div>

				<!-- Service Block Two -->
				<div class="service-block-two">
					<div class="inner-box">
						<div class="color-layer"></div>
						<div class="icon-layer-one" style="background-image: url(resources/images/background/pattern-19.png)"></div>
						<div class="icon-layer-two" style="background-image: url(resources/images/background/pattern-20.png)"></div>
						<div class="icon"><img src="resources/images/icons/service-7.png" alt="" /></div>
						<h4><a href="#">Virtualisasi dan Private Cloud</a></h4>
						<div class="text">Kami siap membatu anda untuk virtualisasi server maupun PC, privat cloud service.</div>
					</div>
				</div>
				
			</div>
			
		</div>
	</section>
	<!-- End Services Section Two -->
	
	<!-- Pricing Section -->
	<section class="pricing-section" id="pricing" style="background-image: url(resources/images/background/pattern-3.png)">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title light centered">
				<div class="title">List Harga Paket</div>
				<h2>Paket Berlanganan Hanya Untukmu</h2>
				<div class="text"> <b>Harga yang tertera belum termasuk pajak,</b> hubungi kami untuk mendapatkan informasi harga keseluruhan maupun penawaran spesial yang sedang berlaku.</div>
			</div>
			<!-- End Sec Title -->
			<div class="row clearfix">
				<?php
                    $db = new DbConnector();
                    $sql = "SELECT * FROM tb_paket WHERE paket_promo = '1' AND is_hidden = '1' ORDER BY harga_paket ASC";
                    $res = $db->query($sql);
        
                    while($row = $db->fetchArray($res)) {
                ?>
                    <!-- Price Block -->
                    <div class="price-block col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="upper-box" style="background-image: url(resources/images/background/pattern-4.png)">
                                <ul class="icon-list">
                                    <li><span class="icon"><img src="resources/images/icons/service-1.svg" alt="" /></span></li>
                                </ul>
								<?
								if($row['id_tb_paket'] == 14) {?>
								<h4><?= $row['nama_paket'] ?> <span style="text-decoration: line-through;color:red;font-size:0.9em;">Rp. <?= number_format($row['harga_paket'],0,',','.').",00"; ?> / Bulan</span></h4>	
								<?
								$sqla = "SELECT * FROM tb_paket WHERE id_paket_utama = '$row[id_tb_paket]' ";
								$resa = $db->query($sqla);
								$rowa = $db->fetchArray($resa);
								?>
								<h4 style="margin-top:5px;"><span>Rp. <?= number_format($rowa['harga_paket'],0,',','.').",00"; ?> / Bulan</span></h4>
								<?} else {?>
								<h4><?= $row['nama_paket'] ?> <span>Rp. <?= number_format($row['harga_paket'],0,',','.').",00"; ?> / Bulan</span></h4>
								<?}?>                                
                            </div>
                            <div class="lower-box">
                                <!-- < print_r($textArr); ?> -->
                                <ul class="price-list">
                                    <?= html_entity_decode($row['isi_paket']) ?>
                                </ul>
                                <div class="button-box">
                                    <a href="dashboard/daftar.php" class="theme-btn btn-style-four"><span class="txt">Get started</span></a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
			</div>
		</div>
	</section>
	<!-- End Pricing Section -->
	
	<!-- Facility Section -->
	<section class="facility-section" style="background-image: url(resources/images/background/pattern-6.png)">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="clearfix">
					<div class="pull-left">
						<div class="title">Fasilitas Kami</div>
						<h2>Beberapa alasan hebat <br> yang membuat Anda <br> memilih kami</h2>
					</div>
					<div class="pull-right">						
						<div class="text"><br><br>Kepuasan pelanggan adalah kebahagiaan bagi kami karena itu kami menyediakan <br> layanan terbaik bagi pelanggan kami. <br> Berikut fasilitas yang diberikan untuk pelanggan kami :</div>
					</div>
				</div>
			</div>
			<!-- End Sec Title -->
			<div class="row clearfix">
			
				<!-- Blocks Column -->
				<div class="blocks-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="row clearfix">
							
							<!-- Facility Block -->
							<div class="facility-block col-lg-6 col-md-6 col-sm-12">
								<div class="inner-box">
									<div class="pattern-layer" style="background-image: url(resources/images/background/pattern-14.png)"></div>
									<div class="icon-box flaticon-swimming-pool"></div>
									<h5><a href="#">Free Installation</a></h5>
									<div class="text">Dapatkan bebas biaya instalasi dan tanpa biaya sewa perangkat untuk pengguna baru Maxi-Line.</div>
								</div>
							</div>
							
							<!-- Facility Block -->
							<div class="facility-block col-lg-6 col-md-6 col-sm-12">
								<div class="inner-box">
									<div class="pattern-layer" style="background-image: url(resources/images/background/pattern-14.png)"></div>
									<div class="icon-box flaticon-5g"></div>
									<h5><a href="#">Ultrafast Connect</a></h5>
									<div class="text">Nikmati layanan internet dengan kecepatan ultra untuk menunjang aktivitas anda.</div>
								</div>
							</div>
							
							<!-- Facility Block -->
							<div class="facility-block col-lg-6 col-md-6 col-sm-12">
								<div class="inner-box">
									<div class="pattern-layer" style="background-image: url(resources/images/background/pattern-14.png)"></div>
									<div class="icon-box flaticon-money"></div>
									<h5><a href="#">Pay As You Go</a></h5>
									<div class="text">Pakai dulu baru bayar. Bayar tagihan anda sesuai dengan apa yang anda pakai.</div>
								</div>
							</div>
							
							<!-- Facility Block -->
							<div class="facility-block col-lg-6 col-md-6 col-sm-12">
								<div class="inner-box">
									<div class="pattern-layer" style="background-image: url(resources/images/background/pattern-14.png)"></div>
									<div class="icon-box flaticon-customer-service"></div>
									<h5><a href="#">Fast Support 24/7</a></h5>
									<div class="text">Jangan khawatir internet anda mati. Kami siap men-support anda selama 24/7.</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				
				<!-- Image Column -->
				<div class="image-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="pattern-layer"></div>
						<div class="image wow slideInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
							<img src="resources/images/resource/facility.png" alt="" />
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>
	<!-- End Facility Section -->
	
	<!-- Internet Section -->
	<section class="internet-section" style="background-image: url(resources/images/background/11.png)">
		<div class="auto-container">
			<div class="clearfix">
				<div class="content-column">
					<h2>Hadirkan Konektivitas Layanan Internet Dimanapun</h2>
					<div class="text">Kami juga menyediakan paket berlanggan internet via satelite berkecepatan tinggi dengan <b>"Starlink"</b>. Sehingga anda dapat terhubung internet saat dirumah ataupun saat berpergian tanpa khawatir waktu dan tempat.</div>
					<div class="price">Harga mulai Rp. 990.000,00/ per bulan</div>
					<a href="#" class="scroll-to-target theme-btn btn-style-four" data-target="#pricing"><span class="txt">Read More <i class="lnr lnr-arrow-right"></i></span></a>
				</div>
			</div>
		</div>
	</section>
	<!-- End Internet Section -->

	<!-- Network Section / Style Two -->
	<section class="network-section style-two" style="background-image: url(resources/images/background/2.jpg)">
		<div class="auto-container">
			<div class="inner-container">
				<div class="row clearfix">
					
					<!-- Image Column -->
					<div class="images-column col-lg-7 col-md-12 col-sm-12">
						<div class="inner-column">
							<div class="image wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
								<img src="resources/images/resource/network-4.png" alt="" />
							</div>
							<div class="image-two wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
								<img src="resources/images/resource/network-5.png" alt="" />
							</div>
							<div class="image-three wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
								<img src="resources/images/resource/network-3.png" alt="" />
							</div>
						</div>
					</div>
					
					<!-- Content Column -->
					<div class="content-column col-lg-5 col-md-12 col-sm-12">
						<div class="inner-column">
							<!-- Sec Title -->
							<div class="sec-title">
								<div class="separator"></div>
								<h2>Bagaimana Kami Bekerja</h2>
								<div class="text">Bagaimana anda dapat menjadi pelanggan Maxi-Line?</div>
							</div>
							<!-- Network List -->
							<ul class="network-list">
								<li>
									<span class="icon flaticon-tick-1"></span>
									<strong>Registrasi</strong>
									Lakukan registrasi terlebih dahulu dengan melakukan akses ke website resmi kami <a href="https://www.maxi-line.net">www.maxi-line.net</a>. Masukkan data diri anda dan pilih paket berlangganan anda.
								</li>
								<li>
									<span class="icon flaticon-tick-1"></span>
									<strong>Survey Lokasi</strong>
									Setelah registrasi tim kami akan merespon dengan SLA 3x24 jam. Kemudian tim kami akan menjadwalkan dan melakukan survey ke lokasi tempat Anda tinggal untuk meninjau lokasi anda berada dalam jangkauan kami atau tidak.
								</li>
								<li>
									<span class="icon flaticon-tick-1"></span>
									<strong>Instalation & Aktivasi</strong>
									Setelah survey dilakukan, tim kami akan melakukan instalasi perangkat dan aktivasi internet di lokasi anda. Setelah itu anda dapat menikmati Layanan Internet dari Maxi-Line.
								</li>
							</ul>
							
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</section>
	<!-- End Network Section / Style Two -->

	<!-- Faq Section -->
	<section class="faq-section" id="faq" style="background-image: url(resources/images/background/4.jpg)">
		<div class="auto-container">
			<div class="row clearfix">
				
				<!-- Accordion Column -->
				<div class="accordion-column col-lg-12 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="sec-title">
							<div class="separator"></div>
							<h3>Frequently Always Question</h3>
						</div>

						<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" id="frq-tab" data-toggle="tab" href="#frq" role="tab" aria-controls="frq" aria-selected="true"><span class="font-weight-bold">FAQ</span></a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="trx-tab" data-toggle="tab" href="#trx" role="tab" aria-controls="trx" aria-selected="false"><span class="font-weight-bold">Metode Pembayaran</span></a>
							</li>
						</ul>

						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="frq" role="tabpanel" aria-labelledby="frq-tab">
								<!-- Accordian Box -->
								<ul class="accordion-box">

									<!--Block-->
									<li class="accordion block active-block">
										<div class="acc-btn active"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>Bagaimana saya dapat berlangganan MAXI-LINE ?</div>
										<div class="acc-content current">
											<div class="content">
												<div class="text">
													Anda dapat langsung berlangganan secara online <a href="https://www.maxi-line.net/dashboard/daftar.php">disini</a>. Anda juga dapat menghubungi Customer Service kami di +6281545928181 atau WhatsApp ke +628112678906 (Sales MAXI-LINE).
												</div>
											</div>
										</div>
									</li>
									
									<!--Block-->
									<li class="accordion block">
										<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>Bagaimana saya mengecek status pendaftaran MAXI-LINE ?</div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
													Anda dapat melakukan pengecekan status pendaftaran anda <a href="https://www.maxi-line.net/cek_pendaftaran.php">disini</a>. Masukkan kode pendaftaran dan email yang anda daftarkan kedalam form yang tersedia. Anda dapat pula mengecek melalui email, karena setiap tahapan mulai dari survey lokasi hingga pengaktifan kami akan mengirim notifikasi ke email anda.
												</div>
											</div>
										</div>
									</li>

									<!--Block-->
									<li class="accordion block">
										<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>Kapan layanan MAXI-LINE dapat mulai di install ?</div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
													Setelah mendaftar, tim kami akan mulai melakukan survey terlebih dahulu tempat anda berada. Setelah itu kami akan melakukan instalasi ke tempat anda dengan SLA 3 hari setelah anda mendaftar.
												</div>
											</div>
										</div>
									</li>

									<!--Block-->
									<li class="accordion block">
										<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>Biaya apa saja yang perlu saya bayar per bulan ?</div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
													<p>Berikut adalah biaya yang harus dibayarkan oleh pelanggan per bulan:<p>

													<div class="price-block">
														<ul class="price-list">
															<li>Biaya langganan internet sesuai pemakaian anda.</li>
															<li>Biaya sewa router atau modem (Free Promo)</li>
															<li>PPN 11% yang dihitung berdasarkan total biaya dari point 1-2</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</li>

									<!--Block-->
									<li class="accordion block">
										<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>Kenapa koneksi internet saya mengalami penurunan atau lambat ?</div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
													<p>Berikut adalah beberapa faktor yang menghambat internet anda:</p>

													<div class="price-block">
														<ul class="price-list">
															<li>Listrik padam</li>
															<li>PC / Komputer anda terkena virus</li>
															<li>Sedang menjalankan download / upload yang besar</li>
															<li>History / Cache / Cookies internet dihapus</li>
															<li>Perangkat / Konfigurasi jaringan yang bermasalah</li>
														</ul>
													</div>

													<b>NB : Untuk point 4 anda dapat meminta bantuan ke teknisi kami melalui Customer Service kami di +6281545928181</b>
												</div>
											</div>
										</div>
									</li>

								</ul>
							</div>
							<div class="tab-pane fade" id="trx" role="tabpanel" aria-labelledby="trx-tab">
								<div class="auto-container">
									<div class="row clearfix">
										<div class="col-lg-3 col-md-3 col-sm-3">
											<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
												<a class="nav-link active" id="v-pills-atm-tab" data-toggle="pill" href="#v-pills-atm" role="tab" aria-controls="v-pills-atm" aria-selected="true">ATM</a>
												<a class="nav-link" id="v-pills-mbanking-tab" data-toggle="pill" href="#v-pills-mbanking" role="tab" aria-controls="v-pills-mbanking" aria-selected="false">Mobile Banking</a>
												<a class="nav-link" id="v-pills-minimart-tab" data-toggle="pill" href="#v-pills-minimart" role="tab" aria-controls="v-pills-minimart" aria-selected="false">Minimart</a>
											</div>
										</div>
										<div class="col-lg-9 col-md-9 col-sm-9">
											<div class="tab-content" id="v-pills-tabContent">
												<div class="tab-pane fade show active" id="v-pills-atm" role="tabpanel" aria-labelledby="v-pills-atm-tab">
													<!-- Accordian Box -->
													<ul class="accordion-box">

														<!--Block-->
														<li class="accordion block active-block">
															<div class="acc-btn active"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>ATM BCA</div>
															<div class="acc-content current">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Kunjungi ATM BCA terdekat</li>
																				<li>Masukkan kartu ATM dan PIN ATM BCA</li>
																				<li>Pilih menu "Penarikan Tunai/Transaksi Lainnya"</li>
																				<li>Pilih menu "Transaksi Lainnya"</li>
																				<li>Pilih menu "Transfer"</li>
																				<li>Masukkan nomor BCA Virtual Account dan klik "Benar"</li>
																				<li>Cek detail transaksi dan pilih "Ya"</li>
																				<li>Transaksi berhasil</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

														<!--Block-->
														<li class="accordion block">
															<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>ATM BRI</div>
															<div class="acc-content">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Kunjungi ATM BRI terdekat</li>
																				<li>Masukkan kartu ATM dan PIN ATM BRI</li>
																				<li>Pilih menu "Transaksi Lain"</li>
																				<li>Pilih "Pembayaran"</li>
																				<li>Pilih menu "Lainnya"</li>
																				<li>Pilih menu "BRIVA"</li>
																				<li>Masukkan nomor BRI Virtual Account (BRIVA) dan klik "Benar"</li>
																				<li>Cek detail transaksi dan pilih "Ya/Benar"</li>
																				<li>Transaksi berhasil</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

														<!--Block-->
														<li class="accordion block">
															<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>ATM MANDIRI</div>
															<div class="acc-content">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Kunjungi ATM Mandiri terdekat</li>
																				<li>Masukkan kartu ATM dan PIN ATM Mandiri</li>
																				<li>Pilih menu "Multi Payment"</li>
																				<li>Masukkan kode perusahaan / penyedia jasa metode virtual account</li>
																				<li>Masukkan nomor rekening tujuan atau Virtual Account, lalu pilih "Benar"</li>
																				<li>Verifikasi apakah nominal dan penerima transfer sesuai tagihan, lalu klik "Benar"</li>
																				<li>Transaksi bayar virtual account Mandiri berhasil</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

														<!--Block-->
														<li class="accordion block">
															<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>ATM BNI</div>
															<div class="acc-content">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Kunjungi ATM BNI terdekat</li>
																				<li>Masukkan kartu ATM dan PIN ATM BNI anda.</li>
																				<li>Pilih menu "Transfer"</li>
																				<li>Pilih jenis rekening yang digunakan</li>
																				<li>Pilih menu "Virtual Account Billing"</li>
																				<li>Masukkan nomor Virtual Account pembayaran anda</li>
																				<li>Verifikasi apakah nominal dan penerima transfer sesuai tagihan, lalu klik "Ya/Benar"</li>
																				<li>Simpan bukti transaksi anda</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

													</ul>
												</div>
												<div class="tab-pane fade" id="v-pills-mbanking" role="tabpanel" aria-labelledby="v-pills-mbanking-tab">
													<!-- Accordian Box -->
													<ul class="accordion-box">

														<!--Block-->
														<li class="accordion block active-block">
															<div class="acc-btn active"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>BCA Mobile (m-BCA)</div>
															<div class="acc-content current">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Klik m-BCA lalu login</li>
																				<li>Klik m-Transfer</li>
																				<li>Klik BCA Virtual Account</li>
																				<li>Masukkan nomor Virtual Account</li>
																				<li>Jika sudah pernah menyimpan nomor VA tersebut sebelumnya, pilih dari daftar transfer. Jika belum dan hendak menyimpannya, klik simpan ke Daftar Transfer</li>
																				<li>Bayarkan sesuai nominal yang tertera, atau masukkan secara manual jika nominalnya tidak muncul</li>
																				<li>Masukkan PIN m-BCA untuk memastikan transaksi</li>
																				<li>Transaksi berhasil</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

														<!--Block-->
														<li class="accordion block">
															<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>BRI Mobile (BRIMO)</div>
															<div class="acc-content">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Login ke mobile banking</li>
																				<li>Klik menu “Pembayaran”</li>
																				<li>Pilih “BRIVA”</li>
																				<li>Masukkan nomor Virtual Account lengkap dengan nominal pembayaran</li>
																				<li>Masukkan nomor PIN lalu klik “OK”</li>
																				<li>Transaksi berhasil</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

														<!--Block-->
														<li class="accordion block">
															<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>Livin by MANDIRI</div>
															<div class="acc-content">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Login ke Livin’ by Mandiri</li>
																				<li>Klik “Bayar”</li>
																				<li>Klik “Buat Pembayaran Baru”</li>
																				<li>Klik “Multi Payment”</li>
																				<li>Klik “Penyedia Jasa”</li>
																				<li>Pilih kode perusahaan</li>
																				<li>Pilih “No. Virtual”</li>
																				<li>Masukkan kode perusahaan diikuti dengan nomor Virtual Account dengan</li>
																				<li>Pilih “Tambah Sebagai Nomor Baru”</li>
																				<li>Masukkan nominal lalu pilih “Konfirmasi” dan “Lanjut”</li>
																				<li>Konfirmasi pembayaran</li>
																				<li>Masukkan PIN dan pilih “Ok”</li>
																				<li>Transaksi berhasil</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

														<!--Block-->
														<li class="accordion block">
															<div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>BNI Mobile Banking (Wondr)</div>
															<div class="acc-content">
																<div class="content">
																	<div class="text">
																		<div class="price-block">
																			<ul class="price-list">
																				<li>Buka aplikasi Wondr By BNI</li>
																				<li>Masuk ke halaman pembayaran Virtual Account dan pilih tujuan baru</li>
																				<li>Masukkan nomor tujuan atau Virtual Account yang akan dibayar</li>
																				<li>Verifikasi alamat tujuan dan jumlah yang akan dibayarkan</li>
																				<li>Kemudian klik "Ya/Benar/Konfirmasi"</li>
																				<li>Masukkan pin Wondr anda</li>
																				<li>Transaksi berhasil</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</li>

													</ul>
												</div>
												<div class="tab-pane fade" id="v-pills-minimart" role="tabpanel" aria-labelledby="v-pills-minimart-tab">
													<div class="price-block">
														<ul class="price-list">
															<li>Pastikan anda mencatat / screen shot Virtual Account yang tertera di Invoice MAXI-LINE anda</li>
															<li>Datangi gerai Alfamart / Indomaret terdekat</li>
															<li>Katakan kepada petugas / kasir bahwa anda akan melakukan pembayaran dengan Virtual Account</li>
															<li>Tunjukan nomor Virtual Account kepada petugas / kasir</li>
															<li>Pastikan bahwa tagihan anda benar</li>
															<li>Simpan bukti pembayaran / struck yang diberikan</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Image Column -->
				<!-- <div class="image-column col-lg-7 col-md-12 col-sm-12">
					<div class="inner-column wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="pattern-layer" style="background-image: url(resources/images/resource/faq-pattern.png)"></div>
						<div class="image">
							<img src="resources/images/resource/faq.png" alt="" />
						</div>
					</div>
				</div> -->
				
			</div>
		</div>
	</section>
	<!-- End Faq Section -->
	
	<!-- Clients Section -->
	<!-- <section class="clients-section">
		<div class="auto-container">
			
			<div class="carousel-outer"> -->
                <!--Sponsors Slider-->
                <!-- <ul class="sponsors-carousel owl-carousel owl-theme">
                    <li><div class="image-box"><a href="#"><img src="resources/images/clients/1.png" alt=""></a></div></li>
                    <li><div class="image-box"><a href="#"><img src="resources/images/clients/2.png" alt=""></a></div></li>
                    <li><div class="image-box"><a href="#"><img src="resources/images/clients/3.png" alt=""></a></div></li>
                    <li><div class="image-box"><a href="#"><img src="resources/images/clients/4.png" alt=""></a></div></li>
					<li><div class="image-box"><a href="#"><img src="resources/images/clients/5.png" alt=""></a></div></li>
                    <li><div class="image-box"><a href="#"><img src="resources/images/clients/1.png" alt=""></a></div></li>
                    <li><div class="image-box"><a href="#"><img src="resources/images/clients/2.png" alt=""></a></div></li>
					<li><div class="image-box"><a href="#"><img src="resources/images/clients/3.png" alt=""></a></div></li>
                    <li><div class="image-box"><a href="#"><img src="resources/images/clients/4.png" alt=""></a></div></li>
                </ul>
            </div>
			
		</div>
	</section> -->
	<!-- End Clients Section -->
	
	<!-- Featured Section -->
	<section class="featured-section">
		<div class="pattern-layer-one" style="background-image: url(resources/images/background/pattern-9.png)"></div>
		<div class="pattern-layer-two" style="background-image: url(resources/images/background/pattern-10.png)"></div>
		<div class="auto-container">
		
			<!-- Sec Title -->
			<div class="sec-title light">
				<div class="clearfix">
					<div class="pull-left">
						<div class="title">Bagaimana Speed Internet Anda?</div>
						<h2 style="color:black;">Check Speed Internet Anda Sekarang</h2>
					</div>
				</div>
			</div>
			<!-- End Sec Title -->

            <!--OST Widget code start-->
            <div style="text-align:right;">
                <div style="min-height:360px;">
                    <div style="width:100%;height:0;padding-bottom:50%;position:relative;">
                        <iframe style="border:none;position:absolute;top:0;left:0;width:100%;height:100%;min-height:360px;border:none;overflow:hidden !important;" src="//openspeedtest.com/speedtest"></iframe>
                    </div>
                </div>
                <span style="color:black;">Provided by <a href="https://openspeedtest.com">OpenSpeedtest.com</a></span>
            </div><!-- OST Widget code end -->
			
		</div>
	</section>
	<!-- End Featured Section -->
	
	<!-- CTA Section -->
	<section class="cta-section" id="contact">
		<div class="auto-container">
			<div class="inner-container" style="background-image: url(resources/images/background/pattern-11.png)">
				<div class="row clearfix">
					
					<!-- Title Column -->
					<div class="title-column col-lg-12 col-md-12 col-sm-12">
						<div class="inner-column centered">
							<h3>Kirim Pesan</h3>
							<div class="text">Jangan takut.. Berkomunikasilah dengan kami.</div>
						</div>
					</div>
					
					<!-- Form Column -->
					<div class="form-column col-lg-12 col-md-12 col-sm-12">
						<div class="inner-column">
							<div class="contact-form">
								<form method="post" action="kirim_aksi.php" id="contact-form" novalidate="novalidate">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="display:none;">
                                            <input type="hidden" name="token" value="<?= $token ?>">
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 form-group">
                                            <input type="text" name="name" placeholder="Nama Lengkap Anda" required>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 form-group">
                                            <input type="email" name="email" placeholder="Alamat Email Anda" required>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                            <input type="text" name="subject" placeholder="Subjek" required>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                            <textarea class="darma" name="message" placeholder="Tulis Pesan Anda..."></textarea>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                            <img id="vimg" class="captcha" src="verificationimage.php?<?php echo rand(1000,9999); ?>" alt="Kode verifikasi, Ketikkan pada kotak isian dikanan / dibawah"/>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-12 form-group">
                                            <input type="text" name="capjay" id="capjay" placeholder="Masukkan angka yang tertera dikiri / diatas" required>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group text-center">
                                            <button class="theme-btn btn-style-four" type="submit" name="submit-form"><span class="txt"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Submit Pesan</span></button>
                                        </div>
                                    </div>
                                    
								</form>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</section>
	<!-- End CTA Section -->
	
	<!-- Main Footer -->
    <footer class="main-footer">
		<div class="pattern-layer-one" style="background-image: url(resources/images/background/pattern-12.png)"></div>
		<div class="pattern-layer-two" style="background-image: url(resources/images/background/pattern-13.png)"></div>
    	<div class="auto-container">
            <div class="widgets-section">
            	<div class="logo">
					<a href="index.html"><img src="resources/images/footer-logo.png" alt="" /></a>
				</div>

                <div class="sec-title centered">
                    <div class="separator"></div>
                    <h2 class="title">Redefine Internet Services</h2>
                </div>

                <div class="contact-page-section">
                    <div class="row clearfix">
                        <!-- Info Column -->
                        <div class="info-column col-lg-4 col-md-12 col-sm-12">
                            <div class="inner-column">
                                <div class="title-box">
                                    <h4>Contact Details</h4>
                                </div>
                                <div class="lower-box">
                                    <ul class="info-list">
                                        <li>
                                            <span class="icon flaticon-map"></span>
                                            Villa Krista no.G17, <br> Kelurahan Gedawang, Banyumanik,<br> Semarang, Jawa Tengah
                                        </li>
                                        <li>
                                            <span class="icon flaticon-call"></span>
                                            <a href="tel:+9684-32-45-789">+6281-5459-28181</a>
                                        </li>
                                        <li>
                                            <span class="icon flaticon-email-1"></span>
                                            <a href="mailto:cs@maxi-line.net">cs@maxi-line.net</a><br>
                                            <a href="www.maxi-line.net">www.maxi-line.net</a>
                                        </li>
                                    </ul>
                                    <div class="timing">Jam Kerja 08:30 - 16:30</div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <!-- Map Column -->
                        <div class="map-column col-lg-8 col-md-12 col-sm-12">
                            <div class="inner-column">
                                <!--Map Outer-->
                                <div class="map-outer">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3959.2967031690705!2d110.428073!3d-7.091565!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708f9e3538f599%3A0x5bc157c707dae8b!2sManunggal%20Sistem%20Sejahtera!5e0!3m2!1sen!2sid!4v1724317385408!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
				
			</div>
			
		</div>
		<!-- Footer Bottom -->
		<div class="footer-bottom">
			<div class="auto-container">
                <ul class="term-condition">
                    <li><a href="syaratdanaturan.php">Syarat & Ketentuan</a></li>
                    <li><a href="kebijakanprivasi.php">Kebijakan Privasi</a></li>
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
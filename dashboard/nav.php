<?php
  $kodeaman = $_SESSION['token'] ?? '';
  $saiki = new DateTime();
  $tauniki = $saiki->format("Y");
  $jam = date("H:i");
?>

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="dist/img/icon_maxi.png" style="width: 70%;"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>MAXI Dashboard</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
	  
	    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
		      <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>			  
              <?php $jtmlitu = ''; ?>
              <?php if($_SESSION['level_user'] == 4) {
                $sq1 = $db->query("SELECT max(id_tr_respon) as id_tr_respon FROM v_pelaporan_respon WHERE jns_laporan = '2' and sts_laporan = '1' or sts_laporan = '2' 
                group by code_pelaporan");

                while($ro1 = $db->fetchArray($sq1)) {
                  $sq2= $db->fetchArray($db->query("SELECT * FROM v_pelaporan_respon WHERE id_tr_respon = '$ro1[id_tr_respon]'"));	
                  if($sq2['level_user'] != 5) {$jum = 0;} else {$jum = 1;}
                  $jai[] = $jum;
                  $idvlap[] = $ro1['id_tr_respon'];
                }

                $jmlitu = array_sum($jai);
              } else if($_SESSION['level_user'] == 2 or $_SESSION['level_user'] == 3) {
                $sq1 = $db->query("SELECT max(id_tr_respon) as id_tr_respon FROM v_pelaporan_respon WHERE jns_laporan = '2' and sts_laporan = '1' or sts_laporan = '2' group by code_pelaporan");
                while($ro1 = $db->fetchArray($sq1)){
                  $sq2= $db->fetchArray($db->query("SELECT * FROM v_pelaporan_respon WHERE id_tr_respon = '$ro1[id_tr_respon]'"));	
                  if($sq2['level_user'] != 5) {$jum = 0; } else {$jum = 1;}
                  $jai[] = $jum;
                  $idvlap[] = $ro1['id_tr_respon'];
                }

                $jmlitu = array_sum($jai);
              } else if($_SESSION['level_user'] == 5) {
                $sq4 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
                $sq1 = $db->query("SELECT max(id_tr_pelaporan) as id_tr_pelaporan FROM tr_pelaporan WHERE sts_laporan = '2' and id_tb_pendaftaran = '$sq4[id_tb_pendaftaran]' group by code_pelaporan");
                while($ro1 = $db->fetchArray($sq1)){
                  $sq2= $db->fetchArray($db->query("SELECT max(id_tr_respon) as id_tr_respon FROM tr_respon WHERE id_tr_pelaporan = '$ro1[id_tr_pelaporan]'"));
                  $sq3= $db->fetchArray($db->query("SELECT * FROM v_pelaporan_respon WHERE id_tr_respon = '$sq2[id_tr_respon]'"));
                  if($sq3['level_user'] == 5) {$jum = 0; } else {$jum = 1;}
                  $jai[] = $jum;
                  $idvlap[] = $sq2['id_tr_respon'];
                }

                $jmlitu = array_sum($jai);
              } else {
                $sq1 = $db->query("SELECT max(id_tr_respon) as id_tr_respon FROM v_pelaporan_respon WHERE sts_laporan = '1' or sts_laporan = '2' group by code_pelaporan");
                while($ro1 = $db->fetchArray($sq1)){
                  $sq2= $db->fetchArray($db->query("SELECT * FROM v_pelaporan_respon WHERE id_tr_respon = '$ro1[id_tr_respon]'"));	
                  if($sq2['level_user'] != 5) {$jum = 0;} else {$jum = 1;}
                  $jai[] = $jum;
                  $idvlap[] = $ro1['id_tr_respon'];
                }

                $jmlitu = array_sum($jai);
              }
              ?>
      
              <?php if($jmlitu > 0){ ?>				 
                <span class="label label-danger"><?=$jmlitu;?></span>
              <?php } ?>
            </a>
            <ul class="dropdown-menu">
              <?php if($jmlitu > 0){ ?>
                <li class="header">Ada <?=$jmlitu;?> pelaporan memerlukan respon</li>
              <?php } else { ?>
                <li class="header">Tidak ada respon pelaporan baru</li>
              <?php } ?>

              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php if($jmlitu > 0){ ?>
                      <?php
                        foreach($idvlap as $id_trespon){
                          $sq3 = $db->fetchArray($db->query("select * from v_pelaporan_respon where id_tr_respon = '$id_trespon'"));
                          if(empty($sq3['pasfoto'])){$foto = "dist/foto_profil/icon.jpg";} else {$foto = "dist/foto_profil/".$sq3['pasfoto'];}
                      ?>	
                          <li><!-- start message -->
                            <a href="pelaporan_add.php?id=<?=maxiline($sq3['id_tr_pelaporan'], 'e');?>&i=<?=maxiline($sq3['code_pelaporan'],'e')?>">
                            <div class="pull-left">
                              <img src="<?=$foto;?>" class="img-circle" alt="User Image">
                            </div>
                            <h4>
                              <?= $sql3['nm_user'];?>
                              <small><i class="fa fa-clock-o"></i>
                              <?php
                                $datetime1 = $sq3['tgl_respon'];
                                $datetime2 = $saiki;

                                $difference = $datetime1->diff($datetime2);
                                $jeda = $difference->days;

                                if($jeda <= 1){
                                  $beda = $difference->h;
                                  if($beda <= 1) {echo $difference->i." Menit";} else {echo $difference->h." Jam";}
                                } else {
                                  echo tglindo(date_format($sq3['tgl_respon'], 'Y-m-d'))." ".date_format($sq3['tgl_respon'], 'H:i');
                                }
                              ?>	
                              </small>
                            </h4>
                            <p>
                              <?$cutnya = chunk_split($sq3['respon'], 25, "<br>"); 
                              echo substr_replace($cutnya, "...", 50);?></p>
                              </a>
                          </li>
                          <!-- end message -->
                        <?php } ?>
                  <?php } ?>
                </ul>
              </li>
              <li class="footer"><a href="pelaporan_v.php">Lihat semua pelaporan</a></li>
            </ul>
          </li>
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <?php 
                $sql1 = $db->fetchArray($db->query("select count(id_tr_pesan) as jmlpesan from tr_pesan where st_baca = '1' and st_pesan = '1' and id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '1'"));					  
                if($sql1['jmlpesan'] > 0){
              ?>				 
                <span class="label label-danger"><?=$sql1['jmlpesan'];?></span>
              <?php } ?>
            </a>
            <ul class="dropdown-menu">
              <?php if($sql1['jmlpesan'] > 0){ ?>
                <li class="header">Ada <?=$sql1['jmlpesan'];?> pesan belum dibaca</li>
              <?php } else { ?>
                <li class="header">Tidak ada pesan baru</li>
              <?php } ?>

              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php if($sql1['jmlpesan'] > 0){ 
                    $sql2 = $db->query("select * from tr_pesan where st_baca = '1' and st_pesan = '1' and id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '1'");	
                    while($row2 = $db->fetchArray($sql2)){
                      $sql3 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row2[id_user_pengirim]'"));
                      if(empty($sql3['pasfoto'])){$foto = "dist/foto_profil/icon.jpg";} else {$foto = "dist/foto_profil/".$sql3['pasfoto'];}
                  ?>
                        <li><!-- start message -->
                          <a href="pesan_baca.php?id=<?=maxiline($row2['id_tr_pesan'],'e');?>&p=i">
                            <div class="pull-left">
                              <img src="<?=$foto;?>" class="img-circle" alt="User Image">
                            </div>
                            <h4>
                              <?=$sql3['nm_user'];?>
                              <small><i class="fa fa-clock-o"></i>
                                <?php
                                  $datetime1 = $row2['tgl_data'];
                                  $datetime2 = $saiki;

                                  $difference = $datetime1->diff($datetime2);
                                  $jeda = $difference->days;

                                  if($jeda <= 1){
                                    $beda = $difference->h;
                                    if($beda <= 1){echo $difference->i." Menit";} else {echo $difference->h." Jam";}
                                  } else {
                                    echo tglindo(date_format($row2['tgl_data'], 'Y-m-d'))." ".date_format($row2['tgl_data'], 'H:i');
                                  }
                                ?>	
                              </small>
                            </h4>
                            <p><?=$row2['subyek'];?></p>
                          </a>
                        </li>
                        <!-- end message -->
                      <?php }?>
                  <?php } ?>
                </ul>
              </li>
              <li class="footer"><a href="pesan_v.php">Lihat semua pesan</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <?php if($_SESSION['level_user'] == 4) {
                $sql4 = $db->fetchArray($db->query("SELECT count(id_tr_pelaporan) as jumlap FROM tr_pelaporan WHERE jns_laporan = '1' and sts_laporan = '1' or sts_laporan = '2'"));
                $jmlitung = $sql4['jumlap'];
              } else if($_SESSION['level_user'] == 2 or $_SESSION['level_user'] == 3) {
                $sql4 = $db->fetchArray($db->query("SELECT count(id_tr_pelaporan) as jumlap FROM tr_pelaporan WHERE jns_laporan = '2' and sts_laporan = '1' or sts_laporan = '1'"));
                $jmlitung = $sql4['jumlap'];
              } else if($_SESSION['level_user'] == 5) {
                $sql4 = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
                $sql5 = "SELECT id_tr_pelaporan FROM tr_pelaporan WHERE id_tb_pendaftaran = '$sql4[id_tb_pendaftaran]' and sts_laporan = '2'";
                $res5 = $db->query($sql5);
                $alli = array();
                while($row5=$db->fetchArray($res5)) {
                  $sql6 = $db->fetchArray($db->query("SELECT max(id_tr_respon) as respone FROM tr_respon WHERE id_tb_pelaporan = '$row5[id_tb_pelaporan]'"));
                  $sql7 = $db->fetchArray($db->query("SELECT * FROM tr_respon WHERE id_tr_respon = '$sql6[respone]'"));
                  if($sql7['id_tb_user'] != $_SESSION['id_tb_user']){
                    $itung = 1;	
                  } else {
                    $itung = 0;		
                  }
                  $alli[] = $itung;
                }

                $jmlitung = array_sum($alli);
              } else {
                $sql4 = $db->fetchArray($db->query("SELECT count(id_tr_pelaporan) as jumlap FROM tr_pelaporan where sts_laporan = '1' or sts_laporan = '2'"));
                $jmlitung = $sql4['jumlap'];			
              }
				
              if($_SESSION['level_user'] == 5) {
                $sql10 = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
                $sql11 = "SELECT * FROM v_invoice WHERE id_tb_pendaftaran = '$sql10[id_tb_pendaftaran]' and sts_invoice = '2' and sts_lunas = '1' and st_layanan = '8' and expired < GETDATE()";
                $res11 = $db->fetchArray($db->query($sql11));
                if($res11['id_tr_invoice'] != 0){$tuto = 1;} else {$tuto = 0;}
              } else {	
                $sql8 = $db->fetchArray($db->query("SELECT count(id_tr_invoice) as jumle FROM v_invoice WHERE sts_invoice = '2' and sts_lunas = '1' and st_layanan = '8' and expired < GETDATE()"));
                if($sql8['jumle'] != 0){$tuto = 1;} else {$tuto = 0;}
              }

              if($jmlitung != 0){$tuti = 1;} else {$tuti = 0;}				
              $situ = $tuti + $tuto;
              ?>
			  
              <span class="label label-danger"><?if($situ == 0){} else {echo "$situ";}?></span>
            </a>
            <ul class="dropdown-menu">			
              <li class="header"><?if($situ == 0){echo "Tidak ada notifikasi";} else {echo $situ." notifikasi";}?></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php if($jmlitung > 0) { ?>
                    <li>
                      <a href="pelaporan_v.php">
                        <i class="fa fa-exclamation-circle text-yellow"></i> <?=$jmlitung;?> Pelaporan aktif
                      </a>
                    </li>
                  <?php } ?>
				
                  <?php if($_SESSION['level_user'] == 4 or $_SESSION['level_user'] == 1) {
                    $sql9 = "SELECT count(id_tr_invoice) as jumle FROM v_invoice WHERE sts_invoice = '2' and sts_lunas = '1' and st_layanan = '8' and expired < GETDATE()";
                    $res9 = $db->query($sql9);
                    $row9 = $db->fetchArray($res9);
                  ?>
                    <?php if($row9['jumle'] != 0){ ?>			
                      <li>
                        <a href="invoice_pending.php?id=2">
                          <i class="fa fa-shopping-cart text-red"></i> <?=$row9['jumle'];?> Invoice expired
                        </a>
                      </li>
                    <?php } ?>
                  <?php } else if($_SESSION['level_user'] == 5){ ?>	
                    <?php if(!empty($res11['id_tr_invoice'])){
                      $ida = maxiline($res11['id_tb_pendaftaran'], 'e');
                      $ido = maxiline($res11['id_tr_invoice'], 'e');
                    ?>
                      <li>
                        <a href="invoice_pelanggan_add.php?id=<?=$ida;?>&i=<?=$ido;?>">
                          <i class="fa fa-shopping-cart text-red"></i> 1 invoice belum dibayar
                        </a>
                      </li>
                    <?php } ?>
                  <?php } ?> 				 
                </ul>
              </li>              
            </ul>
          </li>        
          <li><a href="logout.php?id=<?=maxiline($_SESSION['id_tb_user'], 'e');?>"><i class="fa fa-sign-out "></i></a></li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">		 
          <?php
            $soq = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
            if(empty($soq['pasfoto'])){$foto = "icon.jpg";} else {$foto = $soq['pasfoto'];}
          ?>
          <img src="dist/foto_profil/<?=$foto?>" class="img-circle" alt="User Image">		
        </div>
        <div class="pull-left info">
          <p><?if (!isset($_SESSION['username'])) {echo "guest";} else {echo $_SESSION['nama'];}?></p>
          <a href="user_add.php?i=<?=maxiline($_SESSION['id_tb_user'], 'e');?>&a=b"><i class="fa fa-circle text-success"></i>
            <?php if (!isset($_SESSION['username'])) {echo "guest";} else {echo $st_level[intval("0".$_SESSION['level_user'])];}?>
          </a>
        </div>
      </div>
      <!-- search form -->
     
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <?php
          $userlvl = array(1,2,3,4);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="header">MAIN NAVIGATION</li>
          <li class="<?php if ($page == "index") {echo "active";} ?>">
            <a href="index.php">
              <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
        <?php } ?>

        <?php 
          $userlvl = array(1,2,3);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="<?php if ($page == "zonasi") {echo "active";} ?>">
            <a href="lokasi_v.php">
              <i class="fa fa-globe"></i> <span>Coverage</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> 
		
          <li class="treeview <?php if ($page == "pendaftaran") {echo "active";} ?>">          
            <a href="#">
              <i class="fa fa-pencil"></i> <span>Pendaftaran</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                  <li class="<?php if ($pages == "baru") {echo "active";} ?>"><a href="pendaftaran_v.php"><i class="fa fa-circle-o"></i> Pendaftaran Baru</a></li>
                  <li class="<?php if ($pages == "selesai") {echo "active";} ?>"><a href="pendaftaran_selesai.php"><i class="fa fa-circle-o"></i> Pendaftaran Selesai</a></li>
            </ul>
          </li>
        <?php } ?>

        <?php 
          $userlvl = array(1,2,3,4);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="<?php if ($page == "pelanggan") {echo "active";} ?>">
            <a href="pelanggan_v.php">
              <i class="fa fa-table"></i> <span>Pelanggan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>          
          </li>  
        <?php } ?>
        <?php
          $userlvl = array(1,4);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="header">FINANCE & INVOICE</li>
          <li class="<?php if ($page == "langganan") {echo "active";} ?>">
            <a href="paket_v.php">
              <i class="fa fa-credit-card"></i> <span>Biaya Bulanan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
		
          <li class="treeview <?php if ($page == "adcharge") {echo "active";} ?>">
            <a href="#">
              <i class="fa fa-bar-chart"></i> <span>Biaya Tambahan</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="<?php if ($pages == "pasang") {echo "active";} ?>"><a href="potongan_v.php?idx=bFZHcDF0alhzWTYvZGtYZTc1ZyswUT09"><i class="fa fa-circle-o"></i> Pemasangan</a></li>
              <li class="<?php if ($pages == "bulanan") {echo "active";} ?>"><a href="potongan_v.php?idx=Vy80RENjZGxxTTNDOHN5VkRJSGlJUT09"><i class="fa fa-circle-o"></i> Bulanan</a></li>
              <li class="<?php if ($pages == "layanan") {echo "active";} ?>"><a href="potongan_v.php?idx=WEEvaENpL3VyK3BxTER3cTFYYVJqUT09"><i class="fa fa-circle-o"></i> Layanan</a></li>
              <li class="<?php if ($pages == "pemutusan") {echo "active";} ?>"><a href="potongan_v.php?idx=ckxmWHJMNXpJeTNxbWlXdzQ5TjRKUT09"><i class="fa fa-circle-o"></i> Pemutusan</a></li>
            </ul>
          </li>
		
          <!-- <li class="<?php if ($page == "diskon") {echo "active";} ?>">
            <a href="diskon_v.php">
              <i class="fa fa-cart-arrow-down"></i> <span>Diskon</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> -->
		
          <li class="treeview <?php if ($page == "invoice") {echo "active";} ?>">
            <a href="#">
              <i class="fa fa-money"></i> <span>Invoice</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="<?php if ($pages == "draft") {echo "active";} ?>"><a href="invoice_draft.php"><i class="fa fa-circle-o"></i> Draft Invoice</a></li>
              <li class="<?php if ($pages == "terbit") {echo "active";} ?>"><a href="invoice_v.php"><i class="fa fa-circle-o"></i> Invoice Terbit</a></li>
              <li class="<?php if ($pages == "pending") {echo "active";} ?>"><a href="invoice_pending.php"><i class="fa fa-circle-o"></i> Invoice Pending</a></li>
              <li class="<?php if ($pages == "confirm") {echo "active";} ?>"><a href="invoice_confirm.php"><i class="fa fa-circle-o"></i> Konfirmasi Pembayaran</a></li>
              <li class="<?php if ($pages == "note") {echo "active";} ?>"><a href="note_v.php"><i class="fa fa-circle-o"></i> Note Invoice</a></li>
            </ul>
          </li>
        <?php } ?>
		
        <?php 
          $userlvl = array(1);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="header">ADMIN</li>		
          <li class="<?php if ($page == "diskon") {echo "active";} ?>">
            <a href="diskon_v.php">
              <i class="nav-icon fa fa-star-half-empty"></i><span>Diskon</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
		
          <li class="<?php if ($page == "tos") {echo "active";} ?>">
            <a href="tos_v.php">
              <i class="fa fa-book" ></i><span>Term of Services</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> 		
		
          <li class="<?php if ($page == "user_pelanggan") {echo "active";} ?>">
            <a href="user_pelanggan_v.php">
              <i class="fa fa-address-book" ></i><span>User Pelanggan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> 
		
          <li class="<?php if ($page == "user") {echo "active";} ?>">
            <a href="user_v.php">
              <i class="fa fa-user"></i><span>User Maxi-Line</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
        <?php } ?>
		
        <?php
          $userlvl = array(1,2,3);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="header">SERVICES TEAM</li>
		
          <li class="<?php if ($page == "pelaporan") {echo "active";} ?>">
            <a href="pelaporan_v.php">
              <i class="fa fa-minus-circle"></i> <span>Pelaporan / Tiket</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
 		
          <li class="<?php if ($page == "pesan") {echo "active";} ?>">
            <a href="pesan_v.php">
              <i class="fa fa-commenting"></i> <span>Pesan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> 
		
          <li class="header">Laporan</li>
		
          <li class="<?php if ($page == "laptransaksi") {echo "active";} ?>">
            <a href="laporan_transaksi.php">
              <i class="fa fa-newspaper-o"></i> <span>Laporan Transaksi</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
        <?php } ?>

        <?php
          $userlvl = array(5);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="header">NAVIGASI PELANGGAN</li>
          <li class="<?php if ($page == "index2") {echo "active";} ?>">
            <a href="index_pelanggan.php">
              <i class="fa fa-dashboard"></i> <span>Home</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> 
          <li class="<?php if ($page == "pembayaran") {echo "active";} ?>">
            <a href="invoice_pelanggan.php">
              <i class="fa fa-credit-card"></i><span>Invoice</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
          <li class="<?php if ($page == "voucher") {echo "active";} ?>">
            <a href="voucher_pelanggan.php">
              <i class="fa fa-money"></i><span>Voucher</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
          <li class="<?php if ($page == "pesan") {echo "active";} ?>">
            <a href="pesan_v.php">
              <i class="fa fa-commenting"></i> <span>Pesan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> 
          <li class="<?php if ($page == "invoice") {echo "active";} ?>">
            <a href="invoice_confirms.php">
              <i class="fa fa-book"></i> <span>Konfirmasi Pembayaran</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
          <!-- <li class="<?php if ($page == "bayarol") {echo "active";} ?>">
            <a href="online_pay.php">
              <i class="fa fa-minus-circle"></i> <span>Online Payment</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> -->
          <li class="<?php if ($page == "pelaporan") {echo "active";} ?>">
            <a href="pelaporan_v.php">
              <i class="fa fa-plus-circle"></i> <span>Pelaporan / Tiket</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
        <?php } ?>

        <?php
          $userlvl = array(6);
          if(in_array($_SESSION['level_user'], $userlvl)) {
        ?>
          <li class="header">NAVIGASI RESELLER</li>
          <li class="<?php if ($page == "index-reseller") {echo "active";} ?>">
            <a href="index_reseller.php">
              <i class="fa fa-dashboard"></i> <span>Home</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> 
          <li class="<?php if ($page == "pelanggan") {echo "active";} ?>">
            <a href="pelanggan_v.php">
              <i class="fa fa-users" aria-hidden="true"></i> <span>Pelanggan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>  
          <li class="<?php if ($page == "history-pembayaran") {echo "active";} ?>">
            <a href="invoice_by_reseller.php">
              <i class="fa fa-credit-card"></i><span>Riwayat Tagihan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
          <li class="<?php if ($page == "voucher") {echo "active";} ?>">
            <a href="voucher_pelanggan.php">
              <i class="fa fa-money"></i><span>Voucher</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
          <li class="<?php if ($page == "pesan") {echo "active";} ?>">
            <a href="pesan_v.php">
              <i class="fa fa-commenting"></i> <span>Pesan</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
          <li class="<?php if ($page == "invoice") {echo "active";} ?>">
            <a href="invoice_confirms.php">
              <i class="fa fa-book"></i> <span>Konfirmasi Pembayaran</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
          <!-- <li class="<?php if ($page == "bayarol") {echo "active";} ?>">
            <a href="online_pay.php">
              <i class="fa fa-minus-circle"></i> <span>Online Payment</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li> -->
          <li class="<?php if ($page == "pelaporan") {echo "active";} ?>">
            <a href="pelaporan_v.php">
              <i class="fa fa-plus-circle"></i> <span>Pelaporan / Tiket</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-green"></small>
              </span>
            </a>
          </li>
        <?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<?php
  $page= "index";

  include ("head.php");
  include ("nav.php");

  // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
  // $interface_link = "$protocol://{$_SERVER['HTTP_HOST']}/dashboard/monitoring_interface.php";

  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
  $interface_link = "$protocol://{$_SERVER['HTTP_HOST']}/maxi-line/dashboard/monitoring_interface.php";

  $now = new DateTime();
  $th = $now->format("Y");
  $sekarang = $now->format("Y-m-d");
  $color = array("#ccff33", "#ffcc00", "#ff9933","#ff33cc","#0099ff","#00ffcc","#00cc00","#0000cc","#6b616b","#a8a36b","#c5a6ff","#ccff33", "#ffcc00");
  $bulan = array (1 =>   'Januari', 'Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

  foreach($_GET as $kuncinya => $nilainya) {
    $filter[$kuncinya] = $db->escaped(stripslashes(strip_tags(htmlspecialchars($nilainya, ENT_QUOTES))));
  }

  if($_GET['id']) {
    $idd = $filter['id'];
    $ide = dirpk( $idd, 'd' );
  } else {
    $ide = "2020";
  }
?>

  <?php if($_SESSION['level_user'] == 5) { ?>
    <script>document.location.href="index_pelanggan.php"</script>
    <?php exit(); ?>
  <?php } elseif($_SESSION['level_user'] == 6) { ?>
    <script>document.location.href="index_reseller.php"</script>
    <?php exit();
  } ?>
	
	<link rel="stylesheet" href="resources/dataTables.searchHighlight.css" type="text/css"/>
	
	<link rel="stylesheet" type="text/css" href="resources/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="resources/buttons.dataTables.min.css">
	
	<script type="text/javascript" language="javascript" src="resources/jquery-1.12.4.js">
	</script>

	<script type="text/javascript" language="javascript" src="resources/jquery.dataTables.min.js">
	</script>

	<script type="text/javascript" language="javascript" src="resources/dataTables.searchHighlight.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/jquery.highlight.js">
	</script>

  <style>
      .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0!important;
        border: none !important;
      }

      div.dt-buttons {
        margin-left: 350px;
      }

      .dropdown-menu {
        min-width: 120px;
      }

      div.dataTables_wrapper div.dataTables_paginate {
        padding-top: 30px;
      }

      div.dataTables_wrapper div.dataTables_info {
        padding-top: 30px;
      }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div id="interfaces"></div>

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <?php
                $sql = "SELECT COUNT(id_tb_pendaftaran) AS jml FROM tb_pendaftaran where st_layanan = '8'";
                $res = $db->query($sql);
                $row = $db->fetchArray($res);
              ?>
              <h3><?=$row['jml']?></h3>
              <p>Pelanggan</p>
            </div>
            <div class="icon">
              <i class="ion ion-document"></i>
            </div>
            <a href="pelanggan_v.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <?php
                $sql1 = "SELECT COUNT(id_tb_pendaftaran) AS jml1 FROM tb_pendaftaran where DATEADD(dd, 0, DATEDIFF(dd, 0, tgl_data)) = '$sekarang'";
                $res1 = $db->query($sql1);
                $row1 = $db->fetchArray($res1);
              ?>
              <h3><?php if(empty($row1['jml1'])) {echo "0";} else { echo $row1['jml1'];} ?></h3>
              <p>Pendaftaran Baru</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="pendaftaran_v.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <?php
                $sql2 = "SELECT COUNT(id_tb_pendaftaran) AS jml2 FROM tb_pendaftaran where st_layanan not in('2','8','9')";
                $res2 = $db->query($sql2);
                $row2 = $db->fetchArray($res2);
              ?>
              <h3><?php if(empty($row2['jml2'])) {echo "0";} else { echo $row2['jml2'];} ?></h3>
              <p>Pendaftaran Diproses</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="pendaftaran_v.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <?php
                $sql3 = "SELECT COUNT(id_tb_pendaftaran) AS jml3 FROM tb_rencana where DATEADD(dd, 0, DATEDIFF(dd, 0, tgl_rencana)) = '$sekarang'";
                $res3 = $db->query($sql3);
                $row3 = $db->fetchArray($res3);
              ?>
              <h3><?php if(empty($row3['jml3'])) {echo "0";} else { echo $row3['jml3'];} ?></h3>
              <p>Jadwal Hari Ini</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="pendaftaran_v.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
	  
      <div class="row">
        <div class="col-md-8">
          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Jadwal Hari Ini</h3>

              <div class="box-tools pull-right">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                    <?php
                      $sql4 = "SELECT * FROM tb_rencana where DATEADD(dd, 0, DATEDIFF(dd, 0, tgl_rencana)) = '$sekarang'";
                      $res4 = $db->query($sql4);
                      $jml4 = $db->queryNumRows($sql4);
                      $total4 = $db->getNumRows($jml4);
                      
                      if(empty($total4)) {
                    ?>
                      <tr>
                        <td colspan="5" align="center">Tidak ada jadwal hari ini</td>
                      </tr>
                    <?php } else {	?>
                      <tr>
                        <th>Kode Pendaftaran</th>
                        <th>Nama Pendaftar</th>
                        <th>Jam</th>
                        <th>Pengerjaan</th>
                        <th>Petugas</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php
                      while($row4 = $db->fetchArray($res4)){
                        $sql5 = $db->fetchArray($db->query("SELECT * from tb_pendaftaran where id_tb_pendaftaran = '$row4[id_tb_pendaftaran]'"));
                        $expld = explode(',', $row4['id_tb_user']);
                    ?>
                    <tr>
                      <td><a href="pendaftaran_dtl.php?id=<?=maxiline($row4['id_tb_pendaftaran'],'e');?>"><?=$sql5['kode_daftar'];?></a></td>
                      <td><?=$sql5['nama'];?></td>
                      <td><?=date_format($row4['tgl_rencana'], 'H:i')." WIB";?></td>
                      <td><?=$st_rencana[intval("0".$row4['rencana'])];?></td>
                      <!-- <td>< $sql6['nm_user'];?></td> -->
                      <td>
                        <?php
                          $n = 1;
                          foreach ($expld as $value) {
                            $sql6 = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$value'"));
                            echo $n++ . '. ' . $sql6['nm_user'] . '<br>';
                          }
                        ?>
                      </td>
                    </tr>
                    <?php }} ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
      
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pelaporan / Tiket</h3>

              <div class="box-tools pull-right">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <?php
                  if($_SESSION['level_user'] == 4) {
                    $s1 = "SELECT * FROM tr_pelaporan WHERE jns_laporan = '1' and sts_laporan = '1' or sts_laporan = '2'";
                  } elseif ($_SESSION['level_user'] == 2 or $_SESSION['level_user'] == 3) {
                    $s1 = "SELECT * FROM tr_pelaporan WHERE jns_laporan = '2' and sts_laporan = '1' or sts_laporan = '1'";
                  } else {
                    $s1 = "SELECT * FROM tr_pelaporan where sts_laporan = '1' or sts_laporan = '2'";
                  }

                  $j1 = $db->queryNumRows($s1);
                  $t1 = $db->getNumRows($j1);
                ?>

                <?php if(!empty($t1)){
                  $re1 = $db->query($s1);
                  while($ro1 = $db->fetchArray($re1)){
                    $sql7 = $db->fetchArray($db->query("select * from tb_user where id_tb_pendaftaran = '$ro1[id_tb_pendaftaran]'"));
                    if(empty($sql7['pasfoto'])){$foto = "dist/foto_profil/icon.jpg";} else {$foto = "dist/foto_profil/".$sql7['pasfoto'];}
                ?>
                    <li class="item">
                      <div class="product-img">
                        <img src="<?=$foto;?>" alt="Product Image">
                      </div>
                      <div class="product-info">
                        <a href="pelaporan_add.php?id=<?=maxiline($ro1['id_tr_pelaporan'], 'e');?>&i=<?=maxiline($ro1['code_pelaporan'],'e')?>" class="product-title"><?=$ro1['code_pelaporan'];?>
                          <span class="label label-warning pull-right"><?=$sql7['nm_user'];?></span>
                        </a>
                        <span class="product-description">
                          <?=$ro1['subyek_laporan'];?>
                        </span>
                      </div>
                    </li>
                  <?php } ?>
                <?php } else {?>
                  <li class="item">
                    <div style="text-align:center;">Tidak ada pelaporan <?=$jmlitu;?></div> 
                  </li>
                <?php } ?>
                <!-- /.item -->  
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
            </div>
            <!-- /.box-footer -->
          </div>
        </div>

        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Monitoring Traffic</h3>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table id="table_monitoring" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Service</th>
                            <th>Upload</th>
                            <th>Download</th>
                            <th>Uptime</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- More rows as needed -->
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; <?=$th;?> Maxi-Line.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- DataTables -->
<script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
  setInterval("reloadTable()", 1000);

  var scrollPosition = 0;

  function reloadTable() {
    $.ajax({
      url: 'table_monitoring_json.php',
      dataType: 'json',
      success: function(data) {
        // Save the current scroll position
        scrollPosition = $(window).scrollTop();

        // Initialize or reload DataTable with new data
        if ($.fn.DataTable.isDataTable('#table_monitoring')) {
          var table = $('#table_monitoring').DataTable();
          table.clear();
          table.rows.add(data).invalidate().draw(false); // Redraw without resetting the pagination
        } else {
          $('#table_monitoring').DataTable({
            data: data,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            // stateSave: true, // Retain the table state (pagination, sorting, etc.)
            columns: [
              { data: 'no' },
              { data: 'user' },
              { data: 'service' },
              { data: 'tx' },
              { data: 'rx' },
              { data: 'uptime' },
              { data: 'action' }
            ]
          });
        }

        // Restore the scroll position
        $(window).scrollTop(scrollPosition);
      }
    });
  }
</script>

<script>
    setInterval("reloadInterface()", 1000);

    function reloadInterface() {
      $("#interfaces").load('<?= $interface_link; ?>');
    }
</script>
</body>
</html>

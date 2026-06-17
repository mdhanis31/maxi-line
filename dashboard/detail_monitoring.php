<?php
    $page= "detail_monitoring";

    include ("head.php");
    include ("nav.php");
    // require_once "include/DbConnector.php";

    $now = new DateTime();
    $th = $now->format("Y");

    $id = SafeSQL(maxiline($_GET['id'], 'd'));
    $dataPointsRx = array();
    $dataPointsTx = array();

    $sql = "select * from tb_pendaftaran where kode_daftar = '$_GET[code]'";
    $res = $db->query($sql);
    $row = $db->fetchArray($res);

    $sqld = $db->fetchArray($db->query("select * from tb_paket where id_tb_paket = '$row[id_tb_paket]'"));

?>

<?php if($_SESSION['level_user'] == 5) { ?>
  <script>document.location.href="index_pelanggan.php"</script>
  <?php exit(); ?>
<?php } ?>
	
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
        Detail Monitoring
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Detail Monitoring</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detail Pelanggan</h3>
                    </div>
                    <div class="box-body row" style="padding-left: 50px; padding-right 50px">
                        <div class="col-md-6">
                            <table border="0" width="100%">
                                <tr>
                                    <td width="200px">Kode Pelanggan</td>
                                    <td width="10px">:</td>
                                    <td><?= !empty($row['kode_daftar']) ? $row['kode_daftar'] : "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Nama Pelanggan</td>
                                    <td>:</td>
                                    <td><?= !empty($row['nama']) ? $row['nama'] : "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td><?= !empty($row['email']) ? $row['email'] : "-" ?></td>
                                </tr>
                                <tr>
                                    <td>No Telp</td>
                                    <td>:</td>
                                    <td><?= !empty($row['telp']) ? $row['telp'] : "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td><?= !empty($row['alamat']) ? $row['alamat'] : "-" ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table border="0" width="100%">
                                <tr>
                                    <td width="200px">Paket Berlangganan</td>
                                    <td width="10px">:</td>
                                    <td><?= !empty($sqld['nama_paket']) ? $sqld['nama_paket'] : "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Status Berlangganan</td>
                                    <td>:</td>
                                    <td><?= !empty($row['st_layanan']) ? $st_layanan[$row['st_layanan']] : "-" ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
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

<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>

<script>
    window.onload = function() {
        var dataPointsRx = <?= json_encode($dataPointsRx, JSON_NUMERIC_CHECK) ?>;
        var dataPointsTx = <?= json_encode($dataPointsTx, JSON_NUMERIC_CHECK) ?>;

        function convertBytes(size) {
            const units = ['B', 'kB', 'MB', 'GB', 'TB'];
            let i = 0;
            while (size >= 1024 && i < units.length - 1) {
                size /= 1024;
                i++;
            }
            return { value: size.toFixed(2), unit: units[i] };
        }

        var options = {
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Monitoring Internet Traffic"
            },
            axisX: {
                title: "Time Stamp"
            },
            axisY: {
                title: "Download",
                suffix: "B/s"
            },
            axisY2: {
                title: "Upload",
                suffix: "B/s"
            },
            data: [
                {
                    type: "spline",
                    name: "Download",
                    toolTipContent: "Date: {x} <br>{name}: {y} {unit}/s",
                    showInLegend: true,
                    legendMarkerColor: "green",
                    dataPoints: dataPointsRx
                },
                {
                    type: "spline",
                    name: "Upload",
                    toolTipContent: "Date: {x} <br>{name}: {y} {unit}/s",
                    showInLegend: true,
                    legendMarkerColor: "red",
                    dataPoints: dataPointsTx
                },
            ]
        };

        $("#chartContainer").CanvasJSChart(options);
        updateData();

        function addData(data) {
            var rxRaw = parseFloat(data[0].rx);
            var txRaw = parseFloat(data[0].tx);
            var time = new Date();

            // Convert rx and tx to a dynamic byte format
            var rxConverted = convertBytes(rxRaw);
            var txConverted = convertBytes(txRaw);

            dataPointsRx.push({
                x: time,
                y: parseFloat(rxConverted.value),
                unit: rxConverted.unit,
                lineColor: "green",
                markerColor: "green"
            });

            dataPointsTx.push({
                x: time,
                y: parseFloat(txConverted.value),
                unit: txConverted.unit,
                lineColor: "red",
                markerColor: "red"
            });

            // Update axis suffixes dynamically based on the last data point
            options.axisY.suffix = rxConverted.unit + "/s";
            options.axisY2.suffix = txConverted.unit + "/s";

            // Keep data points limited to the last 50 entries
            if (dataPointsRx.length > 50) dataPointsRx.shift();
            if (dataPointsTx.length > 50) dataPointsTx.shift();

            $("#chartContainer").CanvasJSChart().render();
            setTimeout(updateData, 1500);
        }

        function updateData() {
            var id = <?= json_encode($id) ?>;
            $.getJSON("detail_monitoring_json.php?id=" + id, addData);
        }
    }
</script>
</body>
</html>

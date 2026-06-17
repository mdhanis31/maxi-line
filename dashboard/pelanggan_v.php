<?php
$page= "pelanggan";

include ("head.php");
include ("nav.php");

$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

$status = array(8,9,10);
$ids = join("','",$status);
if ($_SESSION['level_user'] == 6) {
  $qry = $db->query("SELECT * FROM tb_user WHERE id_tb_user = '$_SESSION[id_tb_user]' AND sts_delete = 1");
  $row = $db->fetchRow($qry);

  $sql = "SELECT * FROM tb_pendaftaran WHERE st_layanan IN ('$ids') and referral_by = '$row[referral]' order by tgl_data desc";
}else {
  $sql = "SELECT * FROM tb_pendaftaran WHERE st_layanan IN ('$ids') order by tgl_data desc";
}

$res = $db->query($sql);

if($_SESSION['level_user'] == 5) {
?>
  <script>alert('Level anda tidak memiliki hak akses!')</script>;
  <script>document.location.href="index.php"</script>
<?php
  exit();
}
?>
	
	<!-- <link rel="stylesheet" href="resources/dataTables.searchHighlight.css" type="text/css"/> 
	
	<link rel="stylesheet" type="text/css" href="resources/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="resources/buttons.dataTables.min.css">
	
	<script type="text/javascript" language="javascript" src="resources/jquery-1.12.4.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/jquery.dataTables.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/dataTables.buttons.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.colVis.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.flash.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/pdfmake.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/vfs_fonts.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.html5.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/buttons.print.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="resources/dataTables.searchHighlight.min.js">	
	</script>
	<script type="text/javascript" language="javascript" src="resources/jquery.highlight.js">	
	</script> -->

  <!-- <script type="text/javascript" class="init">
    $(document).ready(function() {
      $.fn.DataTable.ext.pager.numbers_length = 6;
      var dataTable = $('#usaha').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax":{
              url :"json_zonasi.php", // json datasource
              type: "post",  // method  , by default get
            },
            "order": [[ 1, "desc" ]],
            "lengthMenu": [[10, 20, 30], [10, 20, 30]],
            // dom: 'Blfrtip',
            searchHighlight: true,
      });
      
      dataTable.on('draw.dt', function () {
        var info = dataTable.page.info();
        dataTable.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + info.start;
        });
      });
      
      $('#tableindex_filter input').unbind();
      $('#tableindex_filter input').bind('keyup', function(e) {
        if(e.keyCode == 13) {
          dataTable.search(this.value).draw();
        }
      });
    });
  </script> -->

  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.css">

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
        List Pelanggan <?= $row['referral'] ?>
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pelanggan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12">
          <!-- solid sales graph -->
          <div class="box box-primary">
            <!-- <div class="box-header">
              <i class="fa fa-th"></i>
              <h3 class="box-title">Pendaftaran</h3>
              <div class="box-tools">
                <a href="lokasi_add.php" class="btn btn-sm btn-primary">
                  <i class="fa fa-edit"></i> Tambah Lokasi
                </a>
              </div>
            </div> -->
            <div class="box-body border-radius-none">
              <div class="box-body">
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Kode </th>
                        <th>Nama </th>
                        <th>Aktif</th>
                        <th>Virtual Account (BRI)</th>
                        <th>Area</th>
                        <th>Alamat</th>
                        <th>Lokasi</th>
                        <th>Status </th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      while($row = $db->fetchArray($res)) {
                        $sqla = $db->fetchArray($db->query("select * from tb_lokasi where id_tb_lokasi = '$row[id_tb_lokasi]'"));	
                        $sqlb = $db->fetchArray($db->query("SELECT * FROM tb_aktivasi where id_tb_pendaftaran = '$row[id_tb_pendaftaran]' and st_aktivasi = '2'"));

                        $n++;
                    ?>
                        <tr>
                          <td><?=$n;?></td>
                          <td><?=$row['kode_daftar'];?></td>
                          <td><?=$row['nama'];?></td>
                          <td><?=tglindo(date_format($sqlb['tgl_aktivasi'], 'Y-m-d'));?></td>
                          <td>
                            <?php if (empty($row['va_bri']) || $row['va_bri'] == '') { ?>
                              <?php
                                $arr_level = array(1, 4);

                                if (in_array($_SESSION['level_user'], $arr_level)) {
                              ?>
                                <input type="text" name="va_bri" id="va_bri_<?= $row['kode_daftar']; ?>" token="<?= $kodeaman; ?>" placeholder="VA belum di set" <?= $row['st_layanan'] == '9' ? "disabled" : "" ?>>
                              <?php } else { ?>
                                <span class="text-danger" style="font-style:italic">Not Set</span>
                              <?php } ?>
                            <?php } else { ?>
                              <?= $row['va_bri'] ?>
                            <?php } ?>
                          </td>
                          <td><?=$sqla['nama_area'];?></td>
                          <td><?=$row['alamat'];?></td>
                          <td><?=$sqla['alamat_tiang'];?></td>
                          <td>
                            <?php
                              // $st_layanan[$row['st_layanan']];
                              if ($row['st_layanan'] == '8') {
                                echo '<i class="fa fa-circle text-success"></i> Active';
                              } elseif ($row['st_layanan'] == '9') {
                                echo '<i class="fa fa-circle text-danger"></i> Inactive';
                              } elseif ($row['st_layanan'] == '10') {
                                echo '<i class="fa fa-circle text-yellow"></i> Suspended';
                              } else {
                                $st_layanan[$row['st_layanan']];
                              }
                            ?>
                          </td>
                          <td>
                            <a class="btn btn-info btn-sm" href="pelanggan_dtl.php?id=<?=maxiline($row['id_tb_pendaftaran'], 'e');?>">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <?php 
                              $usrLevel = [1,2,3,4];
                              if(in_array($_SESSION['level_user'], $usrLevel)) { ?>
                            <?php
                              $paket_str_link = [10, 11, 12, 13];
                              $sqlq = "SELECT * FROM tb_paket WHERE id_tb_paket = '".$row['id_tb_paket']."'";
                              $queryq = $db->query($sqlq);
    
                              if (!in_array($row['id_tb_paket'], $paket_str_link)) { ?>

                                <?php
                                  // Get last jns_invoice
                                  $sqlr = "SELECT TOP(1) * FROM tr_invoice WHERE id_tb_pendaftaran = '".$row['id_tb_pendaftaran']."' ORDER BY id_tr_invoice DESC";
                                  $rowa = $db->fetchArray($db->query($sqlr));

                                  $jns_invoice = [1,2,3];
                                ?>

                                <?php if (($row['st_layanan'] == '9') || ($row['st_layanan'] == '10')) { ?>
                                  <a class="btn btn-success btn-sm actived-btn" data-id="<?= $row['id_tb_pendaftaran']; ?>" data-nama="<?= $row['nama']; ?>" data-aksi="a" data-kode="<?= $kodeaman; ?>" data-toogle="tooltip" title="Service Activation">
                                    <i class="fa fa-play" aria-hidden="true"></i>
                                  </a>
                                  &nbsp;
                                <?php } elseif ($row['st_layanan'] == '8' && in_array($rowa['jns_invoice'], $jns_invoice)) { ?>
                                  <a class="btn btn-warning btn-sm suspend-btn" data-id="<?= $row['id_tb_pendaftaran']; ?>" data-nama="<?= $row['nama']; ?>" data-aksi="s" data-kode="<?= $kodeaman; ?>" data-toogle="tooltip" title="Service Suspension">
                                    <i class="fa fa-pause" aria-hidden="true"></i>
                                  </a>
                                  &nbsp;
                                <?php } elseif ($row['st_layanan'] == '8' && !in_array($rowa['jns_invoice'], $jns_invoice)) { ?>
                                  <a class="btn btn-danger btn-sm disabled-btn" data-id="<?= $row['id_tb_pendaftaran']; ?>" data-nama="<?= $row['nama']; ?>" data-aksi="d" data-kode="<?= $kodeaman; ?>" data-toogle="tooltip" title="Service Deactivation">
                                    <i class="fa fa-stop" aria-hidden="true"></i>
                                  </a>
                                  &nbsp;
                                <?php } ?>

                              <?php } ?>
                            <?php } ?>
                          </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
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
  <!-- End #footer -->

</div>
<!-- ./wrapper -->

  <!-- Modal Default Size -->
	<div class="modal fade" id="vermodal1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content" id="data">
			
			</div>
		</div>
	</div>
	<!-- // Modal Default Size -->
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
  $(function () {
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
	  "columnDefs": [
		  { "searchable": false, "targets": [0,6,7] }  // Disable search on first and last columns
		]
    })
  })
</script>
<script>
  $(document).ready(function () {
    var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    if (window.location.hash && isChrome) {
        setTimeout(function () {
            var hash = window.location.hash;
            window.location.hash = "";
            window.location.hash = hash;
        }, 300);
    }
  });
  
  $('input[name="va_bri"]').on('keypress', function(e) {
    if (e.keyCode == 13) {  // Detect "Enter" key
      var token = $(this).attr('token');  // Get the ID from the input field
      var kode_daftar = $(this).attr('id').split('_')[2];  // Get the ID from the input field
      var va_bri = $(this).val();  // Get the value entered

      
      if (va_bri) {
        // Perform AJAX request
        $.ajax({
          type: 'POST',
          url: 'update_va_bri.php',
          dataType: 'json',
          data: { token: token, kode_daftar: kode_daftar, va_bri: va_bri },
          success: function(response) {
            if (response.status == "success") {
              Swal.fire({
                text: response.msg,
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                  location.reload();
                }
              });
            } else {
              Swal.fire({
                text: response.msg,
                icon: "error",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                  location.reload();
                }
              });
            }
          }
        });
      }
    }
  });

  $(".actived-btn").on( "click", function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    var nama = $(this).data('nama');
    var aksi = $(this).data('aksi');
    var kode = $(this).data('kode');

    Swal.fire({
      title: "Pengaktifan Layanan",
      text: "Apakah anda ingin mengaktifan kembali layanan internet pelanggan '" +nama+ "'?",
      icon: "question",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: "Ya, Aktifkan",
      denyButtonText: "Tidak"
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          url: 'pelanggan_services.php', // Replace with your PHP script URL for deactivation
          type: 'POST',
          dataType: 'json',
          data: {
            aksi: aksi,
            id: id,
            kode:kode
          },
          success: function(response) {
            Swal.fire({
              text: response.msg,
              icon: "success",
              confirmButtonText: "OK",
            }).then((result) => {
              location.reload();
            });
          },
          error: function(xhr, status, error) {
            // Handle error, e.g., show error message
            console.error(xhr.responseText); // Log error response for debugging
          }
        });
      } else if (result.isDenied) {
        location.reload();
      }
    });
  });

  $(".disabled-btn").on( "click", function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    var nama = $(this).data('nama');
    var aksi = $(this).data('aksi');
    var kode = $(this).data('kode');

    Swal.fire({
      title: "Penonaktifan Layanan Internet",
      text: "Apakah anda ingin menonaktifkan layanan internet customer '" +nama+ "'?",
      icon: "question",
      input:"textarea",
      inputLabel: "Masukkan Keterangan Penonaktifan",
      inputPlaceholder: "Masukkan Keterangan disini..",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: "Ya, Non-aktikan",
      denyButtonText: "Tidak"
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          url: 'pelanggan_services.php', // Replace with your PHP script URL for deactivation
          type: 'POST',
          dataType: 'json',
          data: {
            aksi: aksi,
            id: id,
            kode:kode,
            ket: result.value
          },
          success: function(response) {
            console.log(response);
            Swal.fire({
              text: response.msg,
              icon: "success",
              confirmButtonText: "OK",
            }).then(() => {
              location.reload();
            });
          },
          error: function(xhr, status, error) {
            // Handle error, e.g., show error message
            console.error(xhr.responseText); // Log error response for debugging
          }
        });
      } else if (result.isDenied) {
        // Swal.fire("Changes are not saved", "", "info");
        location.reload();
      }
    });
  } );


  $(".suspend-btn").on( "click", function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    var nama = $(this).data('nama');
    var aksi = $(this).data('aksi');
    var kode = $(this).data('kode');

    Swal.fire({
      title: "Penangguhan Layanan Internet",
      text: "Apakah anda ingin menangguhkan layanan internet customer '" +nama+ "'?",
      icon: "question",
      input:"textarea",
      inputLabel: "Masukkan Keterangan Penangguhan",
      inputPlaceholder: "Masukkan Keterangan disini..",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: "Ya, Suspend",
      denyButtonText: "Tidak"
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          url: 'pelanggan_services.php', // Replace with your PHP script URL for deactivation
          type: 'POST',
          dataType: 'json',
          data: {
            aksi: aksi,
            id: id,
            kode:kode,
            ket: result.value
          },
          success: function(response) {
            console.log(response);
            Swal.fire({
              text: response.msg,
              icon: "success",
              confirmButtonText: "OK",
            }).then(() => {
              location.reload();
            });
          },
          error: function(xhr, status, error) {
            // Handle error, e.g., show error message
            console.error(xhr.responseText); // Log error response for debugging
          }
        });
      } else if (result.isDenied) {
        // Swal.fire("Changes are not saved", "", "info");
        location.reload();
      }
    });
  } );
</script>
</body>
</html>

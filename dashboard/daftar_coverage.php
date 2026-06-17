<?php

@session_start();
//print_r($_SESSION);
include "include/config.php";
include "include/DbConnector.php";

$db = new DbConnector();

$sql = "select * from tb_lokasi";
$res = $db->query($sql);
?>
<style>
table.dataTable {
    font-size: 1em;
}
</style>
<div class="box-body border-radius-none">
	<div class="box-body">
		<div class="modal-header">
			<h4 class="modal-title" id="defaultModalLabel"><? if($_POST['id2']=='e') {echo "Edit Area Coverage";} else {echo "Tambah Area Coverage";}?></h4>
		</div>
		<div class="modal-body">
		<div class="box-body">
		<div class="table-responsive">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
			<tr>
			  <th></th>
			  <th>Nama </th>
			  <th>STO</th>
			  <th>Coverage Desa / Kelurahan</th>
			  <th>Kuota Tarikan </th>			  
			  <th>Keterangan </th>
			  <th><input type="checkbox" id="check-all-lokasi"></th>
			</tr>
			</thead> 
			<?
			while($row = $db->fetchArray($res)) {				
			$n++;?>	
			 <tr>
			  <td><?=$n;?></td>
			  <td><?=$row['nama_tiang'];?></td>
			  <td><?=$row['nama_area'];?></td>
			  <td>
			  <?
			  $desa = explode(',',$row['id_v_alamat']);
			  $alamate = array();
							 
			  foreach($desa as $ndesa){
			  $rowa = $db->fetchArray($db->query("select * from v_alamat where id_data_kd_pos ='$ndesa'"));				 
			  $alamate[] = $rowa['kelurahan_desa'].','.$rowa['kecamatan'].','.$rowa['kabupaten_kota'];
			  }
			  //print_r($alamate);
			   /*
			  
				// 1. Ambil baris pertama dan pecah untuk judul
				$first_parts = explode(',', $alamate[0]);
				$kecamatan = ucfirst(strtolower($first_parts[1]));
				$kota = ucfirst(strtolower($first_parts[2]));

				// 2. Tampilkan Judul
				echo "<strong>Kec. "."$kecamatan ($kota)</strong><br>";

				// 3. Loop untuk menampilkan setiap kelurahan
				foreach ($alamate as $alamat) {
					$parts = explode(',', $alamat);
					$deso = ucfirst(strtolower($parts[0]));
					echo $deso.", ";
				}
				*/					
				
				$grouped = [];

				// 1. Kelompokkan data ke dalam array multidimensi
				foreach ($alamate as $val) {
					$parts = explode(',', $val);
					$kelurahan = $parts[0];
					$kec_kota  = "Kec. ".ucfirst(strtolower($parts[1])) . " (" . ucfirst(strtolower($parts[2])) . ")"; // Contoh: TEMBALANG (SEMARANG)
					
					// Masukkan kelurahan ke dalam grup kecamatan yang sesuai
					$grouped[$kec_kota][] = $kelurahan;
				}

				// 2. Loop hasil pengelompokan untuk ditampilkan
				foreach ($grouped as $header => $list_kelurahan) {
					echo "<strong>$header</strong><br>"; // Cetak Nama Kecamatan (Kota)
					
					foreach ($list_kelurahan as $kel) {
						$kelu = ucfirst(strtolower($kel));
						echo $kelu . "<br>"; // Cetak Daftar Kelurahan di bawahnya
					}
					
					echo "<br>"; // Beri jarak antar kelompok (opsional)
				}
				?>
			  
			  </td>
			  <td><?=$row['kuota_tarikan'];?></td>			  
			  <td><?=$row['keterangan_tiang'];?></td>
			  <td>
			  <input type="checkbox" class="cb-lokasi" value="<?=$row['id_tb_lokasi'];?>">
			  </td>
			</tr>
			<?}?>
		</table>
		</div>
		</div>
		<div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="btn-simpan-coverage">Simpan</button>
	</div>
</div>	

 <!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })

  $(function() {
      var selectedLokasi = $('#id_tb_lokasi').val() ? $('#id_tb_lokasi').val().split(',') : [];

      function updateCheckboxes() {
          $('.cb-lokasi').each(function() {
              var val = $(this).val();
              if (selectedLokasi.indexOf(val) !== -1) {
                  $(this).prop('checked', true);
              } else {
                  $(this).prop('checked', false);
              }
          });
      }

      $('#example1').on('draw.dt', function() {
          updateCheckboxes();
      });

      // Initialize on first loads
      setTimeout(updateCheckboxes, 100);

      $('#check-all-lokasi').on('change', function() {
          var isChecked = $(this).is(':checked');
          $('.cb-lokasi').each(function() {
              $(this).prop('checked', isChecked);
              if (isChecked) {
                  if (selectedLokasi.indexOf($(this).val()) === -1) selectedLokasi.push($(this).val());
              } else {
                  var index = selectedLokasi.indexOf($(this).val());
                  if (index !== -1) selectedLokasi.splice(index, 1);
              }
          });
      });

      $(document).on('change', '.cb-lokasi', function() {
          var val = $(this).val();
          if ($(this).is(':checked')) {
              if (selectedLokasi.indexOf(val) === -1) {
                  selectedLokasi.push(val);
              }
          } else {
              var index = selectedLokasi.indexOf(val);
              if (index !== -1) {
                  selectedLokasi.splice(index, 1);
              }
          }
      });

      $('#btn-simpan-coverage').click(function() {
          var strLokasi = selectedLokasi.join(',');
          $('#id_tb_lokasi').val(strLokasi);
          $('.lokation').val(strLokasi);

          $.ajax({
              url: 'get_coverage_display.php',
              type: 'POST',
              data: { lokasi: strLokasi },
              success: function(resp) {
                  $('#coverage_display').html(resp);
                  $('#vermodal1').modal('hide');
              }
          });
      });
  });
</script>
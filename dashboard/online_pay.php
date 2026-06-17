<? 
$page= "bayarol";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

?>
	
<!--	<link rel="stylesheet" href="resources/dataTables.searchHighlight.css" type="text/css"/> 
	
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
<!--<script type="text/javascript" class="init">
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
		
//		dom: 'Blfrtip',		
		searchHighlight: true,		       
    });	
	
/* 	 dataTable.on('draw.dt', function () {
    var info = dataTable.page.info();
    dataTable.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1 + info.start;
		});
	}); */
	
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
</style> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!-- Load Jokul Checkout JS script -->
<script src="https://sandbox.doku.com/jokul-checkout-js/v1/jokul-checkout-1.0.0.js"></script>

<script src="https://cdn-doku.oss-ap-southeast-5.aliyuncs.com/doku-ui-framework/doku/js/jquery-3.3.1.min.js"></script>
<!-- Popper and Bootstrap JS -->
<script src="https://cdn-doku.oss-ap-southeast-5.aliyuncs.com/doku-ui-framework/doku/js/popper.min.js"></script>
<script src="https://cdn-doku.oss-ap-southeast-5.aliyuncs.com/doku-ui-framework/doku/js/bootstrap.min.js"></script>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
	  Invoice Belum Dibayar
    <!--    <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Invoice</li>
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
    <!--        <div class="box-header">
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
				  <th>Kode Invoice</th>
				  <th>Terbit</th>
				  <th>Jenis</th>				  
				  <th>Status</th>
				  <th></th>
                </tr>
                </thead> 
				<?				
				$sql = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$_SESSION[id_tb_user]'"));
				
				$sqla = "select * from tr_invoice where id_tb_pendaftaran = '$sql[id_tb_pendaftaran]' and sts_invoice = '2' and (sts_lunas = '1' or sts_lunas = 3) order by tgl_data desc";
				$resa = $db->query($sqla);
				
				$isikeranjang = array();
				
				while($rowa = $db->fetchArray($resa)) {
				$n++;?>	
				 <tr>
                  <td><?=$n;?></td>
                  <td><?=$rowa['no_invoice'];?></td>
				  <td><?=tglindo(date_format($rowa['tgl_invoice'], 'Y-m-d'));?></td>
				  <td><?=$jns_potongan[$rowa['jns_invoice']];?></td>				  
				  <td><?=$sts_lunas[$rowa['sts_lunas']];?></td>
				  <td>
				  <?
					$sqlb = "select top (1) * from tr_keranjang where id_tr_invoice = '$rowa[id_tr_invoice]' order by id_tr_keranjang desc ";
					$resb = $db->query($sqlb);
					$rowb = $db->fetchArray($resb);
					if(empty($rowb['id_tr_keranjang']))	{?>
					<a class="btn btn-info btn-sm" href="add_chart.php?id=<?=maxiline($rowa['id_tr_invoice'], 'e');?>&j=a" onclick="return confirm('Tambah ke pembayaran?')"><i class="fa fa-plus"></i></a>
					<?} else if($rowb['st_keranjang'] == 1) {?>
					<a class="btn btn-info btn-sm" href="add_chart.php?id=<?=maxiline($rowb['id_tr_keranjang'], 'e');?>&j=b" onclick="return confirm('Tambah ke pembayaran?')"><i class="fa fa-plus"></i></a>
					<?} else if($rowb['st_keranjang'] == 2) {?>
					<a class="btn btn-warning btn-sm" href="add_chart.php?id=<?=maxiline($rowb['id_tr_keranjang'], 'e');?>&j=c" onclick="return confirm('Hapus dari pembayaran?')"><i class="fa fa-minus"></i></a>
					<?} else if($rowb['st_keranjang'] == 3) {?>
					<a class="btn btn-success  btn-sm" href="#" disabled><i class="fa fa-minus"></i></a>
					<?}?>
				  </td>	
                </tr>
				<?}?>
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
	  <?
	  $sqld = "select tri.* from tr_keranjang trk left join tr_invoice tri on trk.id_tr_invoice = tri.id_tr_invoice where st_keranjang = '2' order by id_tr_invoice asc";
	  $query_jml = $db->queryNumRows($sqld);
	  $totalFiltered = $db->getNumRows($query_jml);
	  if(empty($totalFiltered)) { } else {
	  ?>
	  <div class="row">
        <!-- Left col -->     
		  <section class="col-lg-12">
          <!-- solid sales graph -->
          <div class="box box-primary">
    <!--        <div class="box-header">
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
				   <!-- Main content -->
					<div class="invoice p-3 mb-3" id="total">
					 <!-- Table row -->
					  <div class="row">
						<div class="col-12 table-responsive">
						  <table class="table table-striped">
							<thead>
							<tr>
							  <th>Qty</th>
							  <th>Kode Invoice</th>
							  <th>Terbit</th>
							  <th>Jenis</th>
							  <th>Subtotal</th>
							</tr>
							</thead>
							<tbody>
							<?							
							$resd = $db->query($sqld);
							while($rowd = $db->fetchArray($resd)) {
							$totalsemua +=	$rowd['tot_tagih'];
							?>
							<tr>
								<td>1</td>
								<td><?=$rowd['no_invoice'];?></td>
								<td><?=tglindo(date_format($rowd['tgl_invoice'], 'Y-m-d'));?></td>
								<td><?=$jns_potongan[$rowd['jns_invoice']];?></td>
								<td>Rp. <?=number_format($rowd['tot_tagih'],0,',','.').",00";?></td>
							</tr>
							<?}?>
							<tr>
							  <td style="border-top: 1px solid #bfbfbf; border-bottom: 1px solid #bfbfbf;"></td>
							  <td style="border-top: 1px solid #bfbfbf; border-bottom: 1px solid #bfbfbf;"></td>
							  <td style="border-top: 1px solid #bfbfbf; border-bottom: 1px solid #bfbfbf;"></td>
							  <td style="border-top: 1px solid #bfbfbf; border-bottom: 1px solid #bfbfbf;" align="right"><b>Total</b></td>
							  <td style="border-top: 1px solid #bfbfbf; border-bottom: 1px solid #bfbfbf;"><b>Rp. <?=number_format($totalsemua,0,',','.').",00";?></b></td>
							</tr>
							</tbody>
						  </table>
						</div>
						<!-- /.col -->
					  </div>
					  <!-- /.row -->

					  <div class="row">
						<!-- accepted payments column -->
						<div class="col-lg-12">
						  <p class="lead">Metode Pembayaran :</p>
							<img src="dist/img/credit/bca.png" alt="BCA">
							<img src="dist/img/credit/mandiri.png" alt="MANDIRI">
							<img src="dist/img/credit/bni.png" alt="BNI">
							<img src="dist/img/credit/bri.png" alt="BRI">
							<img src="dist/img/credit/atmbersama.png" alt="ATM Bersama">
							<img src="dist/img/credit/indomart.png" alt="Indomart">
							<img src="dist/img/credit/alphamart.png" alt="Alphamart">
							<img src="dist/img/credit/linkaja.png" alt="LinkAja">
							<img src="dist/img/credit/ovo.png" alt="ovo">
							<img src="dist/img/credit/shopee.png" alt="Shopee Pay">
							<img src="dist/img/credit/qris.png" alt="Qris GPN">

						  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
							Berikut pilihan metode bayar yang tersedia : virtual account, e-wallet, bayar melalui minimarket. Klik tombol bayar dibawah untuk melakukan pembayaran.
						  </p>
						</div>					
					  </div>
					  <!-- /.row -->
					  <br>
					  <!-- this row will not appear when printing -->
					  <div class="row no-print">					  
						<div class="col-12">
						
						<!-- form request -->
						<form id="formRequestData" novalidate>
						 
			<!--			<input type="number" id="amount" name="amount" value="120000">

						<input type="text" id="customerName" name="customerName" value="Anton Budiman" required>

						<input type="text" id="customerId" name="customerId" value="1234" required>
						
						<input type="text" id="kodePembayaran" name="kodePembayarn" value="1234" required>
					
						<input type="email" id="email" name="email" value="anton@budiman.com" required>	
					
						<input type="text" id="phoneNumber" name="phoneNumber" value="6281111111111" required>													
					
						<input type="text" id="address" name="address" value="Menara Mulia" required>
							   
						<input type="text" id="country" name="country" value="ID" required>
					
						<input type="text" id="province" name="province" value="Semarang" required>
						
						<input type="text" id="postalCode" name="postalCode" value="50266" required> -->
								   
						<button class="btn btn-success float-right"><i class="fa fa-credit-card"></i> Bayar</button> 
														
						</form>
						 
						 <!-- end form request -->
						</div>
					  </div>
					</div>
					<!-- /.invoice -->
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
	  <?}?>
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
  </footer><!-- End #footer -->

</div>
<!-- ./wrapper -->


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
	
	//Kirim ke doku
	<?
	$sa = $db->fetchArray($db->query("SELECT * from tb_pendaftaran where id_tb_pendaftaran = '$sql[id_tb_pendaftaran]'"));
	
	?>
	
	$("#formRequestData").submit(function (e) {        
        let indexed_array = {};    

        indexed_array['clientId'] = 'BRN-0210-1688980575897';
        indexed_array['sharedKey'] = 'SK-JewPFxGBEeTzO1BOBkDp';
        indexed_array['customerName'] = '<?=$sa['nama'];?>';
		indexed_array['customerId'] = '<?=$sa['id_tb_pendaftaran'];?>';
		indexed_array['invoiceCode'] = '<?=$sa['kode_daftar'];?>';
        indexed_array['email'] = '<?=str_replace(' ', '', $sa['email']);?>';
        indexed_array['phoneNumber'] = '<?=$sa['telp'];?>';
        indexed_array['address'] = '<?=$sa['alamat'];?>';
        indexed_array['country'] = 'ID';
        indexed_array['expiredTime'] = '1440';
        indexed_array['amount'] = '<?=$totalsemua;?>';

        $.ajax({
            type: "POST",
            dataType: "JSON",
            data: JSON.stringify(indexed_array),
            url: "jokul-checkout.php",
            contentType: "application/json",
            success: function (result) {
                loadJokulCheckout(result.response.payment.url);
            },
            error: function(xhr, textStatus, error){
                Swal.fire({
                    icon: 'error',
                    title: 'Order Failed',
                    confirmButtonText: 'Close',
                })
            }

        });
        e.preventDefault();
        return false;
    });
</script>
</body>
</html>

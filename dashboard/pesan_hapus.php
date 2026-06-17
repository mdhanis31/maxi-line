<? 
$page= "pesan";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

//paging
	if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
	} else {
	$page_no = 1;
	}
	
	$total_records_per_page = 30;
	$offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2"; 

	$result_count = $db->query("SELECT COUNT(DISTINCT codepesan) As total_records FROM v_pesan WHERE id_user_pengirim = '$_SESSION[id_tb_user]' and st_hapus_pengirim = '2' or id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '2' ");
	$total_records = $db->fetchArray($result_count);
	$total_records = $total_records['total_records'];
	$total_no_of_pages = ceil($total_records / $total_records_per_page);
	$second_last = $total_no_of_pages - 1; // total page minus 1

	$sql = "SELECT trp.* FROM tr_pesan trp INNER JOIN (SELECT codepesan, MAX(id_tr_pesan) AS id_tr_pesan FROM tr_pesan
    GROUP BY codepesan)a on trp.id_tr_pesan = a.id_tr_pesan WHERE id_user_pengirim = '$_SESSION[id_tb_user]' and st_hapus_pengirim = '2' or id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '2'  order by tgl_data desc OFFSET $offset ROWS FETCH NEXT $total_records_per_page ROWS ONLY";
	$res = $db->query($sql);
	$jml = $db->queryNumRows($sql);
	$total = $db->getNumRows($jml);
?>
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">	
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

input[type=checkbox] {
    margin: 4px 0 0 6px;
}
</style> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <h1>
        Pesan
        <small><?
		 $sql2 = $db->fetchArray($db->query("select count(id_tr_pesan) as jmlpesan from tr_pesan where st_baca = '1' and st_pesan = '1' and id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '1'"));					  
		  if($sql2['jmlpesan'] > 0){
		  echo "$sql2[jmlpesan] pesan baru";	  
		  } else {
		  echo "Tidak ada pesan baru";	  
		  }
		?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pesan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
           <a href="pesan_tulis.php" class="btn btn-primary btn-block margin-bottom">Tulis Pesan</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                 <li><a href="pesan_v.php"><i class="fa fa-inbox"></i> Inbox
				  <? $sql2 = $db->fetchArray($db->query("select count(id_tr_pesan) as jmlpesan from tr_pesan where st_baca = '1' and st_pesan = '1' and id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '1'"));					  
				  if($sql2['jmlpesan'] > 0){
				  ?>
                  <span class="label label-primary pull-right"><?=$sql2['jmlpesan'];?></span>
				  <?}?>
				  </a></li>
                <li><a href="pesan_sent.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="pesan_draft.php"><i class="fa fa-file-text-o"></i> Drafts</a></li>
				<li class="active"><a href="pesan_hapus.php"><i class="fa fa-trash-o"></i> Trash</a></li>              
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
   
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pesan Dihapus</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
				  <form action="search_pesan.php" method="post" enctype="multipart/form-data" name="form" id="search" role="form">
                  <input type="text" class="form-control input-sm" placeholder="Search Mail" name="kunci" required>
                  <span href="#" class="glyphicon glyphicon-search form-control-feedback" id="cari"></span>
				  </form>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                 <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>				
                <div class="btn-group">				
                 <button type="button" class="btn btn-default btn-sm tombolrestore" name="restore" ><i class="fa fa-history"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm" onclick='window.location.reload(true);'><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  <? echo ($offset+1)."-".($offset+$total)." / ".$total_records; ?>
                  <div class="btn-group">
                    <a class="btn btn-default btn-sm" <?php if($page_no <= 1){ echo "disabled"; }  if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>><i class="fa fa-chevron-left"></i></a>
                    <a class="btn btn-default btn-sm"  <?php if($page_no >= $total_no_of_pages){ echo "disabled"; } if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>><i class="fa fa-chevron-right"></i></a>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
               <table class="table table-hover table-striped">
                  <tbody>
				  <form action="pesan_.php" method="post" enctype="multipart/form-data" name="form" id="restore" role="form">
				  <input type="hidden" name="r" value="y" />
				  <? 
				  if(empty($total)){?>
				   <tr>
                   <td>Belum ada pesan dihapus</td>
				   </tr>  
				  <?} else {
					while($row = $db->fetchArray($res)) {					
					?>
                  <tr>
                    <td><input type="checkbox" name="restore[]" id="pilih" value="<?=$row['codepesan'];?>"><input type="hidden" name="codepesan[]" value="<?=$row['codepesan'];?>" /></td>
                    <td class="mailbox-name"><a href="pesan_baca.php?id=<?=maxiline($row['id_tr_pesan'], 'e');?>&p=h">
					<?
					if($row['id_user_tujuan'] == $_SESSION['id_tb_user']) {
					$sql1 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row[id_user_pengirim]'"));
					$namanya = "From : ".$sql1['nm_user'];
					} else if($row['id_user_pengirim'] == $_SESSION['id_tb_user']) {
					$sql1 = $db->query("select * from tr_pesan where codepesan = '$row[codepesan]'");	
					$tujuan = array();
					while($row1 = $db->fetchArray($sql1)) {
					$sql2 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row1[id_user_tujuan]'"));
					$tujuan[] = $sql2['nm_user'];
					}
					$namanya = "To : ".implode($tujuan, ', ');
					}	
					echo "$namanya";
					?>
					</a></td>
                    <td class="mailbox-subject"><b><a style="color:black;" href="pesan_baca.php?id=<?=maxiline($row['id_tr_pesan'], 'e');?>&p=h"><?=$row['subyek'];?></b> - <?=substr(strip_tags(htmlspecialchars_decode($row['pesan'])), 0, 65);;?>...</a>
                    </td>
					<td>
					<?
					if($row['id_user_tujuan'] == $_SESSION['id_tb_user']) {echo "Inbox";} else if($row['id_user_pengirim'] == $_SESSION['id_tb_user'] and $row['st_pesan'] == 2) {echo "Drafts";} else if($row['id_user_pengirim'] == $_SESSION['id_tb_user']) {echo "Sent";}
					?>
					</td>
                    <td class="mailbox-attachment">
					<?
					$sql9 = "SELECT * FROM tr_file_pesan where id_tr_pesan = '$row[id_tr_pesan]'";
					$jml9 = $db->queryNumRows($sql9);
					$total9 = $db->getNumRows($jml9);
					if(empty($total9)) {} else {
					?>
					<i class="fa fa-paperclip"><?}?></td>
                    <td class="mailbox-date">
					<?
					$datetime1 = $row['tgl_data'];
					$datetime2 = $now;

					$difference = $datetime1->diff($datetime2);
					$jeda = $difference->days;
					if($jeda <= 1){
					$beda = $difference->h;
					if($beda <= 1){
					echo $difference->i." Menit";} else 
					{echo $difference->h." Jam";}
					} else {
					echo tglindo(date_format($row['tgl_data'], 'Y-m-d'))." ".date_format($row['tgl_data'], 'H:i');
					}					
					?>					
					</td>
                  </tr>
				  <?}}?>
				  </form>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>				
                <div class="btn-group">
                   <button type="button" class="btn btn-default btn-sm tombolrestore" name="restore" ><i class="fa fa-history"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm" onclick='window.location.reload(true);'><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                   <? echo ($offset+1)."-".($offset+$total)." / ".$total_records; ?>
                  <div class="btn-group">
                    <a class="btn btn-default btn-sm" <?php if($page_no <= 1){ echo "disabled"; }  if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>><i class="fa fa-chevron-left"></i></a>
                    <a class="btn btn-default btn-sm"  <?php if($page_no >= $total_no_of_pages){ echo "disabled"; } if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>><i class="fa fa-chevron-right"></i></a>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
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
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });

 $('.tombolrestore').click(function(){	
	if (!confirm('Restore pesan?')) {
	event.preventDefault();} else {	
    $('#restore').submit();
   	}
});

$('.star_hapus').click(function(){
var formData = {
  'id': $(this).attr('value'),
  'b' : $(this).attr("id2")
};
console.log(formData);
$.ajax({
      type: 'POST',
      url: 'pesan_.php',
      data: formData,
      dataType: 'json',
      encode: true
  })
  .done(function(data) {
      console.log(data);
  })
});
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
</script>
</body>
</html>

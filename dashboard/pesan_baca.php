<? 
$page= "pesan";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

$id_tr_pesan = maxiline($_GET['id'], 'd');
if($_GET['p'] == "i"){
$sql1 = $db->fetchArray($db->query("update tr_pesan set st_baca = '2' where id_tr_pesan = '$id_tr_pesan'"));
}	  
$sql = "SELECT * FROM tr_pesan where id_tr_pesan = '$id_tr_pesan'";
$res = $db->query($sql);
$row = $db->fetchArray($res);
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
                <li <?if($_GET['p'] == "i") {echo "class=\"active\"";}?>><a href="pesan_v.php"><i class="fa fa-inbox"></i> Inbox
				  <? $sql2 = $db->fetchArray($db->query("select count(id_tr_pesan) as jmlpesan from tr_pesan where st_baca = '1' and st_pesan = '1' and id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '1'"));					  
				  if($sql2['jmlpesan'] > 0){
				  ?>
                  <span class="label label-primary pull-right"><?=$sql2['jmlpesan'];?></span>
				  <?}?>
				  </a></li>
                <li <?if($_GET['p'] == "s") {echo "class=\"active\"";}?>><a href="pesan_sent.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li <?if($_GET['p'] == "d") {echo "class=\"active\"";}?>><a href="pesan_draft.php"><i class="fa fa-file-text-o"></i> Drafts</a></li>
				<li <?if($_GET['p'] == "h") {echo "class=\"active\"";}?>><a href="pesan_hapus.php"><i class="fa fa-trash-o"></i> Trash</a></li> 
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
              <h3 class="box-title">Pesan <?if($_GET['p'] == "i") {echo "Masuk";} else if($_GET['p'] == "s") {echo "Dikirim";} else if($_GET['p'] == "d") {echo "Draft";} else if($_GET['p'] == "h") {echo "Dihapus";}?></h3>

              <div class="box-tools pull-right">
			  <?
			  if($_GET['p'] == "i") {
				$sqlb = "SELECT * FROM tr_pesan WHERE id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '1' and st_pesan = '1' order by tgl_data desc, st_bintang";  
			  } else if($_GET['p'] == "s") {
				$sqlb = "SELECT trp.* FROM tr_pesan trp INNER JOIN (SELECT codepesan, MAX(id_tr_pesan) AS id_tr_pesan FROM tr_pesan
				GROUP BY codepesan)a on trp.id_tr_pesan = a.id_tr_pesan where id_user_pengirim = '$_SESSION[id_tb_user]' and st_hapus_pengirim = '1' and st_pesan != '2' order by tgl_data desc";  
			  } else if($_GET['p'] == "d") {
				$sqlb = "SELECT trp.* FROM tr_pesan trp INNER JOIN (SELECT codepesan, MAX(id_tr_pesan) AS id_tr_pesan FROM tr_pesan
				GROUP BY codepesan)a on trp.id_tr_pesan = a.id_tr_pesan
				where id_user_pengirim = '$_SESSION[id_tb_user]' and st_pesan = '2' and st_hapus_pengirim = '1' order by tgl_data desc";  
			  } else if($_GET['p'] == "h") {
				$sqlb = "SELECT trp.* FROM tr_pesan trp INNER JOIN (SELECT codepesan, MAX(id_tr_pesan) AS id_tr_pesan FROM tr_pesan
				GROUP BY codepesan)a on trp.id_tr_pesan = a.id_tr_pesan
				whereid_user_pengirim = '$_SESSION[id_tb_user]' and st_hapus_pengirim = '2' or id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '2'  order by tgl_data desc";  
			  }
			  
			  if($_GET['p'] == "i") {
				$baca = "p=i";  
			  } else if($_GET['p'] == "s") {
				$baca = "p=s"; 
			  } else if($_GET['p'] == "d") {
				$baca = "p=d";   
			  } else if($_GET['p'] == "h") {
				$baca = "p=h"; 
			  }
			  
				$resb = $db->query($sqlb);
				$idnyo = array();
				while($rowb = $db->fetchArray($resb)){
				$idnyo[] = $rowb['id_tr_pesan'];	
				}
				
				$current = array_search($id_tr_pesan, $idnyo);
				$next = $idnyo[$current+1];
				$nextid = maxiline($next, 'e');
				$prev = $idnyo[$current-1];
				$previd = maxiline($prev, 'e');
							
			  ?>
			 	<a class="btn btn-box-tool" title="Previous" <?php if(empty($prev)){ echo "disabled"; } else {?>href="?id=<?=$previd;?>&<?=$baca;?>" <?}?>><i class="fa fa-chevron-left"></i></a>
				<a class="btn btn-box-tool" title="Next" <?php if(empty($next)){ echo "disabled"; } else {?>href="?id=<?=$nextid;?>&<?=$baca;?>" <?}?>><i class="fa fa-chevron-right"></i></a>
               
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">			
              <div class="mailbox-read-info">
                <h3><?=$row['subyek'];?></h3>
                <h5><?
				if($row['id_user_tujuan'] == $_SESSION['id_tb_user']) {
				$sql2 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row[id_user_pengirim]'"));
				$namanya = "From : ".$sql2['nm_user'];
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
                <span class="mailbox-read-time pull-right"><?=tglindo(date_format($row['tgl_data'], 'Y-m-d'));?> <?=date_format($row['tgl_data'], 'H:i');;?></span></h5>
              </div>         
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <?=htmlspecialchars_decode($row['pesan']);?>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <ul class="mailbox-attachments clearfix">
			  <?
				$sqla = "SELECT * FROM tr_file_pesan where id_tr_pesan = '$id_tr_pesan'";
				$resa = $db->query($sqla);
				while($rowa = $db->fetchArray($resa)){
			  ?>
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="<?echo "dist/file_pesan/".$rowa['link_file'];?>" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i><?=substr_replace($rowa['link_file'], '<br>', 22, 0);?></a>
                        <span class="mailbox-attachment-size">
                         <?
						 $besarfile = filesize("dist/file_pesan/".$rowa['link_file']);
						 echo round($besarfile/1024)." KB";
						 ?>
                          <a href="<?echo "dist/file_pesan/".$rowa['link_file'];?>" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
				<?}?>
              </ul>
            </div>
            <!-- /.box-footer -->
			<div class="box-footer">
			<?if($_GET['p'] == "h") {?>
			<form action="pesan_.php" method="post" enctype="multipart/form-data" name="form" id="restore" role="form">
			<input type="hidden" name="r" value="y" />
			<input type="hidden" name="restore[]" id="pilih" value="<?=$row['codepesan'];?>">
			<input type="hidden" name="codepesan[]" value="<?=$row['codepesan'];?>" />			
			<button type="submit" class="btn btn-default"><i class="fa fa-history"></i> Restore</button>
			</form>
			<?} else {?>           
              <div class="pull-right">
			  <?if($_GET['p'] == "s" or $_GET['p'] == "d") { } else {?>
                <a type="button" class="btn btn-default" href="pesan_tulis.php?id=<?=maxiline($row['id_tr_pesan'], 'e');?>&i=<?=maxiline("b", 'e');?>"><i class="fa fa-reply"></i> Balas</a>
			  <?} 
				if($_GET['p'] == "d") {?> 
				<a type="button" class="btn btn-default" href="pesan_tulis.php?id=<?=maxiline($row['id_tr_pesan'], 'e');?>&i=<?=maxiline("e", 'e');?>"><i class="fa fa-edit"></i> Edit</a>				
				<?} else {
				if($_GET['p'] == "s") {?>
                <a type="button" class="btn btn-default" href="pesan_tulis.php?id=<?=maxiline($row['id_tr_pesan'], 'e');?>&i=<?=maxiline("t", 'e');?>&p=<?=maxiline("s", 'e');?>"><i class="fa fa-share"></i> Teruskan</a>
				<?} else {?>
				 <a type="button" class="btn btn-default" href="pesan_tulis.php?id=<?=maxiline($row['id_tr_pesan'], 'e');?>&i=<?=maxiline("t", 'e');?>&p=<?=maxiline("i", 'e');?>"><i class="fa fa-share"></i> Teruskan</a>				
				<?}}?>
              </div>
				<form action="pesan_.php" method="post" enctype="multipart/form-data" name="form" id="hapus" role="form">
				<input type="hidden" name="pb" value="h" />
				<input type="hidden" name="id_tr_pesan" value="<?=$row['id_tr_pesan'];?>" />
				<?
				if($_GET['p'] == "i") {?>
				<input type="hidden" name="p" value="i" />
				<?} else if($_GET['p'] == "s") {?>
				<input type="hidden" name="p" value="s" />
				<?} else if($_GET['p'] == "d") {?>
				<input type="hidden" name="p" value="d" />
				<?}?>				
                <button type="submit" class="btn btn-default tombolhapus"><i class="fa fa-trash-o"></i> Hapus</button>
			    </form>           
			<?}?>
			 </div>
            <!-- /.box-footer -->
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
 <!--<script>
	$('.tombolhapus').click(function(){	
	if (!confirm('Hapus pesan?')) {
	event.preventDefault();} else {	
    $('#hapus').submit();
   	}
});
</script> -->
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

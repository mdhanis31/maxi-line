<? 
$page= "pesan";

include ("head.php"); 
include ("nav.php"); 
$now = new DateTime();
$th = $now->format("Y");
$tgl = $now->format("d-m-Y");
$waktu = $now->format("H:i:s");

$kodeaman = $_SESSION['token'];

if(!empty($_GET['id'])) {
$id_tr_pesan = maxiline($_GET['id'], 'd');
$sql = "SELECT * FROM tr_pesan where id_tr_pesan = '$id_tr_pesan'";
$res = $db->query($sql);
$row = $db->fetchArray($res);

if (empty($row['id_tr_pesan'])) {	
	?><script>
	alert('Pesan tidak ditemukan!');
	history.back();
	</script><?php
	exit();
	}
}

$balas = maxiline($_GET['i'], 'd');

?>
<style>
li.select2-selection__choice {
    background-color: #3c8dbc !important;
    border-color: #367fa9 !important;
}

.select2-selection__choice__remove:hover {
    color: #fb8484 !important;	
}

.select2-selection__choice__remove {
	color: #fff !important;
}

.select2-container .select2-selection--single {
    height: 32px !important;
}
</style>	
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
		 $sql2 = $db->fetchArray($db->query("select count(id_tr_pesan) as jmlpesan from tr_pesan where st_baca = '1' and id_user_tujuan = '$_SESSION[id_tb_user]' and st_hapus_tujuan = '1'"));					  
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
				<li><a href="pesan_hapus.php"><i class="fa fa-trash-o"></i> Trash</a></li> 
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
              <h3 class="box-title"><?if($balas == "e") {echo "Edit Draft";} else if($balas == "t") {echo "Teruskan Pesan";} else if($balas == "b") {echo "Balas Pesan";} else {echo "Buat Pesan Baru";}?></h3>
            </div>
            <!-- /.box-header -->
			<?
			if($balas == "e"){
			?>
			<form role="form" action="pesan_aksi.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="b" value="e" />
			<input type="hidden" name="codepesan" value="<?=$row['codepesan'];?>" />			
			<?} else if($balas == "t") {?>
			<form role="form" action="pesan_aksi.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="j" value="a" />	
			<?}	else {?>
			<form role="form" action="pesan_.php" method="post" enctype="multipart/form-data" name="form" id="formnya" role="form">
			<input type="hidden" name="j" value="a" />	
			<?}?>			
			<input type="hidden" name="id_user_pengirim" value="<?=$_SESSION['id_tb_user'];?>" />
			<input type="hidden" name="token" value="<?=$kodeaman?>" />					
            <div class="box-body">
              <div class="form-group">   
				<?
				if($_SESSION['level_user'] == 5){
				if($balas == "b") {
				$tujuan = $row['id_user_pengirim'];
				$sql2 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row[id_user_pengirim]'"));
				if(!empty($sql2['jabatan'])){
				$jabatan = $sql2['nm_user']." - ".$sql2['jabatan'];				
				} else {
				$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sql2[id_tb_pendaftaran]'"));	
				$jabatan = $sql2['nm_user']." - ".$sqlb['kode_daftar'];
				}} else if($balas == "e"){
				$tujuan = $row['id_user_tujuan'];
				$sql2 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row[id_user_tujuan]'"));
				if(!empty($sql2['jabatan'])){
				$jabatan = $sql2['nm_user']." - ".$sql2['jabatan'];
				} else {
				$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sql2[id_tb_pendaftaran]'"));	
				$jabatan = $sql2['nm_user']." - ".$sqlb['kode_daftar'];
				}	
				}?>
				<select class="form-control select2" id="user" name="id_user_tujuan[]" data-placeholder="To:" style="width: 100%;" required>
				<option value="<?=$tujuan;?>" selected>To : <?=$jabatan;?></option>
				</select>
				<?} else {?>
				<select class="form-control select2" id="id_tb_user" name="id_user_tujuan[]" multiple data-placeholder="To:" style="width: 100%;" required>
				<?
				if($balas == "e"){
				$sql1 = $db->query("select * from tr_pesan where codepesan = '$row[codepesan]'");	
				$idtujuan = array();
				$nmtujuan = array();
				while($row1 = $db->fetchArray($sql1)) {
				$sql2 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row1[id_user_tujuan]'"));
				if(!empty($sql2['jabatan'])){
				$jabatan = $sql2['nm_user']." - ".$sql2['jabatan'];
				} else {
				$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sql2[id_tb_pendaftaran]'"));	
				$jabatan = $sql2['nm_user']." - ".$sqlb['kode_daftar'];
				}
				$idtujuan[] = $sql2['id_tb_user'];
				$nmtujuan[] = $jabatan;
				}
				
				foreach ($idtujuan as $x=>$idnya) {
				$namanya = SafeSQL($nmtujuan[$x]);?>
				<option value="<?=$idnya;?>" selected>To : <?echo $namanya;?></option>
				<?}} else if($balas == "b") {
				
				$sql2 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row[id_user_pengirim]'"));
				if(!empty($sql2['jabatan'])){
				$jabatan = $sql2['nm_user']." - ".$sql2['jabatan'];
				} else {
				$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sql2[id_tb_pendaftaran]'"));	
				$jabatan = $sql2['nm_user']." - ".$sqlb['kode_daftar'];
				}?>
				<option value="<?=$row['id_user_pengirim'];?>" selected>To : <?echo $jabatan;?></option>
				<?}?>
				</select>
				<?}?>
              </div>
			  <?
			  if($balas == "t"){
				$sql1 = $db->query("select * from tr_pesan where codepesan = '$row[codepesan]'");	
				$idtujuan = array();
				$nmtujuan = array();
				while($row1 = $db->fetchArray($sql1)) {
				$sql2 = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$row1[id_user_tujuan]'"));
				if(!empty($sql2['jabatan'])){
				$jabatan = $sql2['nm_user']." - ".$sql2['jabatan'];
				} else {
				$sqlb = $db->fetchArray($db->query("select * from tb_pendaftaran where id_tb_pendaftaran = '$sql2[id_tb_pendaftaran]'"));	
				$jabatan = $sql2['nm_user']." - ".$sqlb['kode_daftar'];
				}
				$idtujuan[] = $sql2['id_tb_user'];
				$nmtujuan[] = $jabatan;
				}  
			  }
			  ?>
              <div class="form-group">
                <input class="form-control" name="subyek" required placeholder="Subject:" <?if($balas == "e"){?>value="<?=$row['subyek'];?>"<?} else if($balas == "t") {?>value="Fwd : <?=$row['subyek'];?>"<?} else if($balas == "b") {?>value="Re : <?=$row['subyek'];?>"<?}?>>
              </div>
              <div class="form-group">
                    <textarea id="compose-textarea" class="form-control" name="pesan" style="height: 300px" required>
                    <?if($balas == "t" ){echo "<p></p><br><br>";}?>
					<i>
					<?if($balas == "t" or $balas == "b"){echo "----------------------------------------------------------<br>";}?>
					<?if($balas == "t" and $_GET['p'] == "akwrbTFLTEdqNnM2dHZRZHBmemhPUT09"){?>To : <?echo implode($nmtujuan, ', ');?><br>Dikirim : <?=tglindo(date_format($row['tgl_data'], 'Y-m-d'))."<br>";}?>
					<?if($balas == "t" and $_GET['p'] == "WmFYV2kwUEt6T1JNb2pHQnJWemw4dz09"){?>To : <?echo $jabatan;?><br>Dikirim : <?=tglindo(date_format($row['tgl_data'], 'Y-m-d'))."<br>";}?>
					<?if($balas == "b"){?>From : <?echo $jabatan;?><br>Dikirim : <?=tglindo(date_format($row['tgl_data'], 'Y-m-d'))."<br>";}?>				
					<?if($balas == "t" or $balas == "b"){?>
					Subyek : <?echo htmlspecialchars_decode($row['subyek']);?><br><br>	
						<?echo htmlspecialchars_decode($row['pesan'])."<br>";}?>				
					</i>
					<?if($balas == "e"){?>
					<?echo htmlspecialchars_decode($row['pesan'])."<br>";}?>
					</textarea>
              </div>
              <div class="form-group">
                <div >
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" id="exampleInputFile" name="file_pesan[]" multiple>
                </div>
                <p class="help-block">Max. 5MB</p>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
			<?if($balas == "e" or $balas == "t"){?>           
              <ul class="mailbox-attachments clearfix">
			  <?
				$sqla = "SELECT * FROM tr_file_pesan where id_tr_pesan = '$id_tr_pesan'";
				$resa = $db->query($sqla);
				while($rowa = $db->fetchArray($resa)){
				if($balas == "t") {?>
				<input type="hidden" name="filegbr[]" value="<?=$rowa['link_file'];?>" />			
				<?}?>
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="<?echo "dist/file_pesan/".$rowa['link_file'];?>" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>
					<?=substr_replace($rowa['link_file'], '<br>', 22, 0);?>
					</a>
                        <span class="mailbox-attachment-size">
                         <?
						 $besarfile = filesize("dist/file_pesan/".$rowa['link_file']);
						 echo round($besarfile/1024)." KB";
						 ?>
                          <a href="<?echo "dist/file_pesan/".$rowa['link_file'];?>" target="_blank" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
						   <?if($balas == "t") { } else {?>
						  <a class="btn btn-default btn-xs pull-right" href="pesan_.php?id=<?=maxiline($row['codepesan'], 'e');?>&nmfile=<?=maxiline($rowa['link_file'], 'e');?>&i=<?=maxiline("e", 'e');?>&at=h"><i class="fa fa-trash-o"></i></a>
						   <?}?>
                        </span>						
                  </div>
                </li>
				<?}?>
              </ul>           
			<?}?>
              <div class="pull-right">
                <button type="submit" class="btn btn-default" name="draft" value="2"><i class="fa fa-pencil"></i> Draft</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Kirim</button>
              </div>
            </div>	
			</form>
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
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $('#compose-textarea').wysihtml5()
  })
</script>
<script>
	 $(document).ready(function(){
	  $("#id_tb_user").select2({
	  ajax: { 
	   url: "daftar_all_user.php?id=<?=maxiline($_SESSION['id_tb_user'], 'e');?>",
	   type: "post",
	   dataType: 'json',
	   delay: 250,
	   data: function (params) {
		return {
		  searchTerm: params.term // search term
		};
	   },
	   processResults: function (response) {
		 return {
			results: response
		 };
	   },
	   cache: true
	  }
	 });
	});
</script>
<script>
	 $(document).ready(function(){
	  $("#user").select2({
	  ajax: { 
	   url: "daftar_all_pegawai.php",
	   type: "post",
	   dataType: 'json',
	   delay: 250,
	   data: function (params) {
		return {
		  searchTerm: params.term // search term
		};
	   },
	   processResults: function (response) {
		 return {
			results: response
		 };
	   },
	   cache: true
	  }
	 });
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

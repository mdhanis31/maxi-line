
<?php
    @session_start();

    $userlvl = array(1,2,3);
    if(!in_array($_SESSION['level_user'], $userlvl)) {
?>
    <script>alert('Level anda tidak memiliki hak akses!')</script>;
    <script>document.location.href="index.php"</script>
<?php exit(); } ?>

<?php
$pages= "Pemasangan";

include ("partials/head.php"); 
include ("partials/nav.php");
include ("partials/breadcrumb.php");

$db = new DbConnector();

$now = new DateTime();
$waktu = $now->format("H:i:s");

$kodeaman = $_GET['token'];


if($_GET['s'] == "d" or $_GET['s'] == "e") {
    $id_tb_instalasi = maxiline(SafeSQL($_GET['id']), 'd');

    $sqla = "select * from tb_instalasi where id_tb_instalasi = '$id_tb_instalasi'";
	$qrya = $db->query($sqla);
	$rowa = $db->fetchArray($qrya);

	$id_tb_pendaftaran = $rowa['id_tb_pendaftaran'];
    $sql = "select * from tb_pendaftaran where id_tb_pendaftaran = '$id_tb_pendaftaran'";
	$qry = $db->query($sql);
	$row = $db->fetchArray($qry);

    $tgl_instalasi = date_format($rowa['tgl_instalasi'], 'Y-m-d');	
	$jam_instalasi = date_format($rowa['tgl_instalasi'], 'H:i:s');

} else {
    $id_tb_pendaftaran = maxiline($_GET['id'], 'd');

    $sql = "SELECT * FROM tb_pendaftaran WHERE id_tb_pendaftaran = $id_tb_pendaftaran";
    $qry = $db->query($sql);
    $row = $db->fetchArray($qry);
}

?>

<style>
    /* Latest compiled and minified CSS included as External Resource*/

    /* Optional theme */

    /*@import url('//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css');*/
    /* body {
        margin-top:30px;
    } */
    .stepwizard-step p {
        margin-top: 0px;
        color: #666;
    }
    .stepwizard-row {
        display: table-row;
    }
    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }
    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content:" ";
        width: 100%;
        height: 3px;
        background-color: #ccc;
        z-index: 0;
        transition: background 0.6s ease-in-out;
    }

    /* ===== PROGRESS GARIS ===== */
    .stepwizard-row.progress-1:before {
        background: linear-gradient(to right, #00a65a 33%, #ccc 33%);
    }
    .stepwizard-row.progress-2:before {
        background: linear-gradient(to right, #00a65a 66%, #ccc 66%);
    }
    .stepwizard-row.progress-3:before {
        /* background: linear-gradient(to right, #00a65a 75%, #ccc 75%); */
        background: #00a65a;
    }
    /* .stepwizard-row.progress-4:before {
        background: #00a65a;
    } */

    /* animasi glow */
    .stepwizard-row[class*="progress-"]:before {
        animation: stepLineGlow .8s ease;
    }
    @keyframes stepLineGlow {
        0%   { box-shadow: 0 0 0 rgba(0,166,90,0); }
        50%  { box-shadow: 0 0 8px rgba(0,166,90,.6); }
        100% { box-shadow: 0 0 0 rgba(0,166,90,0); }
    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    /* ===== BUTTON CIRCLE ===== */
    .btn-circle {
        width: 32px;
        height: 32px;
        padding: 6px 0;
        border-radius: 50%;
        background: #eee;
        border: 1px solid #ccc;
        color: #999;
        transition: all .3s ease;
    }

    /* aktif */
    .stepwizard-step.active .btn-circle {
        background: #00a65a;
        border-color: #00a65a;
        color: #fff;
        transform: scale(1.1);
    }

    /* selesai */
    .stepwizard-step.completed .btn-circle {
        background: #00a65a;
        border-color: #00a65a;
        color: #fff;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

    .paket-group {
        border: 2px solid transparent;   /* ukuran tetap */
        border-radius: 4px;
        cursor: pointer;
        transition: all .2s ease;
    }

    .paket-group:hover {
        border-color: #3c8dbc;
    }

    .paket-group.active {
        border-color: #3c8dbc;
    }

    .paket-wrapper.has-error .paket-group {
        border-color: #dd4b39;
    }

    .paket-group .form-control {
        cursor: pointer;
        background-color: #fff;
    }

    .paket-group input[type="radio"] {
        cursor: pointer;
    }

    .has-error .select2-selection {
        border-color: #dd4b39 !important;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-title">Form Pemasangan</div>
                </div>
                <div class="box-body">
                    <div class="stepwizard">
                        <div class="stepwizard-row setup-panel progress-1">
                            <!-- STEP 1 -->
                            <div class="stepwizard-step col-xs-4 active">
                                <a href="#step-1" class="btn btn-circle">1</a>
                                <p><small>Data Pelanggan</small></p>
                            </div>
                            <!-- STEP 2 -->
                            <div class="stepwizard-step col-xs-4">
                                <a href="#step-2" class="btn btn-circle disabled">2</a>
                                <p><small>Detail Layanan</small></p>
                            </div>
                            <!-- STEP 3 -->
                            <div class="stepwizard-step col-xs-4">
                                <a href="#step-3" class="btn btn-circle disabled">3</a>
                                <p><small>Petugas Pemasangan</small></p>
                            </div>
                
                            <!-- STEP 4 -->
                            <!-- <div class="stepwizard-step col-xs-3">
                                <a href="#step-4" class="btn btn-circle disabled">4</a>
                                <p><small>Petugas</small></p>
                            </div> -->
                        </div>
                    </div>
        
                    <form role="form" enctype="multipart/form-data" id="form-pemasangan">
                        <!-- STEP FORM 1 -->
                        <div class="panel panel-primary setup-content" id="step-1">
                            <div class="panel-heading">
                                <h3 class="panel-title">Data Pelanggan</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group" >
                                    <?php if($_GET['s'] == 'b') { ?>
                                        <input type="hidden" name="f" class="form-control" value="n" readonly />
                                        <input type="hidden" name="id_tb_pendaftaran" class="form-control" value="<?= $_GET['id'] ?>" readonly />
                                    <?php } elseif ($_GET['s'] == 'e') { ?>
                                        <input type="hidden" class="form-control" name="f" value="e" readonly />
                                        <input type="hidden" class="form-control" name="id_tb_instalasi" value="<?= $_GET['id'] ?>" readonly />
                                    <?php } ?>

                                    <input type="hidden" name="token" class="form-control" value="<?= $kodeaman; ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama Pelanggan</label>
                                    <input type="text" name="nama" id="nama" value="<?= $row['nama'] ?>" required="required" class="form-control" placeholder="Nama Pelanggan" readonly/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nomor ID Pelanggan / Nomor Registrasi</label>
                                    <input type="text" name="kode_daftar" id="kode_daftar" value="<?= $row['kode_daftar'] ?>" required="required" class="form-control" placeholder="Nomor ID Pelanggan / Nomor Registrasi" readonly/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nomor Telepon / HP (aktif)</label>
                                    <input type="text" name="telp" id="telp" value="<?= $row['telp'] ?>" required="required" class="form-control" placeholder="Nomor Telepon / HP (aktif)" readonly/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Email Pelanggan</label>
                                    <input type="text" name="email" id="email" value="<?= $row['email'] ?>" required="required" class="form-control" placeholder="Email Pelanggan" readonly/>
                                </div>
                                <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                            </div>
                        </div>
                        
                        <!-- STEP FORM 2 -->
                        <div class="panel panel-primary setup-content" id="step-2">
                            <div class="panel-heading">
                                <h3 class="panel-title">Detail Layanan Internet</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <?php
                                        $sqlb = "SELECT * FROM tb_paket WHERE id_tb_paket='$row[id_tb_paket]'";
                                        $qryb = $db->query($sqlb);
                                        $resb= $db->fetchAssoc($qryb);
                                    ?>
                                    <label class="control-label">Nama Paket</label>
                                    <input type="text" class="form-control" name="id_tb_paket" id="id_tb_paket" value="<?= $resb['id_tb_paket']; ?>" style="display:none" readonly>
                                    <input type="text" class="form-control" name="nama_paket" id="nama_paket" value="<?= $resb['nama_paket'] . " | Rp " . number_format($resb['harga_paket'],0,',','.') ; ?>" readonly />
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="control-label">Tanggal Pemasangan <?= $tgl_instalasi ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                                            <input type="text" name="tgl_pasang" id="tgl_pasang" value="<?= $tgl_instalasi ?>" required="required" class="form-control datepicker" placeholder="Tanggal Pemasangan" <?= $_GET['s'] == 'd' || $_GET['s'] == 'e' ? 'readonly' : '' ?> />
                                        </div>
                                    </div>
                                    <div class="bootstrap-timepicker col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Waktu Pemasangan</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div>
                                                <input type="text" name="waktu_pasang" id="waktu_pasang" value="<?= $jam_instalasi ?>" required="required" class="form-control timepicker" value="<?= $waktu ?>" placeholder="Waktu Pemasangan" <?= $_GET['s'] == 'd' ? 'readonly' : '' ?> />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                
                                <!-- <div class="form-group" id="paket-wrapper">
                                    <label class="control-label">Nama Paket</label>
                                    <div class="row">
                                        <
                                            $sqlk = "SELECT * FROM tb_paket WHERE paket_promo = '1' AND is_hidden = '1' ORDER BY harga_paket ASC";
                                            $resk = $db->query($sqlk);
                
                                            while($rowk = $db->fetchArray($resk)) {
                                        ?>
                                            <div class="col-md-4 col-sm-6" style="margin-bottom:10px;">
                                                <div class="input-group paket-group">
                                                    <span class="input-group-addon">
                                                        <input type="radio" class="paket-radio" name="id_tb_paket" id="id_tb_paket" value="<?= $rowk['id_tb_paket']; ?>" />
                                                    </span>
                                                    <input type="text" class="form-control" value="<?= $rowk['nama_paket']; ?>" readonly>
                                                </div>
                                            </div>
                                        < } ?>
                                    </div>
                                    <span class="help-block text-red paket-error" style="display:none">
                                        Silakan pilih salah satu paket terlebih dahulu
                                    </span>
                                </div> -->
                
                                <!-- <div class="row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="control-label">Tanggal Pengaktifan</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                                            <input type="text" name="tgl_aktifasi" id="tgl_aktifasi" class="form-control datepicker" placeholder="Tanggal Pengaktifan" />
                                        </div>
                                    </div>
                                </div> -->
                
                                <div id="perangkatin-wrapper">
                                    <label class="control-label">Perangkat indoor yang digunakan <?= $rowa['perangkat_in'] ?></label>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-12" style="margin-bottom:10px">
                                            <select class="form-control" name="perangkat_in" id="perangkat_in" required="required" <?= $_GET['s'] == 'd' ? 'disabled' : '' ?>>
                                                <option value="">-- Pilih Perangkat Indoor --</option>
                                                <?php foreach ($item_indoor as $value => $label) { ?>
                                                    <?php $selected = ($value === $rowa['perangkat_in']) ? 'selected' : ''; ?>
                                                    <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <input type="text" name="sn_in" id="sn_in" value="<?= $rowa['sn_in'] ?>" required="required" class="form-control" placeholder="Serial Number" <?= $_GET['s'] == 'd' ? 'readonly' : '' ?> />
                                        </div>
                                    </div>
                                </div>
                                <div id="perangkatout-wrapper">
                                    <label class="control-label">Perangkat outdoor yang digunakan</label>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-12" style="margin-bottom:10px">
                                            <select class="form-control" name="perangkat_out" id="perangkat_out" required="required" <?= $_GET['s'] == 'd' ? 'disabled' : '' ?>>
                                                <option value="">-- Pilih Perangkat Outdoor --</option>
                                                <?php foreach ($item_outdoor as $value => $label) { ?>
                                                    <?php $selected = ($value === $rowa['perangkat_out']) ? 'selected' : ''; ?>
                                                    <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <input type="text" name="sn_out" id="sn_out" value="<?= $rowa['sn_out'] ?>" required="required" class="form-control" placeholder="Serial Number" <?= $_GET['s'] == 'd' ? 'readonly' : '' ?> />
                                        </div>
                                    </div>
                                </div>
                                <div id="location-wrapper">
                                    <label class="control-label">Koordinat Lokasi</label>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group input-group" style="margin-bottom:10px">
                                                <div class="input-group-addon">Long</div>
                                                <input type="text" name="long" id="long" value="<?= $rowa['long'] ?>" required="required" class="form-control" placeholder="Longitude" <?= $_GET['s'] == 'd' ? 'readonly' : '' ?> />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group input-group" style="margin-bottom:10px">
                                                <div class="input-group-addon">Lat</div>
                                                <input type="text" name="lat" id="lat" value="<?= $rowa['lat'] ?>" required="required" class="form-control" placeholder="Latitude" <?= $_GET['s'] == 'd' ? 'readonly' : '' ?>  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                            </div>
                        </div>
                        
                        <!-- STEP FORM 3 -->
                        <div class="panel panel-primary setup-content" id="step-3">
                            <div class="panel-heading">
                                <h3 class="panel-title">Petugas Pemasangan</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group" id="petugas-wrapper">
                                    <label class="control-label">Pilih Petugas Pemasangan</label>
                                    <select class="form-control" name="id_tb_user[]" id="select-petugas-2" multiple="multiple" data-placeholder="Ketik nama user atau jabatan" <?= $_GET['s'] == 'd' ? 'disabled' : '' ?> >
                                        <?php $id_usere = explode(',',$rowa['id_tb_user']); ?>
                                        <?php foreach($id_usere as $idu) { ?>
                                            <?php $sqld = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$idu'"));	?>
                                            <option value="<?=$sqld['id_tb_user'];?>" selected><?=$sqld['nm_user'];?> <?if(empty($sqld['jabatan'])) {;} else {echo "-";}?> <?=$sqld['jabatan'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- Button notifikasi pelanggan -->
                                <?php if(!($_GET['s'] == "d" or $_GET['s'] == "e")) { ?>
                                    <div class="form-group">
                                        <label class="control-label"></label>
                                        <input type="checkbox" name="kirimnotif" value="1" style="margin-left: 0px;"><span> Kirim notifikasi ke pelanggan</span>
                                    </div>
                                <?php } ?>
                                
                                <!-- Button submit -->
                                <?php if(!($_GET['s'] == "d")) { ?>
                                    <!-- <button class="btn btn-primary nextBtn pull-right" type="button">Next</button> -->
                                    <button class="btn btn-success pull-right" type="submit"> <span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <!-- STEP FORM 4 -->
                        <!-- <div class="panel panel-primary setup-content" id="step-4">
                            <div class="panel-heading">
                                <h3 class="panel-title">Petugas Pemasangan</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">Company Name</label>
                                    <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Company Name" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Company Address</label>
                                    <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Company Address" />
                                </div>
                                <button class="btn btn-success pull-right" type="submit">Finish!</button>
                            </div>
                        </div> -->
                    </form>
                </div>
                <div class="box-footer"></div>
            </div>
        </div>

        <!-- Tambah Laporan -->
        <?php if($_GET['s'] == "d" or $_GET['s'] == "e") { ?>
            <div class="col-lg-4 col-md-8 col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Laporan</h3>
                        <div class="box-tools">
                            <?php if ($row['sts_instalasi'] != 2) {?>
                                <a href="laporan_instalasi_add.php?id=<?=maxiline($id_tb_instalasi, 'e');?>&s=b&token=<?echo $kodeaman;?>" class="btn btn-sm btn-primary" style="margin-top:1px;"> 
                                    <i class="fa fa-plus-square"><span class="tombole"> Input Laporan</span> </i>
                                </a>
                            <?php } ?>				
                        </div>
                    </div>
                    <?php
                        $sqlx = "select * from tb_laporan where id_jns_laporan = '$id_tb_instalasi' and jns_laporan = '2'";
                        $resx = $db->query($sqlx);
                        $query_jmlx = $db->queryNumRows($sqlx);
                        $jmlhx = $db->getNumRows($query_jmlx);
                    ?>
                    <div class="box-body">
                        <?php if(empty($jmlhx)) { ?>
                            <div class="card-body p-0">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    <li class="item">Belum ada laporan</li>
                                </ul>
                            </div>
                        <?php } else {?>
                            <div class="card-body p-0">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    <?php while($rowx = $db->fetchArray($resx)) {
                                        $sqlz = $db->fetchArray($db->query("select * from tb_user where id_tb_user = '$rowx[id_tb_user]'"));	
                                    ?>
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="dist/img/icon.jpg" alt="Laporan Image" class="img-size-50">
                                            </div>
                                            <div class="product-info">
                                                <a href="laporan_instalasi_add.php?id=<?=maxiline($rowx['id_tb_laporan'], 'e');?>&s=e" class="product-title"><?=$sqlz['nm_user']?>
                                                <span class="badge badge-info float-right"><?=date_format($rowx['tgl_laporan'], 'd-m-Y');?></span></a>
                                                <span class="product-description">
                                                    <?=$rowx['judul_laporan'];?>
                                                </span>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>	
                    </div>
                </div>
            </div>
        <?php } ?>
        
        <!-- Kirim Tanggapan -->
        <?php if(!($_GET['s'] == 'b')) { ?>
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="box-title">
                            Tanggapan
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="callout callout-warning">
                           <h4><i class="fa fa-bullhorn"></i> PERINGATAN!</h4>
                            <p>Kirim tanggapan untuk dapat melanjutkan proses selanjutnya</p>
                        </div>

                        <form role="form" enctype="multipart/form-data" id="form-tanggapan">
                            <div class="form-group" style="" >
                                <input type="hidden" class="form-control" name="f" value="r" readonly />
                                <input type="hidden" name="token" class="form-control" value="<?= $kodeaman; ?>" readonly />
                                <input type="hidden" class="form-control" name="id_tb_instalasi" value="<?= $_GET['id'] ?>" readonly />
                                <input type="hidden" class="form-control" name="id_tb_pendaftaran" value="<?= maxiline($id_tb_pendaftaran, 'e') ?>" readonly />
                                
                            </div>
                            <div class="form-group" id="stinstalasi-wrapper">
                                <label class="control-label">Status Instalasi</label>
                                <select class="form-control" name="st_instalasi" id="st_instalasi" <?= $_GET['s'] == "d" ? 'disabled' : '' ?> >
                                    <option value="">-- Pilih Status Instalasi --</option>
                                    <?php foreach ($st_instal as $value => $label) { ?>
                                        <?php $selected = ($value === $rowa['st_instalasi']) ? 'selected' : ''; ?>
                                        <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <input type="text" class="form-control" name="st_instalasi" id="st_instalasi"  required="required" /> -->
                            </div>
                            <div class="form-group">
                                <label class="control-label">Keterangan</label>
                                <textarea type="textarea" class="form-control" name="ket_instalasi" id="ket_instalasi" cols="20" rows="5" placeholder="Keterangan Hasil Instalasi" <?= $_GET['s'] == 'd' ? 'readonly' : '' ?> ><?= !empty($rowa['ket_instalasi']) ? $rowa['ket_instalasi'] :  '-' ?></textarea>
                                <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                            </div>
                            
                            <?php if($_GET['s'] == "e") { ?>
                                <button class="btn btn-success pull-right" type="submit"><span class="glyphicon glyphicon-send"></span> Kirim Tanggapan</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
        
</section>

<?php include ("partials/footer.php"); ?>
<?php include ("partials/js.php"); ?>

<script>
    $(document).ready(function () {

        // =========================
        // GLOBAL SELECTOR
        // =========================
        var formPasang   = $('#form-pemasangan'),
            formTngpn    = $('#form-tanggapan'),
            navListItems = $('.setup-panel a'),
            allWells     = $('.setup-content'),
            allNextBtn   = $('.nextBtn'),
            paketRadio   = $('.paket-radio'),
            paketGroup   = $('.paket-group'),
            selectPtugas = $('#select-petugas-2'),
            selectStinstal = $('#st_instalasi');
            // paketWrapper = $('#paket-wrapper');

        // hide semua step
        allWells.hide();

        selectPtugas.select2({
            ajax: { 
                url: "daftar_user.php",
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

        function updateStep(stepIndex) {
            $('.stepwizard-step').removeClass('active completed');

            $('.stepwizard-step').each(function (i) {
                if (i < stepIndex) $(this).addClass('completed');
                if (i === stepIndex) $(this).addClass('active');
            });

            $('.stepwizard-row')
                .removeClass('progress-1 progress-2 progress-3')
                .addClass('progress-' + (stepIndex + 1));
        }

        // =========================
        // NAV CLICK
        // =========================
        navListItems.click(function (e) {
            e.preventDefault();

            var $target = $($(this).attr('href')),
                $item   = $(this),
                index   = navListItems.index(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-success').addClass('btn-default');
                $item.addClass('btn-success');
                allWells.hide();
                $target.show();
            }

            updateStep(index);
        });

        // =========================
        // NEXT BUTTON
        // =========================
        allNextBtn.click(function () {
            var curStep      = $(this).closest(".setup-content"),
                curStepId    = curStep.attr("id"),
                nextStepLink = $('.setup-panel a[href="#' + curStepId + '"]')
                                .parent().next().children("a"),
                isValid      = true;

            // reset error
            curStep.find(".form-group").removeClass("has-error");
            curStep.find(".help-block").hide();

            // =========================
            // VALIDASI STEP-2 (PAKET)
            // =========================
            // if (curStepId === "step-2") {
            //     if (paketWrapper.find('.paket-radio:checked').length === 0) {
            //         isValid = false;
            //         paketWrapper.addClass("has-error");
            //         paketWrapper.find(".paket-error").show();
            //     }
            // }

            // =========================
            // VALIDASI INPUT REQUIRED
            // =========================
            curStep.find("input[required], select[required]").each(function () {
                if (!this.value) {
                    isValid = false;
                    $(this).closest(".form-group").addClass("has-error");
                }
            });

            if (isValid) {
                nextStepLink.removeClass('disabled').trigger('click');
            }
        });

        // =========================
        // RADIO CHANGE (HIGHLIGHT + CLEAR ERROR)
        // =========================
        // paketRadio.on('change', function () {

        //     // reset semua
        //     paketGroup.removeClass('active');

        //     // aktifkan yg dipilih
        //     $(this).closest('.paket-group').addClass('active');

        //     // clear error
        //     paketWrapper.removeClass('has-error');
        //     paketWrapper.find('.paket-error').hide();
        // });

        // init step pertama
        $('#step-1').show();
        updateStep(0);

        $('.setup-panel a.btn-success').trigger('click');

        // Datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',   // format tanggal
            autoclose: true,        // otomatis menutup
            todayHighlight: true,   // highlight hari ini
            // startDate: new Date()   // disable tanggal sebelum hari ini
        });

        // Timepicker
        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false     
        })

        // Simpan Ajax
        formPasang.on('submit', function (e) {
            e.preventDefault();

            var form = formPasang[0],
                formData = new FormData(form),
                petugas  = selectPtugas.val();

            $("#petugas-wrapper").removeClass("has-error");

            if(petugas === null || petugas.length === 0) {
                $("#petugas-wrapper").closest(".form-group").addClass("has-error");

                $('.setup-panel a[href="#step-3"]')
                    .removeClass("disabled")
                    .trigger("click");

                return false;
            }

            Swal.fire({
                title: "Apakah anda ingin menyimpan data?",
                icon: "question",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Ya, Simpan data!",
                denyButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'instalasi_aksi.php',
                        type: 'POST',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(res) {
                            // console.log(res);

                            Swal.fire({
                                text: res.message,
                                icon: res.status ? "success" : "error",
                                confirmButtonText: "OK",
                            }).then((result) => {
                                if(res.status && res.redirect) {
                                    window.location.href = res.redirect
                                } else {
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });

        // Kirim Tanggapan
        formTngpn.on('submit', function (e) {
            e.preventDefault();

            var form = formTngpn[0],
                formData = new FormData(form),
                st_instalasi = selectStinstal.val();

            $("#stinstalasi-wrapper").removeClass("has-error");

            if(st_instalasi === null || st_instalasi == '') {
                $("#stinstalasi-wrapper").closest(".form-group").addClass("has-error");
                return false;
            }

            $.ajax({
                url: 'instalasi_aksi.php',
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: formData,
                success: function(res) {
                    // console.log(res);

                    Swal.fire({
                        text: res.message,
                        icon: res.status ? "success" : "error",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if(res.status) {
                            window.location.href = res.redirect
                        } else {
                            location.reload();
                        }
                    });
                }
            });

        });

    });
</script>
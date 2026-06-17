<?php
@session_start();
include "dashboard/include/config.php";
include "dashboard/include/DbConnector.php";

if(maxiline($_POST['cari'],'d') == 'Y4') {
	
	$kecamatan = SafeSQL($_POST['kecamatan']);
	
	$db = new DbConnector();
    $sql = "SELECT * FROM tb_lokasi WHERE ',' + id_v_alamat + ',' LIKE '%,' + '$kecamatan' + ',%'";
	
	$query_jml = $db->queryNumRows($sql);
	$totaldata = $db->getNumRows($query_jml);
	
	if(empty($totaldata)) {
	echo "<div class='col-12 d-flex align-items-center justify-content-center'><img src=\"resources/images/icons/no_net.png\" style=\"width: 120px; height: auto; margin-right: 20px;\"><h4 style=\"color:white; margin:0;\">Mohon maaf layanan kami belum sampai ke lokasi anda.</h4></div>";	
	exit;
	}		
	
    $res = $db->query($sql);

    while($row = $db->fetchArray($res)) {
		
	$sqla = "SELECT * FROM tb_paket WHERE ',' + id_tb_lokasi + ',' LIKE '%,' + '$row[id_tb_lokasi]' + ',%' and is_hidden = '1' ORDER BY urutan ASC";
	$resa = $db->query($sqla);
	while($rowa = $db->fetchArray($resa)) {
    ?>
        <!-- Price Block -->
        <div class="price-block col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                <div class="upper-box" style="background-image: url(resources/images/background/pattern-4.png)">
                    <ul class="icon-list">
                        <li><span class="icon"><img src="resources/images/icons/service-1.svg" alt="" /></span></li>
                    </ul>
                    <?php if($rowa['id_tb_paket'] == 14) {?>
                        <h4><?= $rowa['nama_paket'] ?> <span style="text-decoration: line-through;color:red;font-size:0.9em;">Rp. <?= number_format($rowa['harga_paket'],0,',','.').",00"; ?></span></h4>	
                    <?php
                        $sqlb = "SELECT * FROM tb_paket WHERE id_paket_utama = '".$rowa['id_tb_paket']."' ";
                        $resb = $db->query($sqlb);
                        $rowb = $db->fetchArray($resb);
                    ?>
                        <h4 style="margin-top:5px;"><span>Rp. <?= number_format($rowb['harga_paket'],0,',','.').",00"; ?></span></h4>
                    <?php } else {?>
                        <h4><?= $rowa['nama_paket'] ?> <span>Rp. <?= number_format($rowa['harga_paket'],0,',','.').",00"; ?></span></h4>
                    <?php }?>                                
                </div>
                <div class="lower-box">
                    <ul class="price-list">
                        <?= html_entity_decode($rowa['isi_paket']) ?>
                    </ul>
                    <div class="button-box">
						<form method="post" action="dashboard/daftar.php" >
						<input type="hidden" name="cari" value="<?=maxiline('Y4', 'e');?>">
						<input type="hidden" name="id_data_kd_pos" value="<?=$kecamatan;?>">
						<input type="hidden" name="id_tb_paket" value="<?=$rowa['id_tb_paket'];?>">
						<input type="hidden" name="id_tb_lokasi" value="<?=$row['id_tb_lokasi'];?>">						
                        <button type="submit" name="daftar" class="theme-btn btn-style-four"><span class="txt">Get started</span></button>
						</form>
                    </div>
                </div>
            </div>
        </div>

    <?php }
	}
} else {
    echo "<div class='col-12 d-flex align-items-center justify-content-center'><img src=\"resources/images/icons/no_net.png\" style=\"width: 120px; height: auto; margin-right: 20px;\"><h4 style=\"color:white; margin:0;\">Mohon maaf layanan kami belum sampai ke lokasi anda.</h4></div>";	
}
?>

<?php
@session_start();
include "include/config.php";
include "include/DbConnector.php";

$db = new DbConnector();

$lokasi_str = $_POST['lokasi'];
if(empty($lokasi_str)) {
    echo "";
    exit;
}

$lokasi = explode(',', $lokasi_str);
foreach($lokasi as $nlokasi){
    $sqlb = "select * from tb_lokasi where id_tb_lokasi = '$nlokasi'";
    $resb = $db->query($sqlb);
    $rowb = $db->fetchArray($resb);
    
    if(!$rowb) continue;

    echo "<strong>$rowb[nama_tiang]</strong><br><strong>$rowb[nama_area]</strong><br>";
    
    $desa = explode(',',$rowb['id_v_alamat']);
    $alamate = array();
    foreach($desa as $ndesa){
        $res_desa = $db->query("select * from v_alamat where id_data_kd_pos ='$ndesa'");
        if ($res_desa) {
            $rowc = $db->fetchArray($res_desa);				 
            if ($rowc) {
                $alamate[] = $rowc['kelurahan_desa'].','.$rowc['kecamatan'].','.$rowc['kabupaten_kota'].','.$rowc['kd_pos'];
            }
        }
    }
    
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
    }
    ?>
    <div style="width:40%;">----------------------</div><br>
    <?php
}  
?>

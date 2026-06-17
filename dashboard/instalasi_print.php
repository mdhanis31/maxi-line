<?php
//@session_start();
include "include/config.php";
include "include/DbConnector.php";

//memanggil library mpdf
include("plugins/mpdf/autoload.php");

// echo json_encode($_POST);
$db = new DbConnector();

$id_tb_instalasi = maxiline(!empty($_POST['id']) ? $_POST['id'] : $_GET['id'], 'd');
$action = $_POST['action'];

$sql = "SELECT * FROM tb_instalasi WHERE id_tb_instalasi = '$id_tb_instalasi'";
$qryNum = $db->queryNumRows($sql);

if (isset($action) && $action == 'check') {

    if ($db->getNumRows($qryNum) > 0) {
        $arrMsg = [
            'status' => true
        ];
    } else {
        $arrMsg = [
            'status' => false,
            'message' => 'Data tidak ditemukan!'
        ];
    }
    
    echo json_encode($arrMsg);
    exit;
}

$sqla = "SELECT * FROM tb_instalasi ins 
    INNER JOIN tb_pendaftaran df ON df.id_tb_pendaftaran = ins.id_tb_pendaftaran
    LEFT JOIN tb_paket pkt ON pkt.id_tb_paket = df.id_tb_paket
    WHERE ins.id_tb_instalasi = $id_tb_instalasi";
$qrya = $db->query($sqla);
$resa = $db->fetchArray($qrya);

// Tanggal 

// Petugas
$arrPetugas = explode(',', $resa['id_tb_user']);
$n = 1;
$list_petugas = '';
foreach ($arrPetugas as $value) {
    $sqlb = $db->fetchArray($db->query("SELECT * from tb_user where id_tb_user = '$value'"));
    $list_petugas .= $n++ . '. ' . $sqlb['nm_user'] . '<br>';
}

$judul = 'form_pemasangan_' . $resa['kode_daftar'];

$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_top' => 40,
    'margin_bottom' => 10,
    'margin_left' => 15,
    'margin_right' => 12,
    'default_font_size' => '10',
    'default_font' => 'tahoma',
]);

// Define the Header/Footer before writing anything so they appear on the first page
$mpdf->SetHTMLHeader('
<table class="tabel-kop">
    <tr>
        <td style="width:50%">
            <img class="img-kop" src="dist/img/MaxiLineinvoice.png" alt="Logo Maxi-Line">
        </td>
        <td>
            <table class="tabel-desc">
                <tr>
                    <td>Form No</td>
                    <td>:</td>
                    <td>FRM-CUST-01-03</td>
                </tr>
                <tr>
                    <td>Nama Form</td>
                    <td>:</td>
                    <td>Pengaktifan Customer</td>
                </tr>
                <tr>
                    <td>Revisi</td>
                    <td>:</td>
                    <td>01</td>
                </tr>
                <tr>
                    <td>Tanggal Effective</td>
                    <td>:</td>
                    <td>'. date('d-m-Y') .'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><hr class="line-kop"></td>
    </tr>
</table>
');

$mpdf->SetHTMLFooter('
<table class="tabel-footer">
    <tr>
        <td width="33%">Lembar Putih : Admin</td>
        <td width="33%" align="center">Lembar Pink : Finance</td>
        <td width="33%" style="text-align: right;">Lembar Kuning : Customer</td>
    </tr>
</table>');

$stylesheet = file_get_contents('dist/css/form-pemasangan.css');

$html .= '<div class="judul-form">FORMULIR INSTALASI DAN AKTIFASI PELANGGAN M@XI-LINE.NET</div>';

$html .= '
<div class="section-title">A. Data Pelanggan</div>
<table class="tabel-content-1">
    <tr>
        <td>1.</td>
        <td class="tabel-header">Nama Pelanggan</td>
        <td>:</td>
        <td>'. $resa['nama'] .'</td>
    </tr>
    <tr>
        <td>2.</td>
        <td class="tabel-header">Nomor ID Pelanggan / Nomor Registrasi</td>
        <td>:</td>
        <td>'. $resa['kode_daftar'] .'</td>
    </tr>
    <tr>
        <td>3.</td>
        <td class="tabel-header">Alamat Pemasangan</td>
        <td>:</td>
        <td>'. $resa['alamat'] .'</td>
    </tr>
    <tr>
        <td>4.</td>
        <td class="tabel-header">Nomor Telepon / HP (aktif)</td>
        <td>:</td>
        <td>'. $resa['telp'] .'</td>
    </tr>
    <tr>
        <td>5.</td>
        <td class="tabel-header">Email Pelanggan</td>
        <td>:</td>
        <td>'. $resa['email'] .'</td>
    </tr>
</table>';

$html .= '
<div class="section-title">B. Detail Layanan Internet</div>
<table class="tabel-content-2">
    <tr>
        <td>1.</td>
        <td class="tabel-header">Nama Paket</td>
        <td>:</td>
        <td>'. $resa['nama_paket'] .'</td>
    </tr>
    <tr>
        <td>2.</td>
        <td class="tabel-header">Tanggal Pemasangan</td>
        <td>:</td>
        <td>'. tglindo(date_format($resa['tgl_instalasi'], 'Y-m-d')) .'</td>
    </tr>
    <tr>
        <td>3.</td>
        <td class="tabel-header">Tanggal Pengaktifan</td>
        <td>:</td>
        <td>..................................</td>
    </tr>
    <tr>
        <td>4.</td>
        <td class="tabel-header">Perangkat yang indoor digunakan</td>
        <td>:</td>
        <td>
            <table class="tabel-perangkat">
                <tr>
                    <td>Type</td>
                    <td>:</td>
                    <td>'. $resa['perangkat_in'] .'</td>
                </tr>
                <tr>
                    <td>SN</td>
                    <td>:</td>
                    <td>'. $resa['sn_in'] .'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>5.</td>
        <td class="tabel-header">Perangkat yang outdoor digunakan</td>
        <td>:</td>
        <td>
            <table class="tabel-perangkat">
                <tr>
                    <td>Type</td>
                    <td>:</td>
                    <td>'. $resa['perangkat_out'] .'</td>
                </tr>
                <tr>
                    <td>SN</td>
                    <td>:</td>
                    <td>'. $resa['sn_out'] .'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>6.</td>
        <td class="tabel-header">Titik Koordinat</td>
        <td>:</td>
        <td>'. $resa['long'] . ', ' . $resa['lat'] .'</td>
    </tr>
</table>';

$html .= '
<div class="section-title">C. Status Aktivasi</div>
<div class="checkbox-group">
    <span class="checkbox">&#9744;</span> Telah dilakukan aktivasi layanan<br>
    <span class="checkbox">&#9744;</span> Telah dilakukan uji koneksi<br>
    <span class="checkbox">&#9744;</span> Jaringan berjalan normal<br>
    <span class="checkbox">&#9744;</span> Perangkat telah diserahkan ke pelanggan
</div>';

$html .= '
<div class="section-title">D. Teknisi yang ditugaskan</div>
<div class="list-teknisi">'
    .$list_petugas.
'</div>';

$html .= '
<div class="section-title">E. Tanda Tangan dan Persetujuan</div>
<div class="persetujuan">
    Dengan ini saya menyatakan bahwa layanan internet telah diaktifkan, dan saya 
    telah menerima serta memahami informasi mengenai paket layanan, biaya berlangganan, 
    dan prosedur penggunaan.
</div>';

$html .='
<table class="tabel-content-3">
    <tr>
        <td>Pelanggan</td>
        <td>Teknisi</td>
    </tr>
    <tr>
        <td class="tanda-tangan"></td>
        <td class="tanda-tangan"></td>
    </tr>
    <tr>
        <td>'. $resa['nama'] .'</td>
        <td>(.....................................................)</td>
    </tr>
</table>';

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

$mpdf->Output($judul.".pdf" ,'I');
?>

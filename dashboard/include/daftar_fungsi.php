<?
function ftgl($tgl, $format_tgl) {
	if($tgl!=''){
		if ($format_tgl == ''){
			$result_tgl = date("Y-m-d H:i:s", strtotime($tgl));
		}else{
			$result_tgl = date($format_tgl, strtotime($tgl));
		}
	}else{
		$result_tgl='';
	}
    return $result_tgl;
}



function trim_value(&$value) {

    $value = trim($value);

}



/* Fungsi daftar_bulan() */



function daftar_bulan($id) {

    switch ($id) {

        case "01": $nama_bulan = "Januari";

            break;

        case "02": $nama_bulan = "Februari";

            break;

        case "03": $nama_bulan = "Maret";

            break;

        case "04": $nama_bulan = "April";

            break;

        case "05": $nama_bulan = "Mei";

            break;

        case "06": $nama_bulan = "Juni";

            break;

        case "07": $nama_bulan = "Juli";

            break;

        case "08": $nama_bulan = "Agustus";

            break;

        case "09": $nama_bulan = "September";

            break;

        case "10": $nama_bulan = "Oktober";

            break;

        case "11": $nama_bulan = "November";

            break;

        case "12": $nama_bulan = "Desember";

            break;

    }

    return $nama_bulan;

}



function daftar_bulan_sel($id) {

    switch ($id) {

        case "01": $sel = "selected='selected'";

            break;

        case "02": $sel = "selected='selected'";

            break;

        case "03": $sel = "selected='selected'";

            break;

        case "04": $sel = "selected='selected'";

            break;

        case "05": $sel = "selected='selected'";

            break;

        case "06": $sel = "selected='selected'";

            break;

        case "07": $sel = "selected='selected'";

            break;

        case "08": $sel = "selected='selected'";

            break;

        case "09": $sel = "selected='selected'";

            break;

        case "10": $sel = "selected='selected'";

            break;

        case "11": $sel = "selected='selected'";;

            break;

        case "12": $sel = "selected='selected'";

            break;

        default : $sel = "";

            break;

    }

    return $sel;

}



function daftar_bulan_new($id) {

    switch ($id) {

        case "1": $nama_bulan = "Januari";

            break;

        case "2": $nama_bulan = "Februari";

            break;

        case "3": $nama_bulan = "Maret";

            break;

        case "4": $nama_bulan = "April";

            break;

        case "5": $nama_bulan = "Mei";

            break;

        case "6": $nama_bulan = "Juni";

            break;

        case "7": $nama_bulan = "Juli";

            break;

        case "8": $nama_bulan = "Agustus";

            break;

        case "9": $nama_bulan = "September";

            break;

        case "10": $nama_bulan = "Oktober";

            break;

        case "11": $nama_bulan = "November";

            break;

        case "12": $nama_bulan = "Desember";

            break;

    }

    return $nama_bulan;

}


function bulan($id) {

    switch ($id) {

        case "1": $nama_bulan = "Jan";

            break;

        case "2": $nama_bulan = "Feb";

            break;

        case "3": $nama_bulan = "Mar";

            break;

        case "4": $nama_bulan = "Apr";

            break;

        case "5": $nama_bulan = "Mei";

            break;

        case "6": $nama_bulan = "Jun";

            break;

        case "7": $nama_bulan = "Jul";

            break;

        case "8": $nama_bulan = "Agu";

            break;

        case "9": $nama_bulan = "Sep";

            break;

        case "10": $nama_bulan = "Okt";

            break;

        case "11": $nama_bulan = "Nov";

            break;

        case "12": $nama_bulan = "Des";

            break;

    }

    return $nama_bulan;

}



function daftar_hari($id) {

    switch ($id) {

        case "0": $nama_bulan = "Minggu";

            break;

        case "1": $nama_bulan = "Senin";

            break;

        case "2": $nama_bulan = "Selasa";

            break;

        case "3": $nama_bulan = "Rabu";

            break;

        case "4": $nama_bulan = "Kamis";

            break;

        case "5": $nama_bulan = "Jum'at";

            break;

        case "6": $nama_bulan = "Sabtu";

            break;

    }

    return $nama_bulan;

}



/* Fungsi replace_char() */



function replace_char($str) {

    $hasil = str_replace(".", "", $str);

    return $hasil;

}



/* Fungsi input() */



function input($in) {

    if (get_magic_quotes_gpc(true)) {

        $input_data = $in;

    } else {

        $input_data = addslashes($in);

    }

    return $input_data;

}



/* Fungsi konversi_waktu */



function konversi_waktu($waktudb) {



    $hari = daftar_hari(date("w", @strtotime($waktudb)));

    $bulan = daftar_bulan(date("m", @strtotime($waktudb)));

    $tanggal = date("d ", @strtotime($waktudb));

    $tahun = date(" Y H:i:s", @strtotime($waktudb));

    $waktu = "$hari, $tanggal $bulan $tahun";

    return $waktu;

}



/* Fungsi time_complete */



function time_complete($waktudb) {

    $waktu = date('d-m-Y H:i:s', @strtotime($waktudb));

    return $waktu;

}



/* Fungsi addslashes_mssql */



function addslashes_mssql($str) {

    if (is_array($str)) {

        foreach ($str AS $id => $value) {

            $str[$id] = addslashes_mssql($value);

        }

    } else {

        $str = str_replace("'", "''", $str);

    }



    return $str;

}



/* Fungsi date_in */



function date_in($date) {

    $d = explode(' ', $date);

    $d2 = explode('-', $d[0]);

    $dt = $d2[2] . '/' . $d2[1] . '/' . $d2[0] . ' ' . $d[1];

    return $dt;

}



/* Fungsi array_sort */



function array_sort($array, $type = 'asc') {

    $result = array();

    foreach ($array as $var => $val) {

        $set = false;

        foreach ($result as $var2 => $val2) {

            if ($set == false) {

                if ($val > $val2 && $type == 'desc' || $val < $val2 && $type == 'asc') {

                    $temp = array();

                    foreach ($result as $var3 => $val3) {

                        if ($var3 == $var2)

                            $set = true;

                        if ($set) {

                            $temp[$var3] = $val3;

                            unset($result[$var3]);

                        }

                    }

                    $result[$var] = $val;

                    foreach ($temp as $var3 => $val3) {

                        $result[$var3] = $val3;

                    }

                }

            }

        }

        if (!$set) {

            $result[$var] = $val;

        }

    }

    return $result;

}



/**

 * Works for ordering by integers or strings, no need to specify which.

 *

 * Example:

 *

 * $array=array('a' => 50, 'b' => 25, 'c' => 75);

 * print_r(array_sort($array));

 *

 * Returns:

 * Array

 * (

 * [b] => 25

 * [a] => 50

 * [c] => 75

 * ) 

 */

function showMessageJS($str, $url) {

    $str = addslashes($str);

    echo "<script>";

    echo "alert('" . $str . "');";

    echo "location.href = '" . $url . "';";

    echo "</script>";

}



function debug($arrVar) {

    print "<pre>";

    print_r($arrVar);

    print "</pre>";

    return $arrVar;

}



function conv_quotes($teks) {

    return str_replace('"', '`', $teks);

}



function uang($duit) {

    if ($duit < 100000000) {

        $uang = $duit / 1000000;

        $nilai = number_format($uang, 2, ",", ".") . " Jt";

    }

    if ($duit > 100000000 && $duit < 100000000000) {

        $uang = $duit / 1000000000;

        $nilai = number_format($uang, 2, ",", ".") . " M";

    }

    if ($duit > 100000000000) {

        $uang = $duit / 1000000000000;

        $nilai = number_format($uang, 2, ",", ".") . " T";

    }



    return $nilai;

}



function angka($jml) {

    $jmle = number_format($jml, 0, ',', '.');

    return $jmle;

}



function tgl($date) {

    //$tgl = date("d M Y H:i:s",  date_create($date));

    $tgl = date_create($date);

    return date_format($tgl, "Y/m/d H:i:s");

    //return $tgl;

}



function tgldb($tgl) {

    $waktu = strtotime($tgl);

    $t = date("d",$waktu);

    $b = daftar_bulan_new(date("n",$waktu));

    $th = date("Y");

    $h = date("H:i", $waktu);

    $tgle = "$t $b $th $h";

    return $tgle;

}



function esc ($str) {

    return mysql_real_escape_string($str);

}



?>
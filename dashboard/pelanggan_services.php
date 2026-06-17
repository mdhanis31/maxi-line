<?php
@session_start();

include "include/config.php";
include "include/DbConnector.php";
require_once "include/MikrotikApi.php";

$db = new DbConnector();
$kodeaman = $_SESSION['token'];

if ($_POST['aksi'] == 'a') {
    unset($_SESSION['token']);
    session_write_close();

    if (!empty($kodeaman) && $_POST['kode'] == $kodeaman) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM tb_pendaftaran WHERE id_tb_pendaftaran='$id' AND st_layanan='10'";
        $res = $db->query($sql);
        $row = $db->fetchRow($res);

        if (!empty($row)) {
            $name = $row['kode_daftar'] . '@maxi-line.net';

            // With Class MikrotikApi
            try {
                $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
                $secret = $mikrotik->getSecret($name);
                
                if (!empty($secret)) {
                    if ($mikrotik->enableService($secret[0]['.id'])) {
                        $data = array(
                            'status' => false,
                            'msg' => "Layanan internet pelanggan '".$row['nama']."' gagal diaktifkan."
                        );
    
                        $result = json_encode($data, JSON_PRETTY_PRINT);
                    } else {
                        $id_daftar = SafeSQL($row['id_tb_pendaftaran']);
                        $sql = "UPDATE tb_pendaftaran SET st_layanan = '8' WHERE id_tb_pendaftaran = '$id_daftar'";
                        $res = $db->query($sql);
                            
                        if ($res) {
                            // kirim email.
                            $recipients = [$row['email']];
                            $cc = ['cs@maxi-line.net'];
                            $bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
                            $subject = "Pengaktifan Layanan Internet";
                            $mailContent = '
                                <html>
                                <head>
                                <title>MAXI-LINE - Pengaktifan Layanan Internet</title>
                                </head>
                                <body>
                                <p>Kepada Yth. Pelanggan MAXI-LINE</p>
                                <p>
                                    Kami menginformasikan bahwa layanan internet pelanggan "'.$row['nama'].'" telah aktif.
                                    Anda dapat menikmati kembali layanan internet MAXI-LINE seperti sedia kala.<br> Kami mohon maaf atas ketidaknyamanan yang Anda alami sebelumnya. <br><br>
                                    Jika anda memiliki kendala anda dapat melakukan Pelaporan/Open tiket melalui website kami <a href="https://www.maxi-line.net/dashboard/login.php">www.maxi-line.net</a> <br><br>
                                    Terima kasih telah menjadi keluarga besar MAXI-LINE.
                                </p>
                                <p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p>
                                </body>
                                </html>
                            ';

                            $sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);

                            if ($sendMail) {
                                $kode_daftar = SafeSQL($row['kode_daftar']);

                                if (isset($_SESSION['id_tb_user']) || !empty($_SESSION['id_tb_user'])) {
                                    $id_tb_user = $_SESSION['id_tb_user'];
                                } else {
                                    $id_tb_user = 'System';
                                }

                                $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

                                $sql = "INSERT INTO tb_log_services(kode_daftar, st_layanan, user_aksi, url_aksi) VALUES ('".$kode_daftar."', '8', '".$id_tb_user."','".$url."' )";
                                $result = $db->query($sql);
                                
                                if ($result) {
                                    $data = array(
                                        'status' => true,
                                        'msg' => "Email berhasil dikirim. Layanan internet pelanggan '".$row['nama']."' telah diaktifkan."
                                    );
                                } else {
                                    $data = array(
                                        'status' => false,
                                        'msg' => "Email berhasil dikirim. Log Pelanggan Services gagal di catat!"
                                    );
                                }
                            } else {
                                $data = array(
                                    'status' => false,
                                    'msg' => "Email gagal dikirim! Silahkan hubungi Administrator."
                                );
                            }
    
                            $result = json_encode($data, JSON_PRETTY_PRINT);
                        }
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'msg' => "Akun PPPOE pelanggan '".$row['nama']."' tidak ditemukan. Silahkan hubungi NOC/Administrator untuk mengaktifkan layanan."
                    );
    
                    $result = json_encode($response, JSON_PRETTY_PRINT);
                }
            } catch (Exception $e) {
                $response = array(
                    'status' => false,
                    'msg' => $e->getMessage()
                );

                $result = json_encode($response);
                die();
            }
        } else {
            $response = array(
                'status' => false,
                'msg' => 'Data pelanggan tidak ditemukan.'
            );

            $result = json_encode($response);
        }

        echo $result;
    } else {
        echo "<script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
        <script>history.back();</script>";
        exit();
    }
} elseif ($_POST['aksi'] == 'd') {
    unset($_SESSION['token']);
    session_write_close();

    if (!empty($kodeaman) && $_POST['kode'] == $kodeaman) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM tb_pendaftaran WHERE id_tb_pendaftaran='$id' AND st_layanan='8'";
        $res = $db->query($sql);
        $row = $db->fetchRow($res);
    
        if (!empty($row)) {
            $name = $row['kode_daftar'] . '@maxi-line.net';
    
            // With Class MikrotikApi
            try {
                $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
                $secret = $mikrotik->getSecret($name);
    
                if (!empty($secret)) {
                    if ($mikrotik->disableService($secret[0]['.id'])) {
                        $data = array(
                            'status' => false,
                            'msg' => "Layanan internet pelanggan '".$row['nama']."' gagal dinonaktifkan."
                        );
    
                        $result = json_encode($data, JSON_PRETTY_PRINT);
                    } else {
                        $id_daftar = SafeSQL($row['id_tb_pendaftaran']);
                        $sql = "UPDATE tb_pendaftaran SET st_layanan = '9' WHERE id_tb_pendaftaran = '$id_daftar'";
                        $res = $db->query($sql);
    
                        if ($res) {
                            $pppoe = $mikrotik->getPppoeIdByName($name);

                            if ($mikrotik->removePppoe($pppoe[0]['.id'])) {
                                $data = array(
                                    'status' => false,
                                    'msg' => "Layanan internet pelanggan '".$row['nama']."' gagal dinonaktifkan."
                                );

                                $result = json_encode($data, JSON_PRETTY_PRINT);
                            } else {
                                
                                // kirim email.
                                $recipients = [$row['email']];
                                $cc = ['cs@maxi-line.net'];
                                $bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
                                $subject = "Penonaktifan Layanan Internet";
                                $mailContent = '
                                    <html>
                                    <head>
                                    <title>MAXI-LINE - Penonaktifan Layanan Internet</title>
                                    </head>
                                    <body>
                                    <p>Kepada Yth. Pelanggan MAXI-LINE</p>
                                    <p>
                                        Kami dengan berat hati menginformasikan bahwa layanan internet pelanggan "'.$row['nama'].'" telah dinonaktifkan dikarenakan "'.$_POST['ket'].'". <br><br>
                                        Jika anda memiliki kendala anda dapat melakukan Pelaporan/Open tiket melalui website kami <a href="https://www.maxi-line.net/dashboard/login.php">www.maxi-line.net</a> <br><br>
                                        Terima kasih telah menjadi keluarga besar MAXI-LINE.
                                    </p>
                                    <p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p>
                                    </body>
                                    </html>
                                ';
    
                                $sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);
    
                                if ($sendMail) {
                                    $kode_daftar = SafeSQL($row['kode_daftar']);

                                    if (isset($_SESSION['id_tb_user']) || !empty($_SESSION['id_tb_user'])) {
                                        $id_tb_user = $_SESSION['id_tb_user'];
                                    } else {
                                        $id_tb_user = 'System';
                                    }
                                    
                                    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
                                    $sql = "INSERT INTO tb_log_services(kode_daftar, st_layanan, user_aksi, url_aksi) VALUES ('".$kode_daftar."', '9', '".$id_tb_user."','".$url."' )";
                                    $result = $db->query($sql);
                                    
                                    if ($result) {
                                        $data = array(
                                            'status' => true,
                                            'msg' => "Email berhasil dikirim. Layanan internet pelanggan '".$row['nama']."' telah dinonaktifkan."
                                        );
                                    } else {
                                        $data = array(
                                            'status' => false,
                                            'msg' => "Email berhasil dikirim. Log Pelanggan Services gagal di catat!"
                                        );
                                    }
                                } else {
                                    $data = array(
                                        'status' => false,
                                        'msg' => "Email gagal dikirim! Silahkan hubungi Administrator."
                                    );
                                }
            
                                $result = json_encode($data, JSON_PRETTY_PRINT);
                            }
                        }
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'msg' => "Akun PPPOE pelanggan '".$row['nama']."' tidak ditemukan. Silahkan hubungi NOC/Administrator untuk menonaktifkan layanan."
                    );
    
                    $result = json_encode($response, JSON_PRETTY_PRINT);
                }
                
            } catch (Exception $e) {
                $response = array(
                    'status' => false,
                    'msg' => $e->getMessage()
                );
    
                $result = json_encode($response);
                die();
            }
        } else {
            $response = array(
                'status' => false,
                'msg' => 'Data pelanggan tidak ditemukan.'
            );
    
            $result = json_encode($response);
        }
    
        echo $result;
    } else {
        echo "<script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
        <script>history.back();</script>";
        exit();
    }
} elseif ($_POST['aksi'] == 's') {
    unset($_SESSION['token']);
    session_write_close();

    if (!empty($kodeaman) && $_POST['kode'] == $kodeaman) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM tb_pendaftaran WHERE id_tb_pendaftaran='$id' AND st_layanan='8'";
        $res = $db->query($sql);
        $row = $db->fetchRow($res);
    
        if (!empty($row)) {
            $name = $row['kode_daftar'] . '@maxi-line.net';
    
            // With Class MikrotikApi
            try {
                $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
                $secret = $mikrotik->getSecret($name);
    
                if (!empty($secret)) {
                    if ($mikrotik->disableService($secret[0]['.id'])) {
                        $data = array(
                            'status' => false,
                            'msg' => "Layanan internet pelanggan '".$row['nama']."' gagal ditangguhkan."
                        );
    
                        $result = json_encode($data, JSON_PRETTY_PRINT);
                    } else {
                        $id_daftar = SafeSQL($row['id_tb_pendaftaran']);
                        $sql = "UPDATE tb_pendaftaran SET st_layanan = '10' WHERE id_tb_pendaftaran = '$id_daftar'";
                        $res = $db->query($sql);
    
                        if ($res) {
                            $pppoe = $mikrotik->getPppoeIdByName($name);

                            if ($mikrotik->removePppoe($pppoe[0]['.id'])) {
                                $data = array(
                                    'status' => false,
                                    'msg' => "Layanan internet pelanggan '".$row['nama']."' gagal ditangguhkan."
                                );

                                $result = json_encode($data, JSON_PRETTY_PRINT);
                            } else {
                                
                                // kirim email.
                                $recipients = [$row['email']];
                                $cc = ['cs@maxi-line.net'];
                                $bcc = ['muhammad_dhani@manunggalintegrasi.com', 'business.admin@manunggalintegrasi.com', 'sendayu@manunggalintegrasi.com'];
                                $subject = "Penangguhan Layanan Internet";
                                $mailContent = '
									<html>
									<head>
									<title>MAXI-LINE - Penangguhan Layanan Internet</title>
									</head>
									<body>
                                        <p>Yth. pelanggan a/n '.$row['nama'].', ID Pelanggan no. '.$row['kode_daftar'].' dengan berat hati kami menginformasikan bahwa layanan internet anda telah ditangguhkan dikarenakan <b>"'.$_POST['ket'].'"</b>.<br></p>
                                        <p>Jika anda memiliki kendala anda dapat melakukan Pelaporan/Open tiket melalui website kami <a href="https://www.maxi-line.net/dashboard/login.php">www.maxi-line.net</a>, terima kasih.</p><br>
                                        <p>Pesan ini dikirimkan secara otomatis oleh system, mohon untuk tidak membalas email ini.</p>
									</body>
									</html>
								';
    
                                $sendMail = sendToMails($recipients, $cc, $bcc, $subject, $mailContent);
    
                                if ($sendMail) {
                                    $kode_daftar = SafeSQL($row['kode_daftar']);

                                    if (isset($_SESSION['id_tb_user']) || !empty($_SESSION['id_tb_user'])) {
                                        $id_tb_user = $_SESSION['id_tb_user'];
                                    } else {
                                        $id_tb_user = 'System';
                                    }

                                    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

                                    $sql = "INSERT INTO tb_log_services(kode_daftar, st_layanan, user_aksi, url_aksi) VALUES ('".$kode_daftar."', '10', '".$id_tb_user."','".$url."' )";
                                    $result = $db->query($sql);
                                    
                                    if ($result) {
                                        $data = array(
                                            'status' => true,
                                            'msg' => "Email berhasil dikirim. Layanan internet pelanggan '".$row['nama']."' telah ditangguhkan."
                                        );
                                    } else {
                                        $data = array(
                                            'status' => false,
                                            'msg' => "Email berhasil dikirim. Log Pelanggan Services gagal di catat!"
                                        );
                                    }
                                } else {
                                    $data = array(
                                        'status' => false,
                                        'msg' => "Email gagal dikirim! Silahkan hubungi Administrator."
                                    );
                                }
            
                                $result = json_encode($data, JSON_PRETTY_PRINT);
                            }
                        }
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'msg' => "Akun PPPOE pelanggan '".$row['nama']."' tidak ditemukan. Silahkan hubungi NOC/Administrator untuk menangguhkan layanan."
                    );
    
                    $result = json_encode($response, JSON_PRETTY_PRINT);
                }
                
            } catch (Exception $e) {
                $response = array(
                    'status' => false,
                    'msg' => $e->getMessage()
                );
    
                $result = json_encode($response);
                die();
            }
        } else {
            $response = array(
                'status' => false,
                'msg' => 'Data pelanggan tidak ditemukan.'
            );
    
            $result = json_encode($response);
        }
    
        echo $result;
    } else {
        echo "<script>alert('Terdapat kesalahan, Re-fresh halaman & submit ulang');</script>
        <script>history.back();</script>";
        exit();
    }
}else {
    $response = array(
        'status' => false,
        'msg' => 'Aksi tidak ditemukan.'
    );

    $result = json_encode($response, JSON_PRETTY_PRINT);

    echo $result;
}
?>
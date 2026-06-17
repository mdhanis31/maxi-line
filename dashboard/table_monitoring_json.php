<?php
  include "include/config.php";
  require_once "include/MikrotikApi.php";
  include "include/DbConnector.php";


  $db = new DbConnector();

  $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
  $ppoeserver = $mikrotik->getAllPpoeserver();

  $data = [];

  $n = 1;
  foreach ($ppoeserver as $value) {
    $monitor = $mikrotik->monitorPpoein($value['.id']);
    // $id_pelanggan = str_replace("@maxi-line.net", "", $value['user']);

    $pppoe_user = $value['user'];
    $pecah = explode('@', $pppoe_user);
    $kode_daftar = $pecah[0];

    $sql =  "SELECT nama FROM tb_pendaftaran WHERE kode_daftar = '$kode_daftar'";
    $query = $db->query($sql);
    $row = $db->fetchArray($query);

    $data[] = [
        "no" => $n++,
        "user" => $row['nama'] . '<br>' . '('.$value['user'].')',
        "service" => $value['service'],
        "rx" => '<i class="fa fa-arrow-down" style="color:green" aria-hidden="true"></i> ' . convertBytes($monitor['rx']),
        "tx" => '<i class="fa fa-arrow-up" style="color:red" aria-hidden="true"></i> ' . convertBytes($monitor['tx']),
        "uptime" => '<i class="fa fa-clock-o" aria-hidden="true"></i> ' .$value['uptime'],
        "action" => '<a class="btn btn-sm btn-primary" href="detail_monitoring.php?id=' . maxiline($value['.id'], 'e') . '&code='.$kode_daftar.'"><i class="fa fa-eye" aria-hidden="true"></i></a>'
    ];
  }

  echo json_encode($data, JSON_PRETTY_PRINT);
?>
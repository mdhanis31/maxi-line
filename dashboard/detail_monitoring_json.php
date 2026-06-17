<?php
    include "include/config.php";
    require_once "include/MikrotikApi.php";

    $id = $_GET['id'];
    $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
    $monitor = $mikrotik->monitorPpoein($id);

    $dataPoints = array();

    $dataPoints[] = [
        'rx' => $monitor['rx'],
        'tx' => $monitor['tx']
    ];

    echo json_encode($dataPoints);
?>
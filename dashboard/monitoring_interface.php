<?php
    require_once "include/MikrotikApi.php";
    include "include/config.php"
?>

<div class="row">
    <?php
        $mikrotik = new MikrotikApi('160.20.79.226', 'administrator', 'KompasArah2022@');
        $result = $mikrotik->monitorInterfaces();
    ?>
    <div class="col-lg-4 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-blue">
                <i class="fa fa-cogs" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">
                    <p class="h4">CPU Traffic</p>
                </span>
                <span class="info-box-number">
                    <p class="font-weight-bold"><?= $result['cpu-load']; ?><small>%</small></p>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xs-12">
        <div class="info-box">
        <span class="info-box-icon bg-teal">
            <i class="fa fa-server" aria-hidden="true"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                <p class="h4">Free Memory</p>
            </span>
            <span class="info-box-number">
                <p class="font-weight-bold">
                    <?= convertBytes($result['free-memory']); ?>
                </p>
            </span>
        </div>
        </div>
    </div>
    <div class="col-lg-4 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-orange">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">
                    <p class="h4">Up Time</p>
                </span>
                <span class="info-box-number">
                    <p class="font-weight-bold"><?= $result['uptime']; ?></p>
                </span>
            </div>
        </div>
    </div>
</div>
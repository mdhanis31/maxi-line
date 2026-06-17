<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> 
        <?php 
            if($_GET['s']=='d') {
                echo "Detail Data";
            } else if($_GET['s']=='e') {
                echo "Revisi Data";
            } else {
                echo "Input Data";
            }
        ?>

        <!-- <small>Control panel</small> -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $pages ?></li>
        </ol>
    </section>
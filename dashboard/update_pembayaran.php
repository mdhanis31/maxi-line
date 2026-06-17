<?php
include ("include/config.php");
include ("include/DbConnector.php");
$db = new DbConnector();

$id = SafeSQL($_POST['id']);
$status = SafeSQL($_POST['status']);
$reason = SafeSQL($_POST['reason']);

if ($id && $status) {
    $sql = "UPDATE tr_confirm SET st_confirm = '$status', alasan_tolak = '$reason' WHERE id_tr_confirm = '$id'";
    $res = $db->query($sql);
    if ($res) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
}
?>

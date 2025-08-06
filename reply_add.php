<?php
  session_start();
   if (!isset($_SESSION['email'])) {

    header("Location: login.php");
    exit;
  }
$mid  = $_POST["mid"];
$replycontent =$_POST["replycontent"];
$replymemo  = $_POST["replymemo"];
$replyman  = $_POST["replyman"];
$memoman  = $_POST["memoman"];
$replydate = date("Y-m-d");

require_once("dbtools.inc.php");
$link = create_connection();
mysqli_select_db($link, "tw1_cowell_task");
$link->set_charset("utf8mb4");

$stmt = $link->prepare("
    INSERT INTO `reply_list` (mid,replydate, replycontent, replymemo, replyman, memoman)
    VALUES (?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
  die("Prepare 失敗: " . $link->error);
}

$stmt->bind_param("ssssss", $mid, $replydate, $replycontent, $replymemo, $replyman, $memoman );

if (!$stmt->execute()) {
  die('無法插入資料: ' . $stmt->error);
}

// 移除 echo 避免干擾 header()
$stmt->close();
$link->close();

header("Location: meet_enter.php?mid=$mid");
exit();
?>
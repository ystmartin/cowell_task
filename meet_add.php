<?php
$title  = $_POST["title"];
$content =$_POST["content"];
$memo  = $_POST["memo"];
$applyman  = $_POST["applyman"];
$meetdate = date("Y-m-d");

require_once("dbtools.inc.php");
$link = create_connection();
mysqli_select_db($link, "tw1_cowell_task");
$link->set_charset("utf8mb4");

$stmt = $link->prepare("
    INSERT INTO `meet_task` (meetdate, title, content, memo, applyman)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
  die("Prepare 失敗: " . $link->error);
}

$stmt->bind_param("sssss", $meetdate, $title, $content, $memo, $applyman );

if (!$stmt->execute()) {
  die('無法插入資料: ' . $stmt->error);
}

// 移除 echo 避免干擾 header()
$stmt->close();
$link->close();

header("Location: crmeet.php?meetdate=$meetdate");
exit();
?>
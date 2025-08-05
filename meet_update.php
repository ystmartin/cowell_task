<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$mid = $_GET['mid'];
echo 'mid:'.$mid."<br>";
$meetdate = $_POST["meetdate"];
echo 'meetdate:'.$meetdate."<br>";
$title = $_POST["title"];
echo 'title:'.$title."<br>";
$content = $_POST["content"];
echo 'content:'.$content."<br>";
$memo = $_POST["memo"];
echo 'mid:'.$mid."<br>";
$applyman = $_POST["applyman"];
echo 'mid:'.$mid."<br>";
$status = $_POST["status"];
echo 'status:'.$status."<br>";
$statusdate = date('Y-m-d');
echo 'date:'.$statusdate."<br>";

require_once("dbtools.inc.php");
$link = create_connection();
$sql = "SELECT * FROM meet_task where mid=$mid ";
$result = execute_sql($link, "tw1_cowell_task", $sql);
$row = mysqli_fetch_assoc($result);
// $meetdate = $row['meetdate'];
echo 'meetdate:'.$meetdate."<br>";

mysqli_select_db($link, "tw1_cowell_task");
$link->set_charset("utf8mb4");

// 準備 UPDATE 語句
$stmt = $link->prepare("
    UPDATE `meet_task`
    SET meetdate = ?, title = ?, content = ?, memo = ?, applyman = ?, status = ?, statusdate = ?
    WHERE mid = ?
");

if (!$stmt) {
  die("Prepare 失敗: " . $link->error);
}

// 正確綁定參數
$stmt->bind_param("ssssssss", $meetdate, $title, $content, $memo, $applyman, $status, $statusdate, $mid);

// 執行更新
if (!$stmt->execute()) {
  die('無法更新資料: ' . $stmt->error);
}

$stmt->close();
$link->close();

// 導向回原頁面
header("Location: crmeet.php?meetdate=$meetdate");
exit();

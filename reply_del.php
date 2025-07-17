<?php
$rid   = $_GET["rid"];

require_once("dbtools.inc.php");
$link = create_connection();

$sql = "SELECT * FROM reply_list where rid =$rid ";
$result = execute_sql($link, "tw1_cowell_task", $sql);
$row = $result->fetch_assoc();
$mid = $row['mid'];

$sql = "DELETE FROM reply_list where rid='$rid'";
$result = execute_sql($link, "tw1_cowell_task", $sql);

if (! $result) {
  die('無法插入資料: ' . mysqli_error($link));
}
echo "資料插入成功\n";
header("Location: meet_enter.php?mid=$mid");

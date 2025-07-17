<?php
$mid   = $_GET["mid"];

require_once("dbtools.inc.php");
$link = create_connection();

$sql = "SELECT * FROM meet_task where mid =$mid ";
$result = execute_sql($link, "tw1_cowell_task", $sql);
$row = $result->fetch_assoc();
$meetdate = $row['meetdate'];

$sql = "DELETE FROM meet_task where mid='$mid'";
$result = execute_sql($link, "tw1_cowell_task", $sql);

if (! $result) {
  die('無法插入資料: ' . mysqli_error($link));
}
echo "資料插入成功\n";
header("Location: crmeet.php?meetdate=$meetdate");

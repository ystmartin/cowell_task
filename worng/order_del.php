<?php
$sn   = $_GET["sn"];

require_once("dbtools.inc.php");
$link = create_connection();
$sql = "DELETE FROM orders where sn='$sn'";
$result = execute_sql($link, "tw1_bluenewshop", $sql);

if (! $result) {
  die('無法插入資料: ' . mysqli_error($link));
}
echo "資料插入成功\n";
header("Location: index.php");

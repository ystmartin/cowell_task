<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$itemname  = $_POST["itemname"];
$unitprice = (float) $_POST["unitprice"];
$itemmemo  = $_POST["itemmemo"];

require_once("dbtools.inc.php");
$link = create_connection();
mysqli_select_db($link, "tw1_bluenewshop");
$link->set_charset("utf8mb4");

$stmt = $link->prepare("
    INSERT INTO `goods` (itemname, unitprice, itemmemo)
    VALUES (?, ?, ?)
");

if (!$stmt) {
  die("Prepare 失敗: " . $link->error);
}

$stmt->bind_param("sds", $itemname, $unitprice, $itemmemo);

if (!$stmt->execute()) {
  die('無法插入資料: ' . $stmt->error);
}

// 移除 echo 避免干擾 header()
$stmt->close();
$link->close();

header("Location: index.php");
exit();
?>
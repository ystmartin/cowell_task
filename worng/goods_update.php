<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
</head>

<body>

  <?php

  $id    =   $_GET["id"];
  $itemname    =   $_POST["itemname"];
  $unitprice    =   $_POST["unitprice"];
  $itemmemo    =   $_POST["itemmemo"];

  date_default_timezone_set("Asia/Shanghai");
  require_once("dbtools.inc.php");
  $link = create_connection();
  $sql = "UPDATE goods SET itemname='$itemname',unitprice='$unitprice',itemmemo='$itemmemo'  where id='$id' ";
  $result = execute_sql($link, "tw1_bluenewshop", $sql);
  echo $result;

  if (! $result) {
    die('無法更新資料: ' . mysqli_error($link));
  }
  echo "資料更新成功\n";


  mysqli_free_result($result);
  mysqli_close($link);


  header("Location: index.php");


  ?>


</body>

</html>
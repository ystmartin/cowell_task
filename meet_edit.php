<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>科威會議</title>
  <!-- 引入 Bootstrap CSS -->
  <link rel="shortcut icon" href="favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
</head>

<body>

  <?php
  // $pass 	= $_COOKIE["pass"];
  //   $level 		= $_COOKIE["level"];

  //   if ($pass != "TRUE") {
  //       header("location:login.php");
  //       exit();
  //   }
  //   if ($level != "admin") {
  //       header("location:backend.php");
  //       exit();
  //   }

  $mid = $_GET["mid"];
  require_once("dbtools.inc.php");
  date_default_timezone_set("Asia/Shanghai");
  ?>

    <div class="text-center">
      <div>
        <H1>會議清單</H1>
      </div>
    </div>
    <?php
    require_once("dbtools.inc.php");
    date_default_timezone_set("Asia/Shanghai");
    $ysman   = $_COOKIE["ysman"];
    $cowellman  = $_COOKIE["cowellman"];
    ?>
    <table class="table container">
      <tr>
        <!-- <td>會議日期:<?php echo $meetdate; ?></td> -->
        <td>永信代表:<?php echo $ysman; ?></td>
        <td>科威代表:<?php echo $cowellman; ?></td>
      </tr>
    </table>

    <?php
    $link = create_connection();
    $sql = "SELECT * FROM meet_task where mid='$mid' ";
    $result = execute_sql($link, "tw1_cowell_task", $sql);
    $row = mysqli_fetch_assoc($result);

    /*
    $username = $row['username'];
	$password = $row['password'];
	$level = $row['level'];
	$crdate = $row['crdate'];
	$memo = $row['memo'];


*/
    ?>

    <div class='input-form' style="text-align:center">
      <form name='form' method='post' action='meet_update.php?mid=<?php echo $mid?>'>
        <!-- <div class="row"> -->
        <div class="col">
          <label for="title" class="title" style="width: 200px;height: 30px;">標題</label>
          <input type='text' name='title' placeholder="標題" style="width: 600px;height: 30px;"
            value=<?php echo  $row['title'] ?>>
        </div>
        <div class="col">
          <label for="content" class="content" style="width: 200px;height: 30px;">內容</label>
          <input type='text' name='content' placeholder="內容" style="width: 600px;height: 30px;"
            value=<?php echo  $row['content'] ?>>
        </div>
        <div class="col">
          <label for="applyman" class="applyman" style="width: 200px;height: 30px;">提出人</label>
          <input type='text' name='applyman' placeholder="提出人" style="width: 600px;height: 30px;"
            value=<?php echo  $row['applyman'] ?>>
        </div>
        <div class="col">
          <label for="memo" class="memo" style="width: 200px;height: 30px;">備註</label>
          <input type='text' name='memo' placeholder="備註" style="width: 600px;height: 30px;"
            value=<?php echo  $row['memo'] ?>>
        </div>
        <div class="col">
          <label for="status" class="status" style="width: 200px;height: 30px;">狀態</label>
          <select name="status" style="width: 600px; height: 30px;">
            <option value="">-- 請選擇狀態 --</option>
            <option value="提出需求" <?php if ($row['memo'] == '提出需求') echo 'selected'; ?>>提出需求</option>
            <option value="完成需求" <?php if ($row['memo'] == '完成需求') echo 'selected'; ?>>完成需求</option>
            <option value="評估中" <?php if ($row['memo'] == '評估中') echo 'selected'; ?>>評估中</option>
            <option value="無法處理" <?php if ($row['memo'] == '無法處理') echo 'selected'; ?>>無法處理</option>
            <option value="不要處理" <?php if ($row['memo'] == '不要處理') echo 'selected'; ?>>不要處理</option>
            <option value="取消需求" <?php if ($row['memo'] == '取消需求') echo 'selected'; ?>>取消需求</option>
            <option value="其他" <?php if ($row['memo'] == '其他') echo 'selected'; ?>>其他</option>
          </select>
        </div>
        <div class="col">
          <input class="input-submit" type="submit" value="儲存" />
        </div>
        <!-- </div> -->
      </form>
    </div>


  <?php

  mysqli_free_result($result);
  mysqli_close($link);
  ?>
</body>

</html>
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



  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>

<body>
  <div class="text-center">
    <div>
      <H1><a href="index.php">報表及搜尋</a></H1>
    </div>
    <div>
      <H2>狀態統計</H2>
    </div>
  </div>
  <?php
  require_once("dbtools.inc.php");
  date_default_timezone_set("Asia/Shanghai");
  $ysman   = $_COOKIE["ysman"];
  $cowellman  = $_COOKIE["cowellman"];
  $searchkey  = $_POST["search_key"];
  echo $searchkey;

  if (!$_GET['key']) {

    $key = '提出需求';
  } else {
    $key = $_GET['key'];
  }

  ?>
  <table class="table container">
    <thead>
      <tr>
        <th scope="col"><a href="report_list.php?key=提出需求">提出需求</a></th>
        <th scope="col"><a href="report_list.php?key=完成需求">完成需求</a></th>
        <th scope="col"><a href="report_list.php?key=待提需求">待提需求</a></th>
        <th scope="col"><a href="report_list.php?key=評估中">評估中</a></th>
        <th scope="col"><a href="report_list.php?key=無法處理">無法處理</a></th>
        <th scope="col"><a href="report_list.php?key=不要處理">不要處理</a></th>
        <th scope="col"><a href="report_list.php?key=取消需求">取消需求</a></th>
        <th scope="col"><a href="report_list.php?key=其他">其他</a></th>
      </tr>
    </thead>
    <tbody>
      <?php

      $array = array("提出需求", "完成需求", "待提需求", "評估中", "無法處理", "不要處理", "取消需求", "其他");
      echo "<tr>";
      $link = create_connection();
      for ($i = 0; $i <= 7; $i++) {

        $sql = "SELECT COUNT(*) AS count FROM meet_task where status = '$array[$i]'";
        $result = execute_sql($link, "tw1_cowell_task", $sql);
        $row = $result->fetch_assoc();
        echo "<td>" . $row['count'] . "</td>";
      };
      echo "</tr>";
      ?>


    </tbody>

  </table>


  <div class="container text-center" style="max-width: 500px;">
    <form name='form' method='post' action='report_list.php'>
      <div class="row g-2 align-items-end justify-content-center">
        <div class="col-auto">
          <label for="search_key" class="form-label">關鍵字</label>
          <input type="text" name="search_key" class="form-control" placeholder="關鍵字">
        </div>
        <div class="col-auto">
          <input type="submit" class="btn btn-primary" value="送出">
        </div>
      </div>
    </form>
  </div>


  <table class="table">
    <thead>
      <tr>
        <th scope="col">編號</th>
        <th scope="col">日期</th>
        <th scope="col">標題</th>
        <th scope="col">內容</th>
        <th scope="col">提出人</th>
        <th scope="col">狀態</th>
        <th scope="col">編輯</th>
        <th scope="col">刪除</th>
        <th scope="col">跟進</th>
        <th scope="col">跟進數</th>
      </tr>
    </thead>
    <tbody>
      <?php

      $link = create_connection();
      if (!$key) {
        $sql = "SELECT * FROM meet_task where status = '提出需求' order by mid DESC";
      } else {
        $sql = "SELECT * FROM meet_task where status = '$key' order by mid DESC";
      }
      if ($searchkey) {
       $sql = "SELECT * FROM meet_task WHERE title LIKE '%$searchkey%' ORDER BY mid DESC";

        echo $sql;
      } 
      $result = execute_sql($link, "tw1_cowell_task", $sql);
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<th>" . $row['mid'] . "</th>";
        echo "<td>" . $row['meetdate'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['content'] . "</td>";
        echo "<td>" . $row['applyman'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td><a class='btn btn-primary' href='meet_edit.php?mid=" . $row['mid'] . "' role='button'>編輯</a></td>";
        echo "<td><a class='btn btn-danger' 
           href='meet_del.php?mid=" . $row['mid'] . "' 
           role='button' 
           onclick=\"return confirm('確定要刪除這筆資料嗎？');\">
           刪除
        </a></td>";
        echo "<td><a class='btn btn-success' href='meet_enter.php?mid=" . $row['mid'] . "' role='button'>跟進</a></td>";

        $sql2 = "SELECT COUNT(*) AS reply FROM reply_list where mid = '$row[mid]'";
        $result2 = execute_sql($link, "tw1_cowell_task", $sql2);
        $row2 = $result2->fetch_assoc();

        echo "<td>" . $row2['reply'] . "</td>";
        echo "<tr>";
      };

      ?>


    </tbody>


    <?php
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
</body>

</html>
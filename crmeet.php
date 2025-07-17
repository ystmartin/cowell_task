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
      <H1><a href="index.php">議題清單</a></H1>
    </div>
  </div>
  <?php
  require_once("dbtools.inc.php");
  date_default_timezone_set("Asia/Shanghai");
  $ysman   = $_COOKIE["ysman"];
  $cowellman  = $_COOKIE["cowellman"];
  $meetdate  = $_GET["meetdate"];
  ?>
  <table class="table">
    <tr>
      <td>會議日期:<?php echo $meetdate; ?></td>
      <td>永信代表:<?php echo $ysman; ?></td>
      <td>科威代表:<?php echo $cowellman; ?></td>
    </tr>
    <?php
    if (!$ysman) {
      $ysman = "洪馬丁";
    }
    if (!$cowellman) {
      $cowellman = "dily";
    }

    ?>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">編號</th>
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
        $sql = "SELECT * FROM meet_task where meetdate = '$meetdate' order by mid ASC";
        $result = execute_sql($link, "tw1_cowell_task", $sql);
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<th>" . $row['mid'] . "</th>";
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
    </table>
    <div class='input-form' style="text-align:center">
      <form name='form' method='post' action='meet_add.php?meetdate=$meetdate'>
        <!-- <div class="row"> -->
        <div class="col">
          <label for="title" class="title" style="width: 200px;height: 30px;">標題</label>
          <input type='text' name='title' placeholder="標題" style="width: 600px;height: 30px;">
        </div>
        <div class="col">
          <label for="content" class="content" style="width: 200px;height: 30px;">內容</label>
          <input type='text' name='content' placeholder="內容" style="width: 600px;height: 30px;">
        </div>
        <div class="col">
          <label for="applyman" class="applyman" style="width: 200px;height: 30px;">提出人</label>
          <input type='text' name='applyman' placeholder="提出人" style="width: 600px;height: 30px;">
        </div>
        <div class="col">
          <label for="memo" class="memo" style="width: 200px;height: 30px;">備註</label>
          <input type='text' name='memo' placeholder="備註" style="width: 600px;height: 30px;">
        </div>
        <div class="col">
          <input class="input-submit" type="submit" value="儲存" />
        </div>
        <!-- </div> -->
      </form>
    </div>

    <div class='input-form' style="text-align:center">
      <form name='form' method='post' action='meetman_add.php'>
        <!-- <div class="row"> -->
        <div class="col">
          <label for="ysman" class="ysman">永信代表</label>
          <input type='text' name='ysman' placeholder="永信代表" value=<?php echo $ysman; ?>>
        </div>
        <div class="col">
          <label for="cowellman" class="cowellman">科威代表</label>
          <input type='text' name='cowellman' placeholder="科威代表" value=<?php echo $cowellman; ?>>
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
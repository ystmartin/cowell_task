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
      <H1><a href="index.php">會議主題</a></H1>
    </div>
  </div>
  <?php
  require_once("dbtools.inc.php");
  date_default_timezone_set("Asia/Shanghai");

  $ysman   = $_COOKIE["ysman"];
  $cowellman  = $_COOKIE["cowellman"];
  $mid = $_GET["mid"];



  ?>
  <table class="table container">
    <tr>
      <td>永信代表:<?php echo $ysman; ?></td>
      <td>科威代表:<?php echo $cowellman; ?></td>
    </tr>

    <table class="table container">
      <thead>
        <tr>
          <th scope="col">編號</th>
          <th scope="col">標題</th>
          <th scope="col">內容</th>
          <th scope="col">提出人</th>
          <th scope="col">編輯</th>
          <th scope="col">刪除</th>
          <!-- <th scope="col">跟進2</th> -->
        </tr>
      </thead>
      <tbody>
        <?php
        $link = create_connection();
        $sql = "SELECT * FROM meet_task where mid =$mid ";
        $result = execute_sql($link, "tw1_cowell_task", $sql);
        $row = $result->fetch_assoc();
        echo "<tr>";
        echo "<th>" . $row['mid'] . "</th>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['content'] . "</td>";
        echo "<td>" . $row['applyman'] . "</td>";
        echo "<td><a class='btn btn-primary' href='meet_edit.php?id=" . $row['mid'] . "' role='button'>編輯</a></td>";
        echo "<td><a class='btn btn-danger' href='meet_del.php?id=" . $row['mid'] . "' role='button'>刪除</a></td>";
        // echo "<td><a class='btn btn-success' href='meet_enter.php?id=" . $row['id'] . "' role='button'>跟進</a></td>";
        echo "<tr>";
        $meetdate = $row['meetdate'];

        ?>
      </tbody>
    </table>
    <div class="text-center">
      <div>
        <H1><a href="crmeet.php?meetdate=<?php echo $meetdate?>">議題回覆</a></H1>
      </div>
    </div>
    <table class="table container">
      <thead>
        <tr>
          <th scope="col">議題編號</th>
          <th scope="col">回覆編號</th>
          <th scope="col">回覆日期</th>
          <th scope="col">回覆內容</th>
          <th scope="col">回覆人</th>
          <th scope="col">備註</th>
          <th scope="col">編輯</th>
          <th scope="col">刪除</th>
          <!-- <th scope="col">跟進2</th> -->
        </tr>
      </thead>
      <tbody>
        <?php
        $link = create_connection();
        $sql = "SELECT * FROM reply_list where mid =$mid order by rid ASC";
        $result = execute_sql($link, "tw1_cowell_task", $sql);
        // $row = $result->fetch_assoc();
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<th>" . $row['mid'] . "</th>";
          echo "<td>" . $row['rid'] . "</td>";
          echo "<td>" . $row['replydate'] . "</td>";
          echo "<td>" . $row['replycontent'] . "</td>";
          echo "<th>" . $row['replyman'] . "</th>";
          echo "<td>" . $row['replymemo'] . "</td>";
          echo "<td><a class='btn btn-primary' href='reply_edit.php?rid=" . $row['rid'] . "' role='button'>編輯</a></td>";
          echo "<td><a class='btn btn-danger' href='reply_del.php?rid=" . $row['rid'] . "' role='button'>刪除</a></td>";
          // echo "<td><a class='btn btn-success' href='meet_enter.php?id=" . $row['id'] . "' role='button'>跟進</a></td>";
          $mid=$row['mid'];
          echo "<tr>";
        }

        ?>
      </tbody>
    </table>
    <div class='input-form' style="text-align:center">
      <form name='form' method='post' action='reply_add.php?mid=<?php echo $mid?>'>
        <!-- <div class="row"> -->
        <div class="col">
          <label for="title" class="title" style="width: 200px;height: 30px;">議題編號</label>
          <input type='text' name='mid' placeholder="" style="width: 600px;height: 30px;" value=<?php echo $mid; ?>>
        </div>
        <div class="col">
          <label for="replycontent" class="replycontent" style="width: 200px;height: 30px;">回覆內容</label>
          <input type='text' name='replycontent' placeholder="回覆內容" style="width: 600px;height: 30px;">
        </div>
        <div class="col">
          <label for="replyman" class="replyman" style="width: 200px;height: 30px;">回覆人</label>
          <input type='text' name='replyman' placeholder="回覆人" style="width: 600px;height: 30px;" value=<?php echo $cowellman; ?>>
        </div>
        <div class="col">
          <label for="replymemo" class="replymemo" style="width: 200px;height: 30px;">回覆備註</label>
          <input type='text' name='replymemo' placeholder="備註" style="width: 600px;height: 30px;">
        </div>
        <div class="col">
          <label for="memoman" class="memoman" style="width: 200px;height: 30px;">備註人</label>
          <input type='text' name='memoman' placeholder="備註人" style="width: 600px;height: 30px;" value=<?php echo $ysman; ?>>
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
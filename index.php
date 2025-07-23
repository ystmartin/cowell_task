<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>科威會議</title>
  <link rel="shortcut icon" href="favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <div class="text-center">
    <div>
      <h1>會議清單</h1>
    </div>
  </div>

  <?php
  require_once("dbtools.inc.php");
  date_default_timezone_set("Asia/Shanghai");
  session_start();

  //echo $_SESSION['email'];
  if (!isset($_SESSION['email'])) {

    header("Location: login.php");
    exit;
  }

  if (!$_COOKIE["ysman"]) {
    setcookie("ysman", '馬丁', time() + 28800);
    setcookie("cowellman", 'Dily', time() + 28800);
  }


  $ysman   = $_COOKIE["ysman"];
  $cowellman  = $_COOKIE["cowellman"];
  $email  = $_SESSION['email'];

  // 分頁參數
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $limit = 5;
  $offset = ($page - 1) * $limit;

  // 建立連線
  $link = create_connection();
  ?>

  <table class="table container">
    <tr>
      <td>永信代表:<?php echo htmlspecialchars($ysman); ?></td>
      <td>科威代表:<?php echo htmlspecialchars($cowellman); ?></td>
      <td><a href="report_list.php">報表及搜尋</a></td>
      <?php
      $link = create_connection();
      $sql = "SELECT * FROM accounts where email = '$email' ";
      $result = execute_sql($link, "tw1_cowell_task", $sql);
      $row = $result->fetch_assoc();

      ?>

      <td>帳號:<?php echo $row['account']; ?></td>
      <td><a href="logout.php">登出</a></td>
    </tr>
  </table>

  <table class="table container">
    <thead>
      <tr>
        <th>會議日期</th>
        <th>議題總數</th>
        <th>提出需求</th>
        <th>完成需求</th>
        <th>評估中</th>
        <th>無法處理</th>
        <th>不要處理</th>
        <th>取消需求</th>
        <th>其他</th>
        <th style="text-align:right;">進入</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "
SELECT 
  meetdate,
  COUNT(*) AS total,
  SUM(CASE WHEN status = '提出需求' THEN 1 ELSE 0 END) AS status01,
  SUM(CASE WHEN status = '完成需求' THEN 1 ELSE 0 END) AS status02,
  SUM(CASE WHEN status = '評估中' THEN 1 ELSE 0 END) AS status03,
  SUM(CASE WHEN status = '無法處理' THEN 1 ELSE 0 END) AS status04,
  SUM(CASE WHEN status = '不要處理' THEN 1 ELSE 0 END) AS status05,
  SUM(CASE WHEN status = '取消需求' THEN 1 ELSE 0 END) AS status06,
  SUM(CASE WHEN status = '其他' THEN 1 ELSE 0 END) AS status07,

  MAX(mid) as mid
FROM meet_task
GROUP BY meetdate
ORDER BY mid DESC
LIMIT $limit OFFSET $offset
";

      $result = execute_sql($link, "tw1_cowell_task", $sql);

      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['meetdate'] . "</td>";
        echo "<td>" . $row['total'] . "</td>";
        echo "<td>" . $row['status01'] . "</td>";
        echo "<td>" . $row['status02'] . "</td>";
        echo "<td>" . $row['status03'] . "</td>";
        echo "<td>" . $row['status04'] . "</td>";
        echo "<td>" . $row['status05'] . "</td>";
        echo "<td>" . $row['status06'] . "</td>";
        echo "<td>" . $row['status07'] . "</td>";
        echo "<td style='text-align:right;'><a class='btn btn-primary' href='crmeet.php?meetdate=" . urlencode($row['meetdate']) . "'>進入</a></td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

  <?php
  // 計算總筆數與總頁數
  $count_sql = "SELECT COUNT(DISTINCT meetdate) AS total FROM meet_task";
  $count_result = execute_sql($link, "tw1_cowell_task", $count_sql);
  $count_row = $count_result->fetch_assoc();
  $total_rows = $count_row['total'];
  $total_pages = ceil($total_rows / $limit);
  ?>

  <!-- 分頁導航 -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>

  <!-- 新增按鈕 -->
  <div class='input-form text-center my-4'>
    <button class='btn btn-success' onclick="location.href='crmeet.php?meetdate=<?= date('Y-m-d') ?>'">
      開新會議
    </button>
  </div>

  <?php
  mysqli_free_result($result);
  mysqli_free_result($count_result);
  mysqli_close($link);
  ?>
</body>

</html>
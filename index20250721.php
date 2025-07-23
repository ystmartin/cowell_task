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

  $ysman   = $_COOKIE["ysman"];
  $cowellman  = $_COOKIE["cowellman"];

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
    </tr>
  </table>

  <table class="table container">
    <thead>
      <tr>
        <th>會議日期</th>
        <th style="text-align:right;">進入</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // 查詢本頁資料
      $sql = "SELECT meetdate, MAX(mid) as mid FROM meet_task GROUP BY meetdate ORDER BY mid DESC LIMIT $limit OFFSET $offset";
      $result = execute_sql($link, "tw1_cowell_task", $sql);

      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['meetdate'] . "</td>";
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

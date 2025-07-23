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
      <h1><a href="index.php">報表及搜尋</a></h1>
    </div>
    <div>
      <h2>狀態統計</h2>
    </div>
  </div>

  <?php
  require_once("dbtools.inc.php");
  date_default_timezone_set("Asia/Shanghai");

  $ysman   = $_COOKIE["ysman"];
  $cowellman  = $_COOKIE["cowellman"];
  $searchkey  = isset($_GET["search_key"]) ? $_GET["search_key"] : "";
  $key = isset($_GET['key']) ? $_GET['key'] : '提出需求';
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $limit = 20;
  $offset = ($page - 1) * $limit;
  ?>

  <table class="table container">
    <thead>
      <tr>
        <?php
        $array = array("提出需求", "完成需求", "待提需求", "評估中", "無法處理", "不要處理", "取消需求", "其他");
        foreach ($array as $status) {
          echo "<th scope='col'><a href='report_list.php?key=" . urlencode($status) . "'>$status</a></th>";
        }
        ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php
        $link = create_connection();
        foreach ($array as $status) {
          $sql = "SELECT COUNT(*) AS count FROM meet_task WHERE status = '$status'";
          $result = execute_sql($link, "tw1_cowell_task", $sql);
          $row = $result->fetch_assoc();
          echo "<td>" . $row['count'] . "</td>";
        }
        ?>
      </tr>
    </tbody>
  </table>

  <!-- 搜尋表單 (使用 GET 傳遞以便保留查詢條件) -->
  <div class="container text-center" style="max-width: 500px;">
    <form name='form' method='get' action='report_list.php'>
      <div class="row g-2 align-items-end justify-content-center">
        <div class="col-auto">
          <label for="search_key" class="form-label">關鍵字</label>
          <input type="text" name="search_key" class="form-control" placeholder="關鍵字" value="<?= htmlspecialchars($searchkey) ?>">
          <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
        </div>
        <div class="col-auto">
          <input type="submit" class="btn btn-primary" value="送出">
        </div>
      </div>
    </form>
  </div>

  <table class="table container">
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
      // 查詢資料（有搜尋則以搜尋為主）
      if (!empty($searchkey)) {
        $sql = "SELECT * FROM meet_task WHERE title LIKE '%$searchkey%' ORDER BY mid DESC LIMIT $limit OFFSET $offset";
        $count_sql = "SELECT COUNT(*) AS total FROM meet_task WHERE title LIKE '%$searchkey%'";
      } else {
        $sql = "SELECT * FROM meet_task WHERE status = '$key' ORDER BY mid DESC LIMIT $limit OFFSET $offset";
        $count_sql = "SELECT COUNT(*) AS total FROM meet_task WHERE status = '$key'";
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
        echo "<td><a class='btn btn-primary' href='meet_edit.php?mid=" . $row['mid'] . "'>編輯</a></td>";
        echo "<td><a class='btn btn-danger' href='meet_del.php?mid=" . $row['mid'] . "' onclick=\"return confirm('確定要刪除這筆資料嗎？');\">刪除</a></td>";
        echo "<td><a class='btn btn-success' href='meet_enter.php?mid=" . $row['mid'] . "'>跟進</a></td>";

        $sql2 = "SELECT COUNT(*) AS reply FROM reply_list WHERE mid = '{$row['mid']}'";
        $result2 = execute_sql($link, "tw1_cowell_task", $sql2);
        $row2 = $result2->fetch_assoc();
        echo "<td>" . $row2['reply'] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

  <?php
  // 分頁按鈕顯示
  $count_result = execute_sql($link, "tw1_cowell_task", $count_sql);
  $count_row = $count_result->fetch_assoc();
  $total_rows = $count_row['total'];
  $total_pages = ceil($total_rows / $limit);

  echo '<nav><ul class="pagination justify-content-center">';
  for ($i = 1; $i <= $total_pages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    $url = "report_list.php?page=$i";
    if (!empty($key)) $url .= "&key=" . urlencode($key);
    if (!empty($searchkey)) $url .= "&search_key=" . urlencode($searchkey);
    echo "<li class='page-item $active'><a class='page-link' href='$url'>$i</a></li>";
  }
  echo '</ul></nav>';

  mysqli_free_result($result);
  mysqli_close($link);
  ?>
</body>

</html>

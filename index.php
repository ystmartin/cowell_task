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
    <table class="container">
        <thead>
            <tr>
                <td >會議日期</td>
                <!-- <th scope="col">永信代表</th>
                <th scope="col">科威代表</th>
                <th scope="col">會議備忘</th> -->

                <td  style="float:right;">進入</td>

            </tr>
        </thead>
        <tbody>
            <?php
            $link = create_connection();
            $sql = $sql = "SELECT * FROM meet_task Group by meetdate order by mid DESC";

            $result = execute_sql($link, "tw1_cowell_task", $sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['meetdate'] . "</td>";
                // echo "<td>" . $row['ysman'] . "</td>";
                // echo "<td>" . $row['cowellman'] . "</td>";
                // echo "<td>" . $row['memo'] . "</td>";
                echo "<td style='float:right;'><a class='btn btn-primary' href='crmeet.php?meetdate=" . $row['meetdate'] . "' role='button'>進入</a></td>";
                echo "<tr>";
            };
         
            ?>

    </table>

    <div class='input-form' style="text-align:center">
        <button class='btn btn-primary' onclick="location.href='crmeet.php?meetdate=<?php echo date('Y-m-d'); ?>'">
            開新會議
        </button>
    </div>





    <?php
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
</body>

</html>
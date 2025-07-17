<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>藍新測試</title>
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
            <H1>商品清單</H1>
        </div>
    </div>
    <?php
    require_once("dbtools.inc.php");
    date_default_timezone_set("Asia/Shanghai");
    ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">商品號碼</th>
                <th scope="col">商品名稱</th>
                <th scope="col">單價</th>
                <th scope="col">說明</th>
                <th scope="col">編輯</th>
                <th scope="col">刪除</th>
                <th scope="col">訂購</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $link = create_connection();
            $sql = "SELECT * FROM goods order by id ASC";
            $result = execute_sql($link, "tw1_bluenewshop", $sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<th>" . $row['id'] . "</th>";
                echo "<td>" . $row['itemname'] . "</td>";
                echo "<td>" . $row['unitprice'] . "</td>";
                echo "<td>" . $row['itemmemo'] . "</td>";
                echo "<td><a class='btn btn-primary' href='goods_edit.php?id=" . $row['id'] . "' role='button'>編輯</a></td>";
                echo "<td><a class='btn btn-danger' href='goods_del.php?id=" . $row['id'] . "' role='button'>刪除</a></td>";
                echo "<td><a class='btn btn-success' href='order_add.php?id=" . $row['id'] . "' role='button'>訂購</a></td>";
                echo "<tr>";
            };
            ?>
        </tbody>
    </table>
    <div class='input-form'>
        <form name='form' method='post' action='goods_add.php'>
            <div class="row">
                <div class="col">
                    <label for="itemname" class="input-name"></label>
                    <input type='text' name='itemname' placeholder="商品名稱">
                </div>
                <div class="col">
                    <label for="unitprice" class="input-name"></label>
                    <input type='text' name='unitprice' placeholder="商品單價">
                </div>
                <div class="col">
                    <label for="itemmemo" class="input-name"></label>
                    <input type='text' name='itemmemo' placeholder="商品說明">
                </div>
                <div class="col">
                    <input class="input-submit" type="submit" value="儲存" />
                </div>
            </div>
        </form>
    </div>
    <div class="text-center">
        <div>
            <H1>訂單清單</H1>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">訂單號碼</th>
                <th scope="col">商品號碼</th>
                <th scope="col">商品名稱</th>
                <th scope="col">數量</th>
                <th scope="col">單價</th>
                <th scope="col">總價</th>
                <th scope="col">5%稅</th>
                <th scope="col">合計</th>
                <th scope="col">狀態</th>
                <th scope="col">編輯</th>
                <th scope="col">刪除</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $link = create_connection();
            $sql = "SELECT * FROM orders order by sn ASC";
            $result = execute_sql($link, "tw1_bluenewshop", $sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<th>" . $row['sn'] . "</th>";
                echo "<th>" . $row['id'] . "</th>";
                echo "<td>" . $row['itemname'] . "</td>";
                echo "<td>" . $row['qty'] . "</td>";
                echo "<td>" . $row['unitprice'] . "</td>";
                echo "<td>" . $row['sumprice'] . "</td>";
                echo "<td>" . $row['vat'] . "</td>";
                echo "<td>" . $row['totalprice'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td><a class='btn btn-primary' href='order_edit.php?sn=" . $row['sn'] . "' role='button'>編輯</a></td>";
                echo "<td><a class='btn btn-danger' href='order_del.php?sn=" . $row['sn'] . "' role='button'>刪除</a></td>";
                echo "<tr>";
            };
            ?>
        </tbody>
    </table>
    <?php
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
</body>

</html>
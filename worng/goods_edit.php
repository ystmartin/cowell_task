<!DOCTYPE html>
<html lang="zh-Hant">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>goods編輯</title>
  <link rel="shortcut icon" href="favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

  <div class="text-center">
    <div>
      <H1>商品編輯</H1>
    </div>
  </div>

  <?php
  $id = $_GET["id"];
  require_once("dbtools.inc.php");
  $link = create_connection();
  $sql = "SELECT * FROM goods WHERE id='$id'";
  $result = execute_sql($link, "tw1_bluenewshop", $sql);
  $row = mysqli_fetch_assoc($result);
  ?>

  <div class='input-form'>
    <form name='form' method='post' action='goods_update.php?id=<?php echo $id ?>'>
      <div class="row">
        <!-- <div class="col">
          <label for="id" class="input-name"></label>
          <input type='text' name='id' value='<?php echo $row["id"]; ?>' readonly>
        </div> -->
        <div class="col">
          <label for="itemname" class="input-name"></label>
          <input type='text' name='itemname' value='<?php echo $row["itemname"]; ?>'>
        </div>
        <div class="col">
          <label for="unitprice" class="input-name"></label>
          <input type='text' name='unitprice' value=<?php echo $row["unitprice"]; ?>>
        </div>
        <div class="col">
          <label for="itemmemo" class="input-name"></label>
          <input type='text' name='itemmemo' value=<?php echo $row["itemmemo"]; ?>>
        </div>
        <div class="col">
          <input class="input-submit" type="submit" value="儲存" />
        </div>
        <div class="col">
          <a class='btn btn-danger' href='index.php' role='button'>取消</a>
        </div>
      </div>
    </form>
  </div>

</body>

</html>
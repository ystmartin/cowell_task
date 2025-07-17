<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
</head>

<body>

  <?php
  $ysman = $_POST["ysman"];
  echo $ysman;
  echo $cowellman;
  $cowellman = $_POST["cowellman"];
  echo $cowellman;
  setcookie("ysman", $ysman, time() + 28800);
  setcookie("cowellman", $cowellman, time() + 28800);


  $ysman   = $_COOKIE["ysman"];
  $cowellman  = $_COOKIE["cowellman"];


  header("Location:crmeet.php");  
  ?>




</body>

</html>
<?php
session_start();

if (!isset($_SESSION['google_id'])) {
    header('Location: login.php');
    exit;
}

echo "歡迎，" . $_SESSION['name'] . "<br>";
echo "<img src='" . $_SESSION['picture'] . "'><br>";
echo "Email: " . $_SESSION['email'];

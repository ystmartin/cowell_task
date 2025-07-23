<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 檢查是否已登入
if (!isset($_SESSION['account'])) {
    header("Location: login.php");
    exit;
}

$account = $_SESSION['account'];
$email = $_SESSION['email'] ?? '（未提供）';

?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員中心</title>
    <style>
        body { font-family: sans-serif; background-color: #f9f9f9; padding: 40px; }
        .container {
            background-color: white;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 { margin-bottom: 20px; }
        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #e53935;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👋 歡迎回來，<?= htmlspecialchars($account) ?>！</h1>
        <p>您的電子郵件：<?= htmlspecialchars($email) ?></p>

        <a class="logout-btn" href="logout.php">登出</a>
    </div>
</body>
</html>

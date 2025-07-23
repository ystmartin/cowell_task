<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 資料庫連線設定（請依實際資訊修改）
$host = 'localhost';
$dbname = 'tw1_cowell_task';
$user = 'tw1_cowell_task';
$pass = 'ys86887029';

// 建立 MySQL 連線
$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("資料庫連線失敗: " . $mysqli->connect_error);
}

// 接收表單資料
$account = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($account) || empty($password)) {
    echo "請輸入帳號與密碼。<br><a href='login.php'>返回登入頁面</a>";
    exit;
}

// 查詢帳號
$stmt = $mysqli->prepare("SELECT account, password, email FROM accounts WHERE account = ?");
$stmt->bind_param("s", $account);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // 驗證密碼
    if (password_verify($password, $row['password'])) {
        $_SESSION['account'] = $row['account'];
        $_SESSION['email'] = $row['email'];
        echo $_SESSION['email'];
        $stmt->close();
        $mysqli->close();
        header('Location: index.php');
    } else {
        $stmt->close();
        $mysqli->close();
        echo "密碼錯誤。<br><a href='login.php'>返回登入頁面</a>";
    }
} else {
    $stmt->close();
    $mysqli->close();
    echo "查無此帳號。<br><a href='login.php'>返回登入頁面</a>";
}

$stmt->close();
$mysqli->close();

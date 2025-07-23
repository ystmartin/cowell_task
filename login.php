<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '/home/tw1/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

$client = new Google_Client();
$client->setClientId($config['GOOGLE_CLIENT_ID']);
$client->setClientSecret($config['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($config['GOOGLE_REDIRECT_URI']);
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員登入</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f0f0; padding: 40px; }
        .login-container {
            background-color: white;
            padding: 30px;
            max-width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box;
        }
        button {
            width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none;
            border-radius: 5px; font-size: 16px;
        }
        .google-login {
            display: block; text-align: center; margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>會員登入</h2>
    <form method="post" action="login_process.php">
        <label for="username">帳號</label>
        <input type="text" id="username" name="username" required>

        <label for="password">密碼</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">登入</button>
    </form>

    <div class="google-login">
        <a href="<?= htmlspecialchars($authUrl) ?>">👉 使用 Google 帳號登入</a>
    </div>
</div>

</body>
</html>

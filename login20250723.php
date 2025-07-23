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
<a href="<?= htmlspecialchars($authUrl) ?>">使用 Google 帳號登入</a>

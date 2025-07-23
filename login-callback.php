<?php
require_once '/home/tw1/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

$client = new Google_Client();
$client->setClientId($config['GOOGLE_CLIENT_ID']);
$client->setClientSecret($config['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($config['GOOGLE_REDIRECT_URI']);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // 取得用戶資料
    $oauth = new Google_Service_Oauth2($client);
    $userinfo = $oauth->userinfo->get();

    // 顯示或處理使用者資料
    session_start();
    $_SESSION['google_id'] = $userinfo->id;
    $_SESSION['name'] = $userinfo->name;
    $_SESSION['email'] = $userinfo->email;
    $_SESSION['picture'] = $userinfo->picture;

    header('Location: index.php');
    exit;
}

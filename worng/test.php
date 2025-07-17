<?php
date_default_timezone_set("Asia/Shanghai");
$hashKey = '9SxsfqbZNuGqeWqEpKLBzCAaliwu2t2x';
$hashIV  = 'CaxgDG6xQU6RIIJP';

// 原始資料範例，通常是訂單資訊JSON字串
$plainData = json_encode([
    'Status' => 'SUCCESS',
    'Message' => urlencode('交易成功'),
    'MerchantOrderNo' => 'ORDER123456',
    'Amt' => 1000,
    'PayTime' => urlencode(date('Y-m-d H:i:s')),
    'TradeNo' => 'TRADE987654321',
]);

// PKCS7 補碼函式（notify.php 裡的 stripPadding 是解碼用，這邊加密前要補碼）
function pkcs7_pad($data, $blocksize = 16) {
    $pad = $blocksize - (strlen($data) % $blocksize);
    return $data . str_repeat(chr($pad), $pad);
}

$paddedData = pkcs7_pad($plainData);

// 用 AES-256-CBC 加密，輸出 raw binary
$encrypted = openssl_encrypt($paddedData, 'AES-256-CBC', $hashKey, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $hashIV);

// 轉成十六進位字串
$tradeInfo = bin2hex($encrypted);

echo $tradeInfo;

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 接收 POST 或 GET
$data = $_POST ?: $_GET;

echo "<h3>原始接收資料：</h3><pre>";
print_r($data);
echo "</pre>";

// 確認交易狀態
if (!isset($data['Status']) || $data['Status'] !== 'SUCCESS') {
    echo "<h2>交易失敗或未收到正確資料</h2>";
    exit;
};

//去除padding
function strippadding($string) {
    $slast = ord(substr($string, -1));
    $slastc = chr($slast);
    $pcheck = substr($string, -$slast);
    if (preg_match("/$slastc{" . $slast ."}/",$string)){
        $string = substr($string, 0, strlen($string) - $slast);
        return $string;
    } else {
        return false;
    }
}
// === TradeInfo 解密流程 ===
$key = '9SxsfqbZNuGqeWqEpKLBzCAaliwu2t2x';
$iv  = 'CaxgDG6xQU6RIIJP';

$edata = strippadding(openssl_decrypt(hex2bin($data), "AES-256-CBC",$key,OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv));
echo "解密後資料=[<font color=darkblue><gg
id='outtt'>".$edata."</gg></font>]<br>";                                                                                    


// 將 TradeInfo HEX → Binary
$tradeInfoHex = $data['TradeInfo'];
$tradeInfoBin = hex2bin($tradeInfoHex);

// 解密
$decrypt = openssl_decrypt(
    $tradeInfoBin,
    'AES-256-CBC',
    $hashKey,
    OPENSSL_RAW_DATA,
    $hashIV
);

if ($decrypt === false) {
    echo "<h2>TradeInfo 解密失敗！</h2>";
    exit;
}

echo "<h3>解密後內容：</h3><pre>";
print_r($decrypt);
echo "</pre>";

// 將 JSON 字串轉成陣列
$result = json_decode($decrypt, true);

if (empty($result) || !is_array($result)) {
    echo "<h2>解密後 JSON 格式錯誤！</h2>";
    exit;
}

echo "<h2>付款成功！</h2>";
echo "訂單編號：" . htmlspecialchars($result['MerchantOrderNo']) . "<br>";
echo "金額：" . htmlspecialchars($result['Amt']) . "<br>";
echo "交易時間：" . htmlspecialchars($result['PayTime']) . "<br>";
echo "交易序號：" . htmlspecialchars($result['TradeNo']) . "<br>";
?>



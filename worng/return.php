<?php

date_default_timezone_set("Asia/Shanghai");
// 顯示錯誤訊息（開發階段用）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 設定藍新金流的 Hash Key 與 IV
$hashKey = '9SxsfqbZNuGqeWqEpKLBzCAaliwu2t2x';
$hashIV  = 'CaxgDG6xQU6RIIJP';

// 儲存 raw POST log（開發階段建議使用）
//file_put_contents('return_log.txt', "----\n" . date('Y-m-d H:i:s') . "\n" . print_r($_POST, true), FILE_APPEND);

// Step 1: 取得 POST 的加密資料
$tradeInfoEnc = $_POST['TradeInfo'] ?? '';

if (!$tradeInfoEnc) {
    exit('未收到 TradeInfo（POST 內容為空）');
}

// Step 2: AES 解密
$decrypted = openssl_decrypt(
    hex2bin($tradeInfoEnc),
    'AES-256-CBC',
    $hashKey,
    OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
    $hashIV
);

// Step 3: 移除 padding
function stripPKCS7Padding($string) {
    $pad = ord(substr($string, -1));
    return substr($string, 0, -$pad);
}

$decrypted = stripPKCS7Padding($decrypted);

// Step 4: 嘗試轉為 JSON 陣列
$tradeData = json_decode($decrypted, true);

// Step 5: 錯誤檢查
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<h3>TradeInfo 解密失敗：JSON 格式錯誤</h3>";
    echo "<pre>解密後原始資料：\n" . htmlentities($decrypted) . "</pre>";
    exit;
}

// Step 6: 顯示交易資訊
$result = $tradeData['Result'] ?? null;

if (!$result) {
    exit('找不到 Result 欄位');
}

$orderNo = $result['MerchantOrderNo'] ?? '未提供';
$amount  = $result['Amt'] ?? '未提供';
$status  = $tradeData['Status'] ?? '未知';
$message = $tradeData['Message'] ?? '';

echo "<h2>交易結果</h2>";
echo "<p>狀態：{$status}（{$message}）</p>";
echo "<p>訂單編號：{$orderNo}</p>";
echo "<p>金額：{$amount} 元</p>";
?>
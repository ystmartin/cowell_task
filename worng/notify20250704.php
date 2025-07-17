<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

file_put_contents('notify_test.txt', date('Y-m-d H:i:s') . " POST Data:\n" . print_r($_POST, true), FILE_APPEND);

$hashKey = '9SxsfqbZNuGqeWqEpKLBzCAaliwu2t2x';
$hashIV  = 'CaxgDG6xQU6RIIJP';

function stripPadding($string) {
    $pad = ord(substr($string, -1));
    if ($pad < 1 || $pad > 16) {
        // 補碼長度錯誤
        return $string;
    }
    return substr($string, 0, -$pad);
}

$tradeInfoEnc = $_POST['TradeInfo'] ?? '';
if (!$tradeInfoEnc) {
    http_response_code(400);
    exit('No TradeInfo received');
}

$binData = hex2bin($tradeInfoEnc);
if ($binData === false) {
    file_put_contents('notify_error.txt', date('Y-m-d H:i:s') . " hex2bin failed\n", FILE_APPEND);
    http_response_code(400);
    exit('Invalid hex string');
}

$decrypted = openssl_decrypt($binData, 'AES-256-CBC', $hashKey, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $hashIV);
if ($decrypted === false) {
    file_put_contents('notify_error.txt', date('Y-m-d H:i:s') . " Decrypt failed\n", FILE_APPEND);
    http_response_code(400);
    exit('Decrypt failed');
}

$decrypted = stripPadding($decrypted);

file_put_contents('notify_decrypted.txt', date('Y-m-d H:i:s') . " Decrypted data:\n" . $decrypted . "\n", FILE_APPEND);

$data = json_decode($decrypted, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    parse_str($decrypted, $data);
}

if (empty($data)) {
    file_put_contents('notify_error.txt', date('Y-m-d H:i:s') . " Parse data failed\n", FILE_APPEND);
    http_response_code(400);
    exit('Parse data failed');
}

file_put_contents('notify_log.txt', date('Y-m-d H:i:s') . "\n" . print_r($data, true) . "\n----\n", FILE_APPEND);

$status = $data['Status'] ?? '';

if ($status === 'SUCCESS') {
    echo 'Status=SUCCESS';
    exit;
} else {
    http_response_code(400);
    exit('交易未成功');
}


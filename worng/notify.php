<?php
date_default_timezone_set("Asia/Shanghai");
ini_set('display_errors', 1);
error_reporting(E_ALL);
file_put_contents('notify_ok.txt', date('Y-m-d H:i:s') . " POST Data:\n" . print_r("notify_ok", true), FILE_APPEND);
file_put_contents('notify_test.txt', date('Y-m-d H:i:s') . " POST Data:\n" . print_r($_POST, true), FILE_APPEND);

$hashKey = '9SxsfqbZNuGqeWqEpKLBzCAaliwu2t2x';
$hashIV  = 'CaxgDG6xQU6RIIJP';

function stripPadding($string)
{
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

$merchant_order_no = $data['Result']['MerchantOrderNo'] ?? '';
file_put_contents('merchant_order_no.txt', date('Y-m-d H:i:s') . "\n" . print_r($merchant_order_no, true) . "\n----\n", FILE_APPEND);

$status = $data['Status'] ?? '';
file_put_contents('notify_status.txt', date('Y-m-d H:i:s') . "\n" . print_r($status, true) . "\n----\n", FILE_APPEND);


$tradeno = $data['Result']['TradeNo'] ?? '';
file_put_contents('tradeno.txt', date('Y-m-d H:i:s') . "\n" . print_r($tradeno, true) . "\n----\n", FILE_APPEND);


if ($status === 'SUCCESS') {
  echo 'Status=SUCCESS';

  date_default_timezone_set("Asia/Shanghai");
  require_once("dbtools.inc.php");
  $link = create_connection();
  $sql = "UPDATE orders SET status='$status'  where merchant_order_no='$merchant_order_no' ";
  $result = execute_sql($link, "tw1_bluenewshop", $sql);
  echo $result;

  if (! $result) {
    die('無法更新資料: ' . mysqli_error($link));
  }
  echo "資料更新成功\n";

  mysqli_free_result($result);
  mysqli_close($link);

  exit;
} else {
  http_response_code(400);
  exit('交易未成功');
}

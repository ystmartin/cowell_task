<?php
require_once("dbtools.inc.php");
date_default_timezone_set("Asia/Shanghai");
$id     = $_GET["id"];
echo "id:" . $id . '<br>';
$orderdate = date("Y/m/d H:i:s");
echo "orderdate:" . $orderdate . '<br>';
$link = create_connection();
$sql = "SELECT * FROM goods where id=$id";
$result = execute_sql($link, "tw1_bluenewshop", $sql);
$row = $result->fetch_assoc();
$itemname     =  $row["itemname"];
echo "itemname:" . $itemname . '<br>';
$unitprice     =  $row["unitprice"];
echo "unitprice:" . $unitprice . '<br>';
$qty = 1;
echo "qty:" . $qty . '<br>';
$sumprice = $unitprice * $qty;
echo "sumprice:" . $sumprice . '<br>';
$vat = $sumprice * 0.05;
echo "vat:" . $vat . '<br>';
$totalprice = $sumprice + $vat;
echo "totalprice:" . $totalprice . '<br>';
$status = "已訂購";
echo "status:" . $status . '<br>';

$link = create_connection();
$sql = "INSERT INTO orders (id,orderdate,itemname,qty,unitprice,vat,sumprice,totalprice,status) VALUES('$id','$orderdate','$itemname','$qty','$unitprice','$vat','$sumprice','$totalprice','$status')";
$result = execute_sql($link, "tw1_bluenewshop", $sql);

if (! $result) {
    die('無法插入資料: ' . mysqli_error($link));
}
echo "資料插入成功\n";
$new_id = mysqli_insert_id($link);

// 建立藍新交易參數
require('config.php');

$merchant_id = "MS156162835";
$hash_key = "9SxsfqbZNuGqeWqEpKLBzCAaliwu2t2x";
$hash_iv = "CaxgDG6xQU6RIIJP";

$merchant_order_no = $new_id . "_" . time();
$amt = (int)$totalprice;
$item_desc = $itemname;
$email = "martin_hung@ystravel.com.tw";
$return_url = "https://7029.tw/bluenewshop/return.php";
$notify_url = "https://7029.tw/bluenewshop/notify.php";
// $notify_url = "";

$trade_info_arr = [
    "MerchantID" => $merchant_id,
    "RespondType" => "JSON",
    "TimeStamp" => time(),
    "Version" => "2.0",
    "MerchantOrderNo" => $merchant_order_no,
    "Amt" => $amt,
    "ItemDesc" => $item_desc,
    "Email" => $email,
    "ReturnURL" => $return_url,
    // "NotifyURL" => $notify_url(怎麼試都不成功,notify.php無法被藍新觸發)
    // "ReturnURL" => $return_url,
    //"NotifyURL" => $notify_url
];


$sql = "UPDATE orders SET merchant_order_no='$merchant_order_no'  where sn='$new_id' ";
$result = execute_sql($link, "tw1_bluenewshop", $sql);
echo $result;

if (! $result) {
    die('無法更新資料: ' . mysqli_error($link));
}
echo "資料更新成功\n";

$trade_info_query = http_build_query($trade_info_arr);

$encrypted = encryptNewebPay($trade_info_query, $hash_key, $hash_iv);

$hashs = "HashKey=$hash_key&$encrypted&HashIV=$hash_iv";
//hashs = "HashKey={$hash_key}&TradeInfo={$encrypted}&HashIV={$hash_iv}";
$check_value = strtoupper(hash("sha256", $hashs));
$tradeInfo_for_form = urlencode($encrypted);
// 自動送出表單

// echo "<pre>";
// echo "原始加密內容（hex）: " . $encrypted . "\n";
// echo "Hash 字串：\n" . $hashs . "\n";
// echo "計算 CheckValue: " . $check_value . "\n";
// echo "</pre>";
// exit;

echo "
<form id='newebpay-form' method='post' action='https://ccore.newebpay.com/MPG/mpg_gateway'>
    <input type='hidden' name='MerchantID' value='$merchant_id'>
    <input type='hidden' name='TradeInfo' value='$tradeInfo_for_form'>
    <input type='hidden' name='TradeSha' value='$check_value'>
    <input type='hidden' name='Version' value='2.0'>
</form>
<script>
    document.getElementById('newebpay-form').submit();
</script>
";
exit;



header("Location: index.php");

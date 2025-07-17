<?php
function encryptNewebPay($data, $key, $iv) {
    $padded = pkcs5_pad($data, 32);
    $encrypted = openssl_encrypt($padded, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    //$encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return bin2hex($encrypted);
}

function pkcs5_pad($text, $blocksize) {
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}
?>
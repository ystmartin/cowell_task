<?php
$password = 'ys19650930'; // 例如 123456
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;

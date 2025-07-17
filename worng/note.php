<?php
file_put_contents('notify_ok.txt', date('Y-m-d H:i:s') . " POST Data:\n" . print_r("notify_ok", true), FILE_APPEND);
?>
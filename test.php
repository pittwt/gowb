<?php
date_default_timezone_set('Asia/Shanghai');
$fp = fopen("test.txt", "a+"); 
fwrite($fp, date("Y-m-d H:i:s") . " test\n"); 
fclose($fp);
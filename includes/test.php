<?php
/*$content = 'asdfadfantondjfjaojfa  aifjdfijenddfads';
preg_match_all('|anton(.*)end|U', $content, $out);
print_r($out);
if(empty($out[1])) echo '$out[1] is empty';
exit;

$arr = array(0=>'',1=>'');
print_r($arr);
if(empty($arr)) echo '$arr is empty';*/

date_default_timezone_set('Asia/Shanghai');
$fp = fopen("test.txt", "a+"); 
fwrite($fp, date("Y-m-d H:i:s") . " test\n"); 
fclose($fp);
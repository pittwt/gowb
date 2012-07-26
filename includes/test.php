<?php
/*$content = 'asdfadfantondjfjaojfa  aifjdfijenddfads';
preg_match_all('|anton(.*)end|U', $content, $out);
print_r($out);
if(empty($out[1])) echo '$out[1] is empty';
exit;

$arr = array(0=>'',1=>'');
print_r($arr);
if(empty($arr)) echo '$arr is empty';*/

print_r($argv);

date_default_timezone_set('Asia/Shanghai');
$fp = fopen("test.txt", "a+"); 
if(isset($_GET['str'])) {
	$str = $_GET['str'];
} else {
	$str = '';
}
fwrite($fp, date("Y-m-d H:i:s") . " test\n"); 
fclose($fp);


/*

D:\>D:\wamp\www\gowb\php\php5.3.13\php.exe -r 'var_dump($argv);' -- -h
PHP Parse error:  syntax error, unexpected $end in Command line code on line 1

Parse error: syntax error, unexpected $end in Command line code on line 1

32 35
29 5 10 33 34
5 8
D:\>
asdf
*/
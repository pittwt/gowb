<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
//error_reporting(0); 
require_once('dbconf.php');
require_once('mysql.class.php');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');
//$sql = "select * from `$t_data_top_hourly` where `source_id` = 241";
$sql = "select * from `$t_data_top_hourly` where `key_words` = '包子西施网络走红'";
echo $sql;
$data = $db->findall($sql);

foreach($data as $value){
	echo "keywords:". $value['key_words'] .", number:". $value['number'] .", time:". date("Y-m-d H:i:s", $value['add_time']) ."<br>";
}
echo "<pre>";
print_r($data);
echo "</pre>";

?>
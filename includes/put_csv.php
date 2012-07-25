<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
set_time_limit(0);
//error_reporting(0); 
require_once('dbconf.php');
require_once('mysql.class.php');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

$sql = "select * from `$t_data_top_hourly` limit 0,3000";
$data = $db->findall($sql);

$items = array();
foreach($data as $key=>$value) {
	$items[$value['source_id']][] = $value;
}
//print_r($items);exit;
$content = '';
foreach($items as $item) {
	$content .= date('Y-m-d H:i:s',$item[0]['add_time']).',';
	foreach($item as $value) {
		$content .= $value['key_words'].'('.$value['number'].')'.',';
	}
	$content .= "\n";
}
echo $content;
file_put_contents('sina_data.csv',$content);













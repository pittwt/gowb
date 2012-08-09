<?php
header("Content-type: text/html; charset=utf-8");
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
require_once("includes/Spider.class.php");
require_once('includes/Crons.class.php');
require_once('includes/common.function.php');
set_time_limit(0);

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

/*//获取每天的热词
$sql = "select * from `$t_data_top_hourly` where `key_words` = '爱情公寓3'";
$list = $db->findall($sql);
//按时间排序
print_r($list);exit;
$list = array_sort($list, 'add_time');

//获取每小时的热词统计结果
$statistic = get_statistic($list);
if($statistic){
	foreach ($statistic as $value) {
		$db->insert($t_topwords_statistic, $value);
	}	
}
print_r($statistic);

exit;*/

$sql = "select * from `$t_data_top_url` where `groups` = 'key'";
$tables = $db->findall($sql);
//print_r($tables);exit;
$statistic = array();
$i=1;
if(!empty($tables)) {
	foreach ($tables as $value) {
		//获取热词
		$sql = "select * from `". $db_prefix . $value['table'] ."` order by key_words desc";
		$list = $db->findall($sql);
//print_r($list);exit;
		//按关键词分组
		$list = array_order($list, 'key_words');
		if(!empty($list)) {
			foreach ($list as $items) {
				//print_r($items);
				//echo ++$i."**".key($items);
				//if($i > 200) break;
				$items = array_sort($items, 'add_time');
				$result = get_statistic($items);
				if(!empty($result)) {
					//echo "  =>".$i;
					$statistic[] = $result;
				}
				//echo "<br>";
			}
		}
	}
}

//print_r($statistic);
//exit;
if($statistic){
	foreach ($statistic as $items) {
		//print_r($items);
		if(is_array($items)){
			foreach ($items as $value) {
				print_r($value);
				//写入每天的热词
				$db->insert($t_topwords_statistic, $value);
			}
		}
	}
}
























?>
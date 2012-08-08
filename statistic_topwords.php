<?php
header("Content-type: text/html; charset=utf-8");
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
require_once("includes/Spider.class.php");
require_once('includes/Crons.class.php');
require_once('includes/common.function.php');
set_time_limit(0);

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

//获取每天的热词
$sql = "select * from `$t_data_top_hourly` where `key_words` = '爱情公寓3'";
$list = $db->findall($sql);
//按时间排序
$list = array_sort($list, 'add_time');
$array_one = array_slice($list, 0, 1);
$array_end = end($list);
print_r($array_end);

$start_time = $array_one[0]['add_time'];
$hour_start = hour_start($start_time); 
$hour_end = hour_end($start_time);
$hour_list = array();
$array = array(
	'min_number' => 100000,
	'max_number' => 0,
	'min_time' => 0,
	'max_time' => 0,
	'count' => 1,
);
$statistic = $array;
foreach ($list as $value) {
	$times = 0;
	
	//计算每小时 
	if($value['add_time']>=$hour_start && $value['add_time']<=$hour_end) {
		//最小次数   最小次数的时间小于最大次数的时间
		if($value['number'] < $statistic['min_number'] /*&& $statistic['min_time'] <= $statistic['max_time']*/) {
			$statistic['min_number'] = $value['number'];
			$statistic['min_time'] = $value['add_time'];
		}
		//最大次数
		if($value['number'] > $statistic['max_number']) {
			$statistic['max_number'] = $value['number'];
			$statistic['max_time'] = $value['add_time'];
		}
		echo date("Y-m-d H:i:s", $value['add_time'])."<br>";
		//次数
		$statistic['count']++;
		
	} else {
		echo "<hr>";
		$hour_start += 3600;
		$hour_end += 3600;
		$statistic['key_words'] = $value['key_words'];
		$statistic['task_id'] = 1;
		$statistic['type'] = 0;
		$statistic['add_time'] = time();
		$statistic['up_value'] = $statistic['max_number']-$statistic['min_number'];
		if($statistic['max_time'] <= $statistic['min_time']) {
			$statistic = $array;
			continue;
		} else {
			$db->insert($t_topwords_statistic,$statistic);
			$statistic = $array;
		}
		
	}
}


















?>
<?php
header("Content-type: text/html; charset=utf-8");
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
require_once("includes/Spider.class.php");
require_once('includes/Crons.class.php');
require_once('includes/common.function.php');
set_time_limit(0);

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

//test start
//获取每天的热词
/*$sql = "select * from `$t_data_top_hourly` where `key_words` = '宋承宪'";
echo $sql;
$list = $db->findall($sql);
//按时间排序
$list = array_sort($list, 'add_time');
//print_r($list);
//获取每小时的热词统计结果
$statistic = get_statistic($list);
//if($statistic){
//	foreach ($statistic as $value) {
//		$db->insert($t_statistic_topwords, $value);
//	}	
//}
print_r($statistic);
exit;*/
//test end
/*
//获取所有热词任务
$sql = "select * from `$t_data_top_url` where `groups` = 'key'";
$tables = $db->findall($sql);
//print_r($tables);exit;
$statistic = array();
$ids = array();
$i=0;
//统计每天的数据
if(!empty($tables)) {
	foreach ($tables as $value) {
		//获取热词
		$sql = "select * from `". $db_prefix . $value['table'] ."` where `stats` = 0 order by key_words desc";
		$list = $db->findall($sql);
		
		$ids[$i]['table'] = $db_prefix . $value['table'];
		$ids[$i]['id'] = '';
		//按关键词分组
		$list = array_order($list, 'key_words');
		if(!empty($list)) {
			foreach ($list as $items) {
				$items = array_sort($items, 'add_time');
				//获取统计每天的数据
				$result = get_statistic($items, $value['id'], 0);
				if(!empty($result['result'])) {
					$statistic[] = $result['result'];
				}
				if(!empty($result['id'])) {
					$ids[$i]['id'] .= $result['id'];
				}
			}
		}
		$i++;
	}
}

//写入每天的统计数据
if($statistic){
	foreach ($statistic as $items) {
		if(is_array($items)){
			foreach ($items as $value) {
				//写入每天的热词
				$db->insert($t_statistic_topwords, $value);
			}
		}
	}
}

//更新统计状态
foreach ($ids as $value) {
	if(!empty($value['id'])){
		$sql = "update ".$value['table']." set stats = 1 where id in (".substr($value['id'], 0, -1).")";
		if($db->query($sql)) {
			echo "update succeed<br>";
		}
	}
	
}*/

//统计每周的数据
$sql = "select * from `$t_statistic_topwords` where `type` = 0 and weekly_stats = 0";
$week = $db->findall($sql);
//按关键词分组
$week = array_order($week, 'key_words');
if(!empty($week)) {
	foreach ($week as $items) {
		
	}
}


//统计每月的数据























?>
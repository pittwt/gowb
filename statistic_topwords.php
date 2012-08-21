<?php
header("Content-type: text/html; charset=utf-8");
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
require_once("includes/Spider.class.php");
require_once('includes/Crons.class.php');
require_once('includes/common.function.php');
set_time_limit(0);
ini_set ('memory_limit', '256M');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

//test start //////////////////////////////////////////////////////
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
//test end /////////////////////////////////////////////////////////

//获取每天的统计时间
$sql = "select * from `$t_statistic_task` where type = 0";
$days = $db->findone($sql);

if($days['nextrun'] < time()) {
	
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
		
	}
	
	//更新下次执行时间
	$now = time();
	$nexttime = get_timestamp($now, 0, 1);
	$sql = "update `$t_statistic_task` set nextrun = ".$nexttime[0].", lastrun = ". $now ." where id = ".$days['id'];
	$db->query($sql);
}

//统计每周 每月的数据
$mandw = array();
$sql = "select * from `$t_statistic_task` where type in (1, 2) and nextrun <=".time()." order by type asc";
$rows = $db->findall($sql);

foreach ($rows as $row) {
	
	if($row['type'] == 1) {
		$sql = "select * from `$t_statistic_topwords` where `type` = 0 and weekly_stats = 0 and min_time < '1345334400'";
	} elseif ($row['type'] == 2) {
		$sql = "select * from `$t_statistic_topwords` where `type` = 0 and monthly_stats = 0";
	}
	
	$week = $db->findall($sql);
//print_r($week);exit;
	/*$sql = "select * from `$t_statistic_topwords` where `type` = 0 and weekly_stats = 0";
	$week = $db->findall($sql);*/
	
	//按关键词分组
	$week = array_order($week, 'key_words');
	//print_r($week);
	$week_statistic = array();
	$week_ids = '';
	if(!empty($week)) {
		foreach ($week as $items) {
			$result = get_statistics($items, 0, $row['type']);
			if(!empty($result['result'])) {
				$week_statistic[] = $result['result'];
			}
			if(!empty($result['id'])) {
				$week_ids .= $result['id'];
			}
		}
	}

	//写入数据
	foreach ($week_statistic as $value) {
		if(is_array($value)) {
			foreach ($value as $val) {
				//print_r($val);
				//写入热词
				$db->insert($t_statistic_topwords, $val);
			}
		}
	}
	
	//更新状态
	if(!empty($week_ids)) {
		if($row['type'] == 1) {
			$sql = "update ".$t_statistic_topwords." set weekly_stats = 1 where id in (".substr($week_ids, 0, -1).")";
		} elseif ($row['type'] == 2) {
			$sql = "update ".$t_statistic_topwords." set monthly_stats = 1 where id in (".substr($week_ids, 0, -1).")";
		}
		if($db->query($sql)) {
			echo $row['type']." update succeed<br>";
		}
	}
	
	//更新下次执行时间
	$now = time();
	$nexttime = get_timestamp($now, $row['type'], 1);
	$sql = "update `$t_statistic_task` set nextrun = ".$nexttime[0].", lastrun = $now where id = ".$row['id'];
	$db->query($sql);
	
}




















?>
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
	$sql = "select * from `$t_data_top_url` where `groups` = 'key' order by type asc";
	$tables = $db->findall($sql);
	
	$statistic = array();
	$ids = array();
	$i=0;
	//统计每天的数据
	if(!empty($tables)) {
		foreach ($tables as $value) {
			$countsql = "select count(*) from `". $db_prefix . $value['table'] ."` where `stats` = 0";
			$count = $db->findone($countsql);
			if($count[0] > 100000) {
				//一次未执行完
				$_SESSION['exe_status'] = true;
				//获取最小时间
				$sql = "SELECT MIN(add_time) AS mintime FROM `". $db_prefix . $value['table'] ."` WHERE `stats` = 0";
				$ttimes = $db->findone($sql);
				//数据过多时  一次执行7天
				$mintime = get_timestamp($ttimes['mintime'],0,7);
				$where = " and add_time < ".$mintime[0];
			} else {
				$_SESSION['exe_status'] = false;
				$where = "";
			}
			//获取热词
			$sql = "select * from `". $db_prefix . $value['table'] ."` where `stats` = 0".$where;
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
	//一次问执行完   下次间隔5分钟    之行完下次间隔一天
	if(isset($_SESSION['exe_status']) && $_SESSION['exe_status']){
		$nexttime = $now + 300;
	} else {
		$nexttime = get_timestamp($now, 0, 1);
	}
	
	$sql = "update `$t_statistic_task` set nextrun = ".$nexttime[0].", lastrun = ". $now ." where id = ".$days['id'];
	$db->query($sql);
	unset($_SESSION['exe_status']);
}

//统计每周 每月的数据
$mandw = array();
$sql = "select * from `$t_statistic_task` where type in (1, 2) and nextrun <=".time()." order by type asc";
$rows = $db->findall($sql);

foreach ($rows as $row) {
	
	if($row['type'] == 1) {
		$sql = "select * from `$t_statistic_topwords` where `type` = 0 and weekly_stats = 0";
		$countsql = "select count(*) from `$t_statistic_topwords` where `type` = 0 and weekly_stats = 0";
	} elseif ($row['type'] == 2) {
		$sql = "select * from `$t_statistic_topwords` where `type` = 0 and monthly_stats = 0";
		$countsql = "select count(*) from `$t_statistic_topwords` where `type` = 0 and monthly_stats = 0";
	}
	$count = $db->findone($countsql);
	//数据过多时 每次执行200个词
	if($count[0] > 300){
		$_SESSION['exe_status'] = true;
		$keysql = "select key_words from `$t_statistic_topwords` where `type` = 0 and monthly_stats = 0 group by key_words limit 200";
		$key_list = $db->findall($keysql);
		$sql .= " and key_words in (".get_keylist($key_list).")";
	}
	
	
	$week = $db->findall($sql);
	//按关键词分组
	$week = array_order($week, 'key_words');

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
	//一次问执行完   下次间隔5分钟    之行完下次间隔一天
	if(isset($_SESSION['exe_status']) && $_SESSION['exe_status']){
		$nexttime = $now + 900;
	} else {
		$nexttime = get_timestamp($now, $row['type'], 1);
	}
	
	$sql = "update `$t_statistic_task` set nextrun = ".$nexttime[0].", lastrun = $now where id = ".$row['id'];
	$db->query($sql);
	
}




















?>
<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
//error_reporting(0); 
set_time_limit(0);
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
require_once('includes/Spider.class.php');
require_once('includes/Crons.class.php');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');
$crons = new Crons();

//获取需要执行url
$sql = "select * from `$t_data_top_url` where `groups` = 'key' and `status` = 1 order by id";
$data = $db->findall($sql);
$list = $crons->getRunlist($data, time());

//错误信息
$error_info = '';

if(!empty($list)) {
	$spider = new Spider();
	foreach($list as $item) {
		//日志信息
		$log_info = '';
		
		$url = $item['url'];
		
		//获取页面数据
		$spider->setUrl($url);
		$data = $spider->openUrl();
		$data = $spider->getData($data, '<table cellspacing="0" cellpadding="0" class="box_Show_z box_zs"', '</table>');
					
		if($data){
			$keyWords = $spider->getTextDataAll($data, '<span class=\"zw_topic\"><[^>]+>', '</[^>]+></span>');
			$number = $spider->getTextDataAll($data, '<span class="times_zw">', '</span>');
		
			if(empty($keyWords)){
				$error_info .= '获取关键词错误， ';
			}
			if(empty($number)){
				$error_info .= '获取关键词出现次数错误， ';
			}
		
			//添加数据来源
			$source = array(
				'origin' => $url,
				'version' => $spider->version,
				'html_source' => str_replace("'",'"',$data),
				'add_time' => time()
			);
			$source_id = 0;
			if($source_id = $db->insert($t_data_top_source, $source)){
				$log_info .= $t_data_top_source.', ';
			}else{
				//echo '添加来源错误， ';
				$error_info .= '添加来源错误， ';
			}
			
			//添加采集数据
			$items = array();
			$num = 0;
			$table = $db_prefix . $item['table'];
			foreach($keyWords as $key=>$value){
				$items['source_id'] = $source_id;
				$items['key_words'] = $value;
				$items['number'] = $number[$key];
				$items['add_time'] = time();
				if($db->insert($table,$items)){
					$num++;
				}
			}
			if($num>0){
				$log_info .= $table . "($num)columns, ";
			}else{
				echo '写入数据错误';
				$error_info .= '写入数据错误， ';
			}
			
			//添加日志
			if(!empty($log_info)){
				$log['log_info'] = 'insert '. $log_info .' info';
				$log['log_time'] = date("Y-m-d H:i:s");
				$db->insert($t_data_log, $log);
			}
			
		}else{
			$error_info .= '获取实时关键词数据错误， ';
		}
		
		//更新执行时间
		if($item['type'] != 1){
			$now = time();
			$nextrun = $crons->nextRuntime($item['week'], $item['day'], $item['hour'], $item['minute'], $now);
			$items = array(
				'lastrun' => $now,
				'nextrun' => $nextrun,
			);
			$condition = " id = ". $item['id'];
			$db->update($t_data_top_url, $items, $condition);
		}
	}
		
} else {
	$error_info .= '获取执行列表错误， ';
}
echo "<br>error info:".$error_info."<br>";
	
//写入错误日志
if(!empty($error_info)){
	$error = array(
		'error_info' => $error_info,
		'status' => 0,
		'add_time' => date("Y-m-d H:i:s")
	);
	$id = $db->insert($t_error_log, $error);
}
?>

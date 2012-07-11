<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
//error_reporting(0); 
require_once('mysql.class.php');
require_once('dbconf.php');
require_once('spider2.php');
date_default_timezone_set('Asia/Shanghai');

/*
 *	spider weibo version
 */ 
$version = 'spider weibo 1.0';

/*
 * 定义表
 */
$t_data_log = "add_data_log";
$t_data_top_hourly = "data_top_hourly";
$t_data_top_source = "data_top_source";
$t_error_log = "error_data_log";


$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');


$spider2 = new spider2();
$url = 'http://data.weibo.com/top/keyword?k=hour';
//$url = 'http://baidu.com';
$data = $spider2->openUrl($url);
$data = $spider2->getData($data, '<table cellspacing="0" cellpadding="0" class="box_Show_z box_zs">', '</table>');

//错误信息
$error_info = '';

if($data){
	$keyWords = $spider2->getTextDataAll($data, '<span class=\"zw_topic\"><[^>]+>', '</[^>]+></span>');
	$number = $spider2->getTextDataAll($data, '<span class="times_zw">', '</span>');
	
	if(empty($keyWords)){
		$error_info .= '获取关键词错误， ';
	}
	if(empty($number)){
		$error_info .= '获取关键词出现次数错误， ';
	}
	
	//日志信息
	$log_info = '';

	
	//添加数据来源
	$source = array(
		'origin' => $url,
		'version' => $version,
		'html_source' => str_replace("'",'"',$data),
		'add_time' => time()
	);
	$source_id = 0;
	if($source_id = $db->insert($t_data_top_source, $source)){
		$log_info .= $t_data_log.', ';
	}else{
		echo '添加来源错误， ';
		$error_info .= '添加来源错误， ';
	}
	
	//添加采集数据
	$items = array();
	$table = 'data_top_hourly';
	$num = 0;
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
		$log_info .= $t_data_top_hourly . "($num)columns, ";
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
//echo "<br>error info:".$error_info."<br>";

//写入错误日志
if(!empty($error_info)){
	echo 'errot';
	$error = array(
		'error_info' => $error_info,
		'status' => 0,
		'add_time' => date("Y-m-d H:i:s")
	);
	$id = $db->insert($t_error_log, $error);
}


?>

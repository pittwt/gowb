<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
 
require_once('mysql.class.php');
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
$t_data_top_source ="data_top_source";


$db = new mysql('localhost', 'root', 'root', 'sina_data', '', '');
mysql_query("set names 'utf8'");


$spider2 = new spider2();
$url = 'http://data.weibo.com/top/keyword?k=hour';
$data = $spider2->openUrl($url);
$data = $spider2->getData($data, '<table cellspacing="0" cellpadding="0" class="box_Show_z box_zs">', '</table>');

$keyWords = $spider2->getTextDataAll($data, '<span class=\"zw_topic\"><[^>]+>', '</[^>]+></span>');
$number = $spider2->getTextDataAll($data, '<span class="times_zw">', '</span>');

//日志信息
$log_info = '';

//添加数据来源
$source = array(
	'origin' => $url,
	'version' => $version,
	'html_source' => $data,
	'add_time' => time()
);
$source_id = 0;
if($source_id = $db->insert($t_data_top_source, $source)){
	$log_info .= $t_data_log.', ';
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
}


//添加日志
$log['log_info'] = 'insert '. $log_info .' info';
$log['log_time'] = date("Y-m-d H:i:s");
$db->insert('add_data_log', $log);


/*echo "<pre>";print_r($keyWords);print_r($number);echo "</pre>";exit;*/



?>

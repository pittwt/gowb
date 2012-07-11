<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
 
require_once('mysql.class.php');
date_default_timezone_set('Asia/Shanghai');

/*
 *	spider weibo version
 */ 
$version = 'spider weibo 1.0';

/*
 * 定义表
 */
$t_error_log = "error_data_log";


$db = new mysql('localhost', 'root', 'root', 'sina_data', '', '');
mysql_query("set names 'utf8'");

$sql = "select * from $t_error_log where status = 0 order by add_time desc";
$row = $db->findAll($sql);
//echo "<pre>";print_r($row);echo "</pre>";
foreach($row as $value){
	echo $value['error_info']." 时间：".$value['add_time']."<br>";
}


?>

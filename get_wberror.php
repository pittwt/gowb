<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
require_once('dbconf.php');
require_once('mysql.class.php');
date_default_timezone_set('Asia/Shanghai');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

$sql = "select * from $t_error_log where status = 0 order by add_time desc";
$row = $db->findAll($sql);
//echo "<pre>";print_r($row);echo "</pre>";
foreach($row as $value){
	header("HTTP/1.0 404 Not Found");
}


?>

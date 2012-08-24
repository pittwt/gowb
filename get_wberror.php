<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
date_default_timezone_set('Asia/Shanghai');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

$sql = "select * from $t_error_log where status = 0 order by add_time desc";
$row = $db->findAll($sql);
//echo "<pre>";print_r($row);echo "</pre>";
if(!empty($row)) {
	//header("HTTP/1.0 404 Not Found");
	$script_name = $_SERVER['SCRIPT_NAME'];
    $html = '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
     	<html><head>
		<title>404 Not Found</title>
		</head><body>
		<h1>Not Found</h1>
		<p>The requested URL '. $script_name .' was not found on this server.</p>
		</body></html>';
	foreach ($row as $item) {
    	$sql = "update $t_error_log set status = 1, update_time = '". date("Y-m-d H:i:s",time()) ."' where id = ".$item['id'];
     	$db->query($sql);
    }
    header('HTTP/1.1 404 Not Found');
     
    exit($html);
} else {
	//echo date("Y-m-d H:i:s", 1293072805);
	echo "It Works";
}



?>

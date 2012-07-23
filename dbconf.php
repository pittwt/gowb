<?php
$host="localhost:3306";//mysql数据库服务器,比如localhost:3306
$user="root"; 		//mysql数据库默认用户名
$pwd="root"; 		//mysql数据库默认密码
$db="sina_data"; 	//默认数据库名

/*
 * 定义表
 */
$t_data_log = "add_data_log";
$t_data_top_hourly = "data_top_hourly";
$t_data_top_source = "data_top_source";
$t_error_log = "error_data_log";
$t_search_keywords = "search_keywords";
$t_search_keywords_wanhao = "search_keywords_wanhao";

date_default_timezone_set('Asia/Shanghai');

?>
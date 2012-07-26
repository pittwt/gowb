<?php

$host = "localhost:3306";//mysql数据库服务器,比如localhost:3306
$user = "root"; 		//mysql数据库默认用户名
$pwd = "root";		//mysql数据库默认密码
$db = "wbdata"; 	//默认数据库名
$db_prefix = "wb_"; //表前缀

/*
 * 定义表
 */
$t_data_log = $db_prefix . "add_data_log";
$t_data_top_hourly = $db_prefix . "data_top_hourly";
$t_data_top_source = $db_prefix . "data_top_source";
$t_data_top_url = $db_prefix . "data_top_url";
$t_data_top_tech = $db_prefix . "data_top_tech";
$t_error_log = $db_prefix . "error_data_log";
$t_search_keywords = $db_prefix . "search_keywords";
$t_search_keywords_url = $db_prefix . "search_keywords_url";
$t_search_keywords_wanhao = $db_prefix . "search_keywords_wanhao";

date_default_timezone_set('Asia/Shanghai');

?>
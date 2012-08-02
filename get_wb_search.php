<?php
header("Content-type: text/html; charset=utf-8");
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
require_once("includes/Spider.class.php");
require_once('includes/Crons.class.php');
set_time_limit(0);
//echo file_get_contents('http://s.weibo.com/weibo/0Q0&Refer=STopic_box');exit;


$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

//echo date('Y-m-d H:i:s', 1342669636);exit;
$search_words = urlencode(urlencode('万豪酒店'));
$url = 'http://s.weibo.com/weibo/'. $search_words .'&Refer=STopic_realtime';

//获取搜索任务
$sql = "select * from `$t_data_top_url` where `phpfile` = '". basename(__FILE__) ."' and `status` = 1 and nextrun <=".time();
$slist = $db->findall($sql);

$spider = new Spider();
if(!empty($slist)) {
	
	foreach($slist as $list) {
		//新浪微博搜索链接 
		$url = $list['url'].urlencode(urlencode($list['keywords']));
		$spider->setUrl($url);
		//搜索结果页数
		$pages = intval($spider->getSerachPagenum());

		for($i=1; $i<=$pages; $i++) {
			$items = array(
				'search_url' => $url."&page=".$i,
				'table'	=> $list['table'],
				'add_time' => time(),
				'status' => 0
			);
			//生成微博搜索 队列
			$db->insert($t_search_keywords_url,$items);
		}
		//更新下次执行时间
		$now = time();
		$nextrun = Crons::nextRuntime($list['week'], $list['day'], $list['hour'], $list['minute'], $now);
		$update = array(
				'lastrun' => $now,
				'nextrun' => $nextrun,
		);
		$condition = " id = ". $list['id'];
		$db->update($t_data_top_url, $update, $condition);
	}
}

//获取50条搜索地址
$sql = "SELECT * FROM `$t_search_keywords_url` WHERE status = 0 ORDER BY add_time ASC, id ASC LIMIT 0, 2";
$clist = $db->findall($sql);

$insert_list = array();
foreach($clist as $item) {
	$spider->setUrl($item['search_url']);
	$Alldata = $spider->getSearchWeiboAll();
	//查看获取的微博数据是否已存在
	foreach ($Alldata as $value) {
		$sql = "SELECT * FROM `". $db_prefix . $item['table'] ."` WHERE username = '". $value['username'] ."' AND weibo_time = ".$value['weibo_time'];
		$row = $db->findall($sql);
		if(empty($row)) {
			$value['table'] = $db_prefix . $item['table'];
			$insert_list[] = $value;
		}
	}
}

//写入搜索微博数据
foreach ($insert_list as $value) {
	$items = array(
		'weibo_username'	=> $value['username'],
		'weibo_content'	=> $value['weibo_content'],
		'weibo_time'	=> $value['weibo_time'],
		'forward_num'	=> $value['forward_num'],
		'comment_num'	=> $value['comment_num'],
		'weibo_thumbimg'	=> $value['weibo_thumbimg'],
		'weibo_middleimg'	=> $value['weibo_middleimg'],
		'weibo_largeimg'	=> $value['weibo_largeimg'],
	);
	if($id = $db->insert($value['table'],$items))
		echo $id."<br>";
}


exit;





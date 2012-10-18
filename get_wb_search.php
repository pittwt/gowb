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
//test start
/*$search_words = urlencode(urlencode('万豪酒店'));
$url = 'http://s.weibo.com/weibo/'. $search_words .'&Refer=STopic_realtime';
echo $url;
$spider = new Spider();
$spider->setUrl($url);
$Alldata = $spider->getSearchWeiboAll();
print_r($Alldata);
exit;*/
//test end

//获取搜索任务
$sql = "select * from `$t_data_top_url` where `phpfile` = '". basename(__FILE__) ."' and `status` = 1 and nextrun <=".time();
$slist = $db->findall($sql);
/*echo $sql;
print_r($slist);
exit;*/
$spider = new Spider();
if(!empty($slist)) {
	foreach($slist as $list) {
		//新浪微博搜索链接 
		$url = $list['url'].urlencode(urlencode('"'.$list['keywords'].'"'));
		$spider->setUrl($url);
		//搜索结果页数
		$pages = intval($spider->getSerachPagenum());
		
		
		//$insert = array();
		for($i=1; $i<=$pages; $i++) {
			$items = array(
				'search_url' => $url."&page=".$i,
				'task_id' => $list['id'],
				'table'	=> $list['table'],
				'add_time' => time(),
				'status' => 0
			);
			//生成微博搜索 队列
			$db->insert($t_search_keywords_url,$items);
		}
		//更新下次执行时间 $pages<0搜索过多时新浪机器人检测
		if($pages > 0){
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
}

//获取20条搜索地址
$sql = "SELECT * FROM `$t_search_keywords_url` WHERE status = 0 ORDER BY add_time ASC, id ASC LIMIT 0, 20";
$clist = $db->findall($sql);

$insert_list = array();
$num_list = array();
$s_empty = false;
foreach($clist as $item) {
	$spider->setUrl($item['search_url']);
	$Alldata = $spider->getSearchWeiboAll();
	//s_empty 新浪是否机器人检测
	if(empty($Alldata)){
		$s_empty = true;
	}
	//查看获取的微博数据是否已存在
	foreach ($Alldata as $value) {
		$sql = "SELECT * FROM `". $db_prefix . $item['table'] ."` WHERE weibo_username = '". $value['username'] ."' AND weibo_time = ".$value['weibo_time'];
		$row = $db->findall($sql);
		//echo $sql."<be>";
		if(empty($row)) {
			$value['table'] = $db_prefix . $item['table'];
			$insert_list[] = $value;
		} else {
			//更新评论数 转发数
			$value['keywords_id'] = $row[0]['keywords_id'];
			$value['table'] = $db_prefix . $item['table'];
			$num_list[] = $value;
		}
	}
}

/*print_r($insert_list);
print_r($num_list);
exit;*/

//写入搜索微博数据
if(!empty($insert_list)) {
	foreach ($insert_list as $value) {
		$items = array(
			'weibo_username'	=> $value['username'],
			'weibo_content'		=> $value['weibo_content'],
			'weibo_time'		=> $value['weibo_time'],
			'is_verify'			=> $value['is_verify'],
			'weibo_thumbimg'	=> $value['weibo_thumbimg'],
			'weibo_middleimg'	=> $value['weibo_middleimg'],
			'weibo_largeimg'	=> $value['weibo_largeimg'],
		);
		$id = $db->insert($value['table'], $items);
		//if($id = $db->insert($value['table'], $items))
		//	echo $id."<br>";
		//评论数 转发数
		if($id) {
			$num  = array(
				'keywords_id'	=> $id,
				'forward_num'	=> $value['forward_num'],
				'comment_num'	=> $value['comment_num'],
				'update_time'	=> date("Y-m-d H:i:s", time())
			);
			$db->insert($value['table']."_num", $num);
		}
	}
}

//已经存在的数据 更新评论数 转发数
if(!empty($num_list)) {
	foreach ($num_list as $value) {
		$num  = array(
			'keywords_id'	=> $value['keywords_id'],
			'forward_num'	=> $value['forward_num'],
			'comment_num'	=> $value['comment_num'],
			'update_time'	=> date("Y-m-d H:i:s", time())
		);
		if($db->insert($value['table']."_num", $num))
			echo "更新num成功<br>";
	}
}


//print_r($clist);
//更新搜索地址状态
if(!$s_empty){
	$time = date("Y-m-d H:i:s", time());
	foreach ($clist as $value) {
		$sql = "update `$t_search_keywords_url` set run_time = '". $time ."', status = 1 where id = ". $value['id'];
		$db->query($sql);
	}
}





<?php
header("Content-type: text/html; charset=utf-8");
require_once('dbconf.php');
require_once('mysql.class.php');
require_once("spider.php");
set_time_limit(0);
//echo date('Y-m-d H:i:s', 1342669636);exit;
$search_words = urlencode(urlencode('万豪酒店'));
$url = 'http://s.weibo.com/weibo/'. $search_words .'&Refer=STopic_realtime';


$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

$spider = new spider($url);
//print_r($content = $spider->openUrl($url));exit;

echo $spider->getSearchNumbers();

$Alldata = $spider->getSearchWeiboAll();
echo $nums = count($Alldata);
foreach ($Alldata as $value) {
	$sql = "SELECT * FROM `$t_search_keywords_wanhao` WHERE username = '". $value['username'] ."' AND weibo_time = ".$value['weibo_time'];
	$row = $db->findall($sql);
	if(empty($row)) {
		$items = array(
			'username'	=> $value['username'],
			'weibo_content'	=> $value['weibo_content'],
			'weibo_time'	=> $value['weibo_time'],
			'forward_num'	=> $value['forward_num'],
			'comment_num'	=> $value['comment_num'],
			'weibo_thumbimg'	=> $value['weibo_thumbimg'],
			'weibo_middleimg'	=> $value['weibo_middleimg'],
			'weibo_largeimg'	=> $value['weibo_largeimg'],
		);
		if($id = $db->insert($t_search_keywords_wanhao,$items))
			echo $id."<br>";
	} else {
		echo "该数据已存在！<br>";
	}
	
}



//echo $num = $spider->getSerachPagenum($content);
echo "<br>";
//$content = $spider->getSweibo($content);


//print_r($spider->getSearchWeibo($content));
//echo $content;
//file_put_contents('zz.txt', $content);



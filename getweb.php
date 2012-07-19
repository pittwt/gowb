<?php
header("Content-type: text/html; charset=utf-8");
require_once('dbconf.php');
require_once("spider.php");
set_time_limit(0);
//echo date('Y-m-d H:i:s', 1342669636);exit;
$search_words = urlencode(urlencode('万豪酒店'));
$url = 'http://s.weibo.com/weibo/'. $search_words .'&Refer=STopic_realtime';
//echo $url.'<br>';
$spider = new spider($url);
//$content = $spider->openUrl($url);
echo $spider->getSearchNumbers();exit;

print_r($spider->getSearchWeiboAll());



//echo $num = $spider->getSerachPagenum($content);
echo "<br>";
//$content = $spider->getSweibo($content);


//print_r($spider->getSearchWeibo($content));
//echo $content;
//file_put_contents('zz.txt', $content);



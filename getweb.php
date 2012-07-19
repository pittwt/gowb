<?php
header("Content-type: text/html; charset=utf-8");
require_once('dbconf.php');
require_once("spider.php");
//echo date('Y-m-d H:i:s', 1342669636);exit;
$search_words = urlencode(urlencode('万豪酒店'));
$url = 'http://s.weibo.com/weibo/'. $search_words .'&Refer=STopic_realtime&page=13';
echo $url;
$spider = new spider($url);

$content = $spider->openUrl($url);


$content = $spider->getSweibo($content);


print_r($spider->getSearchWeibo($content));
//echo $content;
//file_put_contents('zz.txt', $content);



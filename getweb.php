<?php
header("Content-type: text/html; charset=utf-8");
require_once("spider.php");

$url = 'http://s.weibo.com/weibo/%25E4%25B8%2587%25E8%25B1%25AA%25E9%2585%2592%25E5%25BA%2597&xsort=hot&Refer=STopic_box';
$spider = new spider($url);

//$content = $spider2->openUrl($url);


$content = $spider2->getSweibo($content);


echo $content;
//file_put_contents('zz.txt', $content);



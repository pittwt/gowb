<?php 
header("Content-type: text/html; charset=utf-8");
set_time_limit(0);
//echo date('Y-m-d H:i:s', 1342669636);exit;
$search_words = urlencode(urlencode('万豪酒店'));
$url = 'http://s.weibo.com/weibo/%25E4%25B8%2587%25E8%25B1%25AA%25E9%2585%2592%25E5%25BA%2597&Refer=STopic_realtime';
echo $url."<P>";
//print_r($_SERVER);
$setting_array= array(
'http' => array(
   'timeout' => 5,
   'method' => 'GET',
   'protocol_version'=>'1.1',
   'header' =>
            "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1\r\n" .
            //"Referer: http://s.weibo.com\r\n".//浏览器访问过的,上一个页面的整个url地址字符串,直接在地址栏输入url访问此页面则没有此项
            "Host: s.weibo.com\r\n" .//这项可以省略，如果这里设置错误会报错：failed to open stream: HTTP request failed! 
            "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3\r\n" .
            "Accept-Encoding: gzip, deflate\r\n" .
            "Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n" .
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
            "Connection: keep-alive\r\n" .
			"Accept-Cache_Control: max-age=0\r\n"
)
);
//$url= 'http://test135.info/test/x.php';
$get_header= file_get_contents($url);
print_r($get_header);




?>
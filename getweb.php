<?php
header("Content-type: text/html; charset=utf-8");
$content = file_get_contents("http://s.weibo.com/weibo/%25E9%2587%2591%25E7%2589%259B%25E5%25BA%25A7&xsort=hot&Refer=STopic_box");
echo $content;

//$content = '$d=[{"_id":{"$id":"4fda7d42741d727c14000000"},"name":"\u519c\u592b\u5c71\u6cc9","bc":"123456","pic":"d: pic\water.jpg","aid":"232fd4df3"}]$c=[{"_id":{"$id":"4fdaa7f3741d725816000000"},"bc":"012345678","name":"\u7ef4\u8fbe\u7eb8\u5dfe","cls":{"id":"125","name":"\u65e5\u7528\u54c1"},"std":{"name":"\u5f20\u6570","val":"10"}}]';
$content = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4','\\1'))", $content);
//$content = uni_decode($content);

file_put_contents('zz.txt', stripslashes($content));

function unescape($str){
	$str = rawurldecode($str);
	preg_match_all("/&#(d+);/U",$str,$r);
	$arr = $r[1];
	$cstr = array();
	foreach($arr as $number){
		$cstr[] = iconv("UCS-2","GBK",pack("n",$number));
	}
	return join("",$cstr);
} 


function uni_encode ($str, $code = 'utf-8')
{
    if($code != 'utf-8')
    { 
        $str = iconv($code, 'utf-8', $str); 
    }
    $str = json_encode($str);
    $str = preg_replace_callback('/\\\\u(\w)/', create_function('$hex', 'return \'&#\'.hexdec($hex[1]).\';\';'), substr($str, 1, strlen($str)-2));
    return $str;
}



function uni_decode ($str, $code = 'utf-8')
{
    $str = json_decode(preg_replace_callback('/&#(\d);/', create_function('$dec', 'return \'\\u\'.dechex($dec[1]);'), '"'.$str.'"'));
    if($code != 'utf-8')
    { 
        $str = iconv('utf-8', $code, $str); 
    }
    return $str;
}
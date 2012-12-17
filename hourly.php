<?php 
define('Rclub_WEBSERVICE_URL','test.rclub-china.com');
$server_port = $_SERVER['SERVER_PORT'] != '80' ? ':'.$_SERVER['SERVER_PORT'] :'';
$sprefix = 'http://'.Rclub_WEBSERVICE_URL .$server_port;
echo $sprefix;
echo "<br>basename=".basename($_SERVER['REQUEST_URI'])."<br>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

/**
 * 菱形
 * 		********
 * 	   ********
 * 	  ********
 *   ********
 */
$rows=15;
$nums=6;
for($i=0; $i<$nums; $i++) {
	for($n=0; $n<$i; $n++) {
		echo "&nbsp;&nbsp;";
	}
	for($j=0; $j<$rows; $j++) {
		echo "*";
	}
	
	echo "<br>";
}
echo "<p>";
for ($i=$nums; $i>0; $i--) {
	for($n=$i; $n>0; $n--){
		echo "&nbsp;&nbsp;";
	}
	
	for($j=0; $j<$rows; $j++) {
		echo "*";
	}
	echo "<br>";
}

exit;


/**
 * 冒泡法
 */

function BubbleSort($str){
	for($i=0;$i<count($str);$i++){//从数组末尾取一个值；
		for ($k=count($str)-2;$k>=$i;$k--){//将这个值向前冒泡；
			if($str[$k+1]<$str[$k]){  //将小于号改为大于号，就是降序排列；
				$tmp=$str[$k+1];
				$str[$k+1]=$str[$k];
				$str[$k]=$tmp;
			}
		}
	}
	return $str;
}
function bubble_end($arr) {
	$count = count($arr);
	for($i=0; $i<$count-1; $i++) {
		for($j=$count-1; $j>$i; $j--) {
			if($arr[$j] < $arr[$j-1]) {
				$tmp = $arr[$j];
				$arr[$j] = $arr[$j-1];
				$arr[$j-1] = $tmp;
			}
		}
	}
	return $arr;
}
$a = 2;
$b = 3;
echo "a=".$a.", b=".$b."<br>";
$a = $a + $b;
$b = $a - $b;
$a = $a - $b;
echo "a=".$a.", b=".$b;
function bubble_front($arr) {
	$count = count($arr);
	for($i=0; $i<$count-1; $i++) {
		for($j=0; $j<$count-1-$i; $j++) {
			if($arr[$j] > $arr[$j+1]){
				$arr[$j] = $arr[$j] + $arr[$j+1];
				$arr[$j+1] = $arr[$j] - $arr[$j+1];
				$arr[$j] = $arr[$j] - $arr[$j+1];
			}
		}
	}
	return $arr;
}

//以下是测试
$str=array(12,5,8,2,6,10,11,3,0);
echo "<pre>";
print_r(bubble_end($str));
print_r(bubble_front($str));
echo "</pre>";

exit;
header("Content-type: text/html; charset=utf-8");
require_once('includes/dbconf.php');
require_once('includes/mysql.class.php');
require_once('includes/common.function.php');
set_time_limit(0);
ini_set ('memory_limit', '256M');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

$sql = "CREATE TABLE IF NOT EXISTS `wb_search_keywords_zz` (
  `keywords_id` int(10) NOT NULL AUTO_INCREMENT,
  `weibo_username` varchar(255) NOT NULL COMMENT '微博用户名称',
  `weibo_content` text NOT NULL COMMENT '微博内容',
  `weibo_time` int(10) DEFAULT NULL COMMENT '发微薄时间',
  `forward_num` int(10) DEFAULT NULL COMMENT '转发次数',
  `comment_num` int(10) DEFAULT NULL COMMENT '评论数',
  `weibo_thumbimg` varchar(255) DEFAULT NULL COMMENT '微博图片',
  `weibo_middleimg` varchar(255) DEFAULT NULL,
  `weibo_largeimg` varchar(255) DEFAULT NULL,
  `tag_id` varchar(255) DEFAULT NULL COMMENT '标签id 逗号分隔',
  PRIMARY KEY (`keywords_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";

//$db->query($sql);

echo urlencode(urlencode('"新加坡机场"'));

exit;

echo strtotime('2012-08-01')."<br>";
echo date("Y-m-d H:i:s",1345651200)."<br>";
echo date("Y-m-d H:i:s",1345910400)."<br>";
//echo date("Y-m-d H:i:s","1341763200")."<br>";
//echo date("Y-m-d H:i:s","1342713599")."<br>";


echo date("Y-m-d H:i:s",strtotime(20120820))."<br>";

exit;


$handle=fopen('data_top_hourly.csv','r');

$data = explode("\n", file_get_contents('data_top_hourly.csv'));
$count = count($data);
echo $count."<br>";
$sql = "select count(*) from `wb_data_top_hourly2` where stats = 0";
$now = $db->findone($sql);
print_r($now)."<br>";
exit;
for($i=$now[0]; $i<$count; $i++) {
	$data[$i] = str_replace('"', '', $data[$i]);
	$info = explode(",", $data[$i]);
	$item = array(
		'source_id' => '0',
		'key_words' => $info[2],
		'number' => $info[3],
		'add_time' => $info[4],
		'stats' => '0'
	);
	$db->insert('wb_data_top_hourly2',$item);
/*if($db->insert('wb_data_top_hourly2',$item)) {
	echo "inserted<br>";
}
print_r($item);
exit;*/
	
}


exit;
echo strtotime('2012-08-19')."<br>";
echo date("Y-m-d H:i:s",1345910400)."<br>";

$array = array(
	'one' => 1,
	'two' => 2,
	'there' => 3
);
//echo current($array);echo end($array);exit;
$count = count($array);
for($i=0; $i<$count; $i++) {
    echo current($array)."++<br>";
    next($array);
    if(current($array) == 2){
    	$i--;
    	echo "**".$i."**<br>";
    	
    	next($array);prev($array);
    }
    
}
echo "<p>";
$i=0;
while ($i<5) {
	echo $i."<br>";
	$i++;
}



/*echo date("Y-m-d H:i:s", 1343903702)."<br>";
echo date("Y-m-d H:i:s", 1344486306)."<br>";
echo "<hr>";
$time = time();
echo $time."<br>";
echo date("Y-m-d H:i:s", $time)."<br>";
$result = get_timestamp($time, 2, 1);
echo date("Y-m-d H:i:s", $result[0])."<br>";
echo date("Y-m-d H:i:s", $result[1])."<br>";
print_r($result);*/





?>
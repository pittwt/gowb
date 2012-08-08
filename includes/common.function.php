<?php
//二维数组 排序 
function array_sort($arr,$keys,$type='asc'){ 
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$v[$keys]] = $v;
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	/*reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}*/
	return $keysvalue; 
} 

//获取当前时间整点开始  12:00:00
function hour_start($time) {
	$datetime = date("Y-m-d H:00:00", $time);
	return strtotime($datetime);
}

//获取当前时间整点开始  12:59:59
function hour_end($time) {
	$datetime = date("Y-m-d H:00:00", $time);
	return strtotime($datetime) + 3600 - 1;
}
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

//二维数组 排序 
function array_order($arr,$keys,$type='asc'){ 
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$v[$keys]][] = $v;
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
function day_start($time) {
	$datetime = date("Y-m-d 00:00:00", $time);
	return strtotime($datetime);
}

//获取当前时间整点开始  12:59:59
function day_end($time) {
	$datetime = date("Y-m-d 00:00:00", $time);
	return strtotime($datetime) + 3600 * 24 - 1;
}

//
function day_one() {
	return 3600 * 24;
}

/**
 * 
 * 获取每小时的热词统计结果
 */
function get_statistic($list, $task_id=0, $type=0) {
	global $db, $t_topwords_statistic;
	
	$array_one = array_slice($list, 0, 1);
	$array_end = end($list);
//print_r($array_one);exit;
	$start_time = $array_one[0]['add_time'];
	$day_start = day_start($start_time); 
	$day_end = day_end($start_time);
	
	$hour_list = array();
	$array = array(
		'min_number' => 100000,
		'max_number' => 0,
		'min_time' => 0,
		'max_time' => 0,
		'count' => 1,
	);
	$statistic = array();
	$new_array = $array;
	foreach ($list as $value) {
		$times = 0;
		
		//计算每小时 
		if($value['add_time'] >= $day_start && $value['add_time'] <= $day_end) {
			//最小次数   最小次数的时间小于最大次数的时间
			if($value['number'] < $new_array['min_number'] /*&& $new_array['min_time'] <= $new_array['max_time']*/) {
				$new_array['min_number'] = $value['number'];
				$new_array['min_time'] = $value['add_time'];
				//$new_array['min_t'] = date("Y-m-d H:i:s", $value['add_time']);
			}
			//最大次数
			if($value['number'] > $new_array['max_number']) {
				$new_array['max_number'] = $value['number'];
				$new_array['max_time'] = $value['add_time'];
				//$new_array['max_t'] = date("Y-m-d H:i:s", $value['add_time']);
			}
			//次数
			$new_array['count']++;
			
		} else {
			$day_start += day_one();
			$day_end += day_one();
			$new_array['key_words'] = $value['key_words'];
			$new_array['task_id'] = $task_id;
			$new_array['type'] = $type;
			$new_array['add_time'] = time();
			$new_array['up_value'] = $new_array['max_number']-$new_array['min_number'];
			if($new_array['max_time'] <= $new_array['min_time']) {
				$new_array = $array;
				continue;
			} else {
				//查看 是否已存在该记录
				$sql = "select * from `$t_topwords_statistic` where `min_time` = '". $new_array['min_time'] ."' and `type` = '". $new_array['type']."' and `key_words` = '". $new_array['key_words']  ."'";
				$row = $db->findone($sql);
				if(empty($row)) {
					$statistic[] = $new_array;
				}
				$new_array = $array;
			}
		}
	}
	return $statistic;
}



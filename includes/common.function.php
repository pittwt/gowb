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

//获取一天的开始时间戳  2021-08-08 00:00:00
function day_start($time) {
	$datetime = date("Y-m-d 00:00:00", $time);
	return strtotime($datetime);
}

//获取一天的最后时间戳   2021-08-08 24:59:59
function day_end($time) {
	$datetime = date("Y-m-d 00:00:00", $time);
	return strtotime($datetime) + 3600 * 24 - 1;
}

//获取一周的开始时间戳  12:59:59
function week_time($time) {
	$datetime = date("Y-m-d 00:00:00", $time);
	return strtotime($datetime) + 3600 * 24 - 1;
}

//获取一周的开始时间戳  12:59:59
function week_end($time) {
	$datetime = date("Y-m-d 00:00:00", $time);
	return strtotime($datetime) + 3600 * 24 - 1;
}

//
function day_one() {
	return 3600 * 24;
}

/**
 * 
 * 获取日、周、月的开始和结束时间
 */
function get_timestamp($time, $type='d') {
	if($type == 'd') {
	    $t_start = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time));   //创建日开始时间
	    $t_end   = mktime(23,59,59, date("m", $time), date("d", $time), date("Y", $time));  //创建日结束时间
	}else if($type == 'w') {
	    $w = date("w", $time);   //这天是星期几
	    $t_start = mktime(0, 0, 0, date("m", $time), date("d", $time) - $w, date("Y", $time));       //创建周开始时间
	    $t_end   = mktime(23,59,59, date("m", $time), date("d", $time) - $w + 6, date("Y", $time));  //创建周结束时间
	
	}else if($type == 'm') {
	    $y = date("Y", $time);
	    $m = date("m", $time);
	    $d = date("d", $time);
	    $t = date('t', $time);                   // 本月一共有几天
	
	    $t_start = mktime(0,0,0,$m,1,$y);       // 创建本月开始时间
	    $t_end   = mktime(23,59,59,$m,$t,$y);  // 创建本月结束时间
	}
	return array($t_start, $t_end);
}

/**
 * 
 * 获取每天的热词统计结果
 */
function get_statistic($list, $task_id=0, $type=0) {
	global $db, $t_topwords_statistic;
	
	$array_one = array_slice($list, 0, 1);
	$array_end = end($list);

	$start_time = $array_one[0]['add_time'];
	$day_start = day_start($start_time); 
	$day_end = day_end($start_time);
	
	$hour_list = array();
	$array = array(
		'min_number' => 100000,
		'max_number' => 0,
		'min_time' => 0,
		'max_time' => 0,
		'count' => 0,
	);
	//所有id
	$id = '';
	//统计结果
	$statistic = array();
	$new_array = $array;
	foreach ($list as $value) {
		//跳过执行当天零点开始的时间
		$today = day_start(time());
		if($value['add_time'] >= $today) {
			continue;
		}
		$times = 0;
		$id .= $value['id'] . ",";

		//计算每天
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
			//次数减少的情况
			if($new_array['min_number'] >= $new_array['max_number']) {
				$new_array['up_value'] = 0;
			} else {
				$new_array['up_value'] = $new_array['max_number']-$new_array['min_number'];
			}
			
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
	//最后一天的数据
	if(!empty($new_array)) {
		$new_array['key_words'] = $value['key_words'];
		$new_array['task_id'] = $task_id;
		$new_array['type'] = $type;
		$new_array['add_time'] = time();
		//次数减少的情况
		if($new_array['min_number'] >= $new_array['max_number']) {
			$new_array['up_value'] = 0;
		} else {
			$new_array['up_value'] = $new_array['max_number']-$new_array['min_number'];
		}
		//查看 是否已存在该记录
		$sql = "select * from `$t_topwords_statistic` where `min_time` = '". $new_array['min_time'] ."' and `type` = '". $new_array['type']."' and `key_words` = '". $new_array['key_words']  ."'";
		$row = $db->findone($sql);
		if(empty($row)) {
			$statistic[] = $new_array;
		}
	}
	$result = array(
		'result' => $statistic,
		'id'	 => $id
	);
	return $result;
}

/**
 * 
 * 获取每周热词统计结果
 */
function get_weekly_stats($list, $task_id=0, $type=0) {
	global $db, $t_topwords_statistic;
	
	$array_one = array_slice($list, 0, 1);
	$array_end = end($list);

	$start_time = $array_one[0]['add_time'];
	$day_start = day_start($start_time); 
	$day_end = day_end($start_time);
}

/**
 * 
 * 获取每月热词统计结果
 */
function get_monthly_stats($list, $task_id=0, $type=0) {
	
}



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
function type_time($type) {
	$time = 3600;
	if($tyep == 0) {
		$time *= 24;
	} else if($type == 1) {
		$time *= 24 * 7;
	} else if ($type == 2) {
		$time *= 24;
	}
	return 3600 * 24;
}

/**
 * 
 * 获取日、周、月的开始和结束时间
 */
function get_timestamp($time, $type='0', $num=0) {
	if($type == '0') {
	    $t_start = mktime(0, 0, 0, date("m", $time), date("d", $time) + $num, date("Y", $time));   //创建日开始时间
	    $t_end   = mktime(23,59,59, date("m", $time), date("d", $time) + $num, date("Y", $time));  //创建日结束时间
	}else if($type == '1') {
	    $w = date("w", $time);   //这天是星期几
	    $t_start = mktime(0, 0, 0, date("m", $time), date("d", $time) - $w  + ($num * 7), date("Y", $time));       //创建周开始时间
	    $t_end   = mktime(23,59,59, date("m", $time), date("d", $time) - $w + 6 + ($num * 7), date("Y", $time));  //创建周结束时间
	
	}else if($type == '2') {
	    $y = date("Y", $time);
	    $m = date("m", $time)+$num;
	    $d = date("d", $time);
	
	    $t_start = mktime(0,0,0,$m,1,$y);   	// 创建本月开始时间
	    $t_end   = mktime(0,0,0,$m+1,1,$y)-1;  // 创建本月结束时间
	}
	return array($t_start, $t_end);
}

/**
 * 
 * 获取每天的热词统计结果
 */
function get_statistic($list, $task_id=0, $type=0) {
	global $db, $t_statistic_topwords;
	
	$array_one = array_slice($list, 0, 1);
	$array_end = end($list);

	$start_time = $array_one[0]['add_time'];
	$day_start = day_start($start_time); 
	$day_end = day_end($start_time);
	
	$hour_list = array();
	$array = array(
		'min_number' => 0,
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
			if($new_array['min_number'] == 0 || $value['number'] < $new_array['min_number'] /*&& $new_array['min_time'] <= $new_array['max_time']*/) {
				$new_array['min_number'] = $value['number'];
				$new_array['min_time'] = $value['add_time'];
				$new_array['min_t'] = date("Y-m-d H:i:s", $value['add_time']);
			}
			//最大次数
			if($value['number'] > $new_array['max_number']) {
				$new_array['max_number'] = $value['number'];
				$new_array['max_time'] = $value['add_time'];
				$new_array['max_t'] = date("Y-m-d H:i:s", $value['add_time']);
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
				$sql = "select * from `$t_statistic_topwords` where `min_time` = '". $new_array['min_time'] ."' and `type` = '". $new_array['type']."' and `key_words` = '". $new_array['key_words']  ."'";
				$row = $db->findone($sql);
				if(empty($row)) {
					$statistic[] = $new_array;
				}
				$new_array = $array;
			}
			//当天开始第一个
			//最小次数   最小次数的时间小于最大次数的时间
			if($new_array['min_number'] == 0 || $value['number'] < $new_array['min_number']) {
				$new_array['min_number'] = $value['number'];
				$new_array['min_time'] = $value['add_time'];
				$new_array['min_t'] = date("Y-m-d H:i:s", $value['add_time']);
			}
			//最大次数
			if($value['number'] > $new_array['max_number']) {
				$new_array['max_number'] = $value['number'];
				$new_array['max_time'] = $value['add_time'];
				$new_array['max_t'] = date("Y-m-d H:i:s", $value['add_time']);
			}
			//次数
			$new_array['count']++;
		}
		
	}
	//最后一天的数据
	if($new_array['min_number']) {
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
		$sql = "select * from `$t_statistic_topwords` where `min_time` = '". $new_array['min_time'] ."' and `type` = '". $new_array['type']."' and `key_words` = '". $new_array['key_words']  ."'";
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
function get_statistics($list, $task_id=0, $type=0) {
	global $db, $t_statistic_topwords;
	
	$array_one = array_slice($list, 0, 1);
	$array_end = end($list);
	
	$start_time = $array_one[0]['min_time'];
	$timestamp = get_timestamp($start_time, $type);
	$time_start =  $timestamp[0];
	$time_end = $timestamp[1];
	
	$hour_list = array();
	$array = array(
		'min_number' => 0,
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
	$count = count($list);
	for ($i=0; $i<$count; $i++) {
		$value = $list[$i];
		//跳过执行当前时间段开始的时间
		$nowtime = get_timestamp(time(), $type);
		if($value['min_time'] >= $nowtime[0]) {
			continue;
		}
		$times = 0;
		$id .= $value['id'] . ",";

		//计算每个时间段
		if($value['min_time'] >= $time_start && $value['min_time'] <= $time_end) {
			//最小次数   最小次数的时间小于最大次数的时间
			if($new_array['min_number'] == 0 || $value['min_number'] < $new_array['min_number'] /*&& $new_array['min_time'] <= $new_array['max_time']*/) {
				$new_array['min_number'] = $value['min_number'];
				$new_array['min_time'] = $value['min_time'];
				$new_array['min_t'] = date("Y-m-d H:i:s", $value['min_time']);
			}
			//最大次数
			if($value['max_time'] > $new_array['max_time']) {
				$new_array['max_number'] = $value['max_number'];
				$new_array['max_time'] = $value['max_time'];
				$new_array['max_t'] = date("Y-m-d H:i:s", $value['max_time']);
			}
			//次数
			$new_array['count'] += $value['count'];
		} else {
			$nexttime = get_timestamp($time_start, $type, 1);
			$time_start = $nexttime[0];
			$time_end = $nexttime[1];
			$new_array['key_words'] = $value['key_words'];
			$new_array['task_id'] = isset($value['task_id']) ? $value['task_id'] : $task_id;
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
				$sql = "select * from `$t_statistic_topwords` where `min_time` = '". $new_array['min_time'] ."' and `type` = '". $new_array['type']."' and `key_words` = '". $new_array['key_words']  ."'";
				$row = $db->findone($sql);
				if(empty($row)) {
					
					$statistic[] = $new_array;
				}
				$new_array = $array;
			}
			$i--;
		}
	}

	//最后一个时间段的数据
	if($new_array['min_number']) {
		$new_array['key_words'] = $value['key_words'];
		$new_array['task_id'] = isset($value['task_id']) ? $value['task_id'] : $task_id;;
		$new_array['type'] = $type;
		$new_array['add_time'] = time();
		//次数减少的情况
		if($new_array['min_number'] >= $new_array['max_number']) {
			$new_array['up_value'] = 0;
		} else {
			$new_array['up_value'] = $new_array['max_number']-$new_array['min_number'];
		}
		
		//查看 是否已存在该记录
		$sql = "select * from `$t_statistic_topwords` where `min_time` = '". $new_array['min_time'] ."' and `type` = '". $new_array['type']."' and `key_words` = '". $new_array['key_words']  ."'";
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

function get_date($time) {
	return date("Y-m-d H:i:s", $time);
}

function get_keylist($keylist) {
	$list = null;
	foreach ($keylist as $value) {
		$list .= '\''.$value['key_words'].'\''.',';
	}
	return substr($list, 0, -1);
} 



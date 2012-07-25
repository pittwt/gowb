<?php
/**
 * 
 * Crons class
 * @author pittwt@gmail.com
 *
 */
class Crons{

	public function nextRuntime($week,$day,$hour,$minute,$time) {
		$time += $week * 7 * 24 * 60 * 60;
		$time += $day * 24 * 60 * 60;
		$time += $hour * 60 * 60;
		$time += $minute * 60;
		return $time;
	}
	
	public function getRunlist($array, $time, $type=1) {
		$run = array();
		foreach($array as $key=>$value) {
			if($value['type'] == 1 || $time >= $value['nextrun']) {
				$run[] = $value;
			}
		}
		return $run;
	}
}
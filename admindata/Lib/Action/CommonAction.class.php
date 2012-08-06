<?php
class CommonAction extends BaseAction{
	
	function _initialize(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])){
			//重定向
			$this->error['error'] = '1000';
			$this->ajaxerr($this->error);
		}
	}
	
	public function arrtime($str,$key) {
		$newarr = array();
		foreach($str as $value) {
			$value[$key] = date("Y-m-d H:i:s", $value[$key]);
			$newarr[] = $value;
		}
		return $newarr;
	}
	
	
}
?>

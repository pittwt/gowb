<?php
class CommonAction extends BaseAction{
	
	function _initialize(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])){
			//重定向
			//redirect(PHP_FILE .C('USER_AUTH_GATEWAY'));
			$this->error['error'] = '1000';
			$this->ajaxerr($this->error);
		}
	}
}
?>

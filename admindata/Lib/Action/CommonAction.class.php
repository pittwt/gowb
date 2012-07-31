<?php
class CommonAction extends BaseAction{
	
	function _initialize(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])){
			//重定向
			//echo C('USER_AUTH_GATEWAY');exit;
			redirect(PHP_FILE .C('USER_AUTH_GATEWAY'));
		}
	}
}
?>

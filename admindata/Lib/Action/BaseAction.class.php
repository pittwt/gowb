<?php
class BaseAction extends Action{
	public $error = array('error'=>''/*,'errmsg'=>''*/);
	public $errmsg = array(
		'0' => '添加失败',
		'1000' => '没有登录',
		'1001' => '用户名不能为空',
		'1002' => '密码不能为空',
		'1003' => '验证码不能为空',
		'1004' => '验证码错误',
		'1005' => '用户名或密码错误',
		'1006' => '参数不完整',
		'1007' => '没有列表内容',
		'1008' => '参数不正确'
	);
	
	
	public function ajaxerr($data) {
		//echo "[".json_encode($data)."]";
		echo json_encode($data);
		exit;
	}
	
	public function gdtime($time) {
		
		if(!empty($time)){
			$time = date("Y-m-d H:i:s", $time);
		}
		return $time;
	}
	
	Public function printr($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
	
	
	
	
}
?>

<?php
class UserAction extends CommonAction{

	public function index(){
		$user = M('User');
		$list = $user->order('id asc')->select();
		if(!empty($list[0])) {
			$this->error['error'] = '1';
			$this->error['uid'] = $list[0]['id'];
			$this->error['username'] = $list[0]['username'];
			$this->error['email'] = $list[0]['email'];
			$this->error['lastlogin_time'] = $list[0]['lastlogin_time'];
		} else {
			$this->error['error'] = '1008';
		}
		$this->err($this->error);
    }

	
}
?>
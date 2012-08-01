<?php
class UserAction extends CommonAction{

	public function index(){
		$user = M('User');
		$list = $user->order('id asc')->select();
		if(!empty($list[0])) {
			$this->error['error'] = '1';
			$this->error['total'] = count($list);
			$this->error['rows'] = array(
				'uid' => $list[0]['id'],
				'username' => $list[0]['username'],
				'email' => $list[0]['email'],
				'lastlogin_time' => $list[0]['lastlogin_time']
			);
		} else {
			$this->error['error'] = '1008';
		}
		$this->ajaxerr($this->error);
    }

	
}
?>
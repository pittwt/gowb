<?php
class UserAction extends CommonAction{

	public function index(){
		import("ORG.Util.Page");
		$pageSize = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 30;
		$_GET['p'] = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		
		$user = M('User');
		$count = $user->count();
		$p = new Page($count, $pageSize);
		$list = $user->limit($p->firstRow .','. $p->listRows)->order('id asc')->select();
		if(!empty($list)) {
			$this->error['error'] = '1';
			$this->error['total'] = $count;
			$this->error['page'] = $_GET['p'];
			//$this->error['rows'] = count($list);
			foreach ($list as $value) {
				$this->error['rows'][] = array(
					'uid' => $value['id'],
					'username' => $value['username'],
					'email' => $value['email'],
					'lastlogin_time' => $value['lastlogin_time']
				);
			}
			
		} else {
			$this->error['error'] = '1008';
		}
		$this->ajaxerr($this->error);
    }

	
}
?>
<?php
class TopwordsAction extends CommonAction{
	

	public function index(){
		import("ORG.Util.Page");
		import("ORG.Util.Input");
		$pageSize = isset($_REQUEST['pageSize']) ? intval($_REQUEST['pageSize']) : 2;
		//$_GET['p'] = isset($_REQUEST['pageSize']) ? intval($_REQUEST['pageSize']) : 1;
		$Model = M('DataTopUrl');		
		$count = $Model->count();
		$p = new Page($count, $pageSize);
		$topWords = $Model->limit($p->firstRow .','. $p->listRows)->order('id asc')->select();
		$page = $p->show();
		$this->assign("page", $page);
		$this->assign('topWords', $topWords);
		$this->display("index");
    }
    
    /**
     * 
     * 添加热词任务
     */
    public function add() {
    	import("ORG.Util.Input");
    	$data['url'] = Input::getVar($_REQUEST['url']);
    	$data['type'] = intval($_REQUEST['type']);
    	$data['detail'] = Input::getVar($_REQUEST['detail']);
    	$data['table'] = Input::getVar($_REQUEST['table']);
    	$data['status'] = intval($_REQUEST['status']);
    	$data['week'] = intval($_REQUEST['week']);
    	$data['day'] = intval($_REQUEST['day']);
    	$data['hour'] = intval($_REQUEST['hour']);
    	$data['minute'] = intval($_REQUEST['minute']);
    	$data['groups'] = 'key';
    	
    	if(!$data['week'] && !$data['day'] && !$data['hour']) {
	    	//最小间隔5分钟
	    	if($data['minute'] > 0 && $data['minute'] < 5) {
	    		$data['minute'] = 5;
	    	}
	    	if($data['minute'] == 0) {
	    		$data['status'] = 0;
	    	}
    	}
    	
    	//
    	if(!empty($data['url']) && !empty($data['table']) && !empty($data['detail'])) {
    		$top = M('DataTopUrl');
	    	if($top->data($data)->add()){
	    		$this->error['error'] = 1;
	    	} else {
	    		$this->error['error'] = 0;
	    	}
    	} else {
    		//参数不完整
    		$this->error['error'] = '1006';
    	}
    	
    	$this->ajaxerr($this->error);
    }
    
    /**
     * 
     * 启动/暂定任务
     */
    public function editStatus() {
    	if(isset($_REQUEST['id']) && isset($_REQUEST['status'])){
    		$top = M('DataTopUrl');
    		$data['id'] = intval($_REQUEST['id']);
    		$data['status'] = intval($_REQUEST['status']);
    		if($top->data($data)->save()) {
    			$this->error['error'] = 1;
    		} else {
    			$this->error['error'] = 0;
    		}
    		
    	} else {
    		$this->error['error'] = '1006';
    	}
    	$this->ajaxerr($this->error);
    	
    }
    
    /**
     * 
     * 获取任务列表
     */
    public function taskList() {
    	import("ORG.Util.Page");
    	import("ORG.Util.Input");
    	$group = Input::getVar($_REQUEST['group']);
    	if(!empty($group)) {
    		$top = M('DataTopUrl');
    		if($list = $top->where("groups = '". $group ."'")->select()) {
    			$this->error['error'] = 1;
    			$this->error['total'] = count($list);
    			$data = array();
    			foreach($list as $key=>$value) {
    				$data[$key]['task_id'] = $value['id'];
    				$data[$key]['detail'] = $value['detail'];
    				$data[$key]['status'] = $value['status'];
    				$data[$key]['lastrun'] = $this->gdtime($value['lastrun']);
    				$data[$key]['nextrun'] = $this->gdtime($value['nextrun']);
	    			if($group == 'key') {
	    				$data[$key]['url'] = $value['url'];
	    				$data[$key]['type'] = $value['type'];
	    			} else {
	    				$data[$key]['keywords'] = $value['keywords'];
	    			}
    			}
    			
    			$this->error['rows'] = $data;
    		}
    	} else {
    		$this->error['error'] = '1006'; 
    	}
    	$this->ajaxerr($this->error);
    }
    
    /**
     * 
     * 获取任务详情(搜索)
     */
	public function taskInfo() {
		import("ORG.Util.Input");
		$taskid = intval($_REQUEST['task_id']);
		//$group = Input::getVar($_REQUEST['group']);
		
		if($taskid) {
			$top = M('DataTopUrl');
			$info = $top->where("id = ".$taskid /*." and groups = '". $group ."'"*/)->select();
			if(!empty($info[0])) {
				$this->error['error'] = 1;
				$this->error['detail'] = $info[0]['detail'];
				$this->error['table'] = $info[0]['table'];
				$this->error['status'] = $info[0]['status'];
				$this->error['week'] = $info[0]['week'];
				$this->error['day'] = $info[0]['day'];
				$this->error['hour'] = $info[0]['hour'];
				$this->error['minute'] = $info[0]['minute'];
				
				if($info[0]['groups'] == 'key') {
					$this->error['url'] = $info[0]['url'];
					$this->error['type'] = $info[0]['type'];
				}
				if($info[0]['groups'] == 'search') {
					$this->error['keywords'] = $info[0]['keywords'];
				}	
			} else {
				$this->error['error'] = '1008';
			}
		} else {
			$this->error['error'] = '1006';
		}
		$this->ajaxerr($this->error);
	}

	/**
     * 
     * 修改任务
     */
	public function taskEdit() {
		import("ORG.Util.Input");
		$data['id'] = intval($_REQUEST['task_id']);
		$data['groups'] = Input::getVar($_REQUEST['group']);
		if($data['groups'] == 'key') {
			$data['type'] = intval($_REQUEST['type']);
			$data['url'] = Input::getVar($_REQUEST['url']);
		}
		if($data['groups'] == 'search') {
			$data['keywords'] = Input::getVar($_REQUEST['url']);
		}
		
    	$data['detail'] = Input::getVar($_REQUEST['detail']);
    	$data['table'] = Input::getVar($_REQUEST['table']);
    	$data['status'] = intval($_REQUEST['status']);
    	$data['week'] = intval($_REQUEST['week']);
    	$data['day'] = intval($_REQUEST['day']);
    	$data['hour'] = intval($_REQUEST['hour']);
    	$data['minute'] = intval($_REQUEST['minute']);
		
		if($_REQUEST['task_id']) {
			$top = M('DataTopUrl');
    		if($top->data($data)->save()) {
    			$this->error['error'] = 1;
    		} else {
    			$this->error['error'] = 0;
    		}
		} else {
			$this->error['error'] = '1006';
		}
		$this->ajaxerr($this->error);
	}	
}
?>
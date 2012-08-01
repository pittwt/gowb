<?php
class TopwordsAction extends CommonAction{
	
	function _initialize(){
		import("ORG.Util.Page");
		import("ORG.Util.Input");
	}

	public function index(){
		
		$Model = M('DataTopUrl');		
		$count = $Model->count();
		$p = new Page($count, 3);
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
    	
    	$data['url'] = Input::getVar($_REQUEST['url']);
    	$data['type'] = intval($_REQUEST['type']);
    	$data['detail'] = Input::getVar($_REQUEST['detail']);
    	$data['table'] = Input::getVar($_REQUEST['table']);
    	$data['status'] = intval($_REQUEST['status']);
    	$data['week'] = intval($_REQUEST['week']);
    	$data['day'] = intval($_REQUEST['day']);
    	$data['hour'] = intval($_REQUEST['hour']);
    	$data['minute'] = intval($_REQUEST['minute']);
    	
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
    public function editstatus() {
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
    public function tasklist() {
    	$group = Input::getVar($_REQUEST['group']);
    	if(!empty($group)) {
    		$top = M('DataTopUrl');
    		if($list = $top->where("groups = '". $group ."'")->select()) {
    			$this->error['error'] = 1;
    			$this->error['total'] = count($list);
    			$data = array();
    			foreach($list as $key=>$vlaue) {
    				$data[$key]['detail'] = $vlaue['detail'];
    				$data[$key]['status'] = $vlaue['status'];
    				$data[$key]['lastrun'] = $vlaue['lastrun'];
    				$data[$key]['nextrun'] = $vlaue['nextrun'];
	    			if($group == 'key') {
	    				$data[$key]['url'] = $vlaue['url'];
	    			} else {
	    				$data[$key]['keywords'] = $vlaue['keywords'];
	    			}
    			}
    			
    			$this->error['rows'] = $data;
    		}
    		//print_r($this->error);
    	} else {
    		$this->error['error'] = '1006'; 
    	}
    	$this->ajaxerr($this->error);
    }
    
   

	
}
?>
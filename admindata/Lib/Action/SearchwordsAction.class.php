<?php
class SearchwordsAction extends CommonAction{

	public function index(){
		
		$Model = M('DataTopUrl');
		import("ORG.Util.Page");
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
     * 添加搜索任务
     */
    public function add() {
    	import("ORG.Util.Input");
    	$data['keywords'] = Input::getVar($_REQUEST['keywords']);
    	$data['detail'] = Input::getVar($_REQUEST['detail']);
    	$data['table'] = Input::getVar($_REQUEST['table']);
    	$data['status'] = intval($_REQUEST['status']);
    	$data['week'] = intval($_REQUEST['week']);
    	$data['day'] = intval($_REQUEST['day']);
    	$data['hour'] = intval($_REQUEST['hour']);
    	$data['minute'] = intval($_REQUEST['minute']);
    	
    	$data['type'] = 0;
    	$data['url'] = C('WB_SEARCH_URL');
    	if(!$data['week'] && !$data['day'] && !$data['hour'] && !$data['minute']) {
    		$data['status'] = 0;
    	}
    	//最小间隔15分钟
    	if(!$data['week'] && !$data['day'] && !$data['hour']) {
	    	//最小间隔5分钟
	    	if($data['minute'] > 0 && $data['minute'] < 15) {
	    		$data['minute'] = 15;
	    	}
	    	if($data['minute'] == 0) {
	    		$data['status'] = 0;
	    	}
    	}
    	
    	//
    	if(!empty($data['keywords']) && !empty($data['table']) && !empty($data['detail'])) {
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
    	
    	$this->err($this->error);
    }
    

	
}
?>
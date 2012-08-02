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
    public function searchData() {
    	$taskid = intval($_REQUEST['taskid']);
    	
    	if($taskid) {
    		$top = M('DataTopUrl');
    		$rows = $top->where("id = $taskid and groups = 'search'")->select();
    		//$this->printr($rows);
	    	if(!empty($rows[0])) {
	    		$sql = "select * from `".C('DB_PREFIX').$rows[0]['table']."`";
	    		echo $sql;
	    		$model = M();
	    		$field = "weibo_username, weibo_content, weibo_time";
	    		$list = $model->->field($field)->query($sql);
	    		$this->printr($list);
	    	} else {
	    		$this->error['error'] = '1008';
	    	}
    	} else {
    		//参数不完整
    		$this->error['error'] = '1006';
    	}
    	
    	$this->ajaxerr($this->error);
    }
    

	
}
?>
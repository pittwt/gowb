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
     * 获取/搜索任务原始数据
     */
    public function searchData() {
    	
    	$task_id = intval($_REQUEST['task_id']);
    	$pageSize = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : 10;
    	
    	if($task_id) {
    		import("ORG.Util.Page");
    		$top = M('DataTopUrl');
    		$rows = $top->where("id = $task_id and groups = 'search'")->select();
	    	if(!empty($rows[0])) {
	    		$model = M();
	    		$table = C('DB_PREFIX').$rows[0]['table'];
	    		$count = $model->table($table)->count();
	    		$p = new Page($count, $pageSize);
	    		
	    		$field = "keywords_id, weibo_username, weibo_content, weibo_time";
	    		$list = $model->table($table)->field($field)->limit($p->firstRow .','. $p->listRows)->order("keywords_id desc")->select();
				$page = $p->show();
				if(!empty($list)) {
					$this->error['error'] = 1;
					$this->error['total'] = count($list);
					$this->error['p'] = isset($_REQUEST['p']) ? $_REQUEST['p'] : 10;
					$this->error['pageSize'] = $pageSize;
					$this->error['rows'] = $list;
				} else {
					$this->error['error'] = 0;
				}
				
				//echo "<div>".$page."</div>";
	    		//$this->printr($list);

	    	} else {
	    		$this->error['error'] = '1008';
	    	}
    	} else { 
    		//参数不完整
    		$this->error['error'] = '1006';
    	}
    	
    	$this->ajaxerr($this->error);
    }
    
    /**
     * 
     * 获取关键词数据详细信息
     */
    public function dataInfo() {
    	import("ORG.Util.Input");
    	$keywords_id = intval($_REQUEST['keywords_id']);
    	$task_id = intval($_REQUEST['task_id']);
    	
    	if($keywords_id && $task_id) {
    		import("ORG.Util.Page");
    		$top = M('DataTopUrl');
    		$rows = $top->where("id = ".$task_id." and groups = 'search'")->select();
    		if(!empty($rows[0])) {
    			$model = M();
    			$table = C('DB_PREFIX').$rows[0]['table'];
    			$field = "keywords_id, weibo_username, weibo_content, weibo_time, tag_id";
    			$list = $model->table($table)->field($field)->where("keywords_id = ".$keywords_id)->order("")->select();
    			
    			if(!empty($list[0]['keywords_id'])) {
    				$num = $model->table($table."_num")->where('key_id ='.$list[0]['keywords_id'])->order("id desc")->select();
    				$this->error['nums'] = $num;
    			}
    			if(!empty($list[0]['tag_id'])) {
    				$model = M('Tags');
    				$tag = $model->where("tag_id in (". $list[0]['tag_id'] .")")->select();
    				$this->error['tags'] = $tag;
    				//$this->printr($tag);
    			}
    			$this->error['error'] = 1;
    			$this->error['weibo_username'] = $list[0]['weibo_username'];
    			$this->error['weibo_content'] = $list[0]['weibo_content'];
    			$this->error['weibo_time'] = $this->gdtime($list[0]['weibo_time']);
    		} else {
    			$this->error['error'] = '1008';
    		}
    		$this->printr($list);
    		
    	} else {
    		//参数不完整
    		$this->error['error'] = '1006';
    	}
    	
    	$this->ajaxerr($this->error);
    }

}
?>
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
    	$pageSize = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 30;
    	
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
				//$page = $p->show();
				if(!empty($list)) {
					$this->error['error'] = 1;
					$this->error['count'] = $count;
					$this->error['total'] = count($list);
					$this->error['p'] = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
					//$this->error['pageSize'] = $pageSize;
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
    	$type = Input::getVar($_REQUEST['type']);
    	
    	if($keywords_id && $task_id) {
    		import("ORG.Util.Page");
    		$top = M('DataTopUrl');
    		$rows = $top->where("id = ".$task_id." and groups = 'search'")->select();
    		if(!empty($rows[0])) {
    			$model = M();
    			$table = C('DB_PREFIX').$rows[0]['table'];
    			$field = "keywords_id, weibo_username, weibo_content, weibo_time, tag_id";
    			$list = $model->table($table)->field($field)->where("keywords_id = ".$keywords_id)->select();
    			
    			if($type == 'nums' && !empty($list[0]['keywords_id'])) {
    				$field = "forward_num, comment_num, update_time";
    				$num = $model->table($table."_num")->field($field)->where('keywords_id ='.$list[0]['keywords_id'])->order("id desc")->select();
    				$this->error['rows'] = $num;
	    			if(!empty($num)) {
	    				$this->error['error'] = 1;
	    			} else {
	    				$this->error['error'] = 0;
	    			}
    			}elseif($type == 'tags') {
    				$model = M('Tags');
    				$tags = $model->select();
    				$rows = $model->field("tag_id")->where("tag_id in (". substr($list[0]['tag_id'], 0, -1) .")")->select();
    				
    				$tag = array();
    				foreach ($rows as $value) {
    					$tag[] = $value['tag_id'];
    				}
    				$result = array();
    				foreach ($tags as $value) {
    					if(in_array($value['tag_id'], $tag)){
    						$value['selected'] = 'true';
    					}
    					$result[] = $value;
    				}
    				$this->error = $result;
    			}else {
    				$this->error['weibo_username'] = $list[0]['weibo_username'];
	    			$this->error['weibo_content'] = $list[0]['weibo_content'];
	    			$this->error['weibo_time'] = $this->gdtime($list[0]['weibo_time']);
	    			if(!empty($this->error)) {
		    			$this->error['error'] = 1;
	    			} else {
	    				$this->error['error'] = 0;
	    			}
    			}
    			
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
     * 添加搜索任务
     */
    public function add() {
    	import("ORG.Util.Input");
    	$data['url'] = C('WB_SEARCH_URL');
    	$data['keywords'] = Input::getVar($_REQUEST['keywords']);
    	$data['detail'] = Input::getVar($_REQUEST['detail']);
    	$data['table'] = Input::getVar($_REQUEST['table']);
    	$data['status'] = intval($_REQUEST['status']);
    	$data['week'] = intval($_REQUEST['week']);
    	$data['day'] = intval($_REQUEST['day']);
    	$data['hour'] = intval($_REQUEST['hour']);
    	$data['minute'] = intval($_REQUEST['minute']);
    	$data['groups'] = 'search';
    	$data['phpfile'] = 'get_wb_search.php';
    	$data['nextrun'] = strtotime($_REQUEST['start_time']);
    	
    	if(!$data['week'] && !$data['day'] && !$data['hour']) {
	    	//最小间隔15分钟
	    	if($data['minute'] > 0 && $data['minute'] < 15) {
	    		$data['minute'] = 15;
	    	}
	    	if($data['minute'] == 0) {
	    		$data['status'] = 0;
	    	}
    	}
    	
    	//
    	if(!empty($data['url']) && !empty($data['table']) && !empty($data['detail'])) {
    		$top = M('DataTopUrl');
	    	if($top->data($data)->add()){
	    		$insert_sql = array(
	    			'tab1' => "CREATE TABLE IF NOT EXISTS `".C('DB_PREFIX').$data['table']."` (
							  `keywords_id` int(10) NOT NULL AUTO_INCREMENT,
							  `weibo_username` varchar(255) NOT NULL COMMENT '微博用户名称',
							  `weibo_content` text NOT NULL COMMENT '微博内容',
							  `weibo_time` int(10) DEFAULT NULL COMMENT '发微薄时间',
							  `forward_num` int(10) DEFAULT NULL COMMENT '转发次数',
							  `comment_num` int(10) DEFAULT NULL COMMENT '评论数',
							  `weibo_thumbimg` varchar(255) DEFAULT NULL COMMENT '微博图片',
							  `weibo_middleimg` varchar(255) DEFAULT NULL,
							  `weibo_largeimg` varchar(255) DEFAULT NULL,
							  `tag_id` varchar(255) DEFAULT NULL COMMENT '标签id 逗号分隔',
							  PRIMARY KEY (`keywords_id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;",
	    			'tab2' => "CREATE TABLE IF NOT EXISTS `".C('DB_PREFIX').$data['table']."_num` (
							  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
							  `keywords_id` int(10) DEFAULT NULL COMMENT '关键词id',
							  `forward_num` int(10) DEFAULT NULL,
							  `comment_num` int(10) DEFAULT NULL,
							  `update_time` datetime DEFAULT NULL,
							  PRIMARY KEY (`id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;"
						);
				foreach ($insert_sql as $sql) {
					$top->query($sql);
				}
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
}
?>
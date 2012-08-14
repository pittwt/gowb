<?php
class DataAction extends CommonAction{

    
    function _initialize(){
		import("ORG.Util.Page");
		import("ORG.Util.Input");
	}
	
	/**
	 * 
	 * 添加标签
	 */
	public function addTag() {
		import("ORG.Util.Input");
		$tag_name = Input::getVar($_REQUEST['tag_name']);
		if(!empty($tag_name)) {
			$data = array(
				'tag_name' => $tag_name
			);
			$tag = M('Tags');
			if($tag->data($data)->add()){
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
	 * 获取标签列表
	 */
	public function tagList() {
		import("ORG.Util.Page");
		import("ORG.Util.Input");
		$way = Input::getVar($_REQUEST['way']);
		$tag = M('Tags');
		$list = $tag->select();
		if(!empty($list)) {
			$data = array();
			//print_r($list);exit;
			/*foreach ($list as $key=>$value) {
				$data[$key]['tag_id'] = $value['tag_id'];
				$data[$key]['tag_name'] = $value['tag_name'];
			}*/
			$this->error['total'] = count($list);
			$this->error['rows'] = $list;
			//生成下拉菜单
    		if($way == "select"){
    			$this->error = $list;
    		}
		}
		
		$this->ajaxerr($this->error);
	}
	
	/**
	 * 
	 * 修改标签
	 */
	public function tagEdit() {
		import("ORG.Util.Input");
		$tag_id = intval($_REQUEST['tag_id']);
		$tag_name = Input::getVar($_REQUEST['tag_name']);

		if($tag_id) {
			$tag = M('Tags');
			$data = array(
				'tag_id' => $tag_id,
				'tag_name' => $tag_name
			);
    		if($tag->data($data)->save()) {
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
	 * 获取标签详情
	 */
	public function tagInfo() {
		echo date('Y-m-d H:i:s',1343360023);
		$tag_id = intval($_REQUEST['tag_id']);
		if($tag_id) {
			$tag = M('Tags');
			$info = $tag->where("tag_id = $tag_id")->select();
			if(!empty($info)){
				$this->error['error'] = 1;
				$this->error['tag_id'] = $info[0]['tag_id'];
				$this->error['tag_name'] = $info[0]['tag_name'];
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
	 * 删除标签
	 */
	public function tagDelete() {
		$tag_id = intval($_REQUEST['tag_id']);
		if($tag_id) {
			$tag = M('Tags');
			if($tag->delete($tag_id)) {
				$this->error['error'] = 1;
			} else {
				$this->error['error'] = 0;
			}
		}
		$this->ajaxerr($this->error);
	}
	
	/**
	 * 
	 * 获取/查询关键词有效数据列表
	 */
	public function dataKeyList() {
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
				$page = $p->show();
				if(!empty($list)) {
					$this->error['error'] = 1;
					$this->error['count'] = $count;
					$this->error['total'] = count($list);
					$this->error['p'] = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
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
	 * 查询热词有效数据列表
	 */
	public function dataTopList() {
		import("ORG.Util.Page");
		import("ORG.Util.Input");
		$task_id = intval($_REQUEST['task_id']);
		$topwords = Input::getVar($_REQUEST['topwords']);
		$pageSize = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 30;
    	
		if($task_id) {
			$top = M('DataTopUrl');
	    	$rows = $top->where("id = '". $task_id ."' and groups = 'key'")->select();
	    	//$this->printr($rows);
			$model = M();
			$table = C('DB_PREFIX').$rows[0]['table'];
			
			//获取单个热词所有次数
			if($topwords) {
				$data = $model->table($table)->where("key_words = '".$topwords."'")->order("add_time asc")->select();
				//去除连续两次相同的值
				$result = array();
				$tmp = '';
				foreach ($data as $value) {
					if($tmp == $value['number']) {
						continue;
					}else {
						$tmp = $value['number'];
						$result[] = $value;
					}
				}
				
				if(!empty($data)) {
					$this->error['error'] = 1;
					$this->error['total'] = count($result);
					$this->error['rows'] = $result;
				} else {
					$this->error['error'] = 0;
				}

			} else {
				//获取所有热词
				$count = $model->table($table)->count();
				$p = new Page($count, $pageSize);
				$data = $model->table($table)->distinct(true)->field('key_words')->limit($p->firstRow .','. $p->listRows)->order("id desc")->select();
				//$page = $p->show();

				if($data) {
					$this->error['error'] = 1;
					$this->error['total'] = count($data);
					$this->error['count'] = $count;
					$this->error['rows']  = $data;
					$this->error['p'] = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
					$this->error['pageSize'] = $pageSize;
				} else {
					$this->error['error'] = 0;
				}
			}
			
		} else {
			//参数不完整
    		$this->error['error'] = '1006';
		}
		$this->ajaxerr($this->error);
	}
	
	/**
	 * 
	 * 添加搜索关键词标签
	 */
	public function addKeywordsTag() {
		import("ORG.Util.Input");
		
		$task_id = intval($_REQUEST['task_id']);
		$keywords_id = intval($_REQUEST['keywords_id']);
		$tag_id = Input::getVar($_REQUEST['tag_id']);
		
		if($keywords_id && $task_id && $tag_id) {
    		import("ORG.Util.Page");
    		$top = M('DataTopUrl');
    		$rows = $top->where("id = ".$task_id." and groups = 'search'")->select();
    		if(!empty($rows[0])) {
    			$model = M();
    			$table = C('DB_PREFIX').$rows[0]['table'];
    			$data = array(
    				'tag_id' => $tag_id
    			);
    			$field = "keywords_id, weibo_username, weibo_content, weibo_time, tag_id";
    			if($model->table($table)->where("keywords_id = ".$keywords_id)->data($data)->save()) {
    				$this->error['error'] = 1;
    			} else {
    				$this->error['error'] = 0;
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
	 * 关键词数据排行（时间 标签 统计数）
	 */
	public function keywordsCount() {
		import("ORG.Util.Input");
		$task_id = intval($_REQUEST['task_id']);
		$start_time = Input::getVar($_REQUEST['start_time']);
		$end_time = Input::getVar($_REQUEST['end_time']);
/*echo strtotime('10/8/2012 02:03:56')."<br>";
echo date("Y-m-d H:i:s",1349633036)."<br>";
echo $start_time."<br>";
echo strtotime($start_time)."<br>";
echo date("Y-m-d H:i:s", $start_time)."<br>";
echo date("Y-m-d H:i:s", $end_time)."<br>";*/
		if($task_id) {
			import("ORG.Util.Page");
    		$top = M('DataTopUrl');
    		$rows = $top->where("id = ".$task_id." and groups = 'search'")->select();
    		
			$where = '1=1';
			/*if($tag_id) {
				$where .= " and tag_id like '%". $tag_id .",%'";
			}*/
			if($start_time) {
				$where .= ' and weibo_time > '.strtotime($start_time);
			}
			if($start_time) {
				$where .= ' and weibo_time < '.strtotime($end_time);
			}
			$model = M();
			$table = C('DB_PREFIX').$rows[0]['table'];
			$tag = M('Tags');
			$list = $tag->select();
			$result = array();
			foreach ($list as $key=>$value) {
				$result[$key]['tag_name'] = $value['tag_name'];
				//$where .= " and tag_id like '%". $value['tag_id'] .",%'";
				$result[$key]['count'] = $model->table($table)->where($where." and tag_id like '%". $value['tag_id'] .",%'")->count();
				//echo $model->getLastSql()."<br>";
			}
			
			//print_r($result);exit;
			if(!empty($result)) {
				$this->error['error'] = 1;
				$this->error['total'] = count($result);
				$this->error['rows'] = $result;
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
	 * 获取/查询按关键词统计信息（按时间，标签等）
	 */
	public function keywordsRank() {
		import("ORG.Util.Input");
		$task_id = intval($_REQUEST['task_id']);
		$tag_id = intval($_REQUEST['tag_id']);
		$start_time = Input::getVar($_REQUEST['start_time']);
		$end_time = Input::getVar($_REQUEST['end_time']);

		if($task_id) {
			//import("ORG.Util.Page");
    		$top = M('DataTopUrl');
    		$rows = $top->where("id = ".$task_id." and groups = 'search'")->select();
    		
			$where = '1=1';
			if($tag_id) {
				$where .= " and tag_id like '%". $tag_id .",%'";
			}
			if($start_time) {
				$where .= ' and add_time > '.strtotime($start_time);
			}
			if($start_time) {
				$where .= ' and add_time < '.strtotime($end_time);
			}
			$model = M();
			$table = C('DB_PREFIX').$rows[0]['table'];
			$field = "weibo_username, weibo_content, weibo_time";
			$list = $model->table($table)->field($field)->where($where)->select();
			$list = $this->arrtime($list, 'weibo_time');
			//$this->printr($list);
			
			if(!empty($list)) {
				$this->error['error'] = 1;
				$this->error['total'] = count($list);
				$this->error['rows'] = $list;
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
	 * 查询热词统计数据（上升最快等）
	 */
	public function topwordsRank() {
		import("ORG.Util.Input");
		import("ORG.Util.Page");
		
		
		if(isset($_REQUEST['time']) && isset($_REQUEST['method'])) {
			$task_id = intval($_REQUEST['task_id']);
			$pageSize = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 30;
    		$_GET['p'] = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    	
			$time = intval($_REQUEST['time']);
			$method = Input::getVar($_REQUEST['method']);
			$model = M();
			$table = C('DB_PREFIX').'statistic_topwords';
			$count = $model->table($table)->where("type = $time and task_id = $task_id")->count();
			$p = new Page($count, $pageSize);
			if($method == 'up') {
				$field = "key_words, min_number, max_number";
				$list = $model->table($table)->where("type = $time and task_id = $task_id")->limit($p->firstRow .','. $p->listRows)->order("up_value desc")->select();
				/*$page = $p->show();
				echo $page."<p>";
				foreach ($list as $value) {
					echo $value['id']."=>".$value['key_words']."=>".$value['up_value']."<br>";
				}*/
				if(!empty($list)) {
					$this->error['error'] = 1;
					$this->error['total'] = $count;
					$this->error['rows'] = $list;
				} else {
					$this->error['error'] = 0;
				}
			}
			if($method == 'num') {
				$field = "key_words";
				$list = $model->table($table)->field($field)->where("type = $time and task_id = $task_id")->limit($p->firstRow .','. $p->listRows)->order("count desc")->select();
				if(!empty($list)) {
					$this->error['error'] = 1;
					$this->error['total'] = $count;
					$this->error['rows'] = $list;
				} else {
					$this->error['error'] = 0;
				}
			}
			//print_r($list);
		} else {
			$this->error['error'] = '1006';
		}
		$this->ajaxerr($this->error);
	}
	

	
}




?>
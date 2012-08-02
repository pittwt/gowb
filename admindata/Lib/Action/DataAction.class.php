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
			//print_r($list);
		}
		
		$this->ajaxerr($this->error);
	}

	public function addkeywordstag() {
		
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
	
}
?>
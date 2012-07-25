<?php
class TopwordsAction extends BaseAction{

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
    
    
    public function insert() {
    	
    }
    
    public function update() {
    	
    }
    
    public function edit() {
    	
    }
	
    public function delete() {
    	
    }
    

	
}
?>
<?php
// 本类由系统自动生成，仅供测试用途
class CateAction extends CommonAction {

	public function index(){
        //实例化自定义模型    M是实例化基础模型
        $cate=D("Message");
        
        //执行查询
        
       //$list=$cate->query("SELECT id,name,pid,path,CONCAT(path,'-',id ) as dpath FROM `t_cate` ORDER BY dpath");
        
       $list=$cate->field("id,name,content,ctime,pid,path,concat(path,'-',id) as dpath")->order('dpath asc')->select();
        
       echo $cate->getLastSql();
      
        
        foreach ($list as $key=>$v){
        	
        	//$list[0]['count']=统计数组长度（字符串拆分（“-”，要拆分的字符串））
         	
        	$list[$key]['count']=count(explode("-",$v['path']));
        }
       // dump($list);
        
       //模板输出 
        $this->assign("fl","ThinkPHP无限级别分类");
		$this->assign("catelist",$list);
		
        $this->display("index:main");
    }
    
  	//执行添加子类的操作  

    
    function add(){
		//实例化模型
		$cate=D('Message');
		
		$data['name']=session("qqname");
		$data['content']=$_POST['name'];
		$data['ctime']=date('Y-m-d H:i:s');
		$data['pid']=0;
		$data['path']=0;
		
		
		//创建数据
		//测试if($aaa=$cate->create()){
		
		if($aaa=$cate->create()){
			
			dump($aaa);
			//开始执行add方法用于添加数据
 			if($cate->add($data)){
				
 				$this->success("添加成功！");
 			
 			}else{
				
 				$this->error("添加失败");
 			}
			
		}else{
			
			$this->error($cate->getError());
		}
    	
    	
    }

	
}
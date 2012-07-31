<?php
class MessageAction extends CommonAction{

	public function fabiao(){
		
		
		 $msg=M("Message");
		
		//$data['pid']=$_POST['id'];
		
		$data['path']=$_POST['path'];
		
		//这是获取path字符串12,0-1-3-5==》0-1-3-5-12
		$arr=explode(",", $_POST['path']);
		$data['pid']=$arr[0];
		
		//拼接新的path字符串12,0-1-3-5==》0-1-3-5-12
		$data['path']=$arr[1]."-".$arr[0];
		
		$data['name']=session("qqname");
		
		$data['content']=$_POST['content'];
		
		$data['ctime']=date('Y-m-d H:i:s');
		
		if ($msg->add($data)){
			
			$this->success("发表成功");
			
		}else{
			$this->error("发表失败");
			
		}
		
		
	}
	 

}
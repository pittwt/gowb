<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends BaseAction {
	
	
//***************************************************************************
	
	public function index(){
       // header("Content-Type:text/html; charset=utf-8");
        /**
         * 显示首页，是登录页面
         */
		$this->display("public:index");
    }
    
    
//***************************************************************************   
	//检查登录是否成功，成功则跳转到留言页面。失败返回到登录页面
    public function checkLogin(){
    	/**
    	 *验证用户名与密码是否正确
    	 *
    	 */
    	//1、获取用户输入的内容
    	$uname=$_POST['username'];
    	$pwd=md5($_POST['password']);
    
    	//2、实例化模型
    	$User=M('User');
    
    	$arr=$User->where("username='".$uname."' and password='".$pwd."'")->select();
    
    	echo $User->getLastSql();
    
    	//$dump($arr);
    
    	//3、判断是否查询到数据
    
    	if(count($arr)){
    		//d当成功跳转默认成功也
    		session("qqname",$uname);
    		$this->success("登录成功","main");
    	}else{
    		//失败跳转到失败也
    		$this->error("登录失败","login");
    	}
    
    }
    
    
//***************************************************************************
    //检查是否注册成功，成功跳转到登录页面，失败还在注册页面
    function add(){
    	/**
    	 *验证是否注册成功的方法
    	 *
    	 */
    
    
    	if(md5($_POST['verify'])!=$_SESSION['verify']){
    			
    		$this->error("验证码错误");
    	}
    
    
    	//实例化自定义模型  M('User')实例化基础模型
    	$user=D("User");
    		
    	if($user->create()){
    
    		//session("qqname")
    		//执行插入操作，执行成功后，返回新插入的数据库的ID
    		if($user->add()){
    
    			$this->success("注册成功",login);
    		}else{
    
    			$this->error("注册失败",reg);
    		}
    
    			
    			
    	}else{
    		//把错误信息提示给用户看
    
    
    
    		$this->error($user->getError());
    
    			
    	}
    
    }
//***************************************************************************
    
    
    //生成图片验证码
    function verify(){
    	/**
    	 * 在thinkPHP中如何实现验证码
    	 *
    	 * ThinkPHP已经为我们提供了图像处理的类库ThinkPHP\Extend\...
    	 *
    	 * 如何导入类库？
    	 * 导入类库用"import(文件路径)来导入，但是注意文件的路径中的\要替换成 . 号"
    	 * 1）导入系统的类库  import(从library开始算起) import('ORG.Util.Image')注意大小写
    	 * 2）导入项目类库 import("@.ORG.Image") 我们需要在我恩的项目的Lib目录中存放
    	 */
    	//导入图形处理类库
    	import("ORG.Util.Image");
    
    		
    	//import("@.ORG.Image");
    
    
    	//生成图形验证码
    	/*
    	 length：验证码的长度，默认为4位数
    
    	mode：验证字符串的类型，默认为数字，其他支持类型有0 字母 1 数字 2 大写字母 3 小写字母 4中文 5混合（去掉了容易混淆的字符oOLl和数字01）
    
    	type：验证码的图片类型，默认为png
    
    	width：验证码的宽度，默认会自动根据验证码长度自动计算
    
    	height：验证码的高度，默认为22
    
    	verifyName：验证码的SESSION记录名称，默认为verify
    
    
    	*/
    	//实现英文验证码
    	image::buildImageVerify(4,1,'gif',60,22,'verify');
    
    
    	//实现中文验证码
    	//image::GBVerify();
    }
//***************************************************************************
    
    
    
 	//文件上传方法   
    public function upload(){
    	
    	header("Content-Type:text/html; charset=utf-8");
    	
    	//判断文件是否存在，存在则转到uploadFile()处理
    	if($_FILES){
    		
    		$info=$this->uploadFile();
    		//dump($info);
    		//如果要把上传的信息保存到数据库中
    		
    		//实例化模型
    		$num=0;
    		for($i=0;$i<count($info);$i++){
	    		
    			$file=M("File");
	    		//填充数据
	    	 	 
	    		$data["filename"]=$info[$i]["savename"];
	    		$data["truename"]=$info[$i]["name"];
	    		$data["filetype"]=$info[$i]["type"];
	    		$data["filesize"]=$info[$i]["size"];
	    		$data["ext"]=$info[$i]["extension"];
	    		$data["filepath"]=$info[$i]["savepath"]; 
	    		//执行插入操作
	    		
	    		
	    		//dump($data);
	    		
	    		$aff=$file->add($data);
	    		
	    		$num++;
    	}
    		
    		
    		if($aff){
    			
    			$this->success("文件信息保存成功！");
    		}else{
    			
    			$this->error("文件信息保存失败");
    		} 
    		 
    		
    	
    	
    	}else{
    		
    		
    		
    		
    		$this->error("请选择正确的文件");

    	
    	
    	}
    	
    	
    
    }
    
    
    public function uploadFile(){
    	
    	header("Content-Type:text/html; charset=utf-8");
    	 
		//导入包
    	import("ORG.Net.UploadFile");
    	
    	//实例化
    	$upload = new UploadFile();
    	
    	//上传文件大小限制
    	$upload->maxSize="4069000";

    	// 设置附件上传目录
    	$upload->savePath =  './Public/upload/';
    	
    	 
    	
    	// 设置附件上传类型
    	$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
    	
    	
    	//上传文件名称
    	//$upload->saveRule="uniqid";
    	$upload->saveRule="com_create_guid";
    	
    	//$upload->thumb=true;
    	
    	//$upload->thumbMaxWidth="300,600";
    	
    	//$upload->thumbMaxHeight="200,400";
    	//上传后删除原图
    	//$upload->thumbRemoveOrigin=true;
    	
    	//是不是自动的把文件存储在子文件夹中
    	/* $upload->autoSub=true;
    	
    	$upload->subType="date";
    	
    	$upload->dateFormat="Y-m-d";
    	 */
    	
    	if($upload->upload()){
    		
    		$info=$upload->getUploadFileInfo();
    		//
    		
    	}else{
    		
    		$this->error("上传失败");
    	}
    	//处理完成后，返回这个函数结果，否则upload()无法接受传过来的数据
    	return $info;
    }
    
    
    
   
    
 /*    public function up(){
    	header("Content-Type:text/html; charset=utf-8");
    	$this->display("up");
    }
 */
	
	
	
	

	
	
	//用户登录后跳转到主页
	function main(){
		
		//实例化自定义模型    M是实例化基础模型
		$cate=M("Message");
		
		//执行查询
		
		//$list=$cate->query("SELECT id,name,pid,path,CONCAT(path,'-',id ) as dpath FROM `t_cate` ORDER BY dpath");
		
		$list=$cate->field("id,name,content,ctime,path,concat(path,'-',id) as dpath")->order('dpath asc')->select();
		
		// echo $cate->getLastSql();
		
		//dump($list);
		
		foreach ($list as $key=>$v){
			 
			//$list[0]['count']=统计数组长度（字符串拆分（“-”，要拆分的字符串））
		
			$list[$key]['count']=count(explode("-",$v['path']));
		}
		// dump($list);
		
		//模板输出
		$this->assign("msglist",$list);
		
		
		
		$this->assign("title",session("qqname")."的主页");
		
		$this->display("public:index");
	}

	
	
	
	

}
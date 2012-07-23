<?php
class PublicAction extends Action{
	//***************************************************************************
	public function index(){
		$this->redirect('Public/login');
    }
	
	public function login(){
       if(!isset($_SESSION[C('USER_AUTH_KEY')])){
       		$this->display("login");
       } else {
       		$this->redirect('Index');
       }
	
    }
    
    
//***************************************************************************   
	//检查登录是否成功，成功则跳转到留言页面。失败返回到登录页面
    public function checkLogin(){
    	if(empty($_POST['username'])){
    		$this->error('用户名不能为空');
    	} elseif(empty($_POST['password'])) {
    		$this->error('密码不能为空');
    	} elseif(empty($_POST['verify'])) {
    		$this->error('验证码不能为空');
    	}
    	
    	/*echo $_SESSION['verify']."<br>";
    	echo md5($_POST['verify'])."<br>";
    	print_r($_POST);exit;*/
    	/**
    	 * 验证用户名 密码 验证码是否正确
    	 */
    	//1、获取用户输入的内容
    	$username = $_POST['username'];
    	$password = md5($_POST['password']);
    	$verify   = md5($_POST['verify']);
    	
    	if($verify != $_SESSION['verify']) {
    		$this->error('验证码错误！');
    	} else {
    		//2、实例化模型
	    	$User=M('User');
	    	$auth = $User->where("username='". $username ."' and password='". $password ."'")->select();
	    
	    	if(!empty($auth)){
	    		//d当成功跳转默认成功也
	    		$_SESSION[C('USER_AUTH_KEY')]	=	$auth[0]['id'];
	    		$_SESSION['email']				=	$auth[0]['email'];
           	 	$_SESSION['loginusername']		=	$auth[0]['username'];
	    		
	    		$this->assign("jumpUrl","__APP__/Index");
	    		$this->success("登录成功");
	    	}else{
	    		//失败跳转到失败也
	    		$this->error("登录失败","__APP__/Public/login");
	    	}
    	}
    
    	
    }
    
// 用户登出
    public function logout()
    {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
            $this->assign("jumpUrl",__URL__.'/login/');
            $this->success('登出成功！');
        }else {
            $this->error('已经登出！');
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
    	$type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Util.Image");
        Image::buildImageVerify(4,1,$type);
    }
//***************************************************************************
    
    
  

	
}
?>
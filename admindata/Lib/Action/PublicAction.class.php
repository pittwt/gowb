<?php
class PublicAction extends BaseAction{
	
	public function index(){
		$this->redirect('Public/login');
    }
	
	public function loginStatus(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])){
			$this->error['error'] = '1000';
			
		} else {
			$this->error['error'] = '1';
			$this->error['uid'] = $_SESSION[C('USER_AUTH_KEY')];
			$this->error['username'] = $_SESSION['loginusername'];
		}
		$this->ajaxerr($this->error);
    }
    
    
	//检查登录是否成功，成功则跳转到留言页面。失败返回到登录页面
    public function checkLogin(){
    	if(empty($_REQUEST['username'])){
    		$this->error['error'] = '1001';
    	} elseif(empty($_REQUEST['password'])) {
    		$this->error['error'] = '1002';
    		/*$this->error('密码不能为空');*/
    	} elseif(empty($_REQUEST['verify'])) {
    		$this->error['error'] = '1003';
    	}

     	if($this->error['error']) {
    		$this->ajaxerr(($this->error));
    	}
    	
    	/**
    	 * 验证用户名 密码 验证码是否正确
    	 */
    	//1、获取用户输入的内容
    	$username = $_REQUEST['username'];
    	$password = md5($_REQUEST['password']);
    	$verify   = md5($_REQUEST['verify']);

    	if($verify != $_SESSION['verify']) {
    		/*$this->error('验证码错误！');*/
    		$this->error['error'] = '1004';
    	} else {
    		//2、实例化模型
	    	$User=M('User');
	    	$auth = $User->where("username='". $username ."' and password='". $password ."'")->select();
	    
	    	if(!empty($auth)){
	    		//当成功跳转默认成功页
	    		$_SESSION[C('USER_AUTH_KEY')]	=	$auth[0]['id'];
	    		$_SESSION['email']				=	$auth[0]['email'];
           	 	$_SESSION['loginusername']		=	$auth[0]['username'];
           	 	$_SESSION['lastlogin_time']		=	$auth[0]['lastlogin_time'];
	    		//$this->assign("jumpUrl","__APP__/Index");
	    		//登录成功
	    		$this->error['error'] = '1';
	    		$this->error['uid'] = $auth[0]['id'];
				$this->error['username'] = $auth[0]['username'];
				$this->error['email'] = $auth[0]['email'];
				$this->error['lastlogin_time'] = $_SESSION['lastlogin_time'];
				$data = array(
					'id' => $auth[0]['id'],
					'lastlogin_time' => time(),
				);
				$User->data($data)->save();
				$UserLog = M('UserLog');
				$log = array(
					'uid' => $auth[0]['id'],
					'login_time' => date("Y-m-d H:i:s",time()),
					'login_ip' => $_SERVER['REMOTE_ADDR']
				);
				$UserLog->data($log)->add();
	    		//$this->success("登录成功");
	    	}else{
	    		$this->error['error'] .= "1005";
	    		//失败跳转到失败
	    		//$this->error("登录失败","__APP__/Public/login");
	    	}
    	}
    	$this->ajaxerr($this->error);
    }
    
	// 用户登出
    public function logout()
    {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
            //$this->assign("jumpUrl",__URL__.'/login/');
            //$this->success('登出成功！');
            $this->error['error'] = '1000';
        }else {
            $this->error['error'] = '1000';
            //$this->error('已经登出！');
        } 
        $this->ajaxerr($this->error);
    }
    
    
    //检查是否注册成功，成功跳转到登录页面，失败还在注册页面
    function add(){
    	/**
    	 * 验证是否注册成功的方法
    	 *
    	 */
    	if(md5($_POST['verify'])!=$_SESSION['verify']){
    		$this->error("验证码错误");
    	}
    
    	//实例化自定义模型  M('User')实例化基础模型
    	$user=D("User");
    		
    	if($user->create()){
    
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
    
    
    //生成图片验证码
    function verify(){
    	$type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Util.Image");
        Image::buildImageVerify(4,1,$type);
    }    

	
}
?>
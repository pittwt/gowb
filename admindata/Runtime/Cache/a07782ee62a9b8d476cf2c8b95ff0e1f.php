<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录页面</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery.js"></script>
</head>
<body>
	<form action="__URL__/checkLogin"  method="post" >
		<table align="center">
				<tr><td>用户名：</td><td><input type="text" name="username"></td></tr>
				<tr><td>密码：</td><td><input type="password" name="password"></td></tr>
				<tr><td>验证码：</td><td><input type="text" name="verify" maxLength="4" value="" class="input-a"> <img id="verifyImg" SRC="__URL__/verify/" onClick="fleshVerify()" BORDER="0" ALT="点击刷新验证码" style="cursor:pointer" align="absmiddle"></td></tr>
				<tr><td><input type="submit" value="提交"></td>
				<td><a href='__URL__/reg'>注册</a></td></tr>
			
		</table>
	</form>
<script type="text/javascript">
function fleshVerify(type){ 
	//重载验证码
	var timenow = new Date().getTime();
	if (type){
		$('#verifyImg').attr('src', '__URL__/verify/adv/1/'+timenow);
	}else{
		$('#verifyImg').attr('src', '__URL__/verify/'+timenow);
	}

}
</script>
</body>
</html>
<?php 
require 'PHPFetion.php';
$username = '775248699';
$password = 'an123./';
if($_POST)
{
	$fetion = new PHPFetion($username, $password);	// 手机号、飞信密码
	//$message = iconv('gbk', 'utf-8', $_POST['content']);
	$message=$_POST['content'];

	
	$res=$fetion->send($_POST['username'],$message );	// 接收人手机号、飞信内容
	
	error_log(var_export($res,true),3,__FILE__.'.log');
	if((strpos($res,"发送成功")!==false)||(strpos($res,"发送消息成功")!==false))
	{
		echo"发送成功！";
	}
	else
	{
		echo "发送失败！";
	}
}
function DeleteHtml($str)
{ 
	$str = trim($str); 
	$str = strip_tags($str,"");
	$str = ereg_replace("\t","",$str); 
	$str = ereg_replace("\r\n","",$str); 
	$str = ereg_replace("\r","",$str); 
	$str = ereg_replace("\n","",$str); 
	$str = ereg_replace(" "," ",$str); 
	return trim($str); 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>
<form method="post" action="">
接收人手机号<input type="text" name="username"><br />

飞信内容<textarea name="content"></textarea>
<input type="submit" name="submit" />
</form>
</body>
</html>



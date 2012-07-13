<?php
require_once('smtp.class.php');

//发送邮件
$mailbody = 'test email';
//$mailbody = iconv('UTF-8','GBK//IGNORE',$mailbody);
$mailtitle = '抓取新浪微博数据错误';
//$mailtitle = iconv('UTF-8','GBK//IGNORE',$mailtitle);

$smtp = new smtp('smtp.163.com',25,true,'wtanton@163.com','anton123.');
if($smtp->sendmail('447825484@qq.com', 'wtanton@163.com', $mailtitle, $mailbody, 'HTML')){
	echo "success";
} else {
	echo "fail";
}


/*$mail = "447825484@qq.com"; 
$message="测试一封来服务器的邮件"; 
if(mail($mail, "这里一些文本内容", $message, "From: e@ayohome.com\nReply-To:admin@sina.net\nX-Mailer: PHP/" . phpversion())) 
	echo "邮件已发送至$mail "; 
else 
	echo "fail";*/

?>
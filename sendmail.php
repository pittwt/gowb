<?php
require_once('smtp.class.php');

//�����ʼ�
$mailbody = 'test email';
//$mailbody = iconv('UTF-8','GBK//IGNORE',$mailbody);
$mailtitle = 'ץȡ����΢�����ݴ���';
//$mailtitle = iconv('UTF-8','GBK//IGNORE',$mailtitle);

$smtp = new smtp('smtp.qq.com',25,true,'447825484@qq.com','4478Anton.');
if($smtp->sendmail('447825484@qq.com', '447825484@qq.com', $mailtitle, $mailbody, 'HTML')){
	echo "success";
} else {
	echo "fail";
}


/*$mail = "447825484@qq.com"; 
$message="����һ�������������ʼ�"; 
if(mail($mail, "����һЩ�ı�����", $message, "From: e@ayohome.com\nReply-To:admin@sina.net\nX-Mailer: PHP/" . phpversion())) 
	echo "�ʼ��ѷ�����$mail "; 
else 
	echo "fail";*/

?>
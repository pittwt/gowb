<?php
//包含定义配置数据库连接的配置文件
$dbConf=include './config.inc.php';

//定义项目本身常规配置
$Conf=array(
	//'配置项'=>'配置值'
	'URL_MODEL'			=>	0,			//2表示是URL重写模式
	'USER_AUTH_GATEWAY'	=>	'/Index',
		
);
return array_merge($dbConf,$Conf);

?>
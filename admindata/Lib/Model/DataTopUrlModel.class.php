<?php
class DataTopUrlModel extends Model{
	
	//自动验证
	protected $_validate=array(
		//每个字段的详细验证内容
		array("url","require","用户名不能为空"),
		array("url","url"),
		array("detail","require","用户名长度不符合要求",0,'callback'),
	);
	
}
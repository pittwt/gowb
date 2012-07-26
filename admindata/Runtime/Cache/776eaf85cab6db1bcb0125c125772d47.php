<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
header
<?php if($_SESSION[C('USER_AUTH_KEY')] != ''): ?><a href="__APP__/Public/logout" title="logout">logout</a><?php endif; ?>
<hr>

<div class="cbody b-box blank10">
	<ul class="sidebar f_l" style="width:150px; float:left; border-right:1px solid #ccc;">
	<li><a href="__APP__/Index" class="home-link">管理首页</a></li>
	<li><a href="__APP__/Setting">系统设置</a></li>
	<li><a href="__APP__/Topwords">热词榜</a></li>
</ul>
	<div class="b-area-w f_r" style="width:80%;float:right;">
		<table>

			<tr><td>add</td><td><input type="text" name=""></td></tr>
			<tr><td>链接地址</td><td><input type="text" name=""></td></tr>
			<tr><td>描述</td><td><input type="text" name=""></td></tr>
			<tr><td>对应表</td><td><input type="text" name=""></td></tr>
			<tr><td>状态</td><td><input type="text" name=""></td></tr>
			<tr><td>操作</td><td><input type="text" name=""></td></tr>
			<tr><td colspan="2"><input type="submit" value="添加"></td></tr>
			
		</table>
	</div>
	<div class="clear" style="clear:both;"></div>
</div>
<hr>
footer
</body>
</html>
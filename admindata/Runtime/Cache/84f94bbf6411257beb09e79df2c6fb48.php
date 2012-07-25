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
		<h4>管理员信息</h4>
		<table border="0" cellspacing="1" class="tb-b">
			<tr>
				<th>当前用户</th>
				<td><?php echo ($_SESSION['loginusername']); ?> (<?php if($_SESSION[C('USER_AUTH_KEY')] == 1): ?>超级管理员<?php else: ?>普通管理员<?php endif; ?>)</td>
			</tr>
		</table>
		<h4>统计信息</h4>
	</div>
	<div class="clear" style="clear:both;"></div>
</div>
<hr>
footer
</body>
</html>
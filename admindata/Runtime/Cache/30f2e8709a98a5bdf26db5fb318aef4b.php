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
	<a href="__URL__/add">添加</a>
		<table style="border: 1px solid #888888;border-collapse: collapse; margin-top: 10px;">
			<tr>
				<td>Id</td>
				<td>链接地址</td>
				<td>描述</td>
				<td>对应表</td>
				<td>状态</td>
				<td>操作</td>
			</tr>
			<?php if(is_array($topWords)): foreach($topWords as $key=>$value): ?><tr>
				<td><?php echo ($value["id"]); ?></td>
				<td><?php echo ($value["url"]); ?></td>
				<td><?php echo ($value["detail"]); ?>&nbsp;</td>
				<td><?php echo ($value["table"]); ?>&nbsp;</td>
				<td><?php echo ($value["status"]); ?></td>
				<td><a href="__URL__/edit/id/<?php echo ($value["id"]); ?>">修改</a>  <a href="__URL__/delete/id/<?php echo ($value["id"]); ?>">删除</a></td>
			  </tr><?php endforeach; endif; ?>
			<tr><td><?php echo ($page); ?></td></tr>
		</table>
	</div>
	<div class="clear" style="clear:both;"></div>
</div>
<hr>
footer
</body>
</html>
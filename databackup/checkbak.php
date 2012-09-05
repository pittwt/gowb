<?php 
/**
 * 清除备份数据库文件
 */
$dirname = dirname(__FILE__)."/";
$dir = scandir($dirname,1);

//备份文件 保留天数
$size = 4;
$num=0;
foreach ($dir as $item) {
	if(is_dir($dirname.$item) && $item != '.' && $item != '..') {
		$num++;
		if(strtotime($item) < (time()-3600*24*$size) && $num >$size){
			$subdir = scandir($dirname . "/$item",1);
			foreach ($subdir as $value) {
				if (!is_dir($dirname. "/$item".$value)) {
					unlink($dirname . $item . "/".$value);
				}
			}
			rmdir($dirname . $item);
		}
	}
}


?>
<?php 
/**
 * 清除备份文件
 */
$dirname = dirname(__FILE__)."/";
$dir = scandir($dirname,1);
//print_r($dir);
//备份文件 保留天数
$size = 4;
$num=0;
foreach ($dir as $item) {
	//echo var_dump(is_dir($dirname.$item))."$item<br>";
	if(is_dir($dirname.$item) && $item != '.' && $item != '..') {
		$num++;
		if(strtotime($item) < (time()-3600*24*$size) && $num >$size){
			$subdir = scandir($dirname . "/$item",1);
			foreach ($subdir as $value) {
				if (!is_dir($dirname. "/$item".$value)) {
					echo $dirname . $item."/".$value;
					echo unlink($dirname . $item . "/".$value);
					//exit;
				}
				
			}
			rmdir($dirname . $item);
		}
	}
}
//sleep(30);


?>
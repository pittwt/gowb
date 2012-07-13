<?php 
/*
 * Author Anton
 * param spider 
 */

 
class spider2{
	/*
	 *	spider version
	 */ 
	public $version = 'spider v1.0.1';
	
	function __construct(){
		
	}
	
	/*
	 *	获取网页内容
	 */
	public function openUrl($url){
		return file_get_contents($url);
	}
	
	/*
	 *	获取指定区域的文本
	 */
	public function getTextData($content,$start=null,$end=null)
	{
		if($start)$content = strstr($content,$start);
		if($end)$content = substr($content,0,strpos($content,$end));
		return $content;
	}
	
	/*
	 *	获取指定区域的文本 全局匹配 循环标签
	 */
	public function getTextDataAll($content, $start, $end) {
		preg_match_all('|'. $start .'(.*)'.$end.'|U', $content, $out);
		return $out[1];
	}	
	
	/*
	 *	获取指定区域的html内容
	 */
	public function getData($content, $start, $end) {
		if($start)$content = strstr($content,$start);
		if($end)$content = substr($content,0,strpos($content,$end));
		return $content;
	}
	
	/*
	 *	获取指定区域的html内容 全局匹配
	 */
	public function getDataAll($content) {
		preg_match_all("|<span class=\"zw_topic\">(.*)</span>|U", $content, $out);
		return $out[0];
	}

}

?>
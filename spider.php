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
	public $url = null;
	public $content = null;
	
	function __construct($url){
		$this->url = $url;
		$this->openUrl($this->url);
	}
	
	/*
	 *	获取网页内容
	 */
	public function openUrl(){
		return $this->content = file_get_contents($this->$url);
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
	public function getData($content, $start, $end=null) {
		if($start) $content = strstr($content,$start);
		if($end) $content = substr($content,0,strpos($content,$end));
		return $content;
	}
	
	/*
	 *	获取指定区域的html内容 全局匹配
	 */
	public function getDataAll($content) {
		preg_match_all("|<span class=\"zw_topic\">(.*)</span>|U", $content, $out);
		return $out[0];
	}
	
	/*
	 *	微博搜索
	 *	@$search 搜索关键词
	 */
	public function getSweibo($content) {
		$content = $this->ununicode($content);
		$content = $this->getData($content, '<script>STK && STK.pageletM && STK.pageletM.view({"pid":"pl_weibo_feedlist",', '\n"})</script>');
		$content = $this->getData($content, '<div class=\"search_feed\">');
		return stripslashes(str_replace('\n', '', $content));
	}
	
	/*
	 *	ununicode
	 *	@$content 
	 */
	public function ununicode($content) {
		return preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4','\\1'))", $content);
	}

}

?>
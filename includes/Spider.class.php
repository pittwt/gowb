<?php 
/**
 * 抓取新浪微博数据 
 * Author pittwt@gmail.com
 * 
 */ 

 
class Spider{
	/*
	 *	spider version
	 */ 
	public $version = 'spider v1.0.1';
	public $url = null;
	public $content = null;
	
	function __construct($url=NULL){
		if(!is_null($url))
			$this->setUrl($url);
	}
	
	public function setUrl($url) {
		$this->url = $url;
	}
	
	public function _rand() {
		$length=26;
		$chars = "0123456789abcdefghijklmnopqrstuvwxyz";		
		$max = strlen($chars) - 1;	
		mt_srand((double)microtime() * 1000000);		
		$string = '';
		for($i = 0; $i < $length; $i++) {
			$string .= $chars[mt_rand(0, $max)];
		}
		return $string;
	}
	
	
	/*
	 *	获取网页内容
	 */
	public function openUrl(){
		/*$ch = curl_init();
		curl_setopt ($ch,CURLOPT_URL,$this->url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		curl_setopt($ch,CURLOPT_COOKIE,$this->_rand());
		$res = curl_exec($ch);
		curl_close ($ch);*/
		return file_get_contents($this->url);
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
		if($start) {
			$content = strstr($content,$start);
			$content = substr($content, strlen($start), -1);
		}
		if($end) $content = substr($content,0,strpos($content,$end));
		return $content;
	}
	
	/*
	 *	获取指定区域的html内容 全局匹配
	 */
	public function getDataAll($content, $start, $end) {
		preg_match_all('|'. $start .'(.*)'.$end.'|U', $content, $out);
		return $out;
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
	
	/*
	 * 获取搜索微博的全部内容
	 */
	public function getSearchWeiboAll() {
		
		//$page = $this->getSerachPagenum();
		//$Alldata = array();
		//echo $page;exit;
		//for($i=1; $i<=$page; $i++) {
			//$url = $this->url.'&page='.$i;
			//echo $url."<br>";
			$content = $this->openUrl($this->url);
			$content = $this->getSearchWeibo($this->getSweibo($content));
			//$content = array_merge($Alldata, $content);
		//}
		return $content;
	}
	
	/*
	 * 是否新浪机器人验证 
	 * <span>我真滴不是机器人</span> => <span>\u6211\u771f\u6ef4\u4e0d\u662f\u673a\u5668\u4eba<\/span>
	 */
	public function isCheckRobot() {
		$content = $this->openUrl($this->url);
		return strpos($content, '<span>\u6211\u771f\u6ef4\u4e0d\u662f\u673a\u5668\u4eba<\/span>');
	}
	
	/*
	 * 获取搜索微博的单条内容
	 */
	public function getSearchWeibo($content) {
		$list = $this->getSearchWeibolist($content);
		$data = array();
		if(is_array($list[0])){
			foreach($list[0] as $key=>$item){
				$thumbimg = $this->getData($item, '<img class="bigcursor" src="', '"');
				$data[$key] = array(
					'username'	=> $this->getData($item, 'nick-name="', '"'),
					'weibo_content'	=> $this->getData($item, '<em>', '</em>'),
					'weibo_time'	=> substr($this->getData($item, 'date="', '" class="date"'), 0, 10),
					'forward_num'	=> $this->getData($item, '>转发(', ')</a>'),
					'comment_num'	=> $this->getData($item, '>评论(', ')</a>'),
					'is_verify'		=> $this->getData($item, 'http://verified.weibo.com/verify') ? 1 : 0,
					'weibo_thumbimg'	=> $thumbimg,
					'weibo_middleimg'	=> str_replace('/thumbnail/', '/bmiddle/', $thumbimg),
					'weibo_largeimg'	=> str_replace('/thumbnail/', '/large/', $thumbimg),
				);
			}
			return $data;
		}
		return false;
	}
	
	/*
	 * 去除引用微博和评论
	 */
	public function del_forward_weibo($content) {
		$content = str_replace('<dt node-type="feed_list_forwardContent">(.*)</dt>', '', $content);
		$content = str_replace('<dl class="comment W_textc W_linecolor W_bgcolor">(.*)</dd></dl>');
		return $content;
	}
	
	/*
	 * 获取所有微博内容 分组
	 * @return array
	 */
	public function getSearchWeibolist($content) {
		$content = $this->getDataAll($content, '<dd class="content">[\s^<]*<p node-type="feed_list_content">', '<dd class="clear"></dd></dl>');
		return $content;
	}
	
	/*
	 * 获取搜索结果页数
	 */
	public function getSearchNumbers() {
		/*
		 * source 找到 500+ 条结果
		 * \u627e\u5230  找到
		 * \u6761\u7ed3\u679c 条结果
		 */
		$content = $this->openUrl($this->url);
		
		$search = array(',', '+');
		$replace = array('', '');
		$number = $this->getData($content, '>\u627e\u5230', '\u6761\u7ed3\u679c<');
		//echo $this->url."**  $number<br>".$content;exit;
		return str_replace($search, $replace, $number);
	}
	
	/*
	 * 获取搜索结果数
	 */
	public function getSerachPagenum() {
		$numbers = $this->getSearchNumbers();
		if($numbers >= 500) {
			$page = 50;
		} else {
			$page = ceil($numbers / 20);
		}
		return $page;
	}

}





?>
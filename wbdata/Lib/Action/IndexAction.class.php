<?php
/**
 * 抓取新浪微博数据 
 * Author pittwt@gmail.com
 * 
 */ 
 
class IndexAction extends BaseAction {
	
	function _initialize() {
        import("@.ORG.Spider");
        set_time_limit(0);
	}
		
	public function index(){
		$this->display("index");
    }
    
    
    /**
     * 获取新浪微博实时数据
     * $url = 'http://data.weibo.com/top/keyword?k=hour';
     */
	public function topHourly() {
		$DataTopUrl = M('DataTopUrl');
		$url = $DataTopUrl->where('status=1')->select();
		
		foreach($url as $value){
			$table = $value['table'];
			$url = $value['url'];
			
			$spider = new Spider($url);
			$data = $spider->openUrl();
			$data = $spider->getData($data, '<table cellspacing="0" cellpadding="0" class="box_Show_z box_zs">', '</table>');
			
			//错误信息
			$error_info = '';
			
			if($data){
				$keyWords = $spider->getTextDataAll($data, '<span class=\"zw_topic\"><[^>]+>', '</[^>]+></span>');
				$number = $spider->getTextDataAll($data, '<span class="times_zw">', '</span>');
				
				if(empty($keyWords)){
					$error_info .= '获取关键词错误，  ';
				}
				if(empty($number)){
					$error_info .= '获取关键词出现次数错误，  ';
				}
				
				//日志信息
				$log_info = '';
				
				//添加数据来源
				$source = array(
					'origin' => $url,
					'version' => $spider->version,
					'html_source' => str_replace("'",'"',$data),
					'add_time' => time()
				);
				$DataTopSource = M('DataTopSource');
				$source_id = 0;
				if($source_id = $DataTopSource->data($source)->add()){
					$log_info .= 'data_top_source, ';
				}else{
					$error_info .= '添加来源错误，  ';
				}
				
				//添加采集数据
				$items = array();
				$num = 0;
				$Model = new Model();
				foreach($keyWords as $key=>$value){
					$items['source_id'] = $source_id;
					$items['key_words'] = $value;
					$items['number'] = $number[$key];
					$items['add_time'] = time();
					$sql = "insert into `". C('DB_PREFIX') . $table ."` 
						(`source_id`, `key_words`, `number`, `add_time`) 
						values('". $source_id ."', '". $value ."', '". $number[$key] ."', ". time() .")";

					if($Model->execute($sql)){
						$num++;
					}
				}
				if($num>0){
					$log_info .= "data_top_hourly($num)columns, ";
				}else{
					
					$error_info .= '写入数据错误，  ';
				}
				
				//添加日志
				if(!empty($log_info)){
					$log['log_info'] = 'insert '. $log_info ;
					$log['log_time'] = date("Y-m-d H:i:s");
					$AddDataLog = M('AddDataLog');
					$AddDataLog->data($log)->add();
				}
				
			}else{
				$error_info .= '获取实时关键词数据错误，  ';
			}
			//echo "<br>error info:".$error_info."<br>";
			
			//写入错误日志
			if(!empty($error_info)){
				$error = array(
					'error_info' => $error_info."($url)",
					'status' => 0,
					'add_time' => date("Y-m-d H:i:s")
				);
				$ErrorDataLog = M('ErrorDataLog');
				$ErrorDataLog->data($error)->add();
			}
		}
	}   

	public function WbSearch() {
		
	}
	

}
<?php
/*
 * 抓取新浪微博数据 
 * Author Anton
 */ 
set_time_limit(0);
//error_reporting(0); 
require_once('dbconf.php');
require_once('mysql.class.php');
require_once('pinyin.php');

$db = new mysql($host, $user, $pwd, $db, '', 'UTF8');

/* 生成拼音 */
if(isset($_GET['pinyin']) && $_GET['pinyin']==1){
	$sql = "select * from `$t_data_top_hourly` where pinyin = ''";
	$data = $db->findall($sql);
	foreach($data as $value){
		$str = sub_str($value['key_words'], 0, 2);
		$pinyin = Pinyin($str);
		$db->update($t_data_top_hourly, "pinyin = '".$pinyin."'", "id = " . $value['id']);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style>
body{}
a{ text-decoration:none;}
</style>
</head>
<body>

<div style="border-right:1px #ccc solid; float:left; width:100%; text-align:center;">
<h3>字母索引</h3>
<?php
/* 字母索引 */
$tags = array_merge(range("A", "Z"), range(0,9));
foreach($tags as $value){
	echo '<a href="?tags='. $value .'">'.$value."</a>&nbsp;";
}
?>
<form action="" method="get">
    Key Words:
    <input type="text" name="search" />
	<input type="submit" value="search" />
</form>
</div>
<div style=" clear:both; padding-top:20px;">
<?php

/* 根据tags索引 */
if(isset($_GET['tags']) && !empty($_GET['tags'])){
	$content = '';
	//$content = '<form method="get" action="">';
	$sql = "select * from `$t_data_top_hourly` where pinyin like '".$_GET['tags']."%' group by key_words";
	$data = $db->findall($sql);
	foreach($data as $value){
		//$content .= '<input type="checkbox" name="keywords[]" value="'. $value['key_words'] .'">';
		$content .= ' <a href="?keywords[]='.$value['key_words'].'">'. $value['key_words'] .'</a><br>';
	}
	//$content .= '<input type="submit" value="查看数据"></form>';
	echo $content;
}

/* 搜索关键词 */
if(isset($_GET['search']) && !empty($_GET['search'])){
	$sql = "select * from `$t_data_top_hourly` where key_words like '%". $_GET['search'] ."%' group by key_words";
	$data = $db->findall($sql);
	foreach($data as $value){
		echo "keywords:<a href=\"?keywords[]=".$value['key_words']."\">". $value['key_words'] ."</a>, number:". $value['number'] .", time:". date("Y-m-d H:i:s", $value['add_time']) ."<br>";
	}
}


/* 获取keywords数据 */
if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
	
	$keywords = $_GET['keywords'];
	$ydata = array();
	$numbers=0;
	foreach($keywords as $key=>$item) {
		echo $item;
		$sql = "select * from `$t_data_top_hourly` where key_words = '". $item ."' order by add_time asc";
		$data = $db->findall($sql);
		$ydata[$key] = '';
		$xtime = '';
		$i = 1;
		foreach($data as $value){
			if($numbers == $value['number'] && $i==2){
				$i=1;
				continue;
			}
			if($i=2) $i=1;
			$i++;
			$numbers = $value['number'];
			$ydata[$key] .= $value['number'].',';
			$xtime .= '"'.date("Y-m-d H:i:s", $value['add_time']).'",';
		}
		$ydata[$key] = substr($ydata[$key], 0, -1);
		$xtime = substr($xtime, 0, -1);
	}
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
    $(document).ready(function() {
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
    
        var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'spline',
                marginRight: 10,
            },
            title: {
                text: '关键词图表'
            },
            xAxis: {
                categories: [<?php echo $xtime;?>]
            },
            yAxis: {
                title: {
                    text: '提及次数'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }
				]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+ this.x +'<br/>'+this.y;
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [
				<?php foreach($keywords as $key=>$value){
						if($key>0)echo ",";
					?>
                {name: '<?php echo $value;?>',
                data: [<?php echo $ydata[$key];?>],
            }
			<?php }?>
			]
        });
    });
    
});
</script>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</div>
<?php } ?>
</body>
</html>
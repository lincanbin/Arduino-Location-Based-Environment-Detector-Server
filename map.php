<?php
include(dirname(__FILE__)."/common.php");
//Debug Data:
//device_index=13726247339&longitude=113.540718&latitude=22.256467&temperature=32.5&humidity=90&particulate_matter=25
$point = array();
$data_info = array();
foreach ($DB->query('Select * FROM device') as $value)
{

	$point[]=array('lng'=>($value['longitude']/1000000),
				   'lat'=>($value['latitude']/1000000),
				   'count'=>$value['particulate_matter']
				   );
	$data_info[] = array(($value['longitude']/1000000),
				   ($value['latitude']/1000000),
				   $value['name'].'<br />PM2.5浓度：'.$value['particulate_matter'].'μg/m3<br />温度：'.$value['temperature'].'℃<br />湿度'.$value['humidity'].'%<br />最后更新于：'.FormatTime($value['lasttime']).'<br /><a href="graph.php?index='.$value['device_index'].'" target="_blank">查看详情</a>'
				   );
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=eXhX9AeuaFVGlyObGBOebkth"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
	<title>PM2.5分布热力图</title>
	<style type="text/css">
		ul,li{list-style: none;margin:0;padding:0;float:left;}
		html{height:100%}
		body{height:100%;margin:0px;padding:0px;font-family:"微软雅黑";}
		#container{height:640px;width:100%;}
		#r-result{width:100%;}
	</style>	
</head>
<body>
	<div id="container"></div>
	<script type="text/javascript">
	var map = new BMap.Map("container");          // 创建地图实例

	var point = new BMap.Point(113.547649,22.264787);
	map.centerAndZoom(point, 14);             // 初始化地图，设置中心点坐标和地图级别
	map.enableScrollWheelZoom(); // 允许滚轮缩放
  
	var points =<?php echo json_encode($point); ?>;
   
	if(!isSupportCanvas()){
		alert('热力图目前只支持有canvas支持的浏览器,您所使用的浏览器不能使用热力图功能~')
	}
	//详细的参数,可以查看heatmap.js的文档 https://github.com/pa7/heatmap.js/blob/master/README.md
	//参数说明如下:
	/* visible 热力图是否显示,默认为true
	 * opacity 热力的透明度,1-100
	 * radius 势力图的每个点的半径大小   
	 * gradient  {JSON} 热力图的渐变区间 . gradient如下所示
	 *	{
			.2:'rgb(0, 255, 255)',
			.5:'rgb(0, 110, 255)',
			.8:'rgb(100, 0, 255)'
		}
		其中 key 表示插值的位置, 0~1. 
			value 为颜色值. 
	 */
	heatmapOverlay = new BMapLib.HeatmapOverlay({
												"radius":80,
												 "gradient":{
													.2:'rgb(0, 255, 255)',
													.5:'rgb(0, 110, 255)',
													.8:'rgb(100, 0, 255)'
												}
												 });
	map.addOverlay(heatmapOverlay);
	heatmapOverlay.setDataSet({data:points,max:100});

	//添加信息点
	var data_info = <?php echo json_encode($data_info); ?>;
	var opts = {
				width : 160,     // 信息窗口宽度
				height: 160,     // 信息窗口高度
				title : "监测点信息" , // 信息窗口标题
				enableMessage:true//设置允许信息窗发送短息
			   };
	for(var i=0;i<data_info.length;i++){
		var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));  // 创建标注
		var content = data_info[i][2];
		map.addOverlay(marker);               // 将标注添加到地图中
		marker.addEventListener("click",openInfo.bind(null,content));
	}
	function openInfo(content,e){
		var p = e.target;
		var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
		var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
		map.openInfoWindow(infoWindow,point); //开启信息窗口
	}

	//判断浏览区是否支持canvas
	function isSupportCanvas(){
		var elem = document.createElement('canvas');
		return !!(elem.getContext && elem.getContext('2d'));
	}
</script>
</body>
</html>

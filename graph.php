<?php
include(dirname(__FILE__)."/common.php");
//Debug Data:
//device_index=13726247339&longitude=113.540718&latitude=22.256467&temperature=32.5&humidity=90&particulate_matter=25
$data = array();
$Result = $DB->query('Select * FROM logs WHERE device_index=? ORDER BY time ASC',array(Request('Get', 'index', 0)));

$LocationName = $DB->single('Select name FROM device WHERE device_index=?', array(
	Request('Get', 'index', 0)
	));
foreach ($Result as $key => $value) {
	$Result[$key]['time'] = FormatTime($value['time']);
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title><?php echo $LocationName; ?> 历史监测数据</title>
</head>
<body>
	<script src="http://libs.baidu.com/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<script src="static/echarts.js"></script>
	<div id="container" style="width:100%; height: 600px; margin: 0 auto"></div>
	<script>
	$(function () {
		option = {
			title : {
				text: '<?php echo $LocationName; ?> 历史监测数据',
				subtext: '数据来自暨南大学',
				x: 'center',
				align: 'right'
			},
			grid: {
				bottom: 80
			},
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:['温度','湿度', 'PM2.5(μg/m3)'],
				x: 'left'
			},
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					axisLine: {onZero: false},
					data : <?php echo json_encode(ArrayColumn($Result, "time")); ?>.map(function (str) {
						return str.replace(' ', '\n')
					})
				}
			],
			yAxis: [
				{
					type: 'value'
				}
			],
			dataZoom: [{
				type: 'inside',
				start: 0,
				end: 10
			}, {
				start: 0,
				end: 10
			}],
			series: [
				{
					name:'温度',
					type:'line',
					areaStyle: {normal: {}},
					data: <?php echo json_encode(ArrayColumn($Result, "temperature")); ?>
				},
				{
					name:'湿度',
					type:'line',
					areaStyle: {normal: {}},
					data: <?php echo json_encode(ArrayColumn($Result, "humidity")); ?>
				},
				{
					name:'PM2.5',
					type:'line',
					areaStyle: {normal: {}},
					data: <?php echo json_encode(ArrayColumn($Result, "particulate_matter")); ?>
				}
			]
		};
		var statistics = echarts.init(document.getElementById('container'));
		statistics.setOption(option);

	});
	</script>
</body>
</html>
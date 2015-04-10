<?php
include(dirname(__FILE__)."/common.php");
//Debug Data:
//device_index=13726247339&longitude=113.540718&latitude=22.256467&temperature=32.5&humidity=90&particulate_matter=25
$data = array();
$Result = $DB->query('Select * FROM logs WHERE device_index=? ORDER BY time ASC',array(Request('Get', 'index', 0)));
$Width = count($Result)*40;
foreach ($Result as $key => $value) {
	$value['time'] = FormatTime($value['time']);
	$data[] = $value;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title><?php echo $DB->single('Select name FROM device WHERE device_index=?', array(
	Request('Get', 'index', 0)
	)); ?> 历史监测数据</title>
</head>
<body>
	<script src="http://libs.baidu.com/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<script src="http://ourjnu.com/static/js/highcharts.js"></script>
	<script src="http://ourjnu.com/static/js/exporting.js"></script>
	<div id="container" style="width:100%; min-width: <?php echo $Width; ?>px; height: 600px; margin: 0 auto"></div>
	<script>
	$(function () {
		$('#container').highcharts({
			title: {
				text: '历史监测数据',
				x: -20 //center
			},
			subtitle: {
				text: '',
				x: -20
			},
			xAxis: {
				categories: <?php echo json_encode(ArrayColumn($data, 'time')); ?>
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				valueSuffix: ''
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			},
			series: [{
				name: '温度(℃)',
				data: <?php echo str_replace('"','',json_encode(ArrayColumn($data, 'temperature'))); ?>
			},{
				name: '湿度(%)',
				data: <?php echo str_replace('"','',json_encode(ArrayColumn($data, 'humidity'))); ?>
			},{
				name: 'PM2.5(μg/m3)',
				data: <?php echo str_replace('"','',json_encode(ArrayColumn($data, 'particulate_matter'))); ?>
			}]
		});
	});
	</script>
</body>
</html>
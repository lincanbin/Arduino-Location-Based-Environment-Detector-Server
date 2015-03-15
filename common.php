<?php
date_default_timezone_set('PRC');
require(dirname(__FILE__)."/includes/PDO.class.php");
$DB = new Db();

$TimeStamp = time();

function Request($Type, $Key ,$DefaultValue='')
{
	switch ($Type) {
		case 'Get':
			return array_key_exists($Key, $_GET) ? trim($_GET[$Key]) : $DefaultValue;
			break;
		case 'Post':
			return array_key_exists($Key, $_POST) ? trim($_POST[$Key]) : $DefaultValue;
			break;
		default:
			return array_key_exists($Key, $_REQUEST ) ? trim($_REQUEST [$Key]) : $DefaultValue;
			break;
	}
}

//获取数组中的某一列
function ArrayColumn($Input, $ColumnKey)
{
	if (version_compare(PHP_VERSION, '5.5.0') < 1) {
		$Result = array();
		if($Input)
		{
			foreach ($Input as $Value) {
				$Result[] = $Value[$ColumnKey];
			}
		}
		return $Result;
	}else{
		return array_column($Input, $ColumnKey);
	}
}

// 格式化时间
function FormatTime($db_time)
{
	$diftime = time() - $db_time;
	if ($diftime < 2592000) {
		// 小于30天如下显示
		if ($diftime >= 86400) {
			return round($diftime / 86400, 0) . '天前';
		} else if ($diftime >= 3600) {
			return round($diftime / 3600, 0) . '小时前';
		} else if ($diftime >= 60) {
			return round($diftime / 60, 0) . '分钟前';
		} else {
			return ($diftime + 1) . '秒钟前';
		}
	} else {
		// 大于一年
		return date("Y-m-d", $db_time);
		//gmdate()可以返回格林威治标准时，date()则为当地时
	}
}
?>
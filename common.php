<?php
date_default_timezone_set('Asia/Shanghai');

require(dirname(__FILE__)."/includes/PDO.class.php");

define('DBHost', '127.0.0.1'); 
define('DBName', 'monitor');
define('DBUser', 'root'); 
define('DBPassword', ''); 
$DB = new Db(DBHost, DBName, DBUser, DBPassword); 

$TimeStamp = $_SERVER['REQUEST_TIME'];

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
	return date("Y-m-d H:i", $db_time);
}
?>
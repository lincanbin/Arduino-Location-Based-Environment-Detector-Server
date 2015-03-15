<?php
include(dirname(__FILE__)."/common.php");
//Debug Data:
//http://monitor.ourjnu.com/submit.php?device_index=13726247339&longitude=113.540718&latitude=22.256467&temperature=32.5&humidity=90&particulate_matter=25
$insert = array(
				null,
				Request('Get','device_index',0),
				$TimeStamp,
				Request('Get','longitude',0)*1000000,
				Request('Get','latitude',0)*1000000,
				Request('Get','temperature',0),
				Request('Get','humidity',0),
				Request('Get','particulate_matter',0)
);
$DB->query("INSERT INTO `logs`(`id`, `device_index`, `time`, `longitude`, `latitude`, `temperature`, `humidity`, `particulate_matter`) VALUES (?,?,?,?,?,?,?,?)", $insert);
$DB->query("UPDATE device SET lasttime=?,longitude=?,latitude=?,temperature=?,humidity=?,particulate_matter=? WHERE device_index=?", array(
														$TimeStamp,
														Request('Get','longitude',0)*1000000,
														Request('Get','latitude',0)*1000000,
														Request('Get','temperature',0),
														Request('Get','humidity',0),
														Request('Get','particulate_matter',0),
														Request('Get','device_index',0)));
echo 'ok';
?>
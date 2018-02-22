<?php
// Including the database function
include './resources/includes/databasefunc.inc';
	$cookie_retrieval = optic_query($db,"SELECT pers_data, pers_used FROM persistent_login WHERE pers_token='6qteJ2mifq7hnUdUdelGnqoUAWyjrEC65v4pnrPCptZcBf3JQcAfESSJRCJRoloNWRbaRfZjbevRSl17DZkDLgx1BWNu5YwN6qrswB5ZXBE0T4Ny44bp8VDRap5Sq0YFY8xZtrBVpoIU2xuXEdh9wg8ieRYAwGpzl4WiNukWGjbkAN6R6uL7q6xv31mm2Fukv6dIwHoLaRetwXeWhn0xLWMPtHAGv8iGHo28RhOu1eyH6iiLJEQV2ajSkh2Mz3oI' AND (pers_expiry > now()) ;");
$temp_save = pg_fetch_row($cookie_retrieval);
var_dump($temp_save);
?>

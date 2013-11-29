<?php

$db = eZDB::instance();
$time = time();
$xrowChangeMailINI = eZINI::instance( 'xrowmailchange.ini' );
$keep_duration_hours = $xrowChangeMailINI->variable( 'GeneralSettings', 'KeepChangeRequest' );
$keep_duration_timestamp = $keep_duration_hours * 3600;
$min_keep_time = $time - $keep_duration_timestamp;

$db->begin();
$db->arrayQuery("DELETE FROM xrow_mailchange WHERE change_time < $min_keep_time");
$db->commit();

?>
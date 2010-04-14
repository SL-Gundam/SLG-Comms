<?php
/***************************************************************************
 *                              config.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: config.inc.php,v 1.8 2005/06/30 19:40:04 SC Kruiper Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//security through the use of define != defined
if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}

$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];

//define("DEBUG", 10); /*whether or not to enable debug features. disable by adding 2 backslashes in front of this line and enable by removing them again*/

include('includes/functions.inc.php');
include('includes/classes.inc.php');

include('dbsettings.inc.php');

if ( !defined('NO_DATABASE') ){
	$table['cache'] = $tssettings['table_prefix'].'cache';
	$table['resources'] = $tssettings['table_prefix'].'resources';
	$table['sessions'] = $tssettings['table_prefix'].'sessions';
	$table['settings'] = $tssettings['table_prefix'].'settings';

	require('includes/db/'.$tssettings['db_type'].'.inc.php');

	/* Connect to mysql and database */
	$db = new db;
	$db->connect('pzserverconnect', $tssettings['db_host'], $tssettings['db_user'], $tssettings['db_passwd'], $tssettings['db_name']);
	if ($tssettings['db_type'] == 'mysql'){
		$db->selectdb('pzdatabaseconnect', $tssettings['db_name']);
	}

	// retrieve settings from the database
	$getconfig = $db->execquery('getconfig','SELECT
  variable,
  value
FROM
  '.$table['settings']);

	while ($row = $db->getrow($getconfig)) {
		$tssettings[$row['variable']] = $row['value'];
	}
	$db->freeresult('getconfig',$getconfig);
}

// do we enable gzip compression or not?
$useragent = (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

$zlib_status = ini_get('zlib.output_compression');

if (isset($tssettings['GZIP Compression']) && $tssettings['GZIP Compression'] && (strstr($useragent,'compatible') || strstr($useragent,'Gecko')) && extension_loaded('zlib') && (empty($zlib_status))){
	ob_start('ob_gzhandler');
}
?>

<?php
/***************************************************************************
 *                              config.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: config.inc.php,v 1.37 2007/01/30 16:16:47 SC Kruiper Exp $
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
if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

$mtime = explode( ' ', microtime() );
$starttime = $mtime[1] + $mtime[0];
unset( $mtime );

//define( "SLG_DEBUG", 10 ); /*whether or not to enable debug features. disable by adding 2 backslashes in front of this line and enable by removing them again. This should NEVER be activated on a live website as it might expose you database username, database password and possibly other information which could compromise your webserver to outside attacks.*/

require( $tssettings['Root_path'] . 'includes/functions.inc.php' );
require( $tssettings['Root_path'] . 'includes/classes.inc.php' );

require( $tssettings['Root_path'] . 'dbsettings.inc.php' );

if ( !empty($_POST) )
{
	processincomingdata( $_POST, true );
}

if ( !defined('NO_DATABASE') || ( isset($_POST['variable']['table_prefix']) && checkfilelock('install.php') ) )
{
	if ( isset($_POST['variable']['table_prefix']) && checkfilelock('install.php') )
	{
		$tssettings['table_prefix'] = $_POST['variable']['table_prefix'];
	}

	if ( preg_replace( '/[^a-z0-9_]/i', '', $tssettings['table_prefix'] ) !== $tssettings['table_prefix'] )
	{
		early_error( '{TEXT_UNACCEPTABLE_TABLEPREFIX}' );
	}

	$table['cache'] = $tssettings['table_prefix'] . 'cache';
	$table['resources'] = $tssettings['table_prefix'] . 'resources';
	$table['sessions'] = $tssettings['table_prefix'] . 'sessions';
	$table['settings'] = $tssettings['table_prefix'] . 'settings';
}

if ( !defined('NO_DATABASE') )
{
	$tssettings['db_type'] = ( ( $tssettings['db_type'] === 'mysql' || $tssettings['db_type'] === 'mysql41' ) ? $tssettings['db_type'] : ( ( extension_loaded('mysqli') ) ? 'mysql41' : 'mysql' ) );
	require( $tssettings['Root_path'] . 'includes/db/' . $tssettings['db_type'] . '.inc.php' );

	/* Connect to mysql and database */
	$db = new database;
	$db->connect( 'pzserverconnect', $tssettings['db_host'], $tssettings['db_user'], $tssettings['db_passwd'], $tssettings['db_name'] );

	// retrieve settings from the database
	$sql = '
SELECT
  `variable`,
  `value`
FROM
  `%1$s`';
	$getconfig = $db->execquery( 'getconfig', $sql, $table['settings'] );

	while ( $row = $db->getrow($getconfig) )
	{
		$tssettings[ $row['variable'] ] = $row['value'];
	}
	$db->freeresult( 'getconfig', $getconfig );
	unset( $sql, $row, $getconfig );
}

// checks whether file and installed version differ
$new_version = 'v3.1.0';
if ( !checkfilelock('install.php') )
{
	if ( !isset($tssettings['SLG_version']) || $new_version !== $tssettings['SLG_version'] )
	{
		early_error( '{TEXT_SLGVERSIONCONFLICT}' );
	}
	unset( $new_version );
}

// do we enable gzip compression or not?
$useragent = ( ( isset($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT') );

if ( isset($tssettings['GZIP_Compression']) && $tssettings['GZIP_Compression'] && ( strstr($useragent, 'compatible') || strstr($useragent, 'Gecko') ) )
{
	if ( extension_loaded('zlib') )
	{
		ob_start( 'ob_gzhandler' );
	}
	else
	{
		$tssettings['GZIP_Compression'] = false;
	}
}
unset( $useragent );
?>

<?php
/***************************************************************************
 *                                index.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: index.php,v 1.51 2008/03/24 16:07:50 SC Kruiper Exp $
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

define( "IN_SLG", 10 );
$tssettings['Root_path'] = './';

require( $tssettings['Root_path'] . 'includes/config.inc.php' );
require_once( $tssettings['Root_path'] . 'includes/header.inc.php' );

// start new template
$index = new template;
$template = 'index';

// prepare server list in case database support is enabled
if ( !defined('NO_DATABASE') )
{
	if ( $tssettings['TeamSpeak_support'] && !$tssettings['Ventrilo_support'] )
	{
		$types = '"TeamSpeak", "TSViewer.com"';
	}
	elseif ( !$tssettings['TeamSpeak_support'] && $tssettings['Ventrilo_support'] )
	{
		$types = '"Ventrilo"';
	}
	elseif ( $tssettings['TeamSpeak_support'] && $tssettings['Ventrilo_support'] )
	{
		$types = '"TeamSpeak", "TSViewer.com", "Ventrilo"';
	}
	else
	{
		$types = NULL;
		$servers = array();
	}

	if ( isset($types) )
	{
		$sql = '
SELECT `res_id`, `res_name`
FROM `%1$s` 
WHERE `res_type` IN (%2$s)
ORDER BY `res_name`';

		$getservers = $db->execquery( 'getservers', $sql, array(
			$table['resources'],
			$types
		) );

		$servers = array();
		while( $rowts = $db->getrow($getservers) )
		{
			$servers[] = $rowts;
		}
		$db->freeresult( 'getservers', $getservers );
		unset( $types, $rowts, $sql, $getservers );
	}
}
elseif ( !$tssettings['TeamSpeak_support'] && !$tssettings['Ventrilo_support'] )
{
	$servers = array();
}

//whether or not to display the server list table
$servers = array_values( $servers );
$servertable = ( ( count($servers) > 1 ) || $tssettings['Custom_servers'] );
$index->insert_display( '{SELECT_SERVER}', $servertable );

if ( $servertable )
{
	$server = build_serverlist();
}
elseif ( count($servers) === 1 )
{
	//in case the server list table was disabled we still need to fill the variables below
	reset( $servers );
	$server = $servers[0]['res_id'];
}
else
{
	$errors = array();
	if ( !$tssettings['TeamSpeak_support'] )
	{
		$errors = array_merge( $errors, array( '{TEXT_SUPPORT_TS_DISABLED}' ) );
	}
	if ( !$tssettings['Ventrilo_support'] )
	{
		$errors = array_merge( $errors, array( '{TEXT_SUPPORT_VENT_DISABLED}' ) );
	}
	
	early_error( array_merge( $errors, array( '{TEXT_NOSERVERS}', '{TEXT_NOCUSTOMSERVERS}', '{TEXT_NOTHINGTODO}' ) ) );
}
unset( $servers, $servertable );

if ( $server !== false && !defined("NO_DATABASE") )
{
	$sql = '
SELECT 
  `res_id`,
  `res_data`,
  `res_type`
FROM
  `%1$s`
WHERE
  `res_id` = %2$u
LIMIT 0,1';

	$getserver = $db->execquery( 'getserver', $sql, array(
		$table['resources'],
		$server
	) );

	$server = $db->getrow( $getserver );

	$db->freeresult( 'getserver', $getserver );
	unset( $sql, $getserver );
}
elseif( $tssettings['Custom_servers'] && isset($_POST['ipport']) && (int) $_POST['ipbyname'] === 0 && $server === false )
{
	$server['res_id'] = 0;
	$server['res_data'] = $_POST['ipport'];
	$server['res_type'] = ( ( isset($_POST['type']) ) ? $_POST['type'] : NULL );
	$server['ventsort'] = $_POST['ventsort'];
}

//process the template
$index->insert_text( '{BASE_URL}', ( !empty( $tssettings['Base_url'] ) ? 'http://' . $tssettings['Base_url'] : NULL ) );
$index->insert_text( '{TEMPLATE}', ( !empty( $tssettings['Template'] ) ? $tssettings['Template'] : 'Default' ) );
$index->load_language( 'lng_index' );
$index->load_template( 'tpl_index' );
$index->process();
$index->output();
unset( $index, $template );

// Let's check whether the selected server (custom or predefined) is acceptable (ip / hostname, port and the optional queryport) - minimum amount of data available. More elaborate format check will be performed on a later stage.
if ( isset($server['res_id']) && ( empty($server['res_data']) || empty($server['res_type']) ) )
{
	early_error( '{TEXT_IP_PORT_COMB_ERROR}' );
}

// start the process of retrieving server data for either TeamSpeak or Ventrilo
if ( isset($server['res_type']) && $server['res_type'] === 'TeamSpeak' && $tssettings['TeamSpeak_support'] )
{
	require( $tssettings['Root_path'] . 'includes/teamspeak.inc.php' );
}
elseif ( isset($server['res_type']) && $server['res_type'] === 'Ventrilo' && $tssettings['Ventrilo_support'] )
{
	require( $tssettings['Root_path'] . 'includes/ventrilo.inc.php' );
}
elseif ( isset($server['res_type']) && $server['res_type'] === 'TSViewer.com' && $tssettings['TeamSpeak_support'] )
{
	require( $tssettings['Root_path'] . 'includes/tsviewer.inc.php' );
}
else
{
	unset( $server );
}

require_once( $tssettings['Root_path'] . 'includes/footer.inc.php' );
?>

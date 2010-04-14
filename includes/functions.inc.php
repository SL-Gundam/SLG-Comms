<?php
/***************************************************************************
 *                             functions.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.inc.php,v 1.52 2006/06/24 18:28:18 SC Kruiper Exp $
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

if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

//recursive function that performs addslashes on all items
function processaddslashes( &$value, $key=NULL, $level=0 )
{
	if ( $level > 10 )
	{
		early_error( '{TEXT_RECURSIVE_FUNC_PROT}' );
	}

	if( is_array($value) )
	{
		array_walk( $value, 'processaddslashes', ( $level + 1 ) );
	}
	else
	{
		$value = addslashes( $value );
	}
}

//recursive function that performs stripslashes on all items
function processstripslashes( &$value, $key=NULL, $level=0 )
{
	if ( $level > 10 )
	{
		early_error( '{TEXT_RECURSIVE_FUNC_PROT}' );
	}

	if( is_array($value) )
	{
		array_walk( $value, 'processstripslashes', ( $level + 1 ) );
	}
	else
	{
		$value = stripslashes( $value );
	}
}

// function that decides which one of the 2 recursive slash functions above should be run
function processincomingdata( &$data, $strip=false )
{
	$magicgpc = get_magic_quotes_gpc();
	if ( !$magicgpc && !$strip )
	{
		processaddslashes( $data );
	}
	elseif ( $magicgpc && $strip )
	{
		processstripslashes( $data );
	}
}

// this function prepares the server list and the custom server fields for index.php
function build_serverlist()
{
	// building the server list
	$serverlist = ( ( $GLOBALS['tssettings']['Custom_servers'] ) ? '<option value="0">{TEXT_CUSTOM_SERVER}</option>' : NULL );
	if ( defined('NO_DATABASE') )
	{
		usort ( $GLOBALS['servers'], "SORT_SERVERS" );
	}

	reset( $GLOBALS['servers'] );
	while( $server = array_shift($GLOBALS['servers']) )
	{
		if ( defined('NO_DATABASE') )
		{
			if ( 
				( !$GLOBALS['tssettings']['TeamSpeak_support'] && $server['res_type'] === 'TeamSpeak' ) || 
				( !$GLOBALS['tssettings']['Ventrilo_support'] && $server['res_type'] === 'Ventrilo' ) 
			)
			{
				continue;
			}
		}
		$server['res_id'] = (int) $server['res_id'];
		$serverlist .= '<option value="' . $server['res_id'] . '"';
		if ( 
			( isset($_REQUEST['ipbyname']) && (int) $_REQUEST['ipbyname'] === $server['res_id'] ) || 
			( !isset($_REQUEST['ipbyname']) && (int) $GLOBALS['tssettings']['Default_server'] === $server['res_id'] ) 
		)
		{
			$serverlist .= ' selected';
			if ( defined('NO_DATABASE') )
			{
				$res_id = $server;
			}
			else
			{
				$res_id = $server['res_id'];
			}
		}
		$serverlist .= '>' . htmlentities( $server['res_name'] ) . '</option>';
	}
	// insert the serverlist into the template
	$GLOBALS[ $GLOBALS['template'] ]->insert_content( '{IPBYNAME_OPTIONS}', $serverlist );

	// whether or not to enable the custom server ability
	$GLOBALS[ $GLOBALS['template'] ]->insert_display( '{CUSTOM_SERVER}', $GLOBALS['tssettings']['Custom_servers'] );
	if ( $GLOBALS['tssettings']['Custom_servers'] )
	{
		$typeopt = NULL;
		if ( $GLOBALS['tssettings']['TeamSpeak_support'] )
		{
			$typeopt .= '<option value="TeamSpeak"' . ( ( isset($_POST['type']) && $_POST['type'] === 'TeamSpeak' ) ? ' selected' : NULL ) . '>TeamSpeak</option>';
	  	}
		if ( $GLOBALS['tssettings']['Ventrilo_support'] )
		{
			$typeopt .= '<option value="Ventrilo"' . ( ( isset($_POST['type']) && $_POST['type'] === 'Ventrilo' ) ? ' selected' : NULL ) . '>Ventrilo</option>';
		}
		$GLOBALS[ $GLOBALS['template'] ]->insert_text( '{TYPE_OPTIONS}', $typeopt );
		$GLOBALS[ $GLOBALS['template'] ]->insert_text( '{IPPORT_VALUE}', ( ( isset($_POST['ipport']) ) ? $_POST['ipport'] : NULL ) );
		$GLOBALS[ $GLOBALS['template'] ]->insert_text( '{VENT_SORT_ALPHA}', ( ( !isset($_POST['ventsort']) || $_POST['ventsort'] === 'alpha' ) ? ' checked ' : NULL ) );
		$GLOBALS[ $GLOBALS['template'] ]->insert_text( '{VENT_SORT_MANUAL}', ( ( isset($_POST['ventsort']) && $_POST['ventsort'] === 'manual' ) ? ' checked ' : NULL ) );
	}
	return( ( ( isset($res_id) ) ? $res_id : false ) );
}

// this function allows for easy removal of certain strings from a string
function removechars( $str, $chars )
{
	return( str_replace( $chars, '', $str ) );
}

// this function handles errors which require the full stop of further script execution. ea. sql query errors
function early_error( $messages, $sql=NULL, $sqlerror=NULL )
{
	if ( isset( $GLOBALS['template'], $GLOBALS[ $GLOBALS['template'] ] ) )
	{
		unset( $GLOBALS[ $GLOBALS['template'] ], $GLOBALS['template'] );
	}

	require_once( $GLOBALS['tssettings']['Root_path'] . 'includes/header.inc.php' );

	$error = new template;

	$messages = array_values( (array) $messages );

	while ( $message = array_shift($messages) )
	{
		$error->displaymessage( '{TEXT_ERROR}: ' . $message, $sql, $sqlerror );
	}

	$error->insert_text( '{BASE_URL}', ( isset( $GLOBALS['tssettings']['Base_url'] ) ? 'http://' . $GLOBALS['tssettings']['Base_url'] : NULL ) );
	$error->insert_text( '{TEMPLATE}', ( isset( $GLOBALS['tssettings']['Template'] ) ? $GLOBALS['tssettings']['Template'] : 'Default' ) );
	$error->load_language( 'lng_earlyerrors' );
	$error->process();
	$error->output();
	unset( $error, $message, $messages, $sql, $sqlerror );

	require_once( $GLOBALS['tssettings']['Root_path'] . 'includes/footer.inc.php' );
}

// this function formats a amount of seconds into a nicely readable set
function formattime( $seconds )
{
	$divider_units = array( 31556926, 86400, 3600, 60, 1 ); // 1 Year = 31556926 seconds = 365,242199074074 etc.etc. days according to google ( google query = "1 year in seconds" )
	$divider_var = array( 'YEARS', 'DAYS', 'HOURS', 'MINUTES', 'SECONDS' );
	for ( $i=0, $max=count($divider_units); $i < $max; $i++ )
	{
		$time[ $divider_var[ $i ] ] = floor( $seconds / $divider_units[ $i ] );
		$seconds -= $time[ $divider_var[ $i ] ] * $divider_units[ $i ];

		switch ( $time[ $divider_var[ $i ] ] )
		{
			case '1':
				$time[ $divider_var[ $i ] ] .= ' {TEXT_' . rtrim( $divider_var[ $i ], 'S' ) . '}';
				break;
			case '0':
				$time[ $divider_var[ $i ] ] = NULL;
				break;
			default:
				$time[ $divider_var[ $i ] ] .= ' {TEXT_' . $divider_var[ $i ] . '}';
		}

		$count[ $divider_var[ $i ] ] = ( ( isset( $time[ $divider_var[ $i ] ] ) ) ? 1 : 0 );
	}

	$tcount = array_sum( $count );
	if ($tcount > 1)
	{
		if ( $count['SECONDS'] === 1 )
		{
			$time['SECONDS'] = '{TEXT_AND} ' . $time['SECONDS'];
		}
		elseif ( $count['MINUTES'] === 1 )
		{
			$time['MINUTES'] = '{TEXT_AND} ' . $time['MINUTES'];
		}
		elseif ( $count['HOURS'] === 1 )
		{
			$time['HOURS'] = '{TEXT_AND} ' . $time['HOURS'];
		}
	}
	elseif ( $tcount === 0 )
	{
		$time['SECONDS'] = '0 {TEXT_SECONDS}';
	}

	return( trim( implode( ' ', $time ) ) );
}

// this function formats the bytes so that they are easily readable.
function formatbytes( $bytes )
{
	$units = array( ' {TEXT_BYTES}', ' {TEXT_KB}', ' {TEXT_MB}', ' {TEXT_GB}', ' {TEXT_TB}' );
	for ( $i=0; $bytes > 1024; $i++ )
	{
		$bytes /= 1024;
	}
	return( round( $bytes, 2 ) . $units[ $i ] );
}

// this function cuts strings that are to long according to the parameter
function linewrap( $str, $maxlength )
{
	if ( strlen($str) > $maxlength )
	{
		$str = trim( substr( $str, 0, ( $maxlength-2 ) ) ) . '...';
	}
	return( $str );
}

// This function prepares a tooltip so that it doesn't cause problems with the javascripts
// expects htmlentities() to be performed on $msg
function prep_tooltip( $msg )
{
	return( str_replace( "\n", '<br />', addslashes( str_replace( '&', '&amp;', $msg ) ) ) );
}

// this function checks which file is currently being run (index.php, admin.php or install.php)
function checkfilelock( $file_name )
{
	return( basename( $_SERVER['PHP_SELF'] ) === $file_name );
}

// this function checks the validity of an ip address, port number and optionally also the queryport
function check_ip_port( $ip, $port, $queryport=NULL )
{
	$testip = ip2long( $ip );
	if ( $testip === -1 || $testip === FALSE )
	{
		$ip = gethostbyname( $ip );
		$testip = ip2long( $ip );
	}
	//port check
	$port = ( ( $port > 0 && $port < 65535 && is_numeric( $port ) ) ? true : false );
	//queryport check
	$queryport = ( ( is_null( $queryport ) || ( $queryport > 0 && $queryport < 65535 && is_numeric( $queryport ) ) ) ? true : false );
	//ipv4 check
	$ipv4_parts = substr_count( $ip, '.' );
	$ipv4 = ( ( $testip !== -1 && $testip !== false && $ipv4_parts === 3 ) ? true : false );
	//ipv6 check - EXPERIMENTAL
/*	$ipv6_parts = substr_count( $ip, ':' );
	$ipv6 = ( ( $ipv6_parts >= 2 && $ipv6_parts <= 7 ) ? true : false );*/

	return( $port && ( $ipv4 /*|| $ipv6*/ ) && $queryport );
}

// this function prepares a email address
function prepare_email_addr( $address, $text=NULL, $hyperlink=true )
{
	if ( preg_match("#([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", $address) )
	{
		$search = array( '@', '.' );
		if ( empty($text) )
		{
			$text = str_replace( $search, array( ' [at] ', ' [dot] ' ), $address );
		}
		if ( (bool) $hyperlink )
		{
			$address = str_replace( $search, array('&#64;', '&#46;'), $address );
		}

		return( ( ( (bool) $hyperlink ) ? '<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#58;' . $address . '">' : NULL ) . $text . ( ( (bool) $hyperlink ) ? '</a>' : NULL ) );
	}
	return( NULL );
}

// this function prepares a hyperlink
function prepare_http_link( $url, $text=NULL, $hyperlink=true )
{
	if ( preg_match("#([\w]+?://[^ \"\n\r\t<]*)#is", $url) || preg_match("#((www)\.[^ \"\t\n\r<]*)#is", $url) )
	{
		$checkurl = parse_url( $url );
		if ( !isset($checkurl['scheme']) )
		{
			$url = 'http://' . $url;
		}
		return( ( ( (bool) $hyperlink ) ? '<a href="' . $url . '" target="_blank">' : NULL ) . ( ( empty( $text ) ) ? $url : $text ) . ( ( (bool) $hyperlink ) ? '</a>' : NULL ) );
	}
	return( NULL );
}

// incase you don't use PHP5 this function doesn't exist.
if ( !function_exists('array_combine') )
{
	function array_combine( $keys, $values )
	{
		$combined = array();
		for ( $i=0, $max=count($keys); $i < $max; $i++ )
		{
			$combined[ $keys[ $i ] ] = $values[ $i ];
		}

		return( $combined );
	}
}

// incase you don't use PHP5 this function doesn't exist
if( !function_exists('file_get_contents') )
{
	function file_get_contents( $filename )
	{
		return( implode( '', file( $filename ) ) );
	}
}

// serverlist sort function
function SORT_SERVERS( $a, $b )
{
	$comp_result = strcasecmp( $a['res_name'], $b['res_name'] );
	if ( $comp_result === 0 )
	{
		return( 0 );
	}
	else
	{
		return( ( $comp_result < 0 ) ? -1 : 1 );
	}
}

// TeamSpeak sort function
function SORT_CLIENTS( $a, $b )
{
	if ( $a['pprivs'][1] && $b['pprivs'][1] ) // both server admins check
	{
		$comp_result = strcasecmp( $a['slg_sortname'], $b['slg_sortname'] ); // name sorting
		if ( !empty($a['slg_sortname']) && !empty($b['slg_sortname']) && $comp_result === 0 )
		{
			return( 0 );
		}
		else
		{
			return( ( !empty($a['slg_sortname']) && !empty($b['slg_sortname']) && $comp_result < 0 ) ? -1 : 1 );
		}
	}
	elseif ( !$a['pprivs'][1] && !$b['pprivs'][1] ) // both not server admins check
	{
		if ( 
			( $a['cprivs'][1] && $b['cprivs'][1] ) || 
			( !$a['cprivs'][1] && !$b['cprivs'][1] ) 
		) // both channel admins or both not channel admins
		{
			$comp_result = strcasecmp( $a['slg_sortname'], $b['slg_sortname'] ); // name sorting
			if ( !empty($a['slg_sortname']) && !empty($b['slg_sortname']) && $comp_result === 0 )
			{
				return( 0 );
			}
			else
			{
				return( ( !empty($a['slg_sortname']) && !empty($b['slg_sortname']) && $comp_result < 0 ) ? -1 : 1 );
			}
		}
		else // sorting on channel admin right
		{
			return( ( $a['cprivs'][1] ) ? -1 : 1 );
		}
	}
	else // sorting on server admin right
	{
		return( ( $a['pprivs'][1] ) ? -1 : 1 );
	}
}

function SORT_CHANNELS( $a, $b )
{
	if ( $a['order'] === $b['order'] )
	{
		$comp_result = strcasecmp( $a['slg_sortname'], $b['slg_sortname'] );
		if ( !empty($a['slg_sortname']) && !empty($b['slg_sortname']) && $comp_result === 0 )
		{
			return( 0 );
		}
		else
		{
			return( ( !empty($a['slg_sortname']) && !empty($b['slg_sortname']) && $comp_result < 0 ) ? -1 : 1 );
		}
	}
	else
	{
		return( ( $a['order'] < $b['order'] ) ? -1 : 1 );
	}
}

// Ventrilo sort functions
function SORT_VENTCHANCLI( $a, $b )
{
	$comp_result = strcasecmp( $a['NAME'], $b['NAME'] );
	if ( $comp_result === 0 )
	{
		return( 0 );
	}
	else
	{
		return( ( $comp_result < 0 ) ? -1 : 1 );
	}
}
?>

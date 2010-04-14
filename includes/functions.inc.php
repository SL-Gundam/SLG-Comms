<?php
/***************************************************************************
 *                             functions.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.inc.php,v 1.56 2008/08/12 22:59:41 SC Kruiper Exp $
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
function processaddslashes( &$value, $level=0 )
{
	if ( $level > 10 )
	{
		early_error( '{TEXT_RECURSIVE_FUNC_PROT}' );
	}

	if( is_array($value) )
	{
		array_map( $value, 'processaddslashes', ( $level + 1 ) );
	}
	else
	{
		$value = addslashes( $value );
	}
}

//recursive function that performs stripslashes on all items
function processstripslashes( &$value, $level=0 )
{
	if ( $level > 10 )
	{
		early_error( '{TEXT_RECURSIVE_FUNC_PROT}' );
	}

	if( is_array($value) )
	{
		array_map( $value, 'processstripslashes', ( $level + 1 ) );
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
	return( str_replace( array( "\r\n", "\r", "\n" ), '<br />', addslashes( str_replace( '&', '&amp;', $msg ) ) ) );
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
	if ( preg_match("#([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#is", $address) )
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

// function compares a full directory string + file name against another full directory string + file name.
// Returns true when dir strings matched.
// The length match is allways taken from the $dir2 variable so make sure you place the dir strings in the right parameter on function call
function compare_dir_string( $dir1, $dir2 )
{
	$dir1 = realpath( str_replace( '\\', '/', $dir1 ) );
	$dir2 = realpath( str_replace( '\\', '/', $dir2 ) );
	if ( $dir1 !== false && $dir2 !== false && strncmp($dir1, $dir2, strlen($dir2)) === 0 )
	{
		return( true );
	}
	else
	{
		return( false );
	}
}

// javascript code which will be placed in the header of the html pages
function insert_javascript_code( $check_pagerefreshtimer, $check_customserver )
{
	return( '' . ( ( $check_pagerefreshtimer ) ? '
//REFRESHSCRIPT_BEGIN
/* BEGIN page refresh time javascript - Code copied from Emule 0.46c web interface template. */
var timeoutID=null;
var timeout = {REFRESH_SECS}000;

function RefreshPage()
{
	location.href = "{REFRESH_URI}";
}

function ToggleTimer()
{
	if (timeoutID)
	{
		clearTimeout(timeoutID);
		timeoutID = null;
		document.images[\'ImgToggleTimer\'].src = \'{BASE_URL}templates/{TEMPLATE}/images/l_timer_off.gif\';
	}
	else
	{
		SetTimer();
		if (timeoutID)
			document.images[\'ImgToggleTimer\'].src = \'{BASE_URL}templates/{TEMPLATE}/images/l_timer.gif\';
		else
			document.images[\'ImgToggleTimer\'].src = \'{BASE_URL}templates/{TEMPLATE}/images/l_timer_off.gif\';
	}
}

function SetTimer()
{
	if (timeout != 0)
		timeoutID = setTimeout("RefreshPage()", timeout);
}

if (document.all||document.getElementById)
	SetTimer();
else
	window.onload=SetTimer;
/* END page refresh time javascript - Code copied from Emule 0.46c web interface template. */
//REFRESHSCRIPT_END
' : NULL ) . '

/* browser detection */
var ns4 = document.layers;
var ns6 = document.getElementById && !document.all;

var userAgent = navigator.userAgent;
if (userAgent.indexOf(\'MSIE\') != -1 && userAgent.indexOf(\'Opera\') == -1) var ie4 = document.all;

' . ( ( $check_customserver ) ? '
//CUSTOM_SERVER_BEGIN
/* BEGIN show hide custom server form fields */
var customserverrow = new Array();

function showhidecustom(id)
{
	if ( id == "servertype" ){
		start = 4;
		end = 4;
		value = "Ventrilo";
		indexvalue = servertype.options[servertype.selectedIndex].value;
	}
	else{
		start = 1;
		value = 0;
		indexvalue = customserver.options[customserver.selectedIndex].value;
		if ( indexvalue == value && servertype.options[servertype.selectedIndex].value != "Ventrilo" )
			end = 3;
		else
			end = 4;
	}
		
	for ( i=start; i<=end; i=i+1 )
	{
		if ( indexvalue == value ){
			if (ie4)
				customserverrow[i].style.display = "block";
			else
				customserverrow[i].style.display = "table-row";

			customserverrow[i].style.visibility = "visible";
		}
		else{
			customserverrow[i].style.display = "none";
			customserverrow[i].style.visibility = "hidden";
		}
	}
}
/* END show hide custom server form fields */
//CUSTOM_SERVER_END
' : NULL ) . '

/* BEGIN tooltips javascript */
var timer_hidetoolTip=null;

var offsetX = 10;
var offsetY = 10;
var toolTipmain=null;
var toolTipIEfix=null;
var initrun=false;
var reswidth=0;
var resheight=0;
var width=null;
var height=null;
var scrollX=0;
var scrollY=0;

function initToolTips()
{
//	if(ns4||ns6||ie4)
	if (!initrun)
	{
' . ( ( $check_customserver ) ? '
//CUSTOM_SERVER_BEGIN
		customserverrow[1] = document.getElementById("customserverrow1");
		customserverrow[2] = document.getElementById("customserverrow2");
		customserverrow[3] = document.getElementById("customserverrow3");
		customserverrow[4] = document.getElementById("customserverrow4");

		servertype = document.getElementById("servertype");

		customserver = document.getElementById("customserver");

		showhidecustom(\'servertype\');
		showhidecustom(\'all\');
//CUSTOM_SERVER_END
' : NULL ) . '

		toolTipmain = document.getElementById("toolTipLayer");
		if (ie4)
			toolTipIEfix = document.getElementById("toolTipIEfix");

		if(ns4)
			document.captureEvents(Event.MOUSEMOVE);
		else
		{
			toolTipmain.style.visibility = "visible";
			toolTipmain.style.display = "none";
			if (ie4){
				toolTipIEfix.style.visibility = "visible";
				toolTipIEfix.style.display = "none";
			}
		}
		document.onmousemove = moveToMouseLoc;

		get_window_parameters();
		initrun = true;
	}
}

function hidetoolTip()
{
	reswidth=0;
	resheight=0;
/*	toolTipSTYLE.height="";*/
	toolTipmain.style.width="";
	if(ns4)
		toolTipmain.style.visibility = "hidden";
	else{
		toolTipmain.style.display = "none";
		if (ie4)
			toolTipIEfix.style.display = "none";
	}
	clearTimeout(timer_hidetoolTip);
	timer_hidetoolTip = null;
}

function toolTip(msg, nwidth/*, nheight*/)
{
	if(toolTip.arguments.length < 1) // hide
	{
		timer_hidetoolTip = setTimeout("hidetoolTip()", 1);
	}
	else // show
	{
		if (!initrun)
			initToolTips();

		if (timer_hidetoolTip)
			hidetoolTip();

		toolTipmain.style.left = 0;
//		toolTipmain.style.top = 0;

		var content = \'<table id="sizecheck_table" cellspacing="0" cellpadding="0" class="tooltip"><tr><td>\' + msg + \'</td></tr></table>\';
		if(ns4)
		{
			toolTipmain.style.document.write(content);
			toolTipmain.style.document.close();
			toolTipmain.style.visibility = "visible";
		}
		else
		{
			toolTipmain.innerHTML = content;
			toolTipmain.style.display=\'block\'
			if (ie4)
				toolTipIEfix.style.display=\'block\'
		}

		toolTipsizefix = document.getElementById("sizecheck_table");

		get_window_parameters();

/*		if (nheight) toolTipSTYLE.height = nheight;*/

		if (!nwidth)
			nwidth = 400;
		
		if ( nwidth > toolTipmain.offsetWidth ){
			if ( window.minsize ){
				if ( minsize > toolTipmain.offsetWidth ){
					nwidth = minsize;
				}
				else{
					nwidth = toolTipmain.offsetWidth;
				}
			}
			else{
				nwidth = toolTipmain.offsetWidth;

				if ( width < nwidth )
					nwidth = "";
			}
		}

		toolTipmain.style.width = nwidth;

		if (toolTipmain.offsetWidth < toolTipsizefix.offsetWidth)
			toolTipmain.style.width = toolTipsizefix.offsetWidth;

		if (ie4){
			toolTipIEfix.style.width = toolTipmain.offsetWidth;
			toolTipIEfix.style.height = toolTipmain.offsetHeight;
		}

		reswidth = toolTipmain.offsetWidth;
		resheight = toolTipmain.offsetHeight;
		
		moveToMouseLoc();
	}
}

function moveToMouseLoc(e)
{
	if(ns4||ns6)
	{
		x = e.pageX;
		y = e.pageY;
	}
	else
	{
		x = event.x + document.body.scrollLeft;
		y = event.y + document.body.scrollTop;
	}

	if(x + offsetX + reswidth > width + scrollX)
		if (x - offsetX - reswidth - scrollX < 0)
			if (y != 0)
				x = 0 + scrollX;
			else
				x = x + offsetX;
		else
			x = x - offsetX - reswidth;
	else
		x = x + offsetX;

	if(y + offsetY + resheight > height + scrollY)
		if (y - offsetY - resheight - scrollY < 0)
			if (x != 0)
				y = 0 + scrollY;
			else
				y = y + offsetY;
		else
			y = y - offsetY - resheight;
	else
		y = y + offsetY;

	toolTipmain.style.left = x;
	toolTipmain.style.top = y;
	if (ie4){
		toolTipIEfix.style.left = x;
		toolTipIEfix.style.top = y;
	}
	return true;
}

function get_window_parameters()
{
	width = 0;
	if (window.innerWidth) width = window.innerWidth - 18;
	else if (document.documentElement && document.documentElement.clientWidth) 
		width = document.documentElement.clientWidth;
	else if (document.body && document.body.clientWidth) 
		width = document.body.clientWidth;
	width = width - 1;

	scrollX = 0;
	if (typeof window.pageXOffset == "number") scrollX = window.pageXOffset;
	else if (document.documentElement && document.documentElement.scrollLeft)
		scrollX = document.documentElement.scrollLeft;
	else if (document.body && document.body.scrollLeft) 
		scrollX = document.body.scrollLeft; 
	else if (window.scrollX) scrollX = window.scrollX;


	height = 0;
	if (window.innerHeight) height = window.innerHeight - 18;
	else if (document.documentElement && document.documentElement.clientHeight) 
		height = document.documentElement.clientHeight;
	else if (document.body && document.body.clientHeight) 
		height = document.body.clientHeight;
	height = height - 1;

	scrollY = 0;    
	if (typeof window.pageYOffset == "number") scrollY = window.pageYOffset;
	else if (document.documentElement && document.documentElement.scrollTop)
		scrollY = document.documentElement.scrollTop;
	else if (document.body && document.body.scrollTop) 
		scrollY = document.body.scrollTop; 
	else if (window.scrollY) scrollY = window.scrollY;
}
/* END tooltips javascript */
' );
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

<?php
/***************************************************************************
 *                              footer.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: footer.inc.php,v 1.45 2007/11/18 00:15:24 SC Kruiper Exp $
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

$footer = new template;
$template = 'footer';

$footer->insert_text( '{SLG_VERSION}', ( ( isset($GLOBALS['tssettings']['SLG_version']) ) ? $GLOBALS['tssettings']['SLG_version'] : '{TEXT_UNKNOWN_VERSION}' ) );

if ( isset($GLOBALS['db']->sqlconnectid) )
{
	$GLOBALS['db']->disconnect();
}

$executed_queries = ( ( isset($GLOBALS['db']) ) ? $GLOBALS['db']->num_queries : 0 );
$free_queries = ( ( isset($GLOBALS['db']) ) ? $GLOBALS['db']->num_freequeries : 0 );
$nofree_queries = ( ( isset($GLOBALS['db']) ) ? $GLOBALS['db']->num_nofreequeries : 0 );
$sql_time = ( ( isset($GLOBALS['db']->sqltime, $GLOBALS['db']->sqltime->tottime)) ? $GLOBALS['db']->sqltime->tottime : 0 );
$db_disc_ok = ( ( isset($GLOBALS['db']->sqlconnectid) ) ? true : false );

if ( isset($GLOBALS['dbforum']) )
{
	$executed_queries += $GLOBALS['dbforum']->num_queries;
	$free_queries += $GLOBALS['dbforum']->num_freequeries;
	$nofree_queries += $GLOBALS['dbforum']->num_nofreequeries;
	$sql_time += ( ( isset($GLOBALS['dbforum']->sqltime, $GLOBALS['dbforum']->sqltime->tottime) ) ? $GLOBALS['dbforum']->sqltime->tottime : 0 );
	$fdb_disc_ok = ( ( isset($GLOBALS['dbforum']->sqlconnectid) ) ? true : false );
}

if ( defined("SLG_DEBUG") && ( ( $free_queries + $nofree_queries ) !== $executed_queries || $db_disc_ok || ( isset($fdb_disc_ok) && $fdb_disc_ok ) ) )
{
	$divbugfound = '
<script language="JavaScript" type="text/JavaScript">
<!--

var errordiv=null;
var errorIEfix=null;

var ns4 = document.layers;

var userAgent = navigator.userAgent;
if (userAgent.indexOf(\'MSIE\') != -1 && userAgent.indexOf(\'Opera\') == -1) var ie4 = document.all;

var initrunerror=false;

function initerror()
{
	errordiv = document.getElementById("errorlayer");
	if (ie4){
		errorIEfix = document.getElementById("errorlayerIEfix");
	}

	errordiv.style.visibility = "visible";
	errordiv.style.display = "block";
	if (ie4){
		errorIEfix.style.visibility = "visible";
		errorIEfix.style.display = "block";
	}

	x = 0;
	y = 0;

	x = width / 2 - 250;
	y = height / 2 - 175;

	errordiv.style.left = x;
	errordiv.style.top = y;
	if (ie4){
		errorIEfix.style.left = x;
		errorIEfix.style.top = y;
		errorIEfix.style.width = errordiv.offsetWidth;
		errorIEfix.style.height = errordiv.offsetHeight;
	}
}

function hide_errorlayer()
{
	if(ns4) errordiv.style.visibility = "hidden";
	else{
		errordiv.style.display = "none";
		if (ie4) errorIEfix.style.display = "none";
	}
}

setTimeout("initerror()", 2500);
//-->
</script>

<iframe id="errorlayerIEfix" frameborder=0 scrolling=no marginwidth=0 src="" marginheight=0 style="position:absolute; display:none; visibility: hidden;"></iframe>
<div id="errorlayer" style="padding: 20px; width:500px; position:absolute; display:none; visibility: hidden;" class="error2">
	<p>{TEXT_BUGNOTIFY_P1}</p>
	<p>{TEXT_BUGNOTIFY_P2}</p>
	<pre>' . ( ( isset($GLOBALS['db']) ) ? print_r($GLOBALS['db']->queries, true) : '{TEXT_DATANOTFOUND}' ) . '</pre>
	<pre>' . ( ( isset($GLOBALS['dbforum']) ) ? print_r($GLOBALS['dbforum']->queries, true) : NULL ) . '</pre>
	' . ( ( !$db_disc_ok ) ? '{TEXT_DBCLOSED}' : '{TEXT_DBOPEN}' ) . '<br />
	' . ( ( !isset($fdb_disc_ok) || !$fdb_disc_ok ) ? '{TEXT_DBFORUMCLOSED}' : '{TEXT_DBFORUMOPEN}' ) . '
	<p>{TEXT_THANKS}</p>
  
	<input align="center" name="Close thingy" type="button" id="Close thingy" onClick="hide_errorlayer()" value="{TEXT_CLOSE}">
  
</div>
';
}
else
{
	$divbugfound = NULL;
}

if ( isset($GLOBALS['db']) )
{
	unset( $GLOBALS['db'], $GLOBALS['table'] );
}

if ( isset($GLOBALS['dbforum']) )
{
	unset( $GLOBALS['dbforum'], $GLOBALS['forumdatabase'] );
}
elseif ( isset($GLOBALS['forumdatabase']) )
{
	unset( $GLOBALS['forumdatabase'] );
}

$footer->insert_content( '{BUG_FINDER}', $divbugfound );
unset( $divbugfound, $db_disc_ok, $fdb_disc_ok );

$footer->insert_text( '{BASE_URL}', ( !empty( $GLOBALS['tssettings']['Base_url'] ) ? 'http://' . $GLOBALS['tssettings']['Base_url'] : NULL ) );
$footer->insert_text( '{TEMPLATE}', ( !empty( $GLOBALS['tssettings']['Template'] ) ? $GLOBALS['tssettings']['Template'] : 'Default' ) );
$footer->load_language( 'lng_footer' );
$footer->load_template( 'tpl_footer' );
$footer->process();
$footer->output();
unset( $footer, $template );

if ( isset($GLOBALS['starttime']) && ( !isset($GLOBALS['tssettings']['Page_generation_time']) || $GLOBALS['tssettings']['Page_generation_time'] ) )
{
	//it seems php 5.0.0 or higher has a bug with windows versions of apache 1.3 and the function apache_get_modules(). It says the function exists but hangs the connection when the function is called. This behaviour has only been tested with Apache 1.3 and PHP5 for windows. It probably works fine for Linux / Unix but i'm not sure. anyways just to be sure lets only use it with Apache2
	$gzipheaders = ( ( function_exists('apache_response_headers') ) ? apache_response_headers() : array() );
	$gzipmod = ( ( function_exists('apache_get_modules') && strncasecmp($_SERVER['SERVER_SOFTWARE'], 'Apache/2', 8) === 0 ) ? apache_get_modules() : array() );

	$gzip_text = (
	(
		isset($gzipheaders['vary']) ||
		isset($gzipheaders['Vary']) ||
		(
			isset($gzipheaders['Content-Encoding']) && stristr($gzipheaders['Content-Encoding'], 'gzip') !== false
		) || 
		ini_get('zlib.output_compression') ||
		in_array('mod_deflate', $gzipmod) ||
		in_array('mod_gzip', $gzipmod) ||
		( isset($GLOBALS['tssettings']['GZIP_Compression']) && $GLOBALS['tssettings']['GZIP_Compression'] == true )
	) ? 'GZIP enabled' : 'GZIP disabled' );

	unset( $gzipmod, $gzipheaders );

	$debug_text = ( defined("SLG_DEBUG") ) ? 'Debug on' : 'Debug off';

	$mtime = explode( ' ',microtime() );
	$endtime = $mtime[1] + $mtime[0];

	$gentime = $endtime - $GLOBALS['starttime'];

	$sql_part = (int) round( ( $sql_time / $gentime * 100 ), 0 );

	echo '
<div class="pagegen">Page generation time: ' . round( $gentime, 4 ) . 's (PHP: ' . ( 100 - $sql_part ) . '% - SQL: ' . $sql_part . '%) - SQL queries: ' . $executed_queries . ( ( defined("SLG_DEBUG") ) ? ' (' . $free_queries . ' + ' . $nofree_queries . ')' : NULL ) . ' - ' . $gzip_text . ' - ' . $debug_text . '</div>';

	unset( $gentime, $sql_part, $gzip_text, $debug_text, $mtime, $GLOBALS['starttime'], $endtime );
}

unset( $executed_queries, $free_queries, $nofree_queries, $sql_time, $GLOBALS['tssettings'] );

/*if ( defined("SLG_DEBUG") ) // This is a debug only feature but because of the implications of showing this data it will also be outcommented.
{
	echo '<p>DEBUG information: Still open variables.</p>';
	var_dump( $GLOBALS );
}*/

exit;
?>

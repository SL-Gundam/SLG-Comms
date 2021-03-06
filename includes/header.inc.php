<?php
/***************************************************************************
 *                              header.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: header.inc.php,v 1.32 2007/11/17 00:18:18 SC Kruiper Exp $
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

$header = new template;
$template = 'header';

$header->apply_utf8_charset();

$header->insert_content( '{PAGE_TITLE}', ( ( isset($GLOBALS['tssettings']['Page_title']) ) ? $GLOBALS['tssettings']['Page_title'] : '{TEXT_UNKNOWN_TITLE}' ) );

if ( !defined("NO_DATABASE") )
{
	$header_link = '<a target="_top" href="' . ( ( isset($GLOBALS['tssettings']['Base_url']) ) ? 'http://' . $GLOBALS['tssettings']['Base_url'] : NULL ) . ( ( checkfilelock('index.php') ) ? 'admin.php">{TEXT_ADMIN}' : 'index.php">{TEXT_INDEX}' ) . '</a>';
}
else
{
	$header_link = NULL;
}
$header->insert_content( '{LINK_ADMIN_INDEX}', $header_link );
unset( $header_link );

$curfile = (
	checkfilelock('index.php') && 
	(
		!isset($_REQUEST['ipbyname']) || 
		$_REQUEST['ipbyname'] != 0
	) && 
	isset($GLOBALS['tssettings']['Page_refresh_timer']) &&
	is_numeric($GLOBALS['tssettings']['Page_refresh_timer']) && 
	$GLOBALS['tssettings']['Page_refresh_timer'] != 0
);

$header->insert_content( '{CONTENT_JAVASCRIPT}', insert_javascript_code( $curfile, ( isset($GLOBALS['tssettings']['Custom_servers']) && $GLOBALS['tssettings']['Custom_servers'] && checkfilelock('index.php') ) ) );

$header->insert_display( '{REFRESHSCRIPT}', $curfile );

if ( $curfile )
{
	$header->insert_text( '{REFRESH_URI}', 'index.php' . ( ( isset($_REQUEST['ipbyname']) ) ? '?ipbyname=' . $_REQUEST['ipbyname'] : '' ) );
	$header->insert_text( '{REFRESH_SECS}', $GLOBALS['tssettings']['Page_refresh_timer'] );
}
unset( $curfile );

$header->insert_text( '{BASE_URL}', ( !empty( $GLOBALS['tssettings']['Base_url'] ) ? 'http://' . $GLOBALS['tssettings']['Base_url'] : NULL ) );
$header->insert_text( '{TEMPLATE}', ( !empty( $GLOBALS['tssettings']['Template'] ) ? $GLOBALS['tssettings']['Template'] : 'Default' ) );
$header->load_language( 'lng_header' );
$header->load_template( 'tpl_header' );
$header->process();
$header->output();
unset( $header, $template );
?>

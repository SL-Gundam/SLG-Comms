<?php
/***************************************************************************
 *                             teamspeak.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: tsviewer.inc.php,v 1.1 2008/03/24 16:07:51 SC Kruiper Exp $
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

if ( !$tssettings['TeamSpeak_support'] )
{
	early_error( '{TEXT_SUPPORT_TS_DISABLED}' );
}

$tsviewer = new template;
$template = 'tsviewer';

$tsv = new ts_viewerdata;

$tsv->insert_server( $server );
unset( $server );	

$tsv->collect_data();

$tssettings['Retrieved_data_status'] = ( $tssettings['Retrieved_data_status'] || $tsv->connecterror );
$tsviewer->insert_display( '{DATA_STATUS}', $tssettings['Retrieved_data_status'] );
if ( $tssettings['Retrieved_data_status'] )
{
	$tsviewer->insert_content( '{DATA_STATUS}', $tsv->print_check_cache_lifetime() );
}

$tsviewer->insert_display( '{SERVER_INFO}', false );


#############
## DISPLAY ##
#############

$tsviewer->insert_content( '{CHANNEL_INFO_CONTENT}', '<tr><td>' . $tsv->rawdata . '</td></tr>' );

unset( $comms, $server_content );

$tsviewer->insert_text( '{BASE_URL}', ( !empty( $tssettings['Base_url'] ) ? 'http://' . $tssettings['Base_url'] : NULL ) );
$tsviewer->insert_text( '{TEMPLATE}', ( !empty( $tssettings['Template'] ) ? $tssettings['Template'] : 'Default' ) );
$tsviewer->load_language( 'lng_index_sub' );
$tsviewer->load_template( 'tpl_teamspeak' );
$tsviewer->process();
$tsviewer->output();
unset( $teamspeak, $template );
?>

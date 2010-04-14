<?php
/***************************************************************************
 *                              ventrilo.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: ventrilo.inc.php,v 1.59 2006/06/04 20:41:13 SC Kruiper Exp $
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

if ( !$tssettings['Ventrilo_support'] )
{
	early_error( '{TEXT_SUPPORT_VENT_DISABLED}' );
}

$ventrilo = new template;
$template = 'ventrilo';

$comms = new vent_commsdata;

$comms->insert_server( $server );
unset( $server );

$comms->collect_data();

$tssettings['Retrieved_data_status'] = ( $tssettings['Retrieved_data_status'] || $comms->connecterror );
$ventrilo->insert_display( '{DATA_STATUS}', $tssettings['Retrieved_data_status'] );
if ( $tssettings['Retrieved_data_status'] )
{
	$ventrilo->insert_content( '{DATA_STATUS}', $comms->print_check_cache_lifetime() );
}

$ventrilo->insert_display( '{SERVER_INFO}', $tssettings['Display_server_information'] );

$uptime = htmlentities( formattime( $comms->serverinfo['UPTIME'] ) );
$password_prot = ( ( $comms->serverinfo['AUTH'] ) ? '{TEXT_YES}' : '{TEXT_NO}' );
if ( $tssettings['Display_server_information'] )
{
	$ventrilo->insert_text( '{SERVER_NAME}', htmlentities( $comms->serverinfo['NAME'] ) );
	$ventrilo->insert_text( '{SERVER_PHONETIC}', htmlentities( $comms->serverinfo['PHONETIC'] ) );
	$ventrilo->insert_text( '{PLATFORM}', htmlentities( $comms->serverinfo['PLATFORM'] ) );
	$ventrilo->insert_text( '{VERSION}', htmlentities( $comms->serverinfo['VERSION'] ) );
	$ventrilo->insert_text( '{COMMENT}', ( ( isset($comms->serverinfo['COMMENT']) ) ? htmlentities( $comms->serverinfo['COMMENT'] ) : NULL ) );
	$ventrilo->insert_text( '{UDPPORT}', $comms->server['res_data']['port'] );
	$ventrilo->insert_text( '{MAXCLIENTS}', htmlentities( $comms->serverinfo['MAXCLIENTS'] ) );
	$ventrilo->insert_text( '{ADMINS_CON}', $comms->calc_tot['ADMIN'] );
	$ventrilo->insert_text( '{VOICECODEC}', htmlentities( $comms->serverinfo['VOICECODEC']['NAME'] ) );
	$ventrilo->insert_text( '{VOICEFORMAT}', htmlentities( $comms->serverinfo['VOICEFORMAT']['NAME'] ) );

	$ventrilo->insert_content( '{CHANNEL_COUNT}', ( ( isset($comms->serverinfo['CHANNELCOUNT']) ) ? htmlentities( $comms->serverinfo['CHANNELCOUNT'] ) : '{TEXT_UNKNOWN}' ) );
	$ventrilo->insert_content( '{CLIENTS_CON}', ( ( isset($comms->serverinfo['CLIENTCOUNT']) ) ? htmlentities( $comms->serverinfo['CLIENTCOUNT'] . ' / ' . $comms->serverinfo['MAXCLIENTS'] ) : '{TEXT_UNKNOWN}' ) );
	$ventrilo->insert_content( '{UPTIME}', $uptime );
	$ventrilo->insert_content( '{PASSWORD_PROT}', $password_prot );
}

#############
## DISPLAY ##
#############

$check['channel'] = ( !isset($comms->serverinfo['CHANNELCOUNT']) || $comms->serverinfo['CHANNELCOUNT'] != $comms->calc_tot['TOT_CHANNELS'] );
$check['client'] = ( !isset($comms->serverinfo['CLIENTCOUNT']) || $comms->serverinfo['CLIENTCOUNT'] != $comms->calc_tot['TOT_CLIENTS'] );

$notice = NULL;
if ( $check['channel'] )
{
	$notice .= '<tr><td class="error"><p>{TEXT_CHANNELDATA_DISABLED}</p></td></tr>';
}
if ( $check['client'] )
{
	$notice .= '<tr><td class="error"><p>{TEXT_CLIENTDATA_DISABLED}</p></td></tr>';
}

$ventrilo->insert_display( '{SERVER_NOTICE}', ( $check['channel'] || $check['client'] ) );
if ( $check['channel'] || $check['client'] )
{
	$ventrilo->insert_content( '{NOTICE}', '<table width="100%" border="0">' . $notice . '</table>' );
}

unset( $check, $notice );

$div_content = '<table class=\'tooltip\' cellspacing=\'1\' cellpadding=\'0\'>
<tr><td nowrap valign=\'top\'>{TEXT_SERVER_NAME}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['NAME'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_SERVER_PHONETIC}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['PHONETIC'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PLATFORM}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['PLATFORM']) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_VERSION}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['VERSION'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_UPTIME}:&nbsp;</td><td>' . $uptime . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PASSWORD_PROT}:&nbsp;</td><td>' . $password_prot . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_UDPPORT}:&nbsp;</td><td>' . htmlentities( $comms->server['res_data']['port'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_MAXCLIENTS}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['MAXCLIENTS'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_CLIENTS_CON}:&nbsp;</td><td>' . ( ( isset($comms->serverinfo['CLIENTCOUNT']) ) ? htmlentities( $comms->serverinfo['CLIENTCOUNT'] . ' / ' . $comms->serverinfo['MAXCLIENTS'] ) : '{TEXT_UNKNOWN}' ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_ADMINS_CON}:&nbsp;</td><td>' . $comms->calc_tot['ADMIN'] . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_CHANNEL_COUNT}:&nbsp;</td><td>' . ( ( isset($comms->serverinfo['CHANNELCOUNT']) ) ? htmlentities( $comms->serverinfo['CHANNELCOUNT'] ) : '{TEXT_UNKNOWN}' ) . '</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_VOICECODEC}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['VOICECODEC']['NAME']).'</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_VOICEFORMAT}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['VOICEFORMAT']['NAME'] ) . '</td></tr>' . ( ( !empty($comms->serverinfo['COMMENT']) ) ? '
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_COMMENT}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['COMMENT'] ) . '</td></tr>' : NULL ) . '</table>';

unset( $password_prot, $uptime );

$div_content = prep_tooltip( str_replace( "\n", '', $div_content ) );

$server_content = '
	<tr class="server_row">
		<td nowrap onMouseOver="toolTip(\'' . $div_content . '\')" onMouseOut="toolTip()"><p>
			<img width="16" height="16" src="{BASE_URL}templates/{TEMPLATE}/images/ventrilo/server.gif" align="absmiddle" alt="{TEXT_SERVER_NAME}" border="0" />&nbsp;' . htmlentities( $comms->serverinfo['NAME'] ) . ( ( !empty($comms->serverinfo['COMMENT']) ) ? '&nbsp;&nbsp;&nbsp;(<span class="ventcomment">' . htmlentities( linewrap( $comms->serverinfo['COMMENT'], 30 ) ) . '</span>)' : NULL ) . '
		</p></td>' . ( ( $tssettings['Display_ping'] ) ? '<td nowrap align="right"><p>&nbsp;&nbsp;{TEXT_PING}</p></td>' : NULL ) . '
	</tr>';

unset( $div_content );

//Clients not in a channel
if ( isset($comms->clients[0]) )
{
	$server_content .= $comms->output_vent_clients();
} 

if ( isset($comms->channels[0]) )
{
	$server_content .= $comms->output_vent_channels();
}

$ventrilo->insert_content( '{CHANNEL_INFO_CONTENT}', $server_content );

unset( $server_content, $comms );

$ventrilo->insert_text( '{BASE_URL}', ( isset( $tssettings['Base_url'] ) ? 'http://' . $tssettings['Base_url'] : NULL ) );
$ventrilo->insert_text( '{TEMPLATE}', ( isset( $tssettings['Template'] ) ? $tssettings['Template'] : 'Default' ) );
$ventrilo->load_language( 'lng_index_sub' );
$ventrilo->load_language( 'lng_index_vent' );
$ventrilo->load_template( 'tpl_ventrilo' );
$ventrilo->process();
$ventrilo->output();
unset( $ventrilo, $template );
?>

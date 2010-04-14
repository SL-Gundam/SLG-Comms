<?php
/***************************************************************************
 *                             teamspeak.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: teamspeak.inc.php,v 1.52 2007/01/30 16:16:47 SC Kruiper Exp $
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

$teamspeak = new template;
$template = 'teamspeak';

$comms = new ts_commsdata;

$comms->insert_server( $server );
unset( $server );	

$comms->collect_data();

$tssettings['Retrieved_data_status'] = ( $tssettings['Retrieved_data_status'] || $comms->connecterror );
$teamspeak->insert_display( '{DATA_STATUS}', $tssettings['Retrieved_data_status'] );
if ( $tssettings['Retrieved_data_status'] )
{
	$teamspeak->insert_content( '{DATA_STATUS}', $comms->print_check_cache_lifetime() );
}

$teamspeak->insert_display( '{SERVER_INFO}', $tssettings['Display_server_information'] );

$uptime = htmlentities( formattime( $comms->serverinfo['server_uptime'] ) );
$password_prot = ( ( $comms->serverinfo['server_password'] ) ? '{TEXT_YES}' : '{TEXT_NO}' );
$clanserver = ( ( $comms->serverinfo['server_clan_server'] ) ? '{TEXT_YES}' : '{TEXT_NO}' );
$datasent = htmlentities( formatbytes( $comms->serverinfo['server_bytessend'] ) );
$datareceived = htmlentities( formatbytes( $comms->serverinfo['server_bytesreceived'] ) );

if ( $tssettings['Display_server_information'] )
{
	$teamspeak->insert_text( '{SERVER_NAME}', htmlentities( $comms->serverinfo['server_name'] ) );
	$teamspeak->insert_text( '{PLATFORM}', htmlentities( $comms->serverinfo['server_platform'] ) );
	$teamspeak->insert_text( '{VERSION}', htmlentities( $comms->serverinfo['total_server_version'] ) );
	$teamspeak->insert_text( '{WELCOME}', htmlentities( $comms->serverinfo['server_welcomemessage'] ) );
	$teamspeak->insert_text( '{MAXCLIENTS}', htmlentities( $comms->serverinfo['server_maxusers'] ) );
	$teamspeak->insert_text( '{CLIENTS_CON}', htmlentities( $comms->serverinfo['server_currentusers'] . ' / ' . $comms->serverinfo['server_maxusers'] ) );
	$teamspeak->insert_text( '{ADMINS_CON}', $comms->calc_tot['SA'] );
	$teamspeak->insert_text( '{CHANNEL_COUNT}', htmlentities( $comms->serverinfo['server_currentchannels'] ) );
	$teamspeak->insert_text( '{UDPPORT}', htmlentities( $comms->serverinfo['server_udpport'] ) );

	$teamspeak->insert_content( '{UPTIME}', $uptime );
	$teamspeak->insert_content( '{PASSWORD_PROT}', $password_prot );
	$teamspeak->insert_content( '{CLANSERVER}', $clanserver );
	$teamspeak->insert_content( '{DATASENT}', $datasent );
	$teamspeak->insert_content( '{DATARECEIVED}', $datareceived );

	$teamspeak->insert_display( '{PROVIDER}', ( isset( $comms->serverinfo['isp_ispname'] ) || isset( $comms->serverinfo['isp_linkurl'] ) || isset( $comms->serverinfo['isp_adminemail'] ) ) );

	$teamspeak->insert_text( '{PROVIDER}', ( ( isset($comms->serverinfo['isp_ispname']) ) ? htmlentities( $comms->serverinfo['isp_ispname'] ) : NULL ) );
	$teamspeak->insert_text( '{PROVIDER_WEBSITE}', ( ( isset($comms->serverinfo['isp_linkurl']) ) ? prepare_http_link( $comms->serverinfo['isp_linkurl'] ) : NULL ) );
	$teamspeak->insert_text( '{PROVIDER_EMAIL}', ( ( isset($comms->serverinfo['isp_adminemail'])) ? prepare_email_addr( $comms->serverinfo['isp_adminemail'] ) : NULL ) );
}

#############
## DISPLAY ##
#############

$div_content = '<table class=\'tooltip\' cellspacing=\'1\' cellpadding=\'0\'>
<tr><td nowrap valign=\'top\'>{TEXT_SERVER_NAME}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['server_name'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PLATFORM}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['server_platform'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_VERSION}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['total_server_version'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_UPTIME}:&nbsp;</td><td>' . $uptime . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PASSWORD_PROT}:&nbsp;</td><td>' . $password_prot . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_CLANSERVER}:&nbsp;</td><td>' . $clanserver . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_UDPPORT}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['server_udpport'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_DATASENT}:&nbsp;</td><td>' . $datasent . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_DATARECEIVED}:&nbsp;</td><td>' . $datareceived . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_MAXCLIENTS}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['server_maxusers'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_CLIENTS_CON}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['server_currentusers'] . ' / ' . $comms->serverinfo['server_maxusers'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_ADMINS_CON}:&nbsp;</td><td>' . $comms->calc_tot['SA'] . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_CHANNEL_COUNT}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['server_currentchannels'] ) . '</td></tr>' . ( ( isset( $comms->serverinfo['isp_ispname'] ) || isset( $comms->serverinfo['isp_linkurl'] ) || isset( $comms->serverinfo['isp_adminemail'] ) ) ? '
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PROVIDER}:&nbsp;</td><td>' . ( ( isset( $comms->serverinfo['isp_ispname'] ) ) ? htmlentities( $comms->serverinfo['isp_ispname'] ) . '</td></tr>' : NULL ) . '
<tr><td nowrap valign=\'top\'>{TEXT_PROVIDER_WEBSITE}:&nbsp;</td><td>' . ( ( isset( $comms->serverinfo['isp_linkurl'] ) ) ? htmlentities( prepare_http_link( $comms->serverinfo['isp_linkurl'], NULL, false ) ) . '</td></tr>' : NULL ) . '
<tr><td nowrap valign=\'top\'>{TEXT_PROVIDER_EMAIL}:&nbsp;</td><td>' . ( ( isset( $comms->serverinfo['isp_adminemail'] ) ) ? htmlentities( prepare_email_addr( $comms->serverinfo['isp_adminemail'], NULL, false ) ) . '</td></tr>' : NULL ) : NULL ) . ( ( !empty( $comms->serverinfo['server_welcomemessage'] ) ) ? '
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_WELCOME}:&nbsp;</td><td>' . htmlentities( $comms->serverinfo['server_welcomemessage'] ) . '</td></tr>' : NULL ) . '</table>';

$div_content = prep_tooltip( removechars( $div_content, array( "\r", "\n" ) ) );

$server_content = '
	<tr class="server_row">
		<td nowrap onMouseOver="toolTip(\'' . $div_content . '\')" onMouseOut="toolTip()"><p>
			<img width="16" height="16" src="{BASE_URL}templates/{TEMPLATE}/images/teamspeak/bullet_server.gif" align="absmiddle" alt="{TEXT_SERVER_NAME}" border="0" />&nbsp;' . htmlentities( $comms->serverinfo['server_name'] ) . '
		</p></td>' . ( ( $tssettings['Display_ping'] ) ? '<td nowrap align="right"><p>&nbsp;&nbsp;{TEXT_PING}</p></td>' : NULL ) . '
	</tr>';

unset( $div_content, $uptime, $password_prot, $clanserver, $datasent, $datareceived );

$server_content .= $comms->output_ts_channels();

$teamspeak->insert_content( '{CHANNEL_INFO_CONTENT}', $server_content );

unset( $comms, $server_content );

$teamspeak->insert_text( '{BASE_URL}', ( !empty( $tssettings['Base_url'] ) ? 'http://' . $tssettings['Base_url'] : NULL ) );
$teamspeak->insert_text( '{TEMPLATE}', ( !empty( $tssettings['Template'] ) ? $tssettings['Template'] : 'Default' ) );
$teamspeak->load_language( 'lng_index_sub' );
$teamspeak->load_language( 'lng_index_ts' );
$teamspeak->load_template( 'tpl_teamspeak' );
$teamspeak->process();
$teamspeak->output();
unset( $teamspeak, $template );
?>

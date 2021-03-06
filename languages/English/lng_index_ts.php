<?php
/***************************************************************************
 *                             lng_index_ts.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_ts.php,v 1.10 2008/08/15 17:14:25 SC Kruiper Exp $
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

$this->text += array(
'{TEXT_ADMINS_CON}'              => 'Server Admins online',
'{TEXT_CLANSERVER}'              => 'Clanserver?',
'{TEXT_CODEC}'                   => 'Codec',
'{TEXT_DATARECEIVED}'            => 'Data received',
'{TEXT_DATASENT}'                => 'Data sent',
'{TEXT_IDLEFOR}'                 => 'Idle for',
'{TEXT_PACKETLOSS}'              => 'Packet loss',
'{TEXT_PROVIDER}'                => 'Provider',
'{TEXT_PROVIDER_EMAIL}'          => 'Provider e-mail address',
'{TEXT_PROVIDER_WEBSITE}'        => 'Provider website',
'{TEXT_STICKY}'                  => 'Sticky',
'{TEXT_TOPIC}'                   => 'Topic',
'{TEXT_UNKNOWN_CODEC}'           => 'Unknown codec',
'{TEXT_WELCOME}'                 => 'Welcome message',

'{TEXT_BYTES}'                   => 'Bytes',
'{TEXT_KB}'                      => 'KiB',
'{TEXT_MB}'                      => 'MiB',
'{TEXT_GB}'                      => 'GiB',
'{TEXT_TB}'                      => 'TiB',

'{TEXT_TS_RAWDATA_FLAW}'         => 'Corrupted TeamSpeak server data detected.
Because of a flaw within the layout of the TeamSpeak server data, usage of a combination of tabs and double-quotes make it literally impossible to process it properly. SLG Comms tried the fix the data in question to the best of its abilities. If you see channels and / or clients with incorrect information this will probably be because of this flaw.',
);

$this->tooltips += array(
'{TEXT_EXPLAIN_TSFLAG_U}'        => 'Unregistered',
'{TEXT_EXPLAIN_TSFLAG_R}'        => 'Registered',
'{TEXT_EXPLAIN_TSFLAG_M}'        => 'Moderated',
'{TEXT_EXPLAIN_TSFLAG_P}'        => 'Password Protected',
'{TEXT_EXPLAIN_TSFLAG_S}'        => 'Sub-channels enabled',
'{TEXT_EXPLAIN_TSFLAG_D}'        => 'Default channel',
'{TEXT_EXPLAIN_TSFLAG_SA}'       => 'Server Administrator',
'{TEXT_EXPLAIN_TSFLAG_CA}'       => 'Channel Administrator',
'{TEXT_EXPLAIN_TSFLAG_AO}'       => 'Auto-Operator',
'{TEXT_EXPLAIN_TSFLAG_AV}'       => 'Auto-Voice',
'{TEXT_EXPLAIN_TSFLAG_O}'        => 'Operator',
'{TEXT_EXPLAIN_TSFLAG_V}'        => 'Voice',
'{TEXT_EXPLAIN_TSFLAGS_CHANNEL}' => 'Channel settings',
'{TEXT_EXPLAIN_TSFLAGS_CLIENT}'  => 'Client rights',
'{TEXT_EXPLAIN_TSSTATUS_CLIENT}' => 'Client status',
'{TEXT_PL_STATUS_8}'             => 'Away',
'{TEXT_PL_STATUS_1}'             => 'Channel commander',
'{TEXT_PL_STATUS_4}'             => 'Doesn\'t accept whispers',
'{TEXT_PL_STATUS_16}'            => 'Microphone muted',
'{TEXT_PL_STATUS_64}'            => 'Recording',
'{TEXT_PL_STATUS_32}'            => 'Sound muted',
'{TEXT_PL_STATUS_2}'             => 'Requests for voice',
'{TEXT_PL_RIGHT_2}'              => 'Allowed to register',
);
?>

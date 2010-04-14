<?php
/***************************************************************************
 *                             lng_index_sub.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_sub.php,v 1.9 2005/09/10 14:39:31 SC Kruiper Exp $
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
if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}

$this->text += array(
'{TEXT_CHANNEL_INFO}' => 'Channel information',
'{TEXT_SERVER_INFO}' => 'Server information',
'{TEXT_CACHED_LOADED}' => 'Cached data loaded.',
'{TEXT_CACHEDDATA}' => 'Cached data.',
'{TEXT_LIVEDATA}' => 'Live data.',
'{TEXT_NOCUSTOMCACHE}' => 'Data will not be cached for custom servers',
'{TEXT_CACHEDISABLED}' => 'Data caching is disabled',
'{TEXT_SERVER_NAME}' => 'Server name',
'{TEXT_PLATFORM}' => 'Platform',
'{TEXT_VERSION}' => 'Version',
'{TEXT_WELCOME}' => 'Welcome message',
'{TEXT_UPTIME}' => 'Uptime',
'{TEXT_PASSWORD_PROT}' => 'Password protected?',
'{TEXT_CLANSERVER}' => 'Clanserver?',
'{TEXT_PROVIDER}' => 'Provider',
'{TEXT_PROVIDER_WEBSITE}' => 'Provider website',
'{TEXT_PROVIDER_EMAIL}' => 'Provider e-mail address',
'{TEXT_UDPPORT}' => 'UDP port',
'{TEXT_DATASENT}' => 'Data sent',
'{TEXT_DATARECEIVED}' => 'Data received',
'{TEXT_MAXCLIENTS}' => 'Maximum clients',
'{TEXT_CLIENTS_CON}' => 'Clients connected',
'{TEXT_CHANNEL_COUNT}' => 'Channel count',
'{TEXT_YES}' => 'Yes',
'{TEXT_NO}' => 'No',
'{TEXT_PING}' => 'Ping',
'{TEXT_CHANNEL}' => 'Channel',
'{TEXT_TOPIC}' => 'Topic',
'{TEXT_CODEC}' => 'Codec',
'{TEXT_SUBCHANNEL}' => 'Subchannel',
'{TEXT_NAME}' => 'Name',
'{TEXT_ADMIN}' => 'Admin',
'{TEXT_LOGGEDINFOR}' => 'Logged in for',
'{TEXT_IDLEFOR}' => 'Idle for',
'{TEXT_SERVER_PHONETIC}' => 'Server phonetic',
'{TEXT_COMMENT}' => 'Comment',
'{TEXT_UNKNOWN_CODEC}' => 'Unknown codec',
'{TEXT_DAY}' => 'Day',
'{TEXT_DAYS}' => 'Days',
'{TEXT_HOUR}' => 'Hour',
'{TEXT_HOURS}' => 'Hours',
'{TEXT_MINUTE}' => 'Minute',
'{TEXT_MINUTES}' => 'Minutes',
'{TEXT_SECOND}' => 'Second',
'{TEXT_SECONDS}' => 'Seconds',
'{TEXT_AND}' => 'and',
'{TEXT_BYTES}' => 'Bytes',
'{TEXT_KB}' => 'KB',
'{TEXT_MB}' => 'MB',
'{TEXT_GB}' => 'GB',
'{TEXT_TB}' => 'TB',
'{TEXT_EXPLAIN_TSFLAG_U}' => 'Unregistered',
'{TEXT_EXPLAIN_TSFLAG_R}' => 'Registered',
'{TEXT_EXPLAIN_TSFLAG_M}' => 'Moderated',
'{TEXT_EXPLAIN_TSFLAG_P}' => 'Password protected',
'{TEXT_EXPLAIN_TSFLAG_S}' => 'Sub-channels enabled',
'{TEXT_EXPLAIN_TSFLAG_D}' => 'Default channel',
'{TEXT_EXPLAIN_TSFLAG_SA}' => 'Server administrator',
'{TEXT_EXPLAIN_TSFLAG_CA}' => 'Channel administrator',
'{TEXT_EXPLAIN_TSFLAG_AO}' => 'Auto-op',
'{TEXT_EXPLAIN_TSFLAG_AV}' => 'Auto-voice',
'{TEXT_EXPLAIN_TSFLAG_O}' => 'Op',
'{TEXT_EXPLAIN_TSFLAG_V}' => 'Voice',
'{TEXT_EXPLAIN_TSFLAGS_CHANNEL}' => 'Channel settings',
'{TEXT_EXPLAIN_TSFLAGS_PLAYER}' => 'Player rights'
);

$this->text_adv += array(
'{TEXT_CACHEOLD}' => 'Cached data is $1 old.',
'{TEXT_DATAREFRESHIN}' => 'Data refresh in $1.',
'{TEXT_DATAREFRESHED}' => 'Data is refreshed every $1.'
);

?>

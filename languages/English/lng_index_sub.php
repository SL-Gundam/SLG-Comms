<?php
/***************************************************************************
 *                             lng_index_sub.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_sub.php,v 1.21 2006/06/11 20:32:45 SC Kruiper Exp $
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
'{TEXT_CHANNEL_INFO}'            => 'Channel information',
'{TEXT_SERVER_INFO}'             => 'Server information',

'{TEXT_CACHED_LOADED}'           => 'Cached data loaded.',
'{TEXT_ERROR}'                   => 'ERROR',
'{TEXT_LAST_ERROR}'              => 'LAST ERROR',
'{TEXT_SERVERUPDATE_DISABLED}'   => 'SERVER UPDATES DISABLED.
This server failed 25 consecutive connection attempts. Therefore its likely this server doesn\'t exist anymore.',

'{TEXT_CACHEDDATA}'              => 'Cached data.',
'{TEXT_CACHEDISABLED}'           => 'Data caching is disabled',
'{TEXT_CACHEOLD}'                => 'Cache age',
'{TEXT_DATAREFRESHED}'           => 'Data refresh interval',
'{TEXT_DATAREFRESHIN}'           => 'Data refresh in',
'{TEXT_LIVEDATA}'                => 'Live data.',
'{TEXT_NOCUSTOMCACHE}'           => 'Data will not be cached for custom servers',
'{TEXT_UPDATEINPROGRESS}'        => 'Update in progress.',

'{TEXT_CHANNEL}'                 => 'Channel name',
'{TEXT_CHANNEL_COUNT}'           => 'Channels',
'{TEXT_CLIENT}'                  => 'Client name',
'{TEXT_CLIENTS_CON}'             => 'Clients online',
'{TEXT_LOGGEDINFOR}'             => 'Logged in for',
'{TEXT_MAXCLIENTS}'              => 'Maximum clients',
'{TEXT_MILLISECONDS}'            => 'ms',
'{TEXT_PASSWORD_PROT}'           => 'Password protected?',
'{TEXT_PING}'                    => 'Ping',
'{TEXT_PLATFORM}'                => 'Platform',
'{TEXT_SERVER_NAME}'             => 'Server name',
'{TEXT_VERSION}'                 => 'Version',
'{TEXT_UDPPORT}'                 => 'UDP port',
'{TEXT_UPTIME}'                  => 'Uptime',

'{TEXT_YES}'                     => 'Yes',
'{TEXT_NO}'                      => 'No',

'{TEXT_AND}'                     => 'and',
'{TEXT_DAY}'                     => 'Day',
'{TEXT_DAYS}'                    => 'Days',
'{TEXT_HOUR}'                    => 'Hour',
'{TEXT_HOURS}'                   => 'Hours',
'{TEXT_MINUTE}'                  => 'Minute',
'{TEXT_MINUTES}'                 => 'Minutes',
'{TEXT_SECOND}'                  => 'Second',
'{TEXT_SECONDS}'                 => 'Seconds',
'{TEXT_YEAR}'                    => 'Year',
'{TEXT_YEARS}'                   => 'Years',
);
?>

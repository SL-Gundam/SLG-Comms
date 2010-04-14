<?php
/***************************************************************************
 *                             lng_index_sub.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_sub.php,v 1.12 2005/10/21 14:29:28 SC Kruiper Exp $
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
'{TEXT_UPTIME}' => 'Uptime',
'{TEXT_PASSWORD_PROT}' => 'Password protected?',
'{TEXT_UDPPORT}' => 'UDP port',
'{TEXT_MAXCLIENTS}' => 'Maximum clients',
'{TEXT_CLIENTS_CON}' => 'Clients connected',
'{TEXT_CHANNEL_COUNT}' => 'Channel count',
'{TEXT_YES}' => 'Yes',
'{TEXT_NO}' => 'No',
'{TEXT_PING}' => 'Ping',
'{TEXT_DAY}' => 'Day',
'{TEXT_DAYS}' => 'Days',
'{TEXT_HOUR}' => 'Hour',
'{TEXT_HOURS}' => 'Hours',
'{TEXT_MINUTE}' => 'Minute',
'{TEXT_MINUTES}' => 'Minutes',
'{TEXT_SECOND}' => 'Second',
'{TEXT_SECONDS}' => 'Seconds',
'{TEXT_AND}' => 'and',
'{TEXT_CACHEOLD}' => 'Cache age',
'{TEXT_DATAREFRESHIN}' => 'Data refresh in',
'{TEXT_DATAREFRESHED}' => 'Data refresh interval'
);

$this->tooltips += array(
'{TEXT_CHANNEL}' => 'Channel',
'{TEXT_NAME}' => 'Name',
'{TEXT_LOGGEDINFOR}' => 'Logged in for',
);
?>

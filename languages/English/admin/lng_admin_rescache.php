<?php
/***************************************************************************
 *                            lng_admin_cached.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_rescache.php,v 1.16 2006/06/11 20:32:44 SC Kruiper Exp $
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
'{TEXT_RESOURCE_CACHEHITS}'            => 'Cache hits',
'{TEXT_RESOURCE_LASTUPDATE}'           => 'Cache age',
'{TEXT_RESOURCE_NAME}'                 => 'Resource name',
'{TEXT_VENT_CHANNEL_SORTING}'          => 'Ventrilo channel sorting',

'{TEXT_DATA_CACHE_UNAVAILABLE}'        => 'Data cache not present.',
'{TEXT_DATA_CACHING_DISABLED}'         => 'Data caching is disabled.',

'{TEXT_AND}'                           => 'and',
'{TEXT_DAY}'                           => 'Day',
'{TEXT_DAYS}'                          => 'Days',
'{TEXT_HOUR}'                          => 'Hour',
'{TEXT_HOURS}'                         => 'Hours',
'{TEXT_MINUTE}'                        => 'Minute',
'{TEXT_MINUTES}'                       => 'Minutes',
'{TEXT_SECOND}'                        => 'Second',
'{TEXT_SECONDS}'                       => 'Seconds',
'{TEXT_YEAR}'                          => 'Year',
'{TEXT_YEARS}'                         => 'Years',

'{TEXT_UPDATE_CACHESETTINGS}'          => 'Update cache settings',

'{TEXT_SERVER_ENABLED}'                => 'Server enabled.',

'{TEXT_RESCACHE_NOTE}'                 => 'Here you define the amount of seconds a server should be cached before it refreshes its data. If you set the amount of seconds to zero, caching will be disabled for that specific server. It is advised to allways enable caching even though it\'s just for 1 second. The reason for this is that SLG Comms will be able to protect the webserver on websites with high amounts visitors from getting swamped with live data requests.

You can also define whether you want the Ventrilo channels sorted in a particular way. This is only available for Ventrilo servers because SLG Comms is unable to decide on its own what the right sorting method for Ventrilo channels is due to the fact that this information is missing from the Retrieved server data.

Incase a server has been disabled because it failed 25 consecutive connection attempts you can enable that server again by clicking on the exclamation mark icon in front of the server in question.',
);

$this->text_adv += array(
'{TEXT_CACHESETTING_UPDATE_SUCCESS}'   => 'The settings for "$1" were successfully updated.',
);

$this->tooltips += array(
'{TEXT_LAST_ERROR}'                    => 'LAST ERROR',
'{TEXT_SERVERUPDATE_DISABLED}'         => 'Server updates disabled (due to 25 consecutive connection attempt failures) - Click on the exclamation icon to enable server updates again for this server.',
);
?>

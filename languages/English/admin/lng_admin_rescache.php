<?php
/***************************************************************************
 *                            lng_admin_cached.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_rescache.php,v 1.4 2005/09/10 14:39:30 SC Kruiper Exp $
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
'{TEXT_RESCACHE_NOTE}' => 'Here you define the amount of seconds a server should be cached before it refreshes its data. If you set the amount of seconds to zero, caching will be disabled for that specific server.',
'{TEXT_RESOURCE_NAME}' => 'Resource name',
'{TEXT_RESOURCE_CACHEHITS}' => 'Cache hits',
'{TEXT_RESOURCE_LASTUPDATE}' => 'Cache age',
'{TEXT_DATA_CACHING_DISABLED}' => 'Data caching is disabled.',
'{TEXT_DAY}' => 'Day',
'{TEXT_DAYS}' => 'Days',
'{TEXT_HOUR}' => 'Hour',
'{TEXT_HOURS}' => 'Hours',
'{TEXT_MINUTE}' => 'Minute',
'{TEXT_MINUTES}' => 'Minutes',
'{TEXT_SECOND}' => 'Second',
'{TEXT_SECONDS}' => 'Seconds',
'{TEXT_AND}' => 'and',
'{TEXT_UPDATE_CACHESETTINGS}' => 'Update cache settings',
'{TEXT_CACHESETTINGS_UPDATE_SUCCESS}' => 'All cache settings successfully updated.',
'{TEXT_DATA_CACHE_UNAVAILABLE}' => 'Data cache not present.'
);
?>

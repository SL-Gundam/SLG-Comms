<?php
/***************************************************************************
 *                              lng_footer.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_footer.php,v 1.7 2005/06/21 11:23:14 SC Kruiper Exp $
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
'{TEXT_POWEREDBY}' => 'Powered by',

'{TEXT_BUGNOTIFY_P1}' => 'You\'ve encountered a bug in SLG. Well actually you just bumped into it. Please get in contact with the webmaster as soon as possible and tell him what happened.',
'{TEXT_BUGNOTIFY_P2}' => 'Tell him the page you were visiting and what the action was you performed there. Also copy the info at the bottom of the page about the page generation times, the info in this box and any other errors given in the page and give that to the webmaster.',
'{TEXT_DATANOTFOUND}' => 'Log data not found',
'{TEXT_THANKS}' => 'Thanks in advance.',
'{TEXT_CLOSE}' => 'Close',

'{TEXT_PAGEGEN}' => 'Page generation time',
'{TEXT_SQL_QUERIES}' => 'SQL queries',
'{TEXT_GZIP_ENABLED}' => 'GZIP enabled',
'{TEXT_GZIP_DISABLED}' => 'GZIP disabled',
'{TEXT_DEBUG_ON}' => 'Debug on',
'{TEXT_DEBUG_OFF}' => 'Debug off',
'{TEXT_UNKNOWN_VERSION}' => 'Unknown SLG version',
'{TEXT_NONEED}' => 'No need.',
'{TEXT_FREE}' => 'Free'
);
?>

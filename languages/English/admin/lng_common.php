<?php
/***************************************************************************
 *                              lng_common.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_common.php,v 1.8 2005/06/23 18:39:03 SC Kruiper Exp $
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
'{TEXT_YES}' => 'Yes',
'{TEXT_NO}' => 'No',
'{TEXT_ENABLE}' => 'Enable',
'{TEXT_DISABLE}' => 'Disable',
'{TEXT_CACHEHITS}' => 'Cache hits',
'{TEXT_CUSTOMSERVERS}' => 'Custom servers',
'{TEXT_DEFAULTQUERYPORT}' => 'Default queryport',
'{TEXT_FORUMRELATIVEPATH}' => 'Forum relative path',
'{TEXT_FORUMTYPE}' => 'Forum type',
'{TEXT_LANGUAGE}' => 'Language',
'{TEXT_PAGETITLE}' => 'Page title',
'{TEXT_RETRIEVEDDATASTATUS}' => 'Retrieved data status',
'{TEXT_SHOWSERVERINFORMATION}' => 'Show server information',
'{TEXT_TEMPLATE}' => 'Template',
'{TEXT_VENTRILOSTATUSPROGRAM}' => 'Ventrilo status program',
'{TEXT_FORUMGROUP}' => 'Forum group',
'{TEXT_LANGUAGE}' => 'Language',
'{TEXT_DEFAULTSERVER}' => 'Default server',
'{TEXT_SLGVERSION}' => 'SLG version',
'{TEXT_SHOW_HELP_FIELD}' => 'Show help on the format of the following field',
'{TEXT_FORUM_GROUPS_ERROR}' => 'The forum groups couldn\'t be retrieved. Please inform the webmaster.',
'{TEXT_MISSING_GROUP_QUERY_ERROR}' => 'SLG couldn\'t find a required query. Please inform the webmaster.',
'{TEXT_PAGEREFRESHTIMER}' => 'Page refresh timer',
'{TEXT_GZIPCOMPRESSION}' => 'GZIP Compression'
);

$this->popup += array(
'{TEXT_HELP_CACHEHITS}' => 'This enables or disables the ability to count the number of times cached data was retrieved instead of the live data.',
'{TEXT_HELP_CUSTOMSERVERS}' => 'This enables or disables the ability to retrieve data from custom servers provided by the client.',

'{TEXT_HELP_DEFAULTQUERYPORT}' => 'This is the default TeamSpeak TCP queryport to be used when not defined by other resources.

TeamSpeak normally has port 51234 as a default TCP queryport but this could be different if changed by the company / person hosting the TeamSpeak server. It could have also been changed by a new release of the TeamSpeak server. In any case its not adviseable to change this setting unless the TeamSpeak server uses another default queryport upon a new installation.

If you need a different queryport for a certain TeamSpeak server use the third optional parameter of the "Resource data" field. If you need more information on this optional parameter go to "Add resources" and click on "HELP".',

'{TEXT_HELP_FORUMRELATIVEPATH}' => 'This defines the relative path (meaning you must work from the current directory and to go up a directory you must use "../") to the forum selected in the "Forum type" setting.

Don\'t start this setting with a slash.

Please keep in mind that this directory must be from a servers point of view and not from the url of a browser.

Make sure you end with a slash.

For the best cross platform support use only slashes.

This setting is case sensitive.',

'{TEXT_HELP_FORUMTYPE}' => 'This defines the forum SLG will use as a backbone for user managing. Currently SLG will only work with 5 types of forums and its not possible to run it standalone unless you decided not to use a database. Also the forums supported have version numbers because SLG has been tested on those versions. Using SLG with other versions is unsupported so proceed at your own risk.

If you would like to see support for a forum that is still unsupported either add support yourself or send an email to the person who created it. The email address is in the copyright notice at the bottom of every page.',
'{TEXT_HELP_PAGETITLE}' => 'This is the title of the page. It will be used in the header and in the top part of the page.',
'{TEXT_HELP_RETRIEVEDDATASTATUS}' => 'This enables or disables the ability to display whether the current information that is displayed is cached or live data.',
'{TEXT_HELP_SHOWSERVERINFORMATION}' => 'This enables or disables the ability to display the server information pane.

This information is allways available by clicking in the Channel information pane on the server name.',

'{TEXT_HELP_VENTRILOSTATUSPROGRAM}' => 'This is the relative path (meaning you must work from the current directory and to go up a directory you must use "../" or "..\") to the Ventrilo status program which is needed for retrieving data from a Ventrilo server.

There are two versions, one for Unix / Linux platforms and one for Windows platforms. The Windows version normally has an .exe extension and the Unix / Linux none at all. Select the correct file for the operating system SLG will be run on.

Don\'t start this setting with a slash or backslash.

Please keep in mind that this directory must be from a servers point of view and not from the url of a browser.

Make sure you use backslashes when SLG is hosted on a Windows system and slashes when hosted on Unix / Linux systems.

Version 2.2.0 of the Ventrilo status program has been tested on version 2.1.2 and 2.2.0 of the Ventrilo server.

Because the ventrilo status program is copyright protected i can\'t pack it into this release. Please download a version of the Ventrilo server from www.ventrilo.com and place the "ventrilo_status.exe" (Win32 executable) and / or "ventrilo_status" (Unix / Linux executable) in a folder accessible by SLG. make sure that the files have full rights especially under Unix / Linux. It requires read, write and execute (777) access.

This setting is case sensitive.',

'{TEXT_HELP_FORUMGROUP}' => 'This is the forum group that has access to the administrator section of SLG.',
'{TEXT_HELP_LANGUAGE}' => 'Here you can select what language you would like to use with SLG.',
'{TEXT_HELP_TEMPLATE}' => 'Here you can select what template you would like to use with SLG. The 2 templates delivered with this release, "Default" and "Default 2" are allmost exactly the same except for the menu in the admi pages. So select the version with the menu you like best.',
'{TEXT_HELP_DEFAULTSERVER}' => 'The default server to be loaded if the client hasn\'t selected a server yet.',
'{TEXT_HELP_SLGVERSION}' => 'This is the current version of SLG. This setting can not be changed.',
'{TEXT_HELP_PAGEREFRESHTIMER}' => 'Here you can define a amount of seconds after which the index page should be automatically refreshed. Set this setting to zero to disable this feature and hide the clock. if you enabled this feature, you can allways start and stop this timer while your viewing the page by clicking on the clock in the top right corner of the page.',
'{TEXT_HELP_GZIPCOMPRESSION}' => 'This setting enables or disabled this script GZIP compression engine. This makes the pages outputted to clients smaller.

Personally i advise you to enable GZIP compression. Allthough enabling it this way should be the last option.

The best option would be by allowing your webserver to do it. Apache 1.3 has "mod_gzip" (Not available in the default Apache 1.3 package. http://www.schroepl.net/projekte/mod_gzip/) and Apache 2.0 has "mod_deflate" (Should be available in the default Apache 2.0 package). Apache 2.0 manual can tell you how to setup "mod_deflate" and the "mod_gzip" website has information on how to install "mod_gzip". I\'m not sure whether IIS has anything that provides this service.

If the modules are not available and you don\'t have the option of installing them yourself, you can try the following.

Configure PHP to automatically use GZIP compression through the "zlib.output_compression" setting. If you don\'t know how to do this then go to the next option.

If all of the above is out of the question enable this setting.

The footer at the bottom of every page checks whether or not GZIP compression is working, not whether or not it is enabled. This means that even if you disabled this setting that it can tell say "GZIP enabled". This is because SLG might notice that one of the above mentioned alternate options is working.'
);
?>

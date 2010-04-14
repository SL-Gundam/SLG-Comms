<?php
/***************************************************************************
 *                              lng_common.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_common.php,v 1.33 2008/08/12 22:59:41 SC Kruiper Exp $
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
'{TEXT_ENABLE}'                            => 'Enable',
'{TEXT_DISABLE}'                           => 'Disable',

'{TEXT_BASE_URL}'                          => 'Base URL',
'{TEXT_CACHE_HITS}'                        => 'Cache hits',
'{TEXT_CUSTOM_SERVERS}'                    => 'Custom servers',
'{TEXT_DEFAULT_QUERYPORT}'                 => 'Default queryport',
'{TEXT_DEFAULT_SERVER}'                    => 'Default server',
'{TEXT_DISPLAY_PING}'                      => 'Display ping',
'{TEXT_DISPLAY_SERVER_INFORMATION}'        => 'Display server information',
'{TEXT_FORUM_GROUP}'                       => 'Forum group',
'{TEXT_FORUM_RELATIVE_PATH}'               => 'Forum relative path',
'{TEXT_FORUM_TYPE}'                        => 'Forum type',
'{TEXT_GZIP_COMPRESSION}'                  => 'GZIP Compression',
'{TEXT_LANGUAGE}'                          => 'Language',
'{TEXT_PAGE_GENERATION_TIME}'              => 'Page generation time',
'{TEXT_PAGE_REFRESH_TIMER}'                => 'Page refresh timer',
'{TEXT_PAGE_TITLE}'                        => 'Page title',
'{TEXT_RETRIEVED_DATA_STATUS}'             => 'Retrieved data status',
'{TEXT_ROOT_PATH}'                         => 'Root directory',
'{TEXT_SLG_VERSION}'                       => 'SLG version',
'{TEXT_TEAMSPEAK_SUPPORT}'                 => 'TeamSpeak support',
'{TEXT_TEMPLATE}'                          => 'Template',
'{TEXT_VENTRILO_STATUS_PROGRAM}'           => 'Ventrilo status program',
'{TEXT_VENTRILO_SUPPORT}'                  => 'Ventrilo support',
);

$this->tooltips += array(
'{TEXT_HELP_BASE_URL}'                     => 'This is the complete url excluding "http://" as seen from a browsers point of view. This is the part starting with the hostname till the directory SLG Comms is located in.

Example: 
Location SLG Comms: http://www.test.com/website/SLG Comms/
Then the following would need to be entered in this setting: www.test.com/website/SLG Comms/

This setting is used to create the proper links for images, stylesheets and some hyperlinks.

Please correct this setting if images and stylesheets don\'t show properly',

'{TEXT_HELP_CACHE_HITS}'                   => 'This enables or disables the ability to count the number of times cached data was retrieved instead of the live data.',

'{TEXT_HELP_CUSTOM_SERVERS}'               => 'This enables or disables the ability to retrieve data from custom servers provided by the client. If you don\'t need this feature for a good reason it\'s advised to disable it.',

'{TEXT_HELP_DEFAULT_QUERYPORT}'            => 'This is the default TeamSpeak TCP queryport to be used when not defined by the resource.

TeamSpeak normally has port 51234 as a default TCP queryport but this could be different if changed by the company / person hosting the TeamSpeak server. It could have also been changed by a new release of the TeamSpeak server. In any case its not adviseable to change this setting unless the TeamSpeak server uses another default queryport upon a new installation.

If you need a different queryport for a certain TeamSpeak server use the third optional parameter of the "Resource data" field. If you need more information on this optional parameter go to "Add resources" and hover the mouse above the text "HELP".',

'{TEXT_HELP_DEFAULT_SERVER}'               => 'The default server to be loaded if the client hasn\'t selected a server yet.',

'{TEXT_HELP_DISPLAY_PING}'                 => 'This enabled or disables the displaying of the ping behind client names.

The purpose of this setting is to enable you to free some space if you use SLG Comms in a side-block of a Content Management System.

If you disable this setting the ping will still be displayed in the tooltip of the clients.',

'{TEXT_HELP_DISPLAY_SERVER_INFORMATION}'   => 'This enables or disables the ability to display the server information pane.

This information is always available by hovering over the Channel information pane on the server name.',

'{TEXT_HELP_FORUM_GROUP}'                  => 'This is the forum group that has access to the administrator section of SLG Comms.',

'{TEXT_HELP_FORUM_RELATIVE_PATH}'          => 'This is the relative path (meaning you must work from the current directory and to go up a directory you must use "../") to the forum selected in the "Forum type" setting.

Don\'t start this setting with a slash.

Please keep in mind that this directory must be from a servers point of view and not from the url of a browser.

Make sure you end with a slash.

For the best cross platform support only use slashes for the directory separators.

This setting is case sensitive.',

'{TEXT_HELP_FORUM_TYPE}'                   => 'This defines the forum SLG Comms will use as a backbone for user managing. Currently SLG Comms supports a limited amount of forums ( Some have multiple profiles because of different versions ) and its not possible to run it standalone unless you decided not to use a database. Also the forums supported have version numbers because SLG Comms has been tested on those versions. Using SLG Comms with other versions is unsupported so proceed at your own risk.

If you would like to see support for a forum that is still unsupported either add support yourself or add a feature request on the website of SLG Comms. The URL is in the copyright notice at the bottom of every page.',

'{TEXT_HELP_GZIP_COMPRESSION}'             => 'This setting enables or disabled PHP\'s GZIP compression engine. This makes the files sent to clients smaller in exchange for a tiny bit of processing time.

It\'s advised that you enable GZIP compression. Although enabling it this way should be the last option.

The best option would be by allowing your webserver to do it. Apache 1.3 has "mod_gzip" (Not available in the default Apache 1.3 package. http://www.schroepl.net/projekte/mod_gzip/) and Apache 2.0 has "mod_deflate" (Should be available in the default Apache 2.0 package). The Apache 2.0 manual can tell you how to setup "mod_deflate" and the "mod_gzip" website has information on how to install "mod_gzip". I\'m not sure whether IIS has anything that provides this service.

If the modules are not available and you don\'t have the option of installing them yourself, you can try the following.

Configure PHP to automatically use GZIP compression through the "zlib.output_compression" setting. If you don\'t know how to do this or can\'t then there\'s only one option left.

If all of the above is out of the question enable this setting.

The footer at the bottom of every page checks whether or not GZIP compression is working, not whether or not it is enabled. This means that even if you disabled this setting that it can say "GZIP enabled". This is because SLG Comms might detect that one of the above mentioned alternate options is active.',

'{TEXT_HELP_LANGUAGE}'                     => 'Here you can select what language you would like to use with SLG Comms.

You can add more languages by adding them in the "languages" directory in the SLG Comms directory.',

'{TEXT_HELP_PAGE_GENERATION_TIME}'         => 'This enables or disables the "Page generation time" text at the bottom of the pages.',

'{TEXT_HELP_PAGE_REFRESH_TIMER}'           => 'Here you can define an amount of seconds after which the index page should be automatically refreshed. Set this setting to zero to disable this feature and hide the clock. If you enable this feature, you can always start and stop this timer while you\'re viewing the page by clicking on the clock in the top right corner of the page.',

'{TEXT_HELP_PAGE_TITLE}'                   => 'This is the title of the pages. It will be used in the header and in the top part of the pages.',

'{TEXT_HELP_RETRIEVED_DATA_STATUS}'        => 'This enables or disables the ability to display whether the current information that is displayed is cached or live data.',

'{TEXT_HELP_ROOT_PATH}'                    => 'This is the SLG Comms root directory as seen from the webservers point of view. So that means no http:// etc. stuff. Depending on the operating system where the webserver is running on the format will differ.

Normally this value should never be changed. If you do change it SLG Comms might not work properly anymore and you would have to use "Restore settings" in install.php to fix the problem.

Examples:
Win32: H:/apache/htdocs/SLG Comms/
Unix / Linux: /user/test/htdocs/SLG Comms/

Incase the current value is incorrect it\'s highly advised to correct it.',

'{TEXT_HELP_SLG_VERSION}'                  => 'This is the current version of SLG Comms. This setting can not be changed.',

'{TEXT_HELP_TEAMSPEAK_SUPPORT}'            => 'This enables or disables support for TeamSpeak servers.',

'{TEXT_HELP_TEMPLATE}'                     => 'Here you can select which template you would like to use with SLG Comms. There are 3 templates delivered with this release. "Default" and "Default 2" are almost exactly the same except for the menu in the admin pages. So select the version with the menu you like best.

"Default 2" though has only one purpose and thats to show that it\'s possible to create a template with a vertical menu instead of the horizontal one in the "Default" template. The vertical menu will work without problems although layout might be a bit irritating sometimes.

"Default compact" is basically the same as "Default" except that it is based on the idea that it will be used in a side-block of a Content Management System. To get the most from this template it is advised to set the following settings to the following values:
Custom servers -> Disable
Display ping -> Disable
Display server information -> Disable
Page generation time -> 0
Page refresh timer -> Disable
Retrieved data status -> Disable

Something you should also know about the "Default compact" template is that the template has no form where you can select a server. To get a specific server you need to use the url you get when you click on the name of that specific server in the "Manage resources" and "Manage servers" pages.',

'{TEXT_HELP_VENTRILO_STATUS_PROGRAM}'      => 'This is the relative path (meaning you must work from the current directory and to go up a directory you must use "../" or "..\") to the Ventrilo status program which is needed for retrieving data from a Ventrilo server.

There are different versions for different operating systems, one for Unix / Linux platforms, one for Windows platforms and various other operating systems. The Windows version normally has an .exe extension and the Unix / Linux none at all. Select the correct file for the operating system SLG Comms will be run on.

Don\'t start this setting with a slash or backslash.

Please keep in mind that this directory must be from a servers point of view and not from the url of a browser.

It\'s advised to use only slashes for directory separators but with Windows the backslash is also available.

Version 2.2.0 and 2.3.0 of the Ventrilo status program have been tested on version 2.1.2, 2.2.0 and 2.3.0 of the Ventrilo server.

Because the ventrilo status program is copyright protected i can\'t pack it into this release. Please download a version of the Ventrilo server from www.ventrilo.com and place the "ventrilo_status.exe" (Win32 executable), "ventrilo_status" (Unix / Linux executable) or the Ventrilo status executable for your operating system in a folder accessible by SLG Comms. make sure that the files have full rights especially under Unix / Linux. It requires read, write and execute (777) access. Write might seem weird but with just read and execute it sometimes didn\'t work properly on some systems.

There are also versions of the Ventrilo status program available for other operating systems, but these have not been tested. They should work fine but there aren\'t any guarantees.

This setting is case sensitive.',

'{TEXT_HELP_VENTRILO_SUPPORT}'             => 'This enables or disables support for Ventrilo servers.',
);
?>

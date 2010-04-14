<?php
/***************************************************************************
 *                            lng_earlyerrors.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_earlyerrors.php,v 1.43 2008/08/15 17:46:50 SC Kruiper Exp $
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
'{TEXT_ERROR}'                      => 'ERROR',
'{TEXT_LAST_ERROR}'                 => 'LAST ERROR',
'{TEXT_QUERY}'                      => 'QUERY',
'{TEXT_SQLERROR}'                   => 'SQL ERROR',

'{TEXT_NOINSTALL}'                  => 'By all accounts it seems you haven\'t run install.php yet.
<a href="install.php">Please do so now by clicking here</a>.',
'{TEXT_NO_DATABASE_MODE_ACT}'       => 'SLG Comms is operating in a so called "NO DATABASE" mode which means that its not working with support from a database. The result is that this page is disabled.
All the settings at your disposal are in the file "dbsettings.inc.php"',

'{TEXT_LOADTEMPLATE_ERROR}'         => 'It\'s not allowed to load more then one template into the same class.
Please inform the webmaster.',

'{TEXT_CONF_FILE_NOT_IN_DOCROOT}'   => 'The forum configuration file needs to be within the document root of your webserver.',
'{TEXT_FORUM_NOT_FOUND_ERROR}'      => 'SLG Comms couldn\'t find the forum in the directory specified.
Please fill in the correct information and try again.',
'{TEXT_FORUMTYPE_COMBI_ERROR}'      => 'The current combination of "Forum type" and "Forum relative path" is incorrect.
Please inform the webmaster.',
'{TEXT_INVALID_CONF_FILE}'          => 'Invalid forum configuration file.',
'{TEXT_MISSING_GROUP_QUERY_ERROR}'  => 'SLG Comms couldn\'t find the required query to retrieve the forum groups. Please inform the webmaster.',
'{TEXT_NOGROUP_INSTALL}'            => 'It seems the forum you selected doesn\'t have any groups. Please create one containing the user accounts which are allowed to access the administrator section of SLG Comms.',
'{TEXT_UNKNOWN_FORUMTYPE_ERROR}'    => 'An unknown forum type was encountered in the settings.
Please inform the webmaster.',

'{TEXT_CLASS_TIMECOUNT_ERROR}'      => 'Bug encountered in class "timecount". Please inform the webmaster.',

'{TEXT_SETTINGFORM_ERROR}'          => 'There is a bug in the settings form. Please inform the webmaster.',

'{TEXT_SUPPORT_VENT_DISABLED}'      => 'Support for Ventrilo has been disabled.',
'{TEXT_SUPPORT_TS_DISABLED}'        => 'Support for TeamSpeak has been disabled.',

'{TEXT_OLDVERSION_UNAVAILABLE}'     => 'SLG Comms tried to perform a update of an earlier version of SLG Comms but couldn\'t find the old version number. It\'s highly probable that you do not have a earlier version installed yet.',
'{TEXT_SAMEVERSIONUPDATE}'          => 'Update cancelled because SLG Comms has already been updated with this version or a newer one.',
'{TEXT_SLGVERSIONCONFLICT}'         => 'The internal file version isn\'t the same as the installed version.
Please update SLG Comms by running <a href="install.php">install.php</a>.',

'{TEXT_DEFINED_VENT_PROG_INVALID}'  => 'The defined Ventrilo status program is invalid. Please check the setting and correct it.',
'{TEXT_NOVENTRILO}'                 => 'You havn\'t defined a Ventrilo status program yet. Because of that support for ventrilo is not yet available.',
'{TEXT_VENTRILO_NOT_IN_SLG_DIR}'    => 'The Ventrilo status program needs to be located within the SLG Comms root directory or a sub directory of the SLG Comms root directory. The name of the sub directory doesn\'t matter.',
'{TEXT_UNKNOWN_EXEC_ERROR}'         => 'Unknown EXEC() function error encountered. Its possible that PHP\'s safe mode is activated. See <a href="http://www.php.net/exec">PHP EXEC() function documentation</a> and <a href="http://www.php.net/safe+mode+functions">PHP functions restricted/disabled by safe mode</a>.',

'{TEXT_NOTTEAMSPEAK}'               => 'Corrupt or invalid TeamSpeak server data encountered.',
'{TEXT_STREAM_TIMEOUT}'             => 'TeamSpeak server data retrieval timed out.',
'{TEXT_TSINVALIDID_ERROR}'          => 'SLG Comms connected to a valid TeamSpeak server but the port number provided didn\'t belong to a server hosted by this TeamSpeak server.',

'{TEXT_CONNECT_ALLREADY}'           => 'There is already a connection established in this instance of the database class. The disconnect() function should be called before opening a new connection to the database.',
'{TEXT_DB_DISCONNECT_ERROR}'        => 'Failed to close the database server connection. Most likely the given connection didn\'t exist because of prior errors.',
'{TEXT_DIFFERENT_DB_INFO}'          => 'Different database information filled in compared to stored information in "dbsettings.inc.php". This information has to be exactly the same to continue.',
'{TEXT_NO_CONNECT_AVAILABLE}'       => 'The "Database::connect()" function needs to be called before the other functions can be used.',
'{TEXT_UNACCEPTABLE_TABLEPREFIX}'   => 'Unacceptable table prefix discovered',

'{TEXT_IP_PORT_COMB_ERROR}'         => 'The filled in ip port combination is invalid.',

'{TEXT_LOADCACHE_FAILED}'           => 'Cached server data not found',
'{TEXT_RAWDATA_UNAVAILABLE}'        => 'No rawdata available.',
'{TEXT_RETRIEVALBUSY}'              => 'Retrieval of live server data in progress.',
'{TEXT_SERVERUPDATE_DISABLED}'      => 'SERVER UPDATES DISABLED.
This server failed 25 consecutive connection attempts. Therefore its likely this server doesn\'t exist anymore.',

'{TEXT_NO_RESOURCE}'                => 'This DATABASE function requires the parameter to be a resource, it wasn\'t.',

'{TEXT_RECURSIVE_FUNC_PROT}'        => 'A Recursive function was executed to many times and caused SLG Comms to stop execution of the script as a protective measure.',

'{TEXT_NOSERVERS}'                  => 'No servers available.',
'{TEXT_NOCUSTOMSERVERS}'            => 'Custom server ability disabled.',

'{TEXT_NOTHINGTODO}'                => 'Because of a combination of errors (displayed above) SLG Comms can\'t perform any tasks. Please read the previous errors and act appropriatly',
);

$this->text_adv += array(
'{TEXT_DATA_TYPE_ERROR}'            => 'Unknown data type ($1) encountered during the processing of raw server data.',

'{TEXT_DB_CONNECT_ERROR}'           => 'Could not connect to the database server ($1). Please inform the webmaster.',
'{TEXT_DB_DATASEEK_FAILED}'         => 'Failed to perform a dataseek with the following result identifer: "$1"',
'{TEXT_DB_FREEQUERY_FAILED}'        => 'Failed to free the following result identifer: "$1"',
'{TEXT_DB_QUERY_FAILED}'            => 'Query "$1" Failed.',
'{TEXT_DB_SELECT_ERROR}'            => 'Could not select the database ($1). Please inform the webmaster.',

'{TEXT_EXTNOTLOAD}'                 => '$1 extension not loaded. Please make sure your PHP has the $1 extension loaded.',

'{TEXT_TS_COMMAND_ERROR}'           => 'The "$1" command sent to the TeamSpeak server generated an error.
The exact error returned by the TeamSpeak server can be found below. The returned error message is often very unclear about the specific error though.',

'{TEXT_UNKNOWNSETTING}'             => 'Unknown setting type encountered: "$1".',
);
?>

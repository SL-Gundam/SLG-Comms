<?php
/***************************************************************************
 *                            lng_earlyerrors.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_earlyerrors.php,v 1.15 2005/10/24 14:08:13 SC Kruiper Exp $
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
'{TEXT_NOINSTALL}' => 'By all accounts it seems you havn\'t run install.php yet.
<a href="install.php">Please do so now by clicking here</a>.',
'{TEXT_LOADTEMPLATE_ERROR}' => 'It\'s not allowed to load more then one template into the same class.
Please inform the webmaster.',
'{TEXT_UNKNOWN_FORUMTYPE_ERROR}' => 'An unknown forum type was encountered in the settings.
Please inform the webmaster.',
'{TEXT_FORUMTYPE_COMBI_ERROR}' => 'The current combination of "Forum type" and "Forum relative path" is incorrect.
Please inform the webmaster.',
'{TEXT_NO_DATABASE_MODE_ACT}' => 'SLG is operating in a so called "NO DATABASE" mode which means that its not working with support from a database. The result is that this page is disabled.
All the settings at your disposal are in the file "dbsettings.inc.php"',
'{TEXT_CLASS_TIMECOUNT_ERROR}' => 'Bug encountered in class "timecount". Please notify the webmaster as soon as possible.',
'{TEXT_DATA_TYPE_ERROR}' => 'Error encountered. Something went wrong with the types of data that were defined. Inform the webmaster please.',
'{TEXT_FORUM_NOT_FOUND_ERROR}' => 'SLG couldn\'t find the forum in the directory specified.
Please go back and fill in the correct information.',
'{TEXT_SETTINGFORM_ERROR}' => 'There is a bug in the settings form. Please inform the webmaster.',
'{TEXT_DB_CONNECT_FAILED}' => 'Database server connection failed.',
'{TEXT_DB_DISCONNECT_FAILED}' => 'Database server close connection failed.',
'{TEXT_DB_SELECT_FAILED}' => 'Database selection failed.',
'{TEXT_SUPPORT_VENT_DISABLED}' => 'Support for Ventrilo has been disabled.',
'{TEXT_SUPPORT_TS_DISABLED}' => 'Support for TeamSpeak has been disabled.',
'{TEXT_SAMEVERSIONUPDATE}' => 'Update cancelled because SLG Comms has allready been updated with this version or a newer one.',
'{TEXT_NOVENTRILO}' => 'You havn\'t defined a Ventrilo status program yet. Because of that support for ventrilo is not yet available.',
'{TEXT_OLDVERSION_UNAVAILABLE}' => 'SLG Comms tried to perform a update of an earlier version of SLG Comms but couldn\'t find the old version number. It\'s highly probable that you do not have a earlier version installed yet.',
'{TEXT_NOTTEAMSPEAK}' => 'Corrupt and / or invalid TeamSpeak server data encountered.',
'{TEXT_MYSQLIEXTNOTLOAD}' => 'MySQLi extension not loaded. Please make sure your PHP has the MySQLi extension loaded.',
'{TEXT_NOGROUP}' => 'It seems the forum you selected doesn\'t have any groups. Please create one containing the user accounts which are allowed to access the administrator section of SLG Comms.',
'{TEXT_TSINVALIDID_ERROR}' => 'This error means that you entered a port number which doesn\'t exist for this TeamSpeak server.',
'{TEXT_RETURNED_ERROR}' => 'RETURNED ERROR'
);

$this->text_adv += array(
'{TEXT_TS_COMMAND_ERROR}' => 'The "$1" command sent to the TeamSpeak server generated an error.',
'{TEXT_VENTRILO_EXEC_ERROR}' => 'It seems that something went wrong while executing "$1". Please check all your settings. It could be that the EXEC command is not allowed, safe mode is on and causing problems or that the given path and filename for the Ventrilo status program is wrong.',
'{TEXT_DB_CONNECT_ERROR}' => 'Could not connect to the database server ($1). Please contact your webmaster.',
'{TEXT_DB_DISCONNECT_ERROR}' => 'Failed to close the database server connection ($1).',
'{TEXT_DB_SELECT_ERROR}' => 'Could not select the database ($1). Please contact your webmaster.',
'{TEXT_DB_QUERY_FAILED}' => 'Query "$1" Failed.',
'{TEXT_DB_FREEQUERY_FAILED}' => 'Failed to free the following result identifer: "$1"',
'{TEXT_DB_DATASEEK_FAILED}' => 'Failed to perform a dataseek with the following result identifer: "$1"'
);
?>

<?php
/***************************************************************************
 *                            lng_earlyerrors.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_earlyerrors.php,v 1.12 2005/07/01 15:34:55 SC Kruiper Exp $
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
'{TEXT_DBTESTERROR}' => 'As you can see the database couldn\'t be selected.
Check whether you filled in the correct information, whether the database exist and whether we have the rights to access it with the currently used username and password.',
'{TEXT_FORUM_NOT_FOUND_ERROR}' => 'SLG couldn\'t find the forum in the directory specified.
Please go back and fill in the correct information.',
'{TEXT_SETTINGFORM_ERROR}' => 'There is a bug in the settings form. Please inform the webmaster.',
'{TEXT_DB_CONNECT_FAILED}' => 'Database server connection failed.',
'{TEXT_DB_DISCONNECT_FAILED}' => 'Database server close connection failed.',
'{TEXT_DB_SELECT_FAILED}' => 'Database selection failed.'
);

$this->text_adv += array(
'{TEXT_TS_COMMAND_ERROR}' => 'The "$1" command sent to the TeamSpeak server generated an error.',
'{TEXT_VENTRILO_EXEC_ERROR}' => 'It seems that something went wrong while executing "$1". Please check all your settings. It could be that the EXEC command is not allowed, safe mode is on and causing problems or that the given path and filename for the Ventrilo status program is wrong.',
'{TEXT_DB_CONNECT_ERROR}' => 'Could not connect to the database server ($1). Please contact your webmaster.',
'{TEXT_DB_DISCONNECT_ERROR}' => 'Failed to close the database server connection ($1).',
'{TEXT_DB_SELECT_ERROR}' => 'Could not select the database ($1). Please contact your webmaster.',
'{TEXT_DB_QUERY_FAILED}' => 'Query "$1" Failed.',
'{TEXT_DB_FREEQUERY_FAILED}' => 'Failed to free the following result identifer: "$1"',
'{TEXT_DB_DATASEEK_FAILED}' => 'Failed to perform a dataseek with the following result identifer: "$1"',
'{TEXT_RETURNED_EXEC_ERROR}' => 'RETURNED ERROR: $1'
);
?>

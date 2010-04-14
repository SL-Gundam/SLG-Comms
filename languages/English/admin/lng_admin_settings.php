<?php
/***************************************************************************
 *                           lng_admin_settings.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_settings.php,v 1.14 2007/01/29 22:49:17 SC Kruiper Exp $
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
'{TEXT_SETTINGS_NOTE_P1}'           => 'If you\'re planning on changing the settings for the forum you want to use with SLG Comms, please use the following steps. Once this proces has been started it is required to finish it. You can change back afterwards if you changed your mind.',
'{TEXT_SETTINGS_NOTE_P2}'           => 'Note: Make sure you do this in one login session (Meaning don\'t logout under any surcumstance except for the last step.). You might not be able to login again if you do logout. The only way to correct the situation if you can\'t login again is to do the following.',
'{TEXT_SETTINGS_NOTE_P3}'           => 'Start install.php. It\'s possible you have to upload install.php again if you deleted it.  Select the following as "Installation type": "Restore settings". Fill everything with the correct information when asked for it. You should be back up and running soon enough.',
'{TEXT_SETTINGS_NOTE_L1}'           => 'Change "Forum relative path" and "Forum type".',
'{TEXT_SETTINGS_NOTE_L2}'           => 'Save the settings.',
'{TEXT_SETTINGS_NOTE_L3}'           => 'Change "Forum group".',
'{TEXT_SETTINGS_NOTE_L4}'           => 'Logout and login again.',

'{TEXT_UPDATE_SETTINGS}'            => 'Update settings',

'{TEXT_NEW_FORUM_INVALID}'          => 'The new forum settings are invalid and therefore will not be saved.',
'{TEXT_NOGROUP}'                    => 'No suitable groups found. Please create one.',
);

$this->text_adv += array(
'{TEXT_SETTINGUPDATE_SUCCESS}'      => 'The setting "$1" was successfully updated.',
);
?>

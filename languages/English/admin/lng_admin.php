<?php
/***************************************************************************
 *                               lng_admin.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin.php,v 1.5 2005/06/20 15:25:39 SC Kruiper Exp $
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
'{TEXT_LOGIN}' => 'Login',
'{TEXT_USERNAME}' => 'Username',
'{TEXT_PASSWORD}' => 'Password',
'{TEXT_LOGIN_FORM}' => 'Login',
'{TEXT_LOGIN_SUCCESS}' => 'Succesfully logged in.',
'{TEXT_LOGIN_FAILURE}' => 'That username and password combination did not match any of our records.
Please try again.',
'{TEXT_LOGOUT_SUCCESS}' => 'Succesfully logged out.',
'{TEXT_INSTALL_FILE_PRESENT}' => 'WARNING: You havn\'t removed install.php yet.
Its highly advised to remove it as its a mayor security risk.',
'{TEXT_SESSION_VIOLATION}' => 'Your session has been terminated because a security rule has been violated.
Don\'t worry just continue what you were doing. If neccasary you can login again.',
'{TEXT_MISSING_MENUTYPE}' => 'Couldn\'t find a menu placeholder in the template.',
'{TEXT_RESOURCES}' => 'Resources',
'{TEXT_SETTINGS}' => 'Settings',
'{TEXT_LOGOUT}' => 'Logout',
'{TEXT_ADD_RESOURCES}' => 'Add resources',
'{TEXT_MANAGE_RESOURCES}' => 'Manage resources',
'{TEXT_CACHE_RESOURCES}' => 'Caching servers'
);
?>

<?php
/***************************************************************************
 *                               lng_admin.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin.php,v 1.16 2007/01/27 22:45:56 SC Kruiper Exp $
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
'{TEXT_LOGIN}'                 => 'Login',
'{TEXT_LOGOUT}'                => 'Logout',

'{TEXT_USERNAME}'              => 'Username',
'{TEXT_PASSWORD}'              => 'Password',

'{TEXT_LOGIN_FAILURE}'         => 'That username and password combination did not match any of our records.
Please try again.',
'{TEXT_LOGIN_SUCCESS}'         => 'Successfully logged in.',
'{TEXT_LOGOUT_SUCCESS}'        => 'Successfully logged out.',

'{TEXT_RESOURCES}'             => 'Resources',
'{TEXT_SETTINGS}'              => 'Settings',

'{TEXT_ADD_RESOURCES}'         => 'Add resources',
'{TEXT_CACHE_RESOURCES}'       => 'Manage servers',
'{TEXT_MANAGE_RESOURCES}'      => 'Manage resources',

'{TEXT_ADMIN_WELCOME}'         => 'Welcome to the admin section.',

'{TEXT_INSTALL_FILE_PRESENT}'  => 'WARNING: The file install.php is still present in the install directory.
Its highly advised to remove it as its a mayor security risk.',

'{TEXT_SESSION_VIOLATION}'     => 'Your session has been terminated because a security rule has been violated.
Don\'t worry just continue what you were doing. If necessary you can login again.',

'{TEXT_NAVIGATION}'            => 'Navigation menu',
);

$this->text_adv += array(
'{TEXT_MISSING_MENUTYPE}'      => 'Couldn\'t find the menu placeholder "$1" in the template.',
);
?>

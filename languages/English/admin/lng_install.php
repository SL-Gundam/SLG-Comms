<?php
/***************************************************************************
 *                              lng_install.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_install.php,v 1.9 2005/06/30 19:40:05 SC Kruiper Exp $
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
'{TEXT_REQUIREMENTS}' => 'Requirements',
'{TEXT_PHPVERSION}' => 'PHP Version',
'{TEXT_UPDATEPHP}' => 'please update PHP',
'{TEXT_PCREEXT}' => 'PCRE extension',
'{TEXT_MYSQLDATABASE}' => 'MySQL database',
'{TEXT_WORKINGFORUM}' => 'Working forum',
'{TEXT_SELECTFORUM}' => 'Please select one of the supported forums from the drop down box',
'{TEXT_IGNOREOPTIONS}' => 'A number of options will be disabled because you don\'t want to use a database. Just ignore those disabled options.',
'{TEXT_DATABASE}' => 'Do you want to use a database with SLG?',
'{TEXT_DB_TYPE}' => 'Database type',
'{TEXT_DB_HOST}' => 'Database server hostname',
'{TEXT_DB_NAME}' => 'Database name',
'{TEXT_DB_USER}' => 'Database username',
'{TEXT_DB_PASSWD}' => 'Database password',
'{TEXT_TABLE_PREFIX}' => 'Table prefix',
'{TEXT_SELECTDB_SUCCESS}' => 'Succesfully selected the database.',
'{TEXT_INSTALL_SUCCESS}' => 'Installation successfully completed.',
'{TEXT_INSTALL_RESTORE_SUCCESS}' => 'The attempt to regenerate the settings was successfull. Please remove install.php again.',
'{TEXT_FINISH_INSTALL_LARGE}' => 'Please click on the "Finish installation" button to finish the installation. After you\'re done with install.php please delete it from the web host. If you don\'t delete it you have a mayor security risk on your hands.',
'{TEXT_FINISH_INSTALL}' => 'Finish installation',
'{TEXT_NEXT_STEP}' => 'Next step',
'{TEXT_DOWNLOAD_FILE}' => 'Download file',
'{TEXT_INSTALL_TYPE}' => 'Installation type',
'{TEXT_NEW_INSTALL}' => 'New installation',
'{TEXT_UPGRADE_INSTALL}' => 'Upgrade from an older version',
'{TEXT_RESCUE_INSTALL}' => 'Rescue attempt',
'{TEXT_DISABLED}' => 'Disabled',
'{TEXT_UPGRADE_SUCCESS}' => 'Successfully upgraded SLG'
);

$this->text_adv += array(
'{TEXT_INSTALLATIONSTEP}' => 'Installation step $1',
'{TEXT_TABLE_CREATE_SUCCESS}' => 'Successfully created table "$1".',
'{TEXT_INSERT_DATA_SUCCESS}' => 'Successfully inserted default data in table "$1".',
'{TEXT_TABLE_DROP_SUCCESS}' => 'Successfully removed the table "$1".',
'{TEXT_OPENFILE_ERROR}' => 'Cannot open file "$1".',
'{TEXT_FILEWRITE_ERROR}' => 'Cannot write to file "$1".',
'{TEXT_FILEWRITE_SUCCESS}' => 'Succesfully wrote the information to "$1".
You can now start using SLG by opening <a href="index.php">index.php</a> or <a href="admin.php">admin.php</a>.',
'{TEXT_CANTWRITE_FILE}' => 'The file "$1" is not writable.',
'{TEXT_FILE_DOESNTEXIST}' => 'The file "$1" doesn\'t exist.',
'{TEXT_DOWNLOAD_FILE_LARGE}' => 'It seems SLG couldn\'t save the file automatically. You are gonna have the click on the button "Download file". Download that file and upload it to the server in the same directory as SLG under the file name "$1".

Everything should work after that.

Don\'t forget to remove install.php after you\'re done.',
'{TEXT_NO_DATABASE_SERVERLIST}' => 'Because you don\'t want to use a database you\'re gonna have to manually alter "$1" to setup the server list.'
);

$this->popup += array(
'{TEXT_HELP_DATABASE}' => 'Do you want to use a database with SLG? With a database SLG will be a lot more powerfull. The main reason why its possible to disable the use of a database was to support hosts where there wasn\'t a database available.',
'{TEXT_HELP_DB_TYPE}' => 'The type of database you want to use. This can be either MySQL or MySQl 4.1.x. MySQL 4.1 support is only available when the MySQLi extension is included in your php installation.',
'{TEXT_HELP_DB_HOST}' => 'The hostname, ip address or socket of the database server.

If you selected MySQL 4.1.x as your database type then this setting MUST be a hostname or ip address. Sockets wont work properly.',
'{TEXT_HELP_DB_NAME}' => 'The name of the database thats going to be used to store the tables.',
'{TEXT_HELP_DB_USER}' => 'The username with which we will get access to the database server.',
'{TEXT_HELP_DB_PASSWD}' => 'The password with which we will get access to the database server.',
'{TEXT_HELP_TABLE_PREFIX}' => 'The prefix that will be placed in front of the table names to avoid conflicts with existing tables.',
'{TEXT_HELP_INSTALL_TYPE}' => 'Is this a new installation, a upgrade from an older version or an attempt to perform a rescue of the settings table.'
);
?>

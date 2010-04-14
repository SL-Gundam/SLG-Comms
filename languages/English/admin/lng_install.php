<?php
/***************************************************************************
 *                              lng_install.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_install.php,v 1.34 2007/01/30 16:16:48 SC Kruiper Exp $
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
'{TEXT_YES}'                       => 'Yes',
'{TEXT_NO}'                        => 'No',

'{TEXT_INSTALL_TYPE}'              => 'Installation type',
'{TEXT_NEW_INSTALL}'               => 'New installation',
'{TEXT_RESCUE_INSTALL}'            => 'Restore settings',
'{TEXT_UPGRADE_INSTALL}'           => 'Upgrade from an older version',

'{TEXT_DATABASE}'                  => 'Do you want to use a database with SLG Comms?',
'{TEXT_DB_HOST}'                   => 'Database server hostname',
'{TEXT_DB_NAME}'                   => 'Database name',
'{TEXT_DB_PASSWD}'                 => 'Database password',
'{TEXT_DB_TYPE}'                   => 'Database type',
'{TEXT_DB_USER}'                   => 'Database username',
'{TEXT_MYSQLDATABASE}'             => 'MySQL database',
'{TEXT_TABLE_PREFIX}'              => 'Table prefix',

'{TEXT_PCREEXT}'                   => 'PCRE extension',
'{TEXT_PHPVERSION}'                => 'PHP Version',
'{TEXT_REQUIREMENTS}'              => 'Requirements',
'{TEXT_SELECTFORUM}'               => 'Please select one of the supported forums from the drop down box',
'{TEXT_UPDATEPHP}'                 => 'please update PHP',
'{TEXT_WORKINGFORUM}'              => 'Working forum',
'{TEXT_GZIPEXT}'                   => 'ZLib extension',

'{TEXT_SELECTDB_SUCCESS}'          => 'Successfully selected the database.',
'{TEXT_INSTALL_SUCCESS}'           => 'Installation successfully completed.',
'{TEXT_INSTALL_RESTORE_SUCCESS}'   => 'The attempt to regenerate the settings was successfull. Please remove install.php again.',

'{TEXT_FINISH_INSTALL_LARGE}'      => 'Please click on the "Finish installation" button to finish the installation. After you\'re done with install.php please delete it from the web host. If you don\'t delete it you have a mayor security risk on your hands.',
'{TEXT_FINISH_INSTALL}'            => 'Finish installation',

'{TEXT_INSTALLATIONSTEP}'          => 'Installation step',
'{TEXT_NEXT_STEP}'                 => 'Next step',

'{TEXT_DOWNLOAD_FILE}'             => 'Download file',

'{TEXT_ADDEDNEWSETTINGS}'          => 'Added new settings to the configuration file.',
'{TEXT_MODDEDNEWSETTINGS}'         => 'Modified variable names in the configuration file.',
'{TEXT_UPGRADE_SUCCESS}'           => 'Successfully upgraded SLG Comms.
<a href="index.php">Please continue to the main page</a>.',
);

$this->text_adv += array(
'{TEXT_UPGRADING_FROM}'            => 'Upgrading from: $1.',
'{TEXT_UPGRADING_TO}'              => 'Upgrading to: $1.',

'{TEXT_ADDCOL_SUCCESS}'            => 'Successfully added a new column to the table: "$1".',
'{TEXT_CHANGE_SUCCESS}'            => 'Successfully updated the table: "$1".',
'{TEXT_INSERT_DATA_SUCCESS}'       => 'Successfully inserted default data in table: "$1".',
'{TEXT_TABLE_CREATE_SUCCESS}'      => 'Successfully created table: "$1".',
'{TEXT_TABLE_DROP_SUCCESS}'        => 'Successfully dropped the table: "$1".',
'{TEXT_UPDATE_SUCCESS}'            => 'Successfully updated the data in the table: "$1".',
'{TEXT_VERSION_SUCCESS}'           => 'Completed applying table modifications for versions older then $1.',

'{TEXT_CANTWRITE_FILE}'            => 'The file "$1" is not writable.',
'{TEXT_FILE_DOESNTEXIST}'          => 'The file "$1" doesn\'t exist.',
'{TEXT_FILEWRITE_ERROR}'           => 'Cannot write to file "$1".',
'{TEXT_FILEWRITE_SUCCESS}'         => 'Successfully wrote the information to "$1".
You can now start using SLG Comms by opening <a href="index.php">index.php</a>.',
'{TEXT_OPENFILE_ERROR}'            => 'Cannot open file "$1".',

'{TEXT_NO_DATABASE_SERVERLIST}'    => 'Because you don\'t want to use a database you will have to manually alter "$1" to setup the server list.',

'{TEXT_DOWNLOAD_FILE_LARGE}'       => 'It seems SLG Comms couldn\'t save the file automatically. Please click on the button "Download file". Download that file and upload it to the server in the same directory as SLG Comms under the file name "$1".

Everything should work after that.

Don\'t forget to remove install.php after you\'re done.',
);

$this->tooltips += array(
'{TEXT_HELP_DATABASE}'             => 'Do you want to use a database with SLG Comms? With a database SLG Comms will be a lot more powerfull. The main reason why its possible to disable the use of a database was to support hosts where there wasn\'t a database available.',

'{TEXT_HELP_DB_HOST}'              => 'The hostname, ip address or socket of the database server. Optionally you\'ll also need to add the port number if this is different from the default port "3306", resulting in something like this: "localhost:3305".

If you selected MySQL 4.1.x as your database type then this setting MUST be a hostname or ip address with an optional port number. Sockets wont work properly.',

'{TEXT_HELP_DB_NAME}'              => 'The name of the database thats going to be used to store the tables. Make sure this database already exists and has the rights set up correctly.',

'{TEXT_HELP_DB_PASSWD}'            => 'The password with which SLG Comms will get access to the database server.',

'{TEXT_HELP_DB_TYPE}'              => 'The type of database you want to use. This can be either "MySQL" or "MySQL 4.1.x / 5.0.x". MySQL 4.1 / 5.0 support is only available when the MySQLi extension is included in your php installation. "MySQL 4.1.x / 5.0.x" is the recommended choice for compatibility reasons.

Since MySQL 4.1 / 5.0 uses a new password encryption algorithm. The old MySQL extension provided with PHP doesn\'t support authentication with this new encryption algorithm and will either say it doesn\'t support the new authentication method or that your password + username combination is invalid. It\'s possible to create MySQL user accounts with old password hashes but you have to explicitly tell MySQL to do this when you define the password. Please check www.mysql.com for more information if needed.',

'{TEXT_HELP_DB_USER}'              => 'The username with which SLG Comms will get access to the database server.',

'{TEXT_HELP_TABLE_PREFIX}'         => 'The prefix that will be placed in front of the table names to avoid conflicts with existing tables.',

'{TEXT_HELP_INSTALL_TYPE}'         => 'Is this a "New installation", a "Upgrade from an older version" or do you want to "Restore settings".

"Restore settings" is only usefull when you use the database supported version of SLG Comms as its used to perform a restoration of the settings table when you can\'t log-in anymore in the administrator section section.',
);
?>

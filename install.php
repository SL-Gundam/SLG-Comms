<?php
/***************************************************************************
 *                                install.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: install.php,v 1.94 2006/06/12 14:42:42 SC Kruiper Exp $
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

// download the dbsetings.inc.php file in case it couldn't be auto saved
if ( isset($_GET['step']) && $_GET['step'] == 8 )
{
	if ( isset($_POST['filename'], $_POST['content']) )
	{
		require( $tssettings['Root_path'] . 'includes/functions.inc.php' );

		processincomingdata( $_POST, true );

		header( "Content-type: text/x-plain" );
		header( "Pragma: public" );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( 'Content-Disposition: attachment; filename="' . $_POST['filename'] . '"' );

//		header( 'Content-Type: text/x-delimtext; name="' . $_POST['filename'] . '"' );
//		header( 'Content-disposition: attachment; filename="' . $_POST['filename'] . '"' );

		echo $_POST['content'];
	}
	else
	{
		echo 'No download content available.';
	}
	exit;
}

define( "IN_SLG", 10 );

// install shouldn't use the root_path variables incase they are incorrect. This is also one of the reasons install.php is a riskier file then all the others. 
$tssettings['Root_path'] = './';

require( $tssettings['Root_path'] . 'includes/config.inc.php' );

// it's possible that when SLG Comms has allready been installed and you run install.php that this value will be overwritten with the value from the database. Which incase these values are wrong could deny the use of the "restore settings" functionality. So let's set them correctly again just to be sure.
$tssettings['Root_path'] = './';

if ( !isset($tssettings['SLG_version']) && isset($tssettings['SLG version']) )
{
	$tmparr = $tssettings;
	$tssettings = array();
	foreach ( $tmparr as $variable => $value )
	{
		$tssettings[ str_replace( ' ', '_', $variable ) ] = $value;
	}
	unset( $tmparr, $variable, $value );
}

// if step doesn't exist step must be 1
if (
	!isset($_GET['step']) || 
	!is_numeric($_GET['step']) || 
	(
		!isset($_POST['variable']['install_type']) || 
		!in_array($_POST['variable']['install_type'], array('new', 'upgrade', 'rescue')) || 
		!isset($_POST['variable']['Database'])
	)
)
{
	$_GET['step'] = 1;
	$_POST = array();
}

// lets fill some default values which will be used as default values
if ( isset($tssettings['SLG_version']) )
{
	$old_version = $tssettings['SLG_version'];
	if ( version_compare($old_version, $new_version, '>=') && isset($_POST['variable']['install_type']) && $_POST['variable']['install_type'] === 'upgrade' )
	{
		early_error( '{TEXT_SAMEVERSIONUPDATE}' );
	}
	elseif ( defined("NO_DATABASE") )
	{
		$_POST['variable']['Database'] = 0;
	}
	else
	{
		$_POST['variable']['Database'] = 1;
	}
}

$tssettings['SLG_version'] = $new_version;
$tssettings['Page_title'] = 'SLG Comms ' . $tssettings['SLG_version'] . ' - {TEXT_INSTALLATION}';

//If a language has been selected lets switch to that language instead of the default
if ( isset($_POST['variable']['Language']) )
{
	$tssettings['Language'] = $_POST['variable']['Language'];
}
//If a template has been selected lets switch to that template instead of the default
if ( isset($_POST['variable']['Template']) )
{
	$tssettings['Template'] = $_POST['variable']['Template'];
}

require_once( $tssettings['Root_path'] . 'includes/header.inc.php' );

require( $tssettings['Root_path'] . 'includes/functions.secure.inc.php' );

// initialise the template class
$install = new template;
$template = 'install';

if (
	$_GET['step'] == 1 || 
	(
		(
			$_GET['step'] == 2 || 
			$_GET['step'] == 3 || 
			$_GET['step'] == 4
		) &&
		isset($_POST['updsetting'])
	)
)
{
	// with different $_GET['step'] values we get different variables in $configlist
	if ( $_GET['step'] == 1 )
	{
		$configlist = array(
			array(
				'variable' => 'install_type',
				'value'    => 'new'
			)
		);
		if ( !isset($old_version) )
		{
			$configlist = array_merge( $configlist, array(
				array(
					'variable' => 'Database',
					'value'    => true
				),
				array(
					'variable' => 'Language',
					'value'    => 'English'
				),
				array(
					'variable' => 'Template',
					'value'    => 'Default'
				)
			) );
		}
	}
	elseif ( $_GET['step'] == 2 && $_POST['variable']['Database'] )
	{
		if ( $_POST['variable']['install_type'] === 'upgrade' )
		{
			$_GET['step'] = 5;
		}
		else
		{
			$configlist = array(
				array(
					'variable' => 'db_type',
					'value'    => 'mysql41'
				),
				array(
					'variable' => 'db_host',
					'value'    => NULL
				),
				array(
					'variable' => 'db_name',
					'value'    => NULL
				),
				array(
					'variable' => 'db_user',
					'value'    => NULL
				),
				array(
					'variable' => 'db_passwd',
					'value'    => NULL
				),
				array(
					'variable' => 'table_prefix',
					'value'    => 'slg_'
				)
			);
		}
	}
	elseif ( $_GET['step'] == 3 || ( $_GET['step'] == 2 && !$_POST['variable']['Database'] ) )
	{
		if ( $_GET['step'] == 2 )
		{
			if ( $_POST['variable']['install_type'] === 'upgrade' )
			{
				$_GET['step'] = 6;
			}
			else
			{
				$_GET['step'] = 3;
			}
		}
		if ( $_GET['step'] < 5 )
		{
			// lets fill the requirements list
			$required_version = '4.2.0';
			$requirements = '{TEXT_PHPVERSION} >= ' . $required_version . ': <span class="' . ( ( version_compare(phpversion(), $required_version, '>=') ) ? 'greentext">{TEXT_YES}' : 'redtext">{TEXT_NO}, {TEXT_UPDATEPHP}' ) . '</span>.<br />
	{TEXT_PCREEXT}: <span class="' . ( ( extension_loaded('pcre') ) ? 'greentext">{TEXT_YES}' : 'redtext">{TEXT_NO}' ) . '</span>.<br />';
			if ( $_POST['variable']['Database'] )
			{
				if ( defined('NO_DATABASE') )
				{
					$_POST['variable']['db_type'] = ( ( $_POST['variable']['db_type'] === 'mysql' || $_POST['variable']['db_type'] === 'mysql41' ) ? $_POST['variable']['db_type'] : ( ( extension_loaded('mysqli') ) ? 'mysql41' : 'mysql' ) );

					// lets get our db class
					require( $tssettings['Root_path'] . 'includes/db/' . $_POST['variable']['db_type'] . '.inc.php' );

					/* Connect to mysql and database and test given information */
					$db = new database;
					$db->connect( 'pzinstallserverconnect', $_POST['variable']['db_host'], $_POST['variable']['db_user'], $_POST['variable']['db_passwd'], $_POST['variable']['db_name'] );
				}
				elseif (
					$_POST['variable']['db_host'] !== $tssettings['db_host'] || 
					$_POST['variable']['db_user'] !== $tssettings['db_user'] || 
					$_POST['variable']['db_passwd'] !== $tssettings['db_passwd'] || 
					$_POST['variable']['db_name'] !== $tssettings['db_name']
				)
				{
					early_error( '{TEXT_DIFFERENT_DB_INFO}' );
				}
				$requirements .= '{TEXT_MYSQLDATABASE}: ';
				$requirements .= '<span class="greentext">{TEXT_YES}</span>.<br />
	{TEXT_WORKINGFORUM}: <span class="orangetext">{TEXT_SELECTFORUM}</span>.';
			}

			// insert the data into the template
			$install->insert_content( '{SHOW_REQUIREMENTS}', $requirements );
			unset( $requirements, $required_version );

			$rootpath = pathinfo( str_replace( '\\', '/', __FILE__ ) );
			$base_url = pathinfo( $_SERVER['PHP_SELF'] );
			$configlist = array(
				array(
					'variable' => 'Base_url',
					'value'    => $_SERVER["HTTP_HOST"] . $base_url['dirname'] . '/'
				),
			);
		
			if ( $_POST['variable']['Database'] )
			{
				$configlist = array_merge( $configlist, array(
					array(
						'variable' => 'Cache_hits',
						'value'    => true
					)
				) );
			}

			$configlist = array_merge( $configlist, array(
				array(
					'variable' => 'Custom_servers',
					'value'    => false
				),
				array(
					'variable' => 'Default_queryport',
					'value'    => 51234
				),
				array(
					'variable' => 'Display_ping',
					'value'    => true
				),
				array(
					'variable' => 'Display_server_information',
					'value'    => true
				)
			) );

			if ( $_POST['variable']['Database'] )
			{
				$configlist = array_merge( $configlist, array(
					array(
						'variable' => 'Forum_relative_path',
						'value'    => NULL
					),
					array(
						'variable' => 'Forum_type',
						'value'    => NULL
					)
				) );
			}

			$configlist = array_merge( $configlist, array(
				array(
					'variable' => 'GZIP_Compression',
					'value'    => true
				),
				array(
					'variable' => 'Page_generation_time',
					'value'    => true
				),
				array(
					'variable' => 'Page_refresh_timer',
					'value'    => 0
				),
				array(
					'variable' => 'Page_title',
					'value'    => 'SLG Comms'
				)
			) );

			if ( $_POST['variable']['Database'] )
			{
				$configlist = array_merge( $configlist, array(
					array(
						'variable' => 'Retrieved_data_status',
						'value'    => true
					)
				) );
			}
		
			$configlist = array_merge( $configlist, array(
				array(
					'variable' => 'Root_path',
					'value'    => $rootpath['dirname'] . '/'
				),
				array(
					'variable' => 'TeamSpeak_support',
					'value'    => true
				),
				array(
					'variable' => 'Ventrilo_status_program',
					'value'    => NULL
				),
				array(
					'variable' => 'Ventrilo_support',
					'value'    => true
				)
			) );
			unset( $rootpath, $base_url );
		}
	}
	elseif( $_GET['step'] == 4 )
	{
		if ( defined('NO_DATABASE') )
		{
			// lets get our db class
			$_POST['variable']['db_type'] = ( ( $_POST['variable']['db_type'] === 'mysql' || $_POST['variable']['db_type'] === 'mysql41' ) ? $_POST['variable']['db_type'] : ( ( extension_loaded('mysqli') ) ? 'mysql41' : 'mysql' ) );

			require( $tssettings['Root_path'] . 'includes/db/' . $_POST['variable']['db_type'] . '.inc.php' );
		}

		// check whether the selected forum is correct
		if ( forum_existence_check($_POST['variable']['Forum_type'], $_POST['variable']['Forum_relative_path']) )
		{
			// retrieve needed forum information
			$forumsettings = retrieve_forumsettings( array(
				'Forum_type'          => $_POST['variable']['Forum_type'],
				'Forum_relative_path' => $_POST['variable']['Forum_relative_path']
			) );
		}
		else
		{
			early_error( '{TEXT_FORUM_NOT_FOUND_ERROR}' );
		}

		$configlist = array(
			array(
				'variable' => 'Forum_group',
				'value'    => NULL
			)
		);
	}

	if ( $_GET['step'] < 5 )
	{
		// whether or not display the requirements table
		$install->insert_display( '{REQUIREMENTS}', $_GET['step'] == 3 );

		$install->insert_text( '{INSTALLATIONSTEP}', $_GET['step'] );

		// decide on the next step to take
		if ( $_GET['step'] == 1 )
		{
			$nextstep = '2';
		}
		elseif ( $_GET['step'] == 2 )
		{
			if ( $_POST['variable']['install_type'] === 'upgrade' )
			{
				$nextstep = '5';
			}
			else
			{
				$nextstep = '3';
			}
		}
		elseif ( $_GET['step'] == 3 && $_POST['variable']['Database'] == 1 )
		{
			$nextstep = '4';
		}
		elseif ( $_GET['step'] == 3 && $_POST['variable']['Database'] == 0 )
		{
			$nextstep = '6';
		}
		elseif ( $_GET['step'] == 4 )
		{
			$nextstep = '5';
		}
		else
		{
			early_error( '{TEXT_SETTINGFORM_ERROR}' );
		}
		$install->insert_text( '{NEXTSTEP}', $nextstep );
		unset( $nextstep );

		// parse the configlist array
		$configrows = create_settingslist( $configlist );
		unset( $configlist );

		// put all the variables from previous forms into this form
		if ( !empty($_POST['variable']) )
		{
			reset( $_POST['variable'] );
			foreach ( $_POST['variable'] as $variable => $value )
			{
				$configrows .= '<input name="variable[' . $variable . ']" type="hidden" id="oldset_value" value="' . htmlentities( $value ) . '">';
			}
			unset( $variable, $value );
		}
		$install->insert_content( '{SETTINGS}', $configrows );
		unset( $configrows );
	}
}

if ( $_GET['step'] == 5 && isset($_POST['updsetting']) )
{
	// lets insert everything into the database
	if ( isset($_POST['variable']['db_type']) )
	{
		$_POST['variable']['db_type'] = ( ( $_POST['variable']['db_type'] === 'mysql' || $_POST['variable']['db_type'] === 'mysql41' ) ? $_POST['variable']['db_type'] : ( ( extension_loaded('mysqli') ) ? 'mysql41' : 'mysql' ) );
		$_POST['variable']['table_prefix'] = removechars( $_POST['variable']['table_prefix'], ' ' );

		if ( defined('NO_DATABASE') )
		{
			require( $tssettings['Root_path'] . 'includes/db/' . $_POST['variable']['db_type'] . '.inc.php' );
	
			$table['cache'] = $_POST['variable']['table_prefix'] . 'cache';
			$table['resources'] = $_POST['variable']['table_prefix'] . 'resources';
			$table['sessions'] = $_POST['variable']['table_prefix'] . 'sessions';
			$table['settings'] = $_POST['variable']['table_prefix'] . 'settings';
	
		/* Connect to mysql and database */
		$db = new database;
		$db->connect( 'pzinstallserverconnect', $_POST['variable']['db_host'], $_POST['variable']['db_user'], $_POST['variable']['db_passwd'], $_POST['variable']['db_name'] );
		}
		$install->displaymessage( '{TEXT_SELECTDB_SUCCESS}' );
	}

//---------------------------
	if ( $_POST['variable']['install_type'] === 'new' )
	{
		//resources table
		$sql = '
CREATE TABLE `%1$s` (
  `res_id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `res_name` VARCHAR(50) NOT NULL DEFAULT "",
  `res_data` VARCHAR(100) DEFAULT NULL,
  `res_type` ENUM("TeamSpeak","Ventrilo") NOT NULL DEFAULT "TeamSpeak",
  PRIMARY KEY (`res_id`),
  UNIQUE KEY `res_name` (`res_name`,`res_data`,`res_type`),
  KEY `res_type` (`res_type`)
)';

		$db->execquery( 'createrestable', $sql, $table['resources'] );
		$install->displaymessage( '{TEXT_TABLE_CREATE_SUCCESS;' . $table['resources'] . ';}' );

		$sql = '
INSERT INTO `%1$s` VALUES
(NULL, "Example TeamSpeak", "12.23.34.45:6464:51234", "TeamSpeak"),
(NULL, "Example Ventrilo", "ventrilo.game-host.org:8767:45t8hg4", "Ventrilo")';
		$db->execquery( 'insertresdata', $sql, $table['resources'] );
		$install->displaymessage( '{TEXT_INSERT_DATA_SUCCESS;' . $table['resources'] . ';}' );

		//sessions table
		$sql = '
CREATE TABLE `%1$s` (
  `session_id` CHAR(32) NOT NULL DEFAULT "",
  `session_user_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0",
  `session_ip` CHAR(32) NOT NULL DEFAULT "0",
  PRIMARY KEY (`session_id`),
  KEY `session_user_id` (`session_user_id`)
)'; 
		$db->execquery( 'createsestable', $sql, $table['sessions'] );
		$install->displaymessage( '{TEXT_TABLE_CREATE_SUCCESS;' . $table['sessions'] . ';}' );

		//server caching table
		$sql = '
CREATE TABLE `%1$s` (
  `res_id` SMALLINT(5) UNSIGNED NOT NULL DEFAULT "0",
  `data` TEXT,
  `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT "0",
  `update_attempt` INT(10) UNSIGNED NOT NULL DEFAULT "0",
  `refreshcache` SMALLINT(5) UNSIGNED NOT NULL DEFAULT "0",
  `con_attempts` TINYINT(3) UNSIGNED NOT NULL DEFAULT "0",
  `last_error` VARCHAR(255) DEFAULT NULL,
  `ventsort` ENUM("alpha","manual") DEFAULT NULL,
  `cachehits` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0",
  PRIMARY KEY (`res_id`)
)'; 
		$db->execquery( 'createsertable', $sql, $table['cache'] );
		$install->displaymessage( '{TEXT_TABLE_CREATE_SUCCESS;' . $table['cache'] . ';}' );
	}

	//settings table
	if ( $_POST['variable']['install_type'] === 'rescue' )
	{
		$sql = '
DROP TABLE IF EXISTS `%1$s`';
		$db->execquery( 'dropsettable', $sql, $table['settings'] );
		$install->displaymessage( '{TEXT_TABLE_DROP_SUCCESS;' . $table['settings'] . ';}' );
	}

	if ( $_POST['variable']['install_type'] === 'rescue' || $_POST['variable']['install_type'] === 'new' )
	{
		$sql = '
CREATE TABLE `%1$s` (
  `variable` ENUM( "Base_url", "Cache_hits", "Custom_servers", "Default_queryport", "Default_server", "Display_ping", "Display_server_information", "Forum_group", "Forum_relative_path", "Forum_type", "GZIP_Compression", "Language", "Page_generation_time", "Page_refresh_timer", "Page_title", "Retrieved_data_status", "Root_path", "SLG_version", "TeamSpeak_support", "Template", "Ventrilo_status_program", "Ventrilo_support" ) NOT NULL DEFAULT "Base_url",
  `value` VARCHAR(255) NOT NULL DEFAULT "",
  PRIMARY KEY (`variable`)
)'; 
		$db->execquery( 'createsettable', $sql, $table['settings'] );
		$install->displaymessage( '{TEXT_TABLE_CREATE_SUCCESS;' . $table['settings'] . ';}' );

		$sql = '
INSERT INTO `%1$s` VALUES
("Base_url", "%2$s"),
("Cache_hits", "%3$u"),
("Custom_servers", "%4$u"),
("Default_queryport", "%5$u"),
("Default_server", "0"),
("Display_ping", "%6$u"),
("Display_server_information", "%7$u"),
("Forum_group", "%8$u"),
("Forum_relative_path", "%9$s"),
("Forum_type", "%10$s"),
("GZIP_Compression", "%11$u"),
("Language", "%12$s"),
("Page_generation_time", "%13$u"),
("Page_refresh_timer", "%14$u"),
("Page_title", "%15$s"),
("Retrieved_data_status", "%16$u"),
("Root_path", "%17$s"),
("SLG_version", "%18$s"),
("TeamSpeak_support", "%19$u"),
("Template", "%20$s"),
("Ventrilo_status_program", "%21$s"),
("Ventrilo_support", "%22$u")';
		$db->execquery( 'insertsetdata', $sql, array(
			$table['settings'],
			$db->escape_string( ltrim( prep_dir_string( removechars( $_POST['variable']['Base_url'], 'http://' ) ), '/' ) ),
			(bool) $_POST['variable']['Cache_hits'],
			(bool) $_POST['variable']['Custom_servers'],
			$_POST['variable']['Default_queryport'],
			(bool) $_POST['variable']['Display_ping'],
			(bool) $_POST['variable']['Display_server_information'],
			$_POST['variable']['Forum_group'],
			$db->escape_string( ltrim( prep_dir_string( $_POST['variable']['Forum_relative_path'] ), '/' ) ),
			$db->escape_string( $_POST['variable']['Forum_type'] ),
			(bool) $_POST['variable']['GZIP_Compression'],
			$db->escape_string( $_POST['variable']['Language'] ),
			(bool) $_POST['variable']['Page_generation_time'],
			$_POST['variable']['Page_refresh_timer'],
			$db->escape_string( $_POST['variable']['Page_title'] ),
			(bool) $_POST['variable']['Retrieved_data_status'],
			$db->escape_string( prep_dir_string( $_POST['variable']['Root_path'] ) ),
			$db->escape_string( $new_version ),
			(bool) $_POST['variable']['TeamSpeak_support'],
			$db->escape_string( $_POST['variable']['Template'] ),
			$db->escape_string( trim( str_replace( '\\', '/', $_POST['variable']['Ventrilo_status_program'] ), '/' ) ),
			(bool) $_POST['variable']['Ventrilo_support']
		) );
		$install->displaymessage( '{TEXT_INSERT_DATA_SUCCESS;' . $table['settings'] . ';}' );
	}
	
	if ( $_POST['variable']['install_type'] === 'upgrade' )
	{
		if ( !isset($old_version) )
		{
			early_error( '{TEXT_OLDVERSION_UNAVAILABLE}' );
		}

		$install->displaymessage( '<span class="errorbig">{TEXT_UPGRADING_FROM;' . $old_version . ';}</span>' );
		$install->displaymessage( '<span class="errorbig">{TEXT_UPGRADING_TO;' . $new_version . ';}</span>' );

		/* SLG Comms versions before v2.2.0 */
		if ( version_compare($old_version, 'v2.2.0', '<') )
		{
			$sql = '
ALTER TABLE `%1$s`
CHANGE `variable` `variable` ENUM( "Cache hits", "Custom servers", "Default queryport", "Default server", "Forum group", "Forum relative path", "Forum type", "GZIP Compression", "Language", "Page generation time", "Page refresh timer", "Page title", "Retrieved data status", "Show server information", "SLG version", "TeamSpeak support", "Template", "Ventrilo status program", "Ventrilo support" ) NOT NULL DEFAULT "Cache hits"';
			$db->execquery( 'addvarsettings', $sql, $table['settings'] );
			$install->displaymessage( '{TEXT_CHANGE_SUCCESS;' . $table['settings'] . ';}' );

			$sql = '
INSERT INTO `%1$s` VALUES
("Page generation time", "1"),
("TeamSpeak support", "1"),
("Ventrilo support", "1")';
			$db->execquery( 'addsettings', $sql, $table['settings'] );
			$install->displaymessage( '{TEXT_UPDATE_SUCCESS;' . $table['settings'] . ';}' );

			$install->displaymessage( '<span class="errorbig">{TEXT_VERSION_SUCCESS;v2.2.0;}</span>' );
		}

		/* SLG Comms versions before v3.0.0 */
		if ( version_compare($old_version, 'v3.0.0', '<') )
		{
			$sql = '
ALTER TABLE `%1$s`
CHANGE `res_id` `res_id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT';
			$db->execquery( 'chrestable', $sql, $table['resources'] );
			$install->displaymessage( '{TEXT_CHANGE_SUCCESS;' . $table['resources'] . ';}' );

			$sql = '
ALTER TABLE `%1$s`
CHANGE `session_user_id` `session_user_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT "0",
CHANGE `session_ip` `session_ip` CHAR(32) NOT NULL DEFAULT "0"';
			$db->execquery( 'chsestable', $sql, $table['sessions'] );
			$install->displaymessage( '{TEXT_CHANGE_SUCCESS;' . $table['sessions'] . ';}' );

			$sql = '
ALTER TABLE `%1$s`
CHANGE `cache_id` `res_id` SMALLINT(5) UNSIGNED NOT NULL DEFAULT "0",
CHANGE `timestamp` `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT "0",
ADD `update_attempt` INT(10) UNSIGNED NOT NULL DEFAULT "0" AFTER `timestamp`,
ADD `ventsort` ENUM("alpha","manual") DEFAULT NULL AFTER `refreshcache`,
ADD `con_attempts` TINYINT(3) UNSIGNED NOT NULL DEFAULT "0" AFTER `refreshcache`,
ADD `last_error` VARCHAR(255) NULL AFTER `con_attempts`,
DROP INDEX `refreshcache`';
			$db->execquery( 'chcactable', $sql, $table['cache'] );
			$install->displaymessage( '{TEXT_CHANGE_SUCCESS;' . $table['cache'] . ';}' );
			
			$sql = '
INSERT INTO `%1$s`
( `res_id` )
(
  SELECT RES.`res_id`
  FROM
    `%2$s` AS RES
    LEFT OUTER JOIN `%1$s` AS CAC ON (RES.res_id = CAC.res_id)
  WHERE
    CAC.`res_id` IS NULL AND
	RES.`res_type` IN ("TeamSpeak", "Ventrilo")
)';
			$db->execquery( 'insertcacdata', $sql, array(
				$table['cache'],
				$table['resources']
			) );
			$install->displaymessage( '{TEXT_INSERT_DATA_SUCCESS;' . $table['cache'] . ';}' );

			$sql = '
SELECT
  `variable`,
  `value`
FROM
  `%1$s`';
			$getconfig = $db->execquery( 'getconfig', $sql, $table['settings'] );

			while ( $row = $db->getrow($getconfig) )
			{
				$tmpsettings[ $row['variable'] ] = $row['value'];
			}
			$db->freeresult( 'getconfig', $getconfig );
			unset( $sql, $row, $getconfig );

			$sql = '
TRUNCATE TABLE `%1$s`';
			$db->execquery( 'chsettable', $sql, $table['settings'] );

			$sql = '
ALTER TABLE `%1$s`
CHANGE `variable` `variable` ENUM( "Base_url", "Cache_hits", "Custom_servers", "Default_queryport", "Default_server", "Display_ping", "Display_server_information", "Forum_group", "Forum_relative_path", "Forum_type", "GZIP_Compression", "Language", "Page_generation_time", "Page_refresh_timer", "Page_title", "Retrieved_data_status", "Root_path", "SLG_version", "TeamSpeak_support", "Template", "Ventrilo_status_program", "Ventrilo_support" ) NOT NULL DEFAULT "Base_url",
CHANGE `value` `value` VARCHAR(255) NOT NULL';
			$db->execquery( 'chsettable', $sql, $table['settings'] );

			$sql = '
INSERT INTO `%1$s` VALUES
("Base_url", "%2$s"),
("Cache_hits", "%3$u"),
("Custom_servers", "%4$u"),
("Default_queryport", "%5$u"),
("Default_server", "0"),
("Display_ping", "%6$u"),
("Display_server_information", "%7$u"),
("Forum_group", "%8$u"),
("Forum_relative_path", "%9$s"),
("Forum_type", "%10$s"),
("GZIP_Compression", "%11$u"),
("Language", "%12$s"),
("Page_generation_time", "%13$u"),
("Page_refresh_timer", "%14$u"),
("Page_title", "%15$s"),
("Retrieved_data_status", "%16$u"),
("Root_path", "%17$s"),
("SLG_version", "%18$s"),
("TeamSpeak_support", "%19$u"),
("Template", "%20$s"),
("Ventrilo_status_program", "%21$s"),
("Ventrilo_support", "%22$u")';

			$rootpath = pathinfo( str_replace( '\\', '/', __FILE__ ) );
			$base_url = pathinfo( $_SERVER['PHP_SELF'] );
			$db->execquery( 'insertsetdata', $sql, array(
				$table['settings'],
				$db->escape_string( $_SERVER["HTTP_HOST"] . $base_url['dirname'] . '/' ),
				(bool) $tmpsettings['Cache hits'],
				(bool) $tmpsettings['Custom servers'],
				$tmpsettings['Default queryport'],
				true,
				(bool) $tmpsettings['Show server information'],
				$tmpsettings['Forum group'],
				$db->escape_string( ltrim( prep_dir_string( $tmpsettings['Forum relative path'] ), '/' ) ),
				$db->escape_string( $tmpsettings['Forum type'] ),
				(bool) $tmpsettings['GZIP Compression'],
				$db->escape_string( $tmpsettings['Language'] ),
				(bool) $tmpsettings['Page generation time'],
				$tmpsettings['Page refresh timer'],
				$db->escape_string( $tmpsettings['Page title'] ),
				(bool) $tmpsettings['Retrieved data status'],
				$db->escape_string( $rootpath['dirname'] . '/' ),
				$db->escape_string( $tmpsettings['SLG version'] ),
				(bool) $tmpsettings['TeamSpeak support'],
				$db->escape_string( $tmpsettings['Template'] ),
				$db->escape_string( trim( str_replace( '\\', '/', $tmpsettings['Ventrilo status program'] ), '/' ) ),
				(bool) $tmpsettings['Ventrilo support']
			) );
			unset( $tmpsettings, $rootpath, $base_url );

			$install->displaymessage( '{TEXT_CHANGE_SUCCESS;' . $table['settings'] . ';}' );

			$install->displaymessage( '<span class="errorbig">{TEXT_VERSION_SUCCESS;v3.0.0;}</span>' );
		}

		$sql = '
UPDATE `%1$s`
SET
  `value` = "%2$s"
WHERE
  `variable` = "SLG_version"
LIMIT 1';
		$db->execquery( 'modifysettings', $sql, array(
			$table['settings'],
			$db->escape_string( $new_version )
		) );
		$install->displaymessage( '<span class="errorbig">{TEXT_UPGRADE_SUCCESS}</span>' );
	}
	unset( $sql );

	if ( $_POST['variable']['install_type'] === 'new' )
	{
		$install->displaymessage( '<span class="errorbig">{TEXT_INSTALL_SUCCESS}</span>' );
		$_GET['step'] = 6;
	}
	elseif ( $_POST['variable']['install_type'] === 'rescue' )
	{
		$install->displaymessage( '<span class="errorbig">{TEXT_INSTALL_RESTORE_SUCCESS}</span>' );
	}
//-----------------------------
}

if ( $_GET['step'] == 6 && isset($_POST['updsetting']) )
{
	// display the finish installation information
	reset( $_POST['variable'] );
	$hidden_vars = NULL;
	foreach ( $_POST['variable'] as $variable => $value )
	{
		$hidden_vars .= '<input name="variable[' . htmlentities( $variable ) . ']" type="hidden" id="oldset_value" value="' . htmlentities( $value ) . '">';
	}
	$install->insert_content( '{TEXT_HIDDEN_VARIABLES}', $hidden_vars );
	$install->insert_content( '{TEXT_FINISH_LARGE}', '{TEXT_FINISH_INSTALL_LARGE}' );
	$install->insert_content( '{TEXT_FINISH}', '{TEXT_FINISH_INSTALL}' );
	$install->insert_content( '{NEXTSTEP}', '7' );
	unset( $hidden_vars, $variable, $value );
}

elseif ( $_GET['step'] == 7 and isset($_POST['updsetting']) )
{
	// lets save dbsettings.inc.php
	$filename = 'dbsettings.inc.php';
	
	if ( $_POST['variable']['Database'] )
	{
		$content = '<?php
//security through the use of define != defined
if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

/* Config - Change this if necessary */
$tssettings[\'db_type\']      = \'%1$s\';		/*The type of database to be used. This can be either "mysql" (MySQL version 3.23 or higher) or "mysql41" (MySQL version 4.1 or higher)*/
$tssettings[\'db_host\']      = \'%2$s\';		/*host of the database server*/
$tssettings[\'db_name\']      = \'%3$s\';		/*database to be used*/
$tssettings[\'db_user\']      = \'%4$s\';		/*username for the database*/
$tssettings[\'db_passwd\']    = \'%5$s\';		/*password for the database*/
$tssettings[\'table_prefix\'] = \'%6$s\';		/*The table prefix thats being used*/
/* Don\'t change anything below this line unless you know what youre doing. */
?>
';
		$content = sprintf( $content,
			addslashes( ( ( isset($_POST['variable']['db_type']) && ( $_POST['variable']['db_type'] === 'mysql' || $_POST['variable']['db_type'] === 'mysql41' ) ) ? $_POST['variable']['db_type'] : ( ( extension_loaded('mysqli') ) ? 'mysql41' : 'mysql' ) ) ),
			addslashes( ( ( isset($_POST['variable']['db_host']) ) ? $_POST['variable']['db_host'] : NULL ) ),
			addslashes( ( ( isset($_POST['variable']['db_name']) ) ? $_POST['variable']['db_name'] : NULL ) ),
			addslashes( ( ( isset($_POST['variable']['db_user']) ) ? $_POST['variable']['db_user'] : NULL ) ),
			addslashes( ( ( isset($_POST['variable']['db_passwd']) ) ? $_POST['variable']['db_passwd'] : NULL ) ),
			addslashes( ( ( isset($_POST['variable']['table_prefix']) ) ? removechars( $_POST['variable']['table_prefix'], ' ' ) : NULL ) )
		);
	}
	elseif ( $_POST['variable']['install_type'] === 'upgrade' )
	{
		if ( !isset($old_version) )
		{
			early_error( '{TEXT_OLDVERSION_UNAVAILABLE}' );
		}

		$content = rtrim( file_get_contents( $tssettings['Root_path'] . $filename ) );
		$content = str_replace( $old_version, $new_version, $content );

		/* SLG Comms versions before v2.2.0 */
		if ( version_compare($old_version, 'v2.2.0', '<') )
		{
			$content .= '<?php
/*new options change these if necessary*/
$tssettings[\'Page generation time\'] = true;		/* Show or hide the Page generation times at the bottom of the pages. Default is always enabled */
$tssettings[\'TeamSpeak support\']    = true;		/* Do you want support for TeamSpeak (true or false)*/
$tssettings[\'Ventrilo support\']     = true;		/* Do you want support for Ventrilo (true or false)*/
?>';

			$install->displaymessage( '{TEXT_ADDEDNEWSETTINGS}' );
		}

		/* SLG Comms versions before v3.0.0 */
		if ( version_compare($old_version, 'v3.0.0', '<') )
		{
			$replace_array = array(
				'Custom servers'                => 'Custom_servers',
				'Default queryport'             => 'Default_queryport',
				'Default server'                => 'Default_server',
				'GZIP Compression'              => 'GZIP_Compression',
				'Page generation time'          => 'Page_generation_time',
				'Page refresh timer'            => 'Page_refresh_timer',
				'Page title'                    => 'Page_title',
				'Retrieved data status'         => 'Retrieved_data_status',
				'Show server information'       => 'Display_server_information',
				'TeamSpeak support'             => 'TeamSpeak_support',
				'Ventrilo status program'       => 'Ventrilo_status_program',
				'Ventrilo support'              => 'Ventrilo_support',
				'SLG version'                   => 'SLG_version',
				'\'res_type\' => \'Ventrilo\''  => '\'res_type\' => \'Ventrilo\',
			\'ventsort\' => \'alpha\'							/* This can be "alpha" or "manual". Since ventrilo has the option to sort his channels Alphabetically or Manually and doesn\'t show what was used in the retrieved data, SLG Comms can\'t decide on its own what should be used to sort the channels. You can define here what should be used. This is only available for Ventrilo channels. As you can see above, its not available for the TeamSpeak server */
',
			);
			$content = str_replace( array_keys( $replace_array ), $replace_array, $content );

			$rootpath = pathinfo( str_replace( '\\', '/', __FILE__ ) );
			$base_url = pathinfo( $_SERVER['PHP_SELF'] );
			$content .= sprintf( '<?php
/*new options change these if necessary*/
$tssettings[\'Base_url\']  = \'%1$s\';		/* This is the complete url where SLG Comms is located when visited with a browser. If this setting is wrong please correct it. */
$tssettings[\'Display_ping\'] = true;		/*Whether or not to show the ping of clients behind the name in the channel information pane*/
$tssettings[\'Root_path\'] = \'%2$s\';		/* This is the complete path where SLG Comms is located on the server. If this setting is wrong please correct it. */
?>
', $_SERVER["HTTP_HOST"] . $base_url['dirname'] . '/', $rootpath['dirname'] . '/' );
			unset( $replace_array, $rootpath, $base_url );

			$install->displaymessage( '{TEXT_MODDEDNEWSETTINGS}' );
		}
	}
	else
	{
		$content = '<?php
if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

/* Config - Change this if necessary */
$tssettings[\'Base_url\']                     = \'%1$s\';		/* This is the complete url where SLG Comms is located when visited with a browser. If this setting is wrong please correct it. */
$tssettings[\'Custom_servers\']               = %2$u;		/*This line enables (true) or disables (false) the custom server feature.*/
$tssettings[\'Default_queryport\']            = %3$u;		/*For teamspeak servers the default TCP queryport to be used when not defined by res_data (Look below in the servers array for info on res_data) is 51234. Change this when you know what it is and why you need to change it.*/
$tssettings[\'Default_server\']               = 0;		/*The unique id (This is res_id which you\'ll see again in the arrays a couple lines down) of the server you want to be the default server to be loaded. Zero disables the default loading of a server*/
$tssettings[\'Display_ping\']                 = %4$u;		/*Whether or not to show the ping of clients behind the name in the channel information pane*/
$tssettings[\'Display_server_information\']   = %5$u;		/*This line enables (true) or disables (false) the server information pane. It\'s is not required to display this information because its also integrated in the Channel information pane if you click on the server name.*/
$tssettings[\'GZIP_Compression\']             = %6$u;		/*Whether or not you want to use the internal GZIP compression engine.*/
$tssettings[\'Language\']                     = \'%7$s\';		/*This is the language you want to use with SLG Comms */
$tssettings[\'Page_generation_time\']         = %8$u;		/* Show or hide the Page generation times at the bottom of the pages. Default is always enabled */
$tssettings[\'Page_refresh_timer\']           = %9$u;		/* These are the amount of seconds before the page will be automatically refreshed */
$tssettings[\'Page_title\']                   = \'%10$s\';		/*The title you want to be displayed in the head title tag and in the top of every page.*/
$tssettings[\'Retrieved_data_status\']        = false;		/*This line enables (true) or disables (false) the red text saying what kind of data is getting displayed (Data can be cached or live. Without a database the data is always live so no need to display it.).*/
$tssettings[\'Root_path\']                    = \'%11$s\';		/* This is the complete path where SLG Comms is located on the server. If this setting is wrong please correct it. */
$tssettings[\'TeamSpeak_support\']            = %12$u;		/* Do you want support for TeamSpeak (true or false)*/
$tssettings[\'Template\']                     = \'%13$s\';		/*This is the template you wish to use with SLG Comms*/
$tssettings[\'Ventrilo_status_program\']      = \'%14$s\';		/*Ventrilo status program to be used to get ventrilo server info. The unix version doesn\'t have an extension and the windows has an .exe extension. Whether there are one or two backslashes between directory names doesn\'t matter, SLG Comms though will default to 2 backslashes. It depends on the operating system where SLG Comms will be run whether you need to use slashes or backslashes.*/
$tssettings[\'Ventrilo_support\']             = %15$u;		/* Do you want support for Ventrilo (true or false)*/

/*Here you can insert all the servers you want. These servers are all sorted alphabetically upon displayal.*/
$servers = array(

	array(
			\'res_id\'   => 1,                                  /*Here you enter a unique id for the server.*/
			\'res_name\' => \'Example TeamSpeak\',              /*The name you want to be displayed in the drop down list.*/
			\'res_data\' => \'12.23.34.45:6464:51234\',         /*Here you fill in the ip and port of the servers. For teamspeak servers optionally also the queryport of the server. The default queryport for a teamspeak server is 51234. For Ventrilo the queryport should be replaced with the password to join the server. format is [ip]:[port]:[optional requirements]*/
			\'res_type\' => \'TeamSpeak\'                       /*This is used for possibly extending this script to encompass support for other voice communication servers other then teamspeak. At the moment the only types are "TeamSpeak" and "Ventrilo".*/
	),                                                          /*This comma is needed to imply that there is another server following after this one. The last server doesn\'t need a comma but it wouldn\'t matter one way or the other if there was one.*/

	array(
			\'res_id\'   => 2,
			\'res_name\' => \'Example Ventrilo\',
			\'res_data\' => \'ventrilo.game-host.org:8767:45t8hg4\',
			\'res_type\' => \'Ventrilo\',
			\'ventsort\' => \'alpha\'                           /* This can be "alpha" or "manual". Since ventrilo has the option to sort his channels Alphabetically or Manually and doesn\'t show what was used in the retrieved data, SLG Comms can\'t decide on its own what should be used to sort the channels. You can define here what should be used. This is only available for Ventrilo channels. As you can see above, its not available for the TeamSpeak server */
	),

);
/* Don\'t change anything below this line unless you know what youre doing. */

define( "NO_DATABASE", 10 );			/*Disable the use of the database.*/
$tssettings[\'SLG_version\'] = \'%16$s\';			/*The current version of this script. Please don\'t change it unless you have a good reason for it.*/
?>
';
		$content = sprintf( $content,
			addslashes( ltrim( prep_dir_string( removechars( $_POST['variable']['Base_url'], 'http://' ) ), '/' ) ),
			(bool) $_POST['variable']['Custom_servers'],
			$_POST['variable']['Default_queryport'],
			(bool) $_POST['variable']['Display_ping'],
			(bool) $_POST['variable']['Display_server_information'],
			(bool) $_POST['variable']['GZIP_Compression'],
			addslashes( $_POST['variable']['Language'] ),
			(bool) $_POST['variable']['Page_generation_time'],
			$_POST['variable']['Page_refresh_timer'],
			addslashes( $_POST['variable']['Page_title'] ),
			addslashes( prep_dir_string( $_POST['variable']['Root_path'] ) ),
			(bool) $_POST['variable']['TeamSpeak_support'],
			addslashes( $_POST['variable']['Template'] ),
			addslashes( trim( str_replace( '\\', '/', $_POST['variable']['Ventrilo_status_program'] ), '/' ) ),
			(bool) $_POST['variable']['Ventrilo_support'],
			addslashes( $new_version )
		);
	}

	$file_save = false;
	if ( is_file($filename) )
	{
		// Let's make sure the file exists and is writable first.
		if ( is_writable($filename) )
		{
			if ( !$handle = @fopen($filename, 'w') )
			{
				$install->displaymessage( '{TEXT_OPENFILE_ERROR;' . $filename . ';}' );
			}
			// Write some content to our opened file.
			elseif ( @fwrite($handle, $content) === FALSE )
			{
				$install->displaymessage( '{TEXT_FILEWRITE_ERROR;' . $filename . ';}' );
			}
			else
			{
				$install->displaymessage( '{TEXT_FILEWRITE_SUCCESS;' . $filename . ';}' );
				fclose( $handle );
				$file_save = true;
			}
			unset( $handle );
		}
		else
		{
			$install->displaymessage( '{TEXT_CANTWRITE_FILE;' . $filename . ';}' );
		}
	}
	else
	{
		$install->displaymessage( '{TEXT_FILE_DOESNTEXIST;' . $filename . ';}' );
	}

	if ( !$file_save )
	{
		// auto save failed so lets download the file and let the user upload it himself
		reset( $_POST['variable'] );
		$hidden_vars = '
<input name="content" type="hidden" id="oldset_value" value="' . htmlentities( $content ) . '">
<input name="filename" type="hidden" id="oldset_value" value="' . htmlentities( $filename ) . '">';
		$install->insert_content( '{TEXT_HIDDEN_VARIABLES}', $hidden_vars );
		$install->insert_content( '{TEXT_FINISH_LARGE}', '{TEXT_DOWNLOAD_FILE_LARGE;' . $filename . ';}' );
		$install->insert_content( '{TEXT_FINISH}', '{TEXT_DOWNLOAD_FILE}' );
		$install->insert_content( '{NEXTSTEP}', '8' );
		unset( $hidden_vars );
	}
	if ( !$_POST['variable']['Database'] )
	{
		$install->displaymessage( '{TEXT_NO_DATABASE_SERVERLIST;' . $filename . ';}' );
	}
	unset( $filename, $content, $file_save );
}

// we need to decide on which template is needed
if ( $_GET['step'] >= 5 && isset($_POST['updsetting']) )
{
	// whether or not to display the finish installation information
	$install->insert_display( '{FINISH}', ( ( isset($file_save) && !$file_save ) || ( $_GET['step'] == 6 && isset($_POST['updsetting']) ) ) );
	$install->load_template( 'admin/tpl_install_finish' );
}
elseif (
	( $_GET['step'] >= 2 && $_GET['step'] < 5 && isset($_POST['updsetting']) ) || 
	( $_GET['step'] == 1 && !isset($_POST['updsetting']) )
)
{
	$install->load_template( 'admin/tpl_install_settings' );
}

// process template
$install->insert_text( '{BASE_URL}', NULL );
$install->insert_text( '{TEMPLATE}', ( isset( $tssettings['Template'] ) ? $tssettings['Template'] : 'Default' ) );
$install->load_language( 'admin/lng_install' );
$install->load_language( 'admin/lng_common' );
$install->process();
$install->output();
unset( $install, $template, $new_version, $old_version );

require_once( $tssettings['Root_path'] . 'includes/footer.inc.php' );
?>

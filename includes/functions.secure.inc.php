<?php
/***************************************************************************
 *                          functions.secure.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.secure.inc.php,v 1.48 2007/01/29 22:49:17 SC Kruiper Exp $
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

if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

// this function checks whether you have access by checking your groupid's to the allowed groupid
function checkaccess( $checkvalue )
{
	return( isset( $_SESSION['group_id'] ) && in_array( (int) $checkvalue, $_SESSION['group_id'], true ) );
}

// this function preprares a directory string
function prep_dir_string( $dir_string )
{
	return( rtrim( str_replace( '\\', '/', $dir_string ), '/' ) . '/' );
}

// MD5_hmac Encryption of the smf1.0 forum.
function md5_hmac( $data, $key )
{
	$key = str_pad( ( ( strlen( $key ) <= 64 ) ? $key : pack( 'H*', md5( $key ) ) ), 64, chr( 0x00 ) );
	return( md5( ( $key ^ str_repeat( chr( 0x5c ), 64 ) ) . pack( 'H*', md5( ( $key ^ str_repeat( chr( 0x36 ), 64 ) ) . $data ) ) ) );
}

// this function checks whether the forum settings seems acceptable from the outside
function forum_existence_check( $forumtype, $forumpath )
{
	$conf_file = $GLOBALS['tssettings']['Root_path'] . $forumpath;
	switch ( $forumtype )
	{
		case 'bboardlite102':
		case 'bboardfull234':
			$conf_file .= 'acp/lib/config.inc.php';
			break;

		case 'ipb131':
		case 'ipb204':
			$conf_file .= 'conf_global.php';
			break;

		case 'phpbb2015':
		case 'phpbb3':
		case 'phpnuke78_phpbb207':
			$conf_file .= 'config.php';
			break;

		case 'smf103':
		case 'smf110':
			$conf_file .= 'Settings.php';
			break;

		case 'vb307':
		case 'vb350':
			$conf_file .= 'includes/config.php';
			break;

		case 'xoops_cbb':
			$conf_file .= 'mainfile.php';
			break;

		default:
			early_error( '{TEXT_UNKNOWN_FORUMTYPE_ERROR}' );
	}

	if ( realpath($conf_file) === false )
	{
		return( false );
	}
	elseif ( compare_dir_string( $conf_file, $_SERVER['DOCUMENT_ROOT'] ) )
	{
		return( true );
	}
	else
	{
		early_error( '{TEXT_CONF_FILE_NOT_IN_DOCROOT}' );
	}
}

// this function retrieves the forum information and prepares query's
function retrieve_forumsettings( $settings, $action_login=false )
{
	if ( forum_existence_check($settings['Forum_type'], $settings['Forum_relative_path']) )
	{
		switch ( $settings['Forum_type'] )
		{
/* START - BBOARDLITE102 */
			case 'bboardlite102':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'acp/lib/config.inc.php' );

				if ( !isset($n, $sqldb, $sqlhost, $sqluser, $sqlpassword) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $sqlhost;
				$forumsettings['alt_db_name'] = $sqldb;
				$forumsettings['alt_db_user'] = $sqluser;
				$forumsettings['alt_db_passwd'] = $sqlpassword;

				if ( $action_login === false )
				{
					$table['groups'] = 'bb' . $n . '_groups';

					$forumsettings['groups_sql'] = '
SELECT
  `groupid` AS groupid,
  `title` AS groupname
FROM
  `%1$s`
WHERE
  `default_group` != 1
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = 'bb' . $n . '_users';

					$forumsettings['authchecksql'] = '
SELECT
  `userid` AS userid,
  `username` AS username,
  `username` AS realname,
  `groupid` AS groupid
FROM
  `%1$s`
WHERE
  `username` = "%2$s" AND
  `password` = "%3$s" AND
  `groupid` = %4$u
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] ),
						$settings['Forum_group']
					);
				}
				break;

/* START - BBOARDFULL234 */
			case 'bboardfull234':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'acp/lib/config.inc.php' );

				if ( !isset($n, $sqldb, $sqlhost, $sqluser, $sqlpassword) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $sqlhost;
				$forumsettings['alt_db_name'] = $sqldb;
				$forumsettings['alt_db_user'] = $sqluser;
				$forumsettings['alt_db_passwd'] = $sqlpassword;

				if ( $action_login === false )
				{
					$table['groups'] = 'bb' . $n . '_groups';

					$forumsettings['groups_sql'] = '
SELECT
  `groupid` AS groupid,
  `title` AS groupname
FROM
  `%1$s`
WHERE
  `grouptype` NOT IN (1,2,3)
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = 'bb' . $n . '_users';
					$table['groupsmembers'] = 'bb' . $n . '_groupcombinations';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`userid` AS userid,
  MEM.`username` AS username,
  MEM.`username` AS realname,
  GRM.`groupids` AS groupid
FROM
  `%1$s` AS MEM,
  `%2$s` AS GRM
WHERE
  MEM.`groupcombinationid` = GRM.`groupcombinationid` AND
  MEM.`username` = "%3$s" AND
  MEM.`password` = "%4$s" AND
  MEM.`sha1_password` = "%5$s" AND
  %6$u IN (GRM.`groupids`)
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$table['groupsmembers'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] ),
						sha1( $action_login['fpasswd'] ),
						$settings['Forum_group']
					);
				}
				break;

/* START - IPB131 */
			case 'ipb131':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'conf_global.php' );

				if ( !isset($INFO['sql_tbl_prefix'], $INFO['sql_database'], $INFO['sql_host'], $INFO['sql_port'], $INFO['sql_user'], $INFO['sql_pass']) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $INFO['sql_host'] . ( ( !empty($INFO['sql_port']) ) ? ':' . $INFO['sql_port'] : NULL );
				$forumsettings['alt_db_name'] = $INFO['sql_database'];
				$forumsettings['alt_db_user'] = $INFO['sql_user'];
				$forumsettings['alt_db_passwd'] = $INFO['sql_pass'];

				if ( $action_login === false )
				{
					$table['groups'] = $INFO['sql_tbl_prefix'] . 'groups';

					$forumsettings['groups_sql'] = '
SELECT
  `g_id` AS groupid,
  `g_title` AS groupname
FROM
  `%1$s`
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $INFO['sql_tbl_prefix'] . 'members';

					$forumsettings['authchecksql'] = '
SELECT
  `id` AS userid,
  `name` AS username,
  `name` AS realname,
  `mgroup` AS groupid
FROM
  `%1$s`
WHERE
  `name` = "%2$s" AND
  `password` = "%3$s" AND
  `mgroup` = %4$u
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] ),
						$settings['Forum_group']
					);
				}
				break;

/* START - IPB204 */
			case 'ipb204':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'conf_global.php' );

				if ( !isset($INFO['sql_tbl_prefix'], $INFO['sql_database'], $INFO['sql_host'], $INFO['sql_user'], $INFO['sql_pass']) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $INFO['sql_host'];
				$forumsettings['alt_db_name'] = $INFO['sql_database'];
				$forumsettings['alt_db_user'] = $INFO['sql_user'];
				$forumsettings['alt_db_passwd'] = $INFO['sql_pass'];

				if ( $action_login === false )
				{
					$table['groups'] = $INFO['sql_tbl_prefix'] . 'groups';

					$forumsettings['groups_sql'] = '
SELECT
  `g_id` AS groupid,
  `g_title` AS groupname
FROM
  `%1$s`
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $INFO['sql_tbl_prefix'] . 'members';
					$table['memconverge'] = $INFO['sql_tbl_prefix'] . 'members_converge';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`id` AS userid,
  MEM.`name` AS username,
  MEM.`name` AS realname,
  MEM.`mgroup` AS groupid,
  MEM.`mgroup_others` AS additionalgroups
FROM
  `%1$s` AS MEM,
  `%2$s` AS CON
WHERE
  MEM.`id` = CON.`converge_id` AND
  MEM.`name` = "%3$s" AND
  CON.`converge_pass_hash` = MD5(CONCAT(MD5(CON.`converge_pass_salt`), "%4$s")) AND
  ((TRIM(BOTH "," FROM MEM.`mgroup`) = %5$u) OR 
  (%5$u IN (TRIM(BOTH "," FROM MEM.`mgroup_others`))))
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$table['memconverge'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] ),
						$settings['Forum_group']
					);
				}
				break;

/* START - PHPBB2015 */
			case 'phpbb2015':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'config.php' );

				if ( !isset($table_prefix, $dbhost, $dbname, $dbuser, $dbpasswd) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $dbhost;
				$forumsettings['alt_db_name'] = $dbname;
				$forumsettings['alt_db_user'] = $dbuser;
				$forumsettings['alt_db_passwd'] = $dbpasswd;

				if ( $action_login === false )
				{
					$table['groups'] = $table_prefix . 'groups';

					$forumsettings['groups_sql'] = '
SELECT
  `group_id` AS groupid,
  `group_name` AS groupname
FROM
  `%1$s`
WHERE
  `group_single_user` = 0
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $table_prefix . 'users';
					$table['groupsmembers'] = $table_prefix . 'user_group';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`user_id` AS userid,
  MEM.`username` AS username,
  MEM.`username` AS realname,
  GRM.`group_id` AS groupid
FROM
  `%1$s` AS MEM,
  `%2$s` AS GRM
WHERE
  MEM.`user_id` = GRM.`user_id` AND
  MEM.`username` = "%3$s" AND
  MEM.`user_password` = "%4$s" AND
  MEM.`user_active` = 1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$table['groupsmembers'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] )
					);
				}
				break;

/* START - PHPBB3 */
			case 'phpbb3':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'config.php' );

				if ( !isset($table_prefix, $dbhost, $dbport, $dbname, $dbuser, $dbpasswd) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $dbhost . ( ( !empty($dbport) ) ? ':' . $dbport : NULL );
				$forumsettings['alt_db_name'] = $dbname;
				$forumsettings['alt_db_user'] = $dbuser;
				$forumsettings['alt_db_passwd'] = $dbpasswd;

				if ( $action_login === false )
				{
					$table['groups'] = $table_prefix . 'groups';

					$forumsettings['groups_sql'] = '
SELECT
  `group_id` AS groupid,
  `group_name` AS groupname
FROM
  `%1$s`
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $table_prefix . 'users';
					$table['groupsmembers'] = $table_prefix . 'user_group';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`user_id` AS userid,
  MEM.`username` AS username,
  MEM.`username` AS realname,
  GRM.`group_id` AS groupid
FROM
  `%1$s` AS MEM,
  `%2$s` AS GRM
WHERE
  MEM.`user_id` = GRM.`user_id` AND
  MEM.`username` = "%3$s" AND
  MEM.`user_password` = "%4$s" AND
  MEM.`user_type` != 2';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$table['groupsmembers'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] )
					);
				}
				break;

/* START - PHPNUKE78_PHPBB207 */
			case 'phpnuke78_phpbb207':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'config.php' );

				if ( !isset($prefix, $user_prefix, $dbhost, $dbname, $dbuname, $dbpass) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $dbhost;
				$forumsettings['alt_db_name'] = $dbname;
				$forumsettings['alt_db_user'] = $dbuname;
				$forumsettings['alt_db_passwd'] = $dbpass;

				if ( $action_login === false )
				{
					$table['groups'] = $prefix . '_bbgroups';

					$forumsettings['groups_sql'] = '
SELECT
  `group_id` AS groupid,
  `group_name` AS groupname
FROM
  `%1$s`
WHERE
  `group_single_user` = 0
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $user_prefix . '_users';
					$table['groupsmembers'] = $prefix . '_bbuser_group';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`user_id` AS userid,
  MEM.`username` AS username,
  MEM.`name` AS realname,
  GRM.`group_id` AS groupid
FROM
  `%1$s` AS MEM,
  `%2$s` AS GRM
WHERE
  MEM.`user_id` = GRM.`user_id` AND
  MEM.`username` = "%3$s" AND
  MEM.`user_password` = "%4$s" AND
  MEM.`user_active` = 1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$table['groupsmembers'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] )
					);
				}
				break;

/* START - SMF103 */
			case 'smf103':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'Settings.php' );

				if ( !isset($db_prefix, $db_server, $db_name, $db_user, $db_passwd) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $db_server;
				$forumsettings['alt_db_name'] = $db_name;
				$forumsettings['alt_db_user'] = $db_user;
				$forumsettings['alt_db_passwd'] = $db_passwd;

				if ( $action_login === false )
				{
					$table['groups'] = $db_prefix.'membergroups';

					$forumsettings['groups_sql'] = '
SELECT
  `ID_GROUP` AS groupid,
  `groupName` AS groupname
FROM
  `%1$s`
WHERE
  `minPosts` = -1
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $db_prefix . 'members';

					$forumsettings['authchecksql'] = '
SELECT
  `ID_MEMBER` AS userid,
  `memberName` AS username,
  `realName` AS realname,
  `ID_GROUP` AS groupid,
  `additionalGroups` AS additionalgroups
FROM
  `%1$s` 
WHERE
  `memberName` = "%2$s" AND
  `passwd` = "%3$s" AND
  `is_activated` = 1 AND
  ((`ID_GROUP` = %4$u) OR 
  (%4$u IN (`additionalGroups`)))
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5_hmac( $action_login['fpasswd'], strtolower( $action_login['fusername'] ) ),
						$settings['Forum_group']
					);
				}
				break;

/* START - SMF110 */
			case 'smf110':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'Settings.php' );

				if ( !isset($db_prefix, $db_server, $db_name, $db_user, $db_passwd) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $db_server;
				$forumsettings['alt_db_name'] = $db_name;
				$forumsettings['alt_db_user'] = $db_user;
				$forumsettings['alt_db_passwd'] = $db_passwd;

				if ( $action_login === false )
				{
					$table['groups'] = $db_prefix . 'membergroups';

					$forumsettings['groups_sql'] = '
SELECT
  `ID_GROUP` AS groupid,
  `groupName` AS groupname
FROM
  `%1$s`
WHERE
  `minPosts` = -1
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $db_prefix . 'members';

					$forumsettings['authchecksql'] = '
SELECT
  `ID_MEMBER` AS userid,
  `memberName` AS username,
  `realName` AS realname,
  `ID_GROUP` AS groupid,
  `additionalGroups` AS additionalgroups
FROM
  `%1$s` 
WHERE
  `memberName` = "%2$s" AND
  `passwd` = "%3$s" AND
  `is_activated` = 1 AND
  ((`ID_GROUP` = %4$u) OR 
  (%4$u IN (`additionalGroups`)))
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						sha1( strtolower( $action_login['fusername'] ) . ( $action_login['fpasswd'] ) ),
						$settings['Forum_group']
					);
				}
				break;

/* START - VB307 */
			case 'vb307':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'includes/config.php' );

				if ( !isset($tableprefix, $servername, $dbname, $dbusername, $dbpassword) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $servername;
				$forumsettings['alt_db_name'] = $dbname;
				$forumsettings['alt_db_user'] = $dbusername;
				$forumsettings['alt_db_passwd'] = $dbpassword;

				if ( $action_login === false )
				{
					$table['groups'] = $tableprefix . 'usergroup';

					$forumsettings['groups_sql'] = '
SELECT
  `usergroupid` AS groupid,
  `usertitle` AS groupname
FROM
  `%1$s`
WHERE
  `usertitle` != ""
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $tableprefix . 'user';

					$forumsettings['authchecksql'] = '
SELECT
  `userid` AS userid,
  `username` AS username,
  `username` AS realname,
  `usergroupid` AS groupid,
  `membergroupids` AS additionalgroups
FROM
  `%1$s` 
WHERE
  `username` = "%2$s" AND
  `password` = MD5(CONCAT("%3$s", `salt`)) AND
  ((`usergroupid` = %4$u) OR 
  (%4$u IN (`membergroupids`)))
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] ),
						$settings['Forum_group']
					);
				}
				break;

/* START - VB350 */
			case 'vb350':
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'includes/config.php' );

				if ( !isset($config['Database']['tableprefix'], $config['MasterServer']['servername'], $config['MasterServer']['port'], $config['Database']['dbname'], $config['MasterServer']['username'], $config['MasterServer']['password']) )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = $config['MasterServer']['servername'] . ( ( !empty($config['MasterServer']['port']) ) ? ':' . $config['MasterServer']['port'] : NULL );
				$forumsettings['alt_db_name'] = $config['Database']['dbname'];
				$forumsettings['alt_db_user'] = $config['MasterServer']['username'];
				$forumsettings['alt_db_passwd'] = $config['MasterServer']['password'];

				if ( $action_login === false )
				{
					$table['groups'] = $config['Database']['tableprefix'] . 'usergroup';

					$forumsettings['groups_sql'] = '
SELECT
  `usergroupid` AS groupid,
  `usertitle` AS groupname
FROM
  `%1$s`
WHERE
  `usertitle` != ""
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = $config['Database']['tableprefix'] . 'user';

					$forumsettings['authchecksql'] = '
SELECT
  `userid` AS userid,
  `username` AS username,
  `username` AS realname,
  `usergroupid` AS groupid,
  `membergroupids` AS additionalgroups
FROM
  `%1$s` 
WHERE
  `username` = "%2$s" AND
  `password` = MD5(CONCAT("%3$s", `salt`)) AND
  ((`usergroupid` = %4$u) OR 
  (%4$u IN (`membergroupids`)))
LIMIT 0,1';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] ),
						$settings['Forum_group']
					);
				}
				break;

/* START - xoops_cbb */
			case 'xoops_cbb':
				$xoopsOption['nocommon'] = true;
				require( $GLOBALS['tssettings']['Root_path'] . $settings['Forum_relative_path'] . 'mainfile.php' );

				if ( !defined('XOOPS_DB_PREFIX') || !defined('XOOPS_DB_HOST') || !defined('XOOPS_DB_NAME') || !defined('XOOPS_DB_USER') || !defined('XOOPS_DB_PASS') )
				{
					early_error( '{TEXT_INVALID_CONF_FILE}' );
				}

				$forumsettings['alt_db_host'] = XOOPS_DB_HOST;
				$forumsettings['alt_db_name'] = XOOPS_DB_NAME;
				$forumsettings['alt_db_user'] = XOOPS_DB_USER;
				$forumsettings['alt_db_passwd'] = XOOPS_DB_PASS;

				if ( $action_login === false )
				{
					$table['groups'] = XOOPS_DB_PREFIX.'_groups';

					$forumsettings['groups_sql'] = '
SELECT
  `groupid` AS groupid,
  `name` AS groupname
FROM
  `%1$s`
WHERE
  `group_type` != "Anonymous"
ORDER BY
  `groupname`';

					$forumsettings['groups_sql_params'] = $table['groups'];
				}
				else
				{
					$table['members'] = XOOPS_DB_PREFIX . '_users';
					$table['groupsmembers'] = XOOPS_DB_PREFIX . '_groups_users_link';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`uid` AS userid,
  MEM.`loginname` AS username,
  MEM.`uname` AS realname,
  GRM.`groupid` AS groupid
FROM
  `%1$s` AS MEM,
  `%2$s` AS GRM
WHERE
  MEM.`uid` = GRM.`uid` AND
  MEM.`loginname` = "%3$s" AND
  MEM.`pass` = "%4$s"';

					$forumsettings['authchecksql_params'] = array(
						$table['members'],
						$table['groupsmembers'],
						$GLOBALS['db']->escape_string( $action_login['fusername'] ),
						md5( $action_login['fpasswd'] )
					);
				}
				break;
			default: 
				early_error( '{TEXT_UNKNOWN_FORUMTYPE_ERROR}' );
		}
	}
	else
	{
		early_error( '{TEXT_FORUMTYPE_COMBI_ERROR}' );
	}

	return( $forumsettings );
}

// this function handles the forum database connection
function handle_forum_db_connection( $action_login=false )
{
	if ( !isset($GLOBALS['forumsettings']) )
	{
		$GLOBALS['forumsettings'] = retrieve_forumsettings( array(
			'Forum_type'          => $GLOBALS['tssettings']['Forum_type'],
			'Forum_relative_path' => $GLOBALS['tssettings']['Forum_relative_path'],
			'Forum_group'         => $GLOBALS['tssettings']['Forum_group']
		), $action_login );
	}

	if (
		!isset($GLOBALS['db']) || 
		$GLOBALS['forumsettings']['alt_db_host'] !== $GLOBALS['tssettings']['db_host'] ||
		$GLOBALS['forumsettings']['alt_db_user'] !== $GLOBALS['tssettings']['db_user'] || 
		$GLOBALS['forumsettings']['alt_db_passwd'] !== $GLOBALS['tssettings']['db_passwd'] || 
		$GLOBALS['forumsettings']['alt_db_name'] !== $GLOBALS['tssettings']['db_name']
	)
	{
		$GLOBALS['forumdatabase'] = 'dbforum';
		$GLOBALS[ $GLOBALS['forumdatabase'] ] = new database;
		$GLOBALS[ $GLOBALS['forumdatabase'] ]->connect( 'pzforumserverconnect', $GLOBALS['forumsettings']['alt_db_host'], $GLOBALS['forumsettings']['alt_db_user'], $GLOBALS['forumsettings']['alt_db_passwd'], $GLOBALS['forumsettings']['alt_db_name'] );
	}
	else
	{
		$GLOBALS['forumdatabase'] = 'db';
	}
}

// this function handles the checking of the new forum settings
function test_new_forum( $forum_type, $forum_relative_path )
{
	if ( isset($GLOBALS['tssettings']['Forum_type'], $GLOBALS['tssettings']['Forum_relative_path']) && ( $forum_type !== $GLOBALS['tssettings']['Forum_type'] || $forum_relative_path !== $GLOBALS['tssettings']['Forum_relative_path'] ) )
	{
		if ( forum_existence_check($forum_type, $forum_relative_path) )
		{
			$GLOBALS['forumsettings'] = retrieve_forumsettings( array(
				'Forum_type'          => $forum_type,
				'Forum_relative_path' => $forum_relative_path
			) );
		}
		else
		{
			return( false );
		}
	}

	return( true );
}

// this function checks the new settings for validity
function check_setting( $var_name, $var_value )
{
	/* sprinter help
		s = string
		d = integer signed
		u = integer unsigned
	*/
	switch ( $var_name )
	{
		// booleans
		case 'Cache_hits':
		case 'Custom_servers':
		case 'Display_ping':
		case 'Display_server_information':
		case 'GZIP_Compression':
		case 'Page_generation_time':
		case 'Retrieved_data_status':
		case 'TeamSpeak_support':
		case 'Ventrilo_support':
			$param['sprinter'] = 'u';
			$param['value'] = (bool) $var_value;
			break;

		//integers
		case 'Default_queryport':
		case 'Default_server':
		case 'Forum_group':
		case 'Page_refresh_timer':
			$param['sprinter'] = 'u';
			$param['value'] = $var_value;
			break;

		//directory strings
		case 'Forum_relative_path':
			$var_value = ltrim( $var_value, '/' );
		case 'Root_path':
			$param['sprinter'] = 's';
			$param['value'] = $GLOBALS['db']->escape_string( prep_dir_string( $var_value ) );
			break;

		//strings
		case 'Forum_type':
		case 'Language':
		case 'Page_title':
		case 'SLG_version':
		case 'Template':
			$param['sprinter'] = 's';
			$param['value'] = $GLOBALS['db']->escape_string( $var_value );
			break;

		//custom
		case 'Base_url':
			$param['sprinter'] = 's';
			$param['value'] = $GLOBALS['db']->escape_string( ltrim( prep_dir_string( removechars( $var_value, 'http://' ) ), '/' ) );
			break;

		case 'Ventrilo_status_program':
			$param['sprinter'] = 's';
			$param['value'] = $GLOBALS['db']->escape_string( trim( str_replace( '\\', '/', $var_value ), '/' ) );
			break;

		default: 
			early_error( '{TEXT_UNKNOWNSETTING;' . $var_name . ';}' );
	};

	return( $param );
}

// this function creates the settingslist based on the values in the $settings array
function create_settingslist( &$settings )
{
	$settingslist = NULL;
	$settings = array_values( (array) $settings );
	while ( $setting = array_shift($settings) )
	{
		$setting['helptext'] = '{TEXT_HELP_' . strtoupper( $setting['variable'] ) . '}';
		$setting['text'] = '{TEXT_' . strtoupper( $setting['variable'] ) . '}';
		$setting['variable'] = htmlentities( $setting['variable'] );
		$setting['value'] = htmlentities( $setting['value'] );

		$settingslist .= '
  <tr>
    <td width="40%" nowrap onMouseOver="toolTip(\'' . $setting['helptext'] . '\', 500)" onMouseOut="toolTip()"><p class="para">' . $setting['text'] . ':</p></td>
    <td width="60%" nowrap><p class="para"> ';

		switch ( $setting['variable'] )
		{
			// booleans
			case 'Cache_hits':
			case 'Custom_servers':
			case 'Database':
			case 'Display_ping':
			case 'Display_server_information':
			case 'GZIP_Compression':
			case 'Page_generation_time':
			case 'Retrieved_data_status':
			case 'TeamSpeak_support':
			case 'Ventrilo_support':
				$settingslist .= '<input id="' . $setting['variable'] . '_enable" name="variable[' . $setting['variable'] . ']" type="radio" value="1"' . ( ( $setting['value'] ) ? ' checked' : NULL ) . '><label for="' . $setting['variable'] . '_enable">{TEXT_ENABLE}</label>
<input id="' . $setting['variable'] . '_disable" name="variable[' . $setting['variable'] . ']" type="radio" value="0"' . ( ( !$setting['value'] ) ? ' checked' : NULL ) . '><label for="' . $setting['variable'] . '_disable">{TEXT_DISABLE}</label>
';
				break;

			// integers
			case 'Default_queryport':
			case 'Page_refresh_timer':
				$settingslist .= '<input class="textline" name="variable[' . $setting['variable'] . ']" type="text" id="set_value_' . $setting['variable'] . '" value="' . $setting['value'] . '" size="25" maxlength="5">';
				break;

			// passwords
			case 'db_passwd':
				$settingslist .= '<input class="textline" name="variable[' . $setting['variable'] . ']" type="password" id="set_value_' . $setting['variable'] . '" size="25" maxlength="50">';
				break;

			// strings
			case 'Base_url':
			case 'db_host':
			case 'db_name':
			case 'db_user':
			case 'Forum_relative_path':
			case 'Page_title':
			case 'Root_path':
			case 'table_prefix':
			case 'Ventrilo_status_program':
				$settingslist .= '<input class="textline" name="variable[' . $setting['variable'] . ']" type="text" id="set_value_' . $setting['variable'] . '" size="25" maxlength="200" value="' . $setting['value'] . '">';
				break;

			// strings disabled
			case 'SLG_version':
				$settingslist .= '<input class="textline" name="variable[' . $setting['variable'] . ']" type="text" id="set_value_' . $setting['variable'] . '" value="' . $setting['value'] . '" size="25" maxlength="100" disabled>';
				break;

			// customs
			case 'db_type': 
				$settingslist .= '<select id="set_value_' . $setting['variable'] . '" name="variable[' . $setting['variable'] . ']" class="textline">
<option value="mysql">MySQL 3.23.xx</option>
' . ( ( extension_loaded('mysqli') ) ? '<option value="mysql41">MySQL 4.1.x / 5.0.x</option>' : NULL ) . '
</select>';
				break;

			case 'Default_server':
				$sql = '
SELECT `res_id`, `res_name`
FROM `%1$s`
WHERE `res_type` IN ("TeamSpeak","Ventrilo")
ORDER BY `res_name`';
				$getservers = $GLOBALS['db']->execquery( 'getservers', $sql, $GLOBALS['table']['resources'] );
				unset( $sql );

				$settingslist .= '<select id="set_value_' . $setting['variable'] . '" name="variable[' . $setting['variable'] . ']" class="textline">
			<option value="0">None</option>';
				while( $rowts = $GLOBALS['db']->getrow($getservers) )
				{
					$settingslist .= '<option value="' . $rowts['res_id'] . '"' . ( ( $rowts['res_id'] == $setting['value'] ) ? ' selected' : NULL ) . '>' . htmlentities( $rowts['res_name'] ) . '</option>';
				}
				$settingslist .= '</select>';

				$GLOBALS['db']->freeresult( 'getservers', $getservers );
				unset( $getservers, $rowts );
				break;

			case 'Forum_group':
				handle_forum_db_connection();

				if ( isset($GLOBALS['forumsettings']['groups_sql']) )
				{
					$groupsquery = $GLOBALS[ $GLOBALS['forumdatabase'] ]->execquery( 'getforumgroups', $GLOBALS['forumsettings']['groups_sql'], $GLOBALS['forumsettings']['groups_sql_params'] );

					if ( $GLOBALS[ $GLOBALS['forumdatabase'] ]->numrows($groupsquery) > 0 )
					{
						$settingslist .= '<select id="set_value_' . $setting['variable'] . '" name="variable[' . $setting['variable'] . ']" class="textline">';
						while( $rowgroup = $GLOBALS[ $GLOBALS['forumdatabase'] ]->getrow($groupsquery) )
						{
							$settingslist .= '<option value="' . $rowgroup['groupid'] . '"' . ( ( $rowgroup['groupid'] == $setting['value'] ) ? ' selected' : NULL ) . '>' . $rowgroup['groupname'] . '</option>';
						}
						$settingslist .= '</select>';
						unset( $rowgroup );
					}
					else
					{
						if ( checkfilelock('install.php') )
						{
							early_error( '{TEXT_NOGROUP_INSTALL}' );
						}

						$settingslist .= '<span class="error">{TEXT_NOGROUP}</span>';
					}
					$GLOBALS[ $GLOBALS['forumdatabase'] ]->freeresult( 'getforumgroups', $groupsquery );
					unset( $groupsquery );
				}
				else
				{
					early_error( '{TEXT_MISSING_GROUP_QUERY_ERROR}' );
				}

				if ( isset($GLOBALS['dbforum']->sqlconnectid) )
				{
					$GLOBALS['dbforum']->disconnect();
				}

				unset( $GLOBALS['forumsettings'] );
				break;

			case 'Forum_type':
				$settingslist .= '<select id="set_value_' . $setting['variable'] . '" name="variable[' . $setting['variable'] . ']" class="textline">
<option value="bboardlite102"' . ( ( $setting['value'] === 'bboardlite102' ) ? ' selected' : NULL ) . '>Burning Board Lite 1.0.2</option>
<option value="bboardfull234"' . ( ( $setting['value'] === 'bboardfull234' ) ? ' selected' : NULL ) . '>Burning Board 2.3.4-2.3.6</option>
<option value="ipb131"' . ( ( $setting['value'] === 'ipb131' ) ? ' selected' : NULL ) . '>Invision Power Board 1.3.1</option>
<option value="ipb204"' . ( ( $setting['value'] === 'ipb204' ) ? ' selected' : NULL ) . '>Invision Power Board 2.0.3-2.2.1</option>
<option value="phpbb2015"' . ( ( $setting['value'] === 'phpbb2015' ) ? ' selected' : NULL ) . '>PhpBB 2.0.9-2.0.22</option>
<option value="phpbb3"' . ( ( $setting['value'] === 'phpbb3' ) ? ' selected' : NULL ) . '>PhpBB 3 Beta 4-Beta 5</option>
<option value="phpnuke78_phpbb207"' . ( ( $setting['value'] === 'phpnuke78_phpbb207' ) ? ' selected' : NULL ) . '>PHP-Nuke 7.8-8.0 + PhpBB 2.0.7</option>
<option value="smf103"' . ( ( $setting['value'] === 'smf103' ) ? ' selected' : NULL ) . '>SMF (Simple Machines Forum) 1.0.3-1.0.10</option>
<option value="smf110"' . ( ( $setting['value'] === 'smf110' ) ? ' selected' : NULL ) . '>SMF (Simple Machines Forum) 1.1.0-1.1.1</option>
<option value="vb307"' . ( ( $setting['value'] === 'vb307' ) ? ' selected' : NULL ) . '>vBulletin v3.0.7</option>
<option value="vb350"' . ( ( $setting['value'] === 'vb350' ) ? ' selected' : NULL ) . '>vBulletin v3.5.0-3.6.4</option>
<option value="xoops_cbb"' . ( ( $setting['value'] === 'xoops_cbb' ) ? ' selected' : NULL ) . '>XOOPS 2.2.3a-2.2.5 RC2 + CBB 2.32-3.07</option>
</select>';
				break;

			case 'install_type':
				$settingslist .= '<select id="set_value_' . $setting['variable'] . '" name="variable[' . $setting['variable'] . ']" class="textline">
' . ( ( !isset($GLOBALS['old_version']) ) ? '<option value="new">{TEXT_NEW_INSTALL}</option>' : '<option value="upgrade">{TEXT_UPGRADE_INSTALL}</option>
' . ( ( !defined("NO_DATABASE") && $GLOBALS['old_version'] === $GLOBALS['new_version'] ) ? '<option value="rescue">{TEXT_RESCUE_INSTALL}</option>' : NULL ) . '
' ) . '
</select>';
				break;

			case 'Language':
				$settingslist .= '<select id="set_value_' . $setting['variable'] . '" name="variable[' . $setting['variable'] . ']" class="textline">';

				$dir = 'languages';
				$d = scandir( $dir );
				for ( $i=0, $max=count($d); $i < $max; $i++ )
				{
					if ( file_exists($dir . '/' . $d[ $i ] . '/lng_earlyerrors.php') )
					{
						$settingslist .= '<option value="' . $d[ $i ] . '"' . ( ( $setting['value'] === $d[ $i ] ) ? ' selected' : NULL ) . '>' . $d[ $i ] . '</option>';
					}
				}
				unset( $dir, $d, $i, $max );

				$settingslist .= '</select>';
				break;

			case 'Template':
				$settingslist .= '<select id="set_value_' . $setting['variable'] . '" name="variable[' . $setting['variable'] . ']" class="textline">';

				$dir = 'templates';
				$d = scandir( $dir );
				for ( $i=0, $max=count($d); $i < $max; $i++ )
				{
					if ( file_exists($dir . '/' . $d[ $i ] . '/tpl_header.html') )
					{
						$settingslist .= '<option value="' . $d[ $i ] . '"' . ( ( $setting['value'] === $d[ $i ] ) ? ' selected' : NULL ) . '>' . $d[ $i ] . '</option>';
					}
				}
				unset( $dir, $d, $i, $max );

				$settingslist .= '</select>';
				break;

			default: 
				early_error( '{TEXT_UNKNOWNSETTING;' . $setting['variable'] . ';}' );
		}

		$settingslist .= '
	  </p></td>
  </tr>';
	}

	return( $settingslist );
}

// if you don't use PHP5 this function doesn't exist
if ( !function_exists('scandir') )
{
	function scandir( $dir, $order=0 )
	{
		$dh  = opendir( $dir );
		$files = array();
		while ( false !== ( $filename = readdir($dh) ) )
		{
		   $files[] = $filename;
		}
		closedir( $dh );

		if ( $order === 0 )
		{
			sort( $files );
		}
		elseif( $order === 1 )
		{
			rsort( $files );
		}

		return( $files );
	}
}
?>

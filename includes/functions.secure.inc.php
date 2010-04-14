<?php
/***************************************************************************
 *                          functions.secure.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.secure.inc.php,v 1.10 2005/12/25 20:18:12 SC Kruiper Exp $
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

if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}

function encode_ip($dotquad_ip){
	$ip_sep = explode('.', $dotquad_ip);
	$ip_encoded = sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
	return($ip_encoded);
}

function decode_ip($int_ip){
	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	$ip_decoded = hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
	return($ip_decoded);
}

function checkaccess($checkvalue){
	return(isset($_SESSION['group_id']) && in_array($checkvalue, $_SESSION['group_id']));
}

// MD5 Encryption of the smf forum.
function md5_hmac($data, $key)
{
	$key = str_pad(strlen($key) <= 64 ? $key : pack('H*', md5($key)), 64, chr(0x00));
	return(md5(($key ^ str_repeat(chr(0x5c), 64)) . pack('H*', md5(($key ^ str_repeat(chr(0x36), 64)). $data))));
}

function forum_existence_check($forumtype, $forumpath){
	return((($forumtype === 'ipb131' || $forumtype === 'ipb204') && file_exists($forumpath.'conf_global.php')) || ($forumtype === 'phpbb2015' && file_exists($forumpath.'config.php')) || (($forumtype === 'smf103' || $forumtype === 'smf110') && file_exists($forumpath.'Settings.php')) || (($forumtype === 'vb307' || $forumtype === 'vb350') && file_exists($forumpath.'includes/config.php')));
}

function retrieve_forumsettings(&$tssettings, $action_login=false){
	global $db;
	if (forum_existence_check($tssettings['Forum type'], $tssettings['Forum relative path'])){
		switch ($tssettings['Forum type']){
/* START - IPB131 */
			case 'ipb131':
				include($tssettings['Forum relative path'].'conf_global.php');

				if (!isset($INFO['sql_tbl_prefix'], $INFO['sql_database'], $INFO['sql_host'], $INFO['sql_port'], $INFO['sql_user'], $INFO['sql_pass'])){
					early_error('{TEXT_INVALID_CONF_FILE}');
				}

				$table['groups'] = $INFO['sql_tbl_prefix'].'groups';

				if ($INFO['sql_database'] != $tssettings['db_name']){
					$forumsettings['otherdatabase'] = true;
					$forumsettings['alt_db_host'] = $INFO['sql_host'].(!empty($INFO['sql_port'])) ? ':'.$INFO['sql_port'] : NULL;
					$forumsettings['alt_db_name'] = $INFO['sql_database'];
					$forumsettings['alt_db_user'] = $INFO['sql_user'];
					$forumsettings['alt_db_passwd'] = $INFO['sql_pass'];
				}
				else{
					$forumsettings['otherdatabase'] = false;
				}

				$forumsettings['groups_sql'] = '
SELECT
  g_id AS groupid,
  g_title AS groupname
FROM
  '.$table['groups'].'
ORDER BY
  groupname';

				if ($action_login){
					$table['members'] = $INFO['sql_tbl_prefix'].'members';

					$forumsettings['authchecksql'] = '
SELECT
  `id` AS userid,
  `name` AS username,
  `name` AS realname,
  `mgroup` AS groupid
FROM
  `'.$table['members'].'`
WHERE
  `name` = "'.$db->escape_string($_POST['fusername']).'" AND
  `mgroup` = '.$tssettings['Forum group'].' AND
  `password` = "'.md5($_POST['fpasswd']).'"
limit 0,1';
				}
				break;
/* START - IPB204 */
			case 'ipb204':
				include($tssettings['Forum relative path'].'conf_global.php');

				if (!isset($INFO['sql_tbl_prefix'], $INFO['sql_database'], $INFO['sql_host'], $INFO['sql_user'], $INFO['sql_pass'])){
					early_error('{TEXT_INVALID_CONF_FILE}');
				}

				$table['groups'] = $INFO['sql_tbl_prefix'].'groups';

				if ($INFO['sql_database'] != $tssettings['db_name']){
					$forumsettings['otherdatabase'] = true;
					$forumsettings['alt_db_host'] = $INFO['sql_host'];
					$forumsettings['alt_db_name'] = $INFO['sql_database'];
					$forumsettings['alt_db_user'] = $INFO['sql_user'];
					$forumsettings['alt_db_passwd'] = $INFO['sql_pass'];
				}
				else{
					$forumsettings['otherdatabase'] = false;
				}

				$forumsettings['groups_sql'] = '
SELECT
  g_id AS groupid,
  g_title AS groupname
FROM
  '.$table['groups'].'
ORDER BY
  groupname';

				if ($action_login){
					$table['members'] = $INFO['sql_tbl_prefix'].'members';
					$table['memconverge'] = $INFO['sql_tbl_prefix'].'members_converge';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`id` AS userid,
  MEM.`name` AS username,
  MEM.`name` AS realname,
  MEM.`mgroup` AS groupid,
  MEM.`mgroup_others` AS additionalgroups
FROM
  `'.$table['members'].'` MEM,
  `'.$table['memconverge'].'` CON
WHERE
  MEM.`id` = CON.`converge_id` AND
  MEM.`name` = "'.$db->escape_string($_POST['fusername']).'" AND
  CON.`converge_pass_hash` = md5(CONCAT(md5(CON.`converge_pass_salt`), "'.md5($_POST['fpasswd']).'")) AND
  ((TRIM(BOTH "," FROM MEM.`mgroup`) = '.$tssettings['Forum group'].') OR 
  ('.$tssettings['Forum group'].' IN (TRIM(BOTH "," FROM MEM.`mgroup_others`))))
limit 0,1';
				}
				break;
/* START - PHPBB2015 */
			case 'phpbb2015':
				include($tssettings['Forum relative path'].'config.php');

				if (!isset($table_prefix, $dbhost, $dbname, $dbuser, $dbpasswd)){
					early_error('{TEXT_INVALID_CONF_FILE}');
				}

				$table['groups'] = $table_prefix.'groups';

				if ($dbname != $tssettings['db_name']){
					$forumsettings['otherdatabase'] = true;
					$forumsettings['alt_db_host'] = $dbhost;
					$forumsettings['alt_db_name'] = $dbname;
					$forumsettings['alt_db_user'] = $dbuser;
					$forumsettings['alt_db_passwd'] = $dbpasswd;
				}
				else{
					$forumsettings['otherdatabase'] = false;
				}

				$forumsettings['groups_sql'] = '
SELECT
  group_id AS groupid,
  group_name AS groupname
FROM
  '.$table['groups'].'
WHERE
  group_single_user = 0
ORDER BY
  groupname';

				if ($action_login){
					$table['members'] = $table_prefix.'users';
					$table['groupsmembers'] = $table_prefix.'user_group';

					$forumsettings['authchecksql'] = '
SELECT
  MEM.`user_id` AS userid,
  MEM.`username` AS username,
  MEM.`username` AS realname,
  GRM.`group_id` AS groupid
FROM
  `'.$table['members'].'` MEM,
  `'.$table['groupsmembers'].'` GRM
WHERE
  MEM.`user_id` = GRM.`user_id` AND
  MEM.`user_active` = 1 AND
  MEM.`username` = "'.$db->escape_string($_POST['fusername']).'" AND
  MEM.`user_password` = "'.md5($_POST['fpasswd']).'"';
				}
				break;
/* START - SMF103 */
			case 'smf103':
				include($tssettings['Forum relative path'].'Settings.php');

				if (!isset($db_prefix, $db_server, $db_name, $db_user, $db_passwd)){
					early_error('{TEXT_INVALID_CONF_FILE}');
				}

				$table['groups'] = $db_prefix.'membergroups';

				if ($db_name != $tssettings['db_name']){
					$forumsettings['otherdatabase'] = true;
					$forumsettings['alt_db_host'] = $db_server;
					$forumsettings['alt_db_name'] = $db_name;
					$forumsettings['alt_db_user'] = $db_user;
					$forumsettings['alt_db_passwd'] = $db_passwd;
				}
				else{
					$forumsettings['otherdatabase'] = false;
				}

				$forumsettings['groups_sql'] = '
SELECT
  ID_GROUP AS groupid,
  groupName AS groupname
FROM
  '.$table['groups'].'
WHERE
  minPosts = -1
ORDER BY
  groupname';

				if ($action_login){
					$table['members'] = $db_prefix.'members';

					$forumsettings['authchecksql'] = '
SELECT
  `ID_MEMBER` AS userid,
  `memberName` AS username,
  `realName` AS realname,
  `ID_GROUP` AS groupid,
  `additionalGroups` AS additionalgroups
FROM
  `'.$table['members'].'` 
WHERE
  `memberName` = "'.$db->escape_string($_POST['fusername']).'" AND
  `passwd` = "'.md5_hmac($_POST['fpasswd'], strtolower($_POST['fusername'])).'" AND
  `is_activated` = 1 AND
  ((ID_GROUP = '.$tssettings['Forum group'].') OR 
  ('.$tssettings['Forum group'].' IN (additionalGroups)))
limit 0,1';
				}
				break;
/* START - SMF110 */
			case 'smf110':
				include($tssettings['Forum relative path'].'Settings.php');

				if (!isset($db_prefix, $db_server, $db_name, $db_user, $db_passwd)){
					early_error('{TEXT_INVALID_CONF_FILE}');
				}

				$table['groups'] = $db_prefix.'membergroups';

				if ($db_name != $tssettings['db_name']){
					$forumsettings['otherdatabase'] = true;
					$forumsettings['alt_db_host'] = $db_server;
					$forumsettings['alt_db_name'] = $db_name;
					$forumsettings['alt_db_user'] = $db_user;
					$forumsettings['alt_db_passwd'] = $db_passwd;
				}
				else{
					$forumsettings['otherdatabase'] = false;
				}

				$forumsettings['groups_sql'] = '
SELECT
  ID_GROUP AS groupid,
  groupName AS groupname
FROM
  '.$table['groups'].'
WHERE
  minPosts = -1
ORDER BY
  groupname';

				if ($action_login){
					$table['members'] = $db_prefix.'members';

					$forumsettings['authchecksql'] = '
SELECT
  `ID_MEMBER` AS userid,
  `memberName` AS username,
  `realName` AS realname,
  `ID_GROUP` AS groupid,
  `additionalGroups` AS additionalgroups
FROM
  `'.$table['members'].'` 
WHERE
  `memberName` = "'.$db->escape_string($_POST['fusername']).'" AND
  `passwd` = "'.sha1(strtolower($_POST['fusername']).($_POST['fpasswd'])).'" AND
  `is_activated` = 1 AND
  ((ID_GROUP = '.$tssettings['Forum group'].') OR 
  ('.$tssettings['Forum group'].' IN (additionalGroups)))
limit 0,1';
				}
				break;
/* START - VB307 */
			case 'vb307':
				include($tssettings['Forum relative path'].'includes/config.php');

				if (!isset($tableprefix, $servername, $dbname, $dbusername, $dbpassword)){
					early_error('{TEXT_INVALID_CONF_FILE}');
				}

				$table['groups'] = $tableprefix.'usergroup';

				if ($dbname != $tssettings['db_name']){
					$forumsettings['otherdatabase'] = true;
					$forumsettings['alt_db_host'] = $servername;
					$forumsettings['alt_db_name'] = $dbname;
					$forumsettings['alt_db_user'] = $dbusername;
					$forumsettings['alt_db_passwd'] = $dbpassword;
				}
				else{
					$forumsettings['otherdatabase'] = false;
				}

				$forumsettings['groups_sql'] = '
SELECT
  usergroupid AS groupid,
  usertitle AS groupname
FROM
  '.$table['groups'].'
WHERE
  usertitle != ""
ORDER BY
  groupname';

				if ($action_login){
					$table['members'] = $tableprefix.'user';

					$forumsettings['authchecksql'] = '
SELECT
  `userid` AS userid,
  `username` AS username,
  `username` AS realname,
  `usergroupid` AS groupid,
  `membergroupids` AS additionalgroups
FROM
  `'.$table['members'].'` 
WHERE
  `username` = "'.$db->escape_string($_POST['fusername']).'" AND
  `password` = md5(CONCAT("'.md5($_POST['fpasswd']).'", `salt`)) AND
  ((usergroupid = '.$tssettings['Forum group'].') OR 
  ('.$tssettings['Forum group'].' IN (membergroupids)))
limit 0,1';
				}
				break;
/* START - VB350 */
			case 'vb350':
				include($tssettings['Forum relative path'].'includes/config.php');

				if (!isset($config['Database']['tableprefix'], $config['MasterServer']['servername'], $config['MasterServer']['port'], $config['Database']['dbname'], $config['MasterServer']['username'], $config['MasterServer']['password'])){
					early_error('{TEXT_INVALID_CONF_FILE}');
				}

				$table['groups'] = $config['Database']['tableprefix'].'usergroup';

				if ($config['Database']['dbname'] != $tssettings['db_name']){
					$forumsettings['otherdatabase'] = true;
					$forumsettings['alt_db_host'] = $config['MasterServer']['servername'].':'.$config['MasterServer']['port'];
					$forumsettings['alt_db_name'] = $config['Database']['dbname'];
					$forumsettings['alt_db_user'] = $config['MasterServer']['username'];
					$forumsettings['alt_db_passwd'] = $config['MasterServer']['password'];
				}
				else{
					$forumsettings['otherdatabase'] = false;
				}

				$forumsettings['groups_sql'] = '
SELECT
  usergroupid AS groupid,
  usertitle AS groupname
FROM
  '.$table['groups'].'
WHERE
  usertitle != ""
ORDER BY
  groupname';

				if ($action_login){
					$table['members'] = $config['Database']['tableprefix'].'user';

					$forumsettings['authchecksql'] = '
SELECT
  `userid` AS userid,
  `username` AS username,
  `username` AS realname,
  `usergroupid` AS groupid,
  `membergroupids` AS additionalgroups
FROM
  `'.$table['members'].'` 
WHERE
  `username` = "'.$db->escape_string($_POST['fusername']).'" AND
  `password` = md5(CONCAT("'.md5($_POST['fpasswd']).'", `salt`)) AND
  ((usergroupid = '.$tssettings['Forum group'].') OR 
  ('.$tssettings['Forum group'].' IN (membergroupids)))
limit 0,1';
				}
				break;
			default: 
				early_error('{TEXT_UNKNOWN_FORUMTYPE_ERROR}');
		}
	}
	else{
		early_error('{TEXT_FORUMTYPE_COMBI_ERROR}');
	}
	return($forumsettings);
}

if (!function_exists('scandir')){
	function scandir($dir, $order=0){
		$dh  = opendir($dir);
		$files = array();
		while (false !== ($filename = readdir($dh))) {
		   $files[] = $filename;
		}
		closedir($dh);

		if ($order == 0){
			sort($files);
		}
		elseif($order == 1){
			rsort($files);
		}
		return($files);
	}
}
?>

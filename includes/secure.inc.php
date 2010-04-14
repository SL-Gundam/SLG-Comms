<?php
/***************************************************************************
 *                              secure.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: secure.inc.php,v 1.13 2005/06/30 19:04:42 SC Kruiper Exp $
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

session_name("tsstatssessid");
session_start();

if (checkfilelock('admin.php')){
	include('includes/functions.secure.inc.php');

	if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_SESSION['prerecorded_ip'])){
		$_SESSION['prerecorded_ip'] = encode_ip($_SERVER['REMOTE_ADDR']);
	}

	if (isset($_POST['login']) && isset($_POST['fusername']) && isset($_POST['fpasswd'])){
		$forumsettings = retrieve_forumsettings($tssettings, true);

		if ($forumsettings['otherdatabase']){
			$forumdatabase = 'dbforum';
			$$forumdatabase = new db;
			$$forumdatabase->connect('pzforumserverconnect', $forumsettings['alt_db_host'], $forumsettings['alt_db_user'], $forumsettings['alt_db_passwd'], $forumsettings['alt_db_name']);
			if ($tssettings['db_type'] == 'mysql') $$forumdatabase->selectdb('pzforumdatabaseconnect', $forumsettings['alt_db_name']);
		}
		else{
			$forumdatabase = 'db';
		}

		$authresult = $$forumdatabase->execquery('authcheckquery',$forumsettings['authchecksql']);

		if ($$forumdatabase->numrows($authresult) > 0){
			while ($authrow = $$forumdatabase->getrow($authresult)){
				if ($tssettings['Forum type'] != 'phpbb2015' || ($tssettings['Forum type'] == 'phpbb2015' && $authrow['groupid'] == $tssettings['Forum group'])){
					$sql = 'DELETE FROM '.$table['sessions'].' WHERE `session_user_id` = '.$authrow['userid'];
					$logincleanquery = $db->execquery('logincleanquery',$sql);

					$sql = 'INSERT INTO `'.$table['sessions'].'` ( `session_id` , `session_user_id` , `session_ip` ) VALUES ( MD5("'.session_id().'") , "'.$authrow['userid'].'", "'.encode_ip($_SERVER['REMOTE_ADDR']).'" );'; 
					$loginquery = $db->execquery('loginquery',$sql);
					if ($loginquery == true){
						$$template->displaymessage('{TEXT_LOGIN_SUCCESS}');
					}

					$_SESSION['user_id'] = $authrow['userid'];
					$_SESSION['username'] = $authrow['username'];
					$_SESSION['realname'] = $authrow['realname'];
					if ($tssettings['Forum type'] == 'smf103' || $tssettings['Forum type'] == 'ipb204' || $tssettings['Forum type'] == 'vb307' || $tssettings['Forum type'] == 'smf110'){
						$group_id = $authrow['groupid'];
						if (!empty($authrow['additionalgroups'])) $group_id .= ','.$authrow['additionalgroups'];
						$_SESSION['group_id'] = explode(',',$group_id);
					}
					elseif ($tssettings['Forum type'] == 'ipb131'){
						$_SESSION['group_id'] = array($authrow['groupid']);
					}
				}
				if ($tssettings['Forum type'] == 'phpbb2015'){
					if (isset($group_id)) $group_id .= ','.$authrow['groupid'];
					else $group_id = $authrow['groupid'];
				}
			}
			if (isset($loginquery) && $tssettings['Forum type'] == 'phpbb2015') $_SESSION['group_id'] = explode(',',$group_id);
		}

		if (!isset($loginquery)){
			$$template->displaymessage('{TEXT_LOGIN_FAILURE}');
			$_SESSION = array(); 
			session_destroy(); 
		}

		$$forumdatabase->freeresult('authcheckquery',$authresult);
		if ($forumsettings['otherdatabase']){
			$$forumdatabase->disconnect();
		}
		$secure = true;
	}

	if (isset($_SESSION['username'])){
		if (file_exists('install.php')){
			$$template->displaymessage('{TEXT_INSTALL_FILE_PRESENT}');
		}

		$sql = 'SELECT `session_id` , `session_ip` FROM `'.$table['sessions'].'` WHERE `session_id` = MD5("'.session_id().'") AND `session_user_id` = '.$_SESSION['user_id']; 
		$authtestresult = $db->execquery('annualcheckup',$sql);

		if ($db->numrows($authtestresult) > 0){
			while ($authtestrow = $db->getrow($authtestresult)){
				if ((decode_ip($authtestrow['session_ip']) == $_SERVER['REMOTE_ADDR']) || (decode_ip($_SESSION['prerecorded_ip']) == $_SERVER['REMOTE_ADDR'] && decode_ip($authtestrow['session_ip']) == $_SERVER['HTTP_X_FORWARDED_FOR'])){
					$securityok = true;
				}
				else{
					$sql = 'DELETE FROM `'.$table['sessions'].'` where `session_id` = MD5("'.session_id().'") limit 1';  
					$authtestfailed = $db->execquery('iperror_session_destruct',$sql);

					$$template->displaymessage('{TEXT_SESSION_VIOLATION}');
					$_SESSION = array();
					session_destroy();
				}
			}
		}
		else{
			$$template->displaymessage('{TEXT_SESSION_VIOLATION}');
			$_SESSION = array(); 
			session_destroy(); 
		}
		$db->freeresult('annualcheckup',$authtestresult);
		$secure = true;
	}

	if (isset($_SESSION['username'], $_GET['page']) && $_GET['page'] == 'logout') {
		$sql = 'DELETE FROM `'.$table['sessions'].'` where `session_id` = MD5("'.session_id().'") limit 1';
		$logoutquery = $db->execquery('logoutquery',$sql);
		if ($logoutquery == true){
			$$template->displaymessage('{TEXT_LOGOUT_SUCCESS}');
		}

		$_SESSION = array();
		session_destroy();
		$secure = true;
	}

	if (!isset($secure) || !$secure){
		$_SESSION = (isset($_SESSION['prerecorded_ip'])) ? array('prerecorded_ip' => $_SESSION['prerecorded_ip']) : array();
	//	session_destroy(); 
	}
}
?>

<?php
/***************************************************************************
 *                              secure.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: secure.inc.php,v 1.41 2008/08/12 22:59:41 SC Kruiper Exp $
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

session_name( "slgstatssessid" );
session_start();

if ( checkfilelock('admin.php') )
{
	require( $tssettings['Root_path'] . 'includes/functions.secure.inc.php' );

	// some times proxies only work for GET requests and not POST requests. this code fixes the ip checking in that case
	if ( !isset($_SESSION['prerecorded_ip']) )
	{
		if ( $_SERVER['REQUEST_METHOD'] === 'GET' )
		{
			$_SESSION['prerecorded_ip'] = md5( $_SERVER['REMOTE_ADDR'] );
		}
		else
		{
			$_SESSION['prerecorded_ip'] = NULL;
		}
	}

	// shall we login?
	if ( isset($_POST['login'], $_POST['fusername'], $_POST['fpasswd']) )
	{
		handle_forum_db_connection( array(
			'fusername' => $_POST['fusername']
		) );

		$authresult = $GLOBALS[ $GLOBALS['forumdatabase'] ]->execquery( 'authcheckquery', $forumsettings['authchecksql'], $forumsettings['authchecksql_params'] );

		unset( $forumsettings );

		if ( $GLOBALS[ $GLOBALS['forumdatabase'] ]->numrows($authresult) > 0 )
		{
			$authrow = $GLOBALS[ $GLOBALS['forumdatabase'] ]->getrow($authresult);
			if ( check_password( $_POST, $authrow ) )
			{
				$sql = '
DELETE FROM `%1$s`
WHERE `session_user_id` = %2$u';
				$db->execquery( 'logincleanquery', $sql, array(
					$table['sessions'],
					$authrow['userid']
				) );

				$sql = '
INSERT INTO `%1$s`
( `session_id` , `session_user_id` , `session_ip` )
VALUES 
( "%2$s" , %3$u, "%4$s" )'; 
				$db->execquery( 'loginquery', $sql, array(
					$table['sessions'],
					md5( session_id() ),
					$authrow['userid'],
					md5( $_SERVER['REMOTE_ADDR'] )
				) );
				unset( $sql );

				$_SESSION['user_id'] = $authrow['userid'];
				$_SESSION['username'] = $authrow['username'];
				$_SESSION['realname'] = ( ( !empty($authrow['realname']) ) ? $authrow['realname'] : $authrow['username'] );

				$_SESSION['group_id'] = array();
				
				if ( $GLOBALS[ $GLOBALS['forumdatabase'] ]->numrows($authresult) === 1 )
				{
					$group_id = trim( $authrow['groupid'], ',' );

					if ( !empty($authrow['additionalgroups']) )
					{
						$group_id .= ',' . trim( $authrow['additionalgroups'], ',' );
					}

					$group_id = explode( ',', $group_id );

					for ( $i=0, $max=count($group_id); $i < $max; $i++ )
					{
						$_SESSION['group_id'][] = (int) $group_id[ $i ];
					}

					unset( $group_id, $i, $max );
				}
				else
				{
					$_SESSION['group_id'][] = (int) $authrow['groupid'];
					while ( $authrow = $GLOBALS[ $GLOBALS['forumdatabase'] ]->getrow($authresult) )
					{
						$_SESSION['group_id'][] = (int) $authrow['groupid'];
					}
				}

				if ( checkaccess( $GLOBALS['tssettings']['Forum_group'] ) === TRUE )
				{
					$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_LOGIN_SUCCESS}' );
					$secure = TRUE;
				}
				else
				{
					$_SESSION = array( 'prerecorded_ip' => $_SESSION['prerecorded_ip'] );
				}

			}
			unset( $authrow );
		}
		
		if ( empty( $_SESSION['username'] ) )
		{
			$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_LOGIN_FAILURE}' );
		}

		$GLOBALS[ $GLOBALS['forumdatabase'] ]->freeresult( 'authcheckquery', $authresult );
		unset( $authresult );

		if ( isset($GLOBALS['dbforum']->sqlconnectid) )
		{
			$GLOBALS['dbforum']->disconnect();
		}
	}

	// session checkup
	if ( isset($_SESSION['username']) )
	{
		if ( file_exists($tssettings['Root_path'] . 'install.php') )
		{
			$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_INSTALL_FILE_PRESENT}' );
		}

		$sql = '
SELECT `session_id`
FROM `%1$s`
WHERE 
  `session_id` = "%2$s" AND 
  `session_user_id` = %3$u AND
  `session_ip` = "%4$s"';

		$authtestresult = $db->execquery( 'annualcheckup', $sql, array(
			$table['sessions'],
			md5( session_id() ),
			$_SESSION['user_id'],
			( ( $_SESSION['prerecorded_ip'] === md5( $_SERVER['REMOTE_ADDR'] ) && $_SERVER['REQUEST_METHOD'] = "GET" && isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) ? md5( $_SERVER['HTTP_X_FORWARDED_FOR'] ) : md5( $_SERVER['REMOTE_ADDR'] ) )
		) );

		if ( $db->numrows($authtestresult) < 1 )
		{
			$sql = '
DELETE FROM `%1$s`
WHERE `session_id` = "%2$s"
LIMIT 1';  
			$db->execquery( 'session_destruct', $sql, array(
				$table['sessions'],
				md5( session_id() )
			) );

			$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_SESSION_VIOLATION}' );
			$_SESSION = array();
			session_destroy();
		}

		$db->freeresult( 'annualcheckup', $authtestresult );
		unset( $authtestresult, $sql );
		$secure = true;
	}

	// shall we logout?
	if ( isset($_SESSION['username'], $_GET['page']) && $_GET['page'] === 'logout' )
	{
		$sql = '
DELETE FROM `%1$s`
WHERE `session_id` = "%2$s"
LIMIT 1';
		$db->execquery( 'logoutquery', $sql, array(
			$table['sessions'],
			md5( session_id() )
		) );
		unset( $sql );
		$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_LOGOUT_SUCCESS}' );

		$_SESSION = array();
		session_destroy();
		$secure = true;
	}

	// did anything happen above?
	if ( !isset($secure) || $secure === false )
	{
		$_SESSION = array( 'prerecorded_ip' => $_SESSION['prerecorded_ip'] );
	//	session_destroy(); 
	}

	unset( $secure );
}
?>

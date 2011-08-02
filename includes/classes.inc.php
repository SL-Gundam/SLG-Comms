<?php
/***************************************************************************
 *                              classes.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: classes.inc.php,v 1.76 2008/08/12 22:59:41 SC Kruiper Exp $
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

//this class counts the amount of time certain operations took to perform. You can start and stop the counter multiple times. ea, this way you can count the total all db operations took to perform
class timecount
{
	var $starttime = NULL;
	var $tottime = 0;
	
	// start the count now
	function starttimecount()
	{
		if ( !is_null($this->starttime) )
		{
			early_error( '{TEXT_CLASS_TIMECOUNT_ERROR}' );
		}
		$mtime = explode( ' ', microtime() );
		$this->starttime = $mtime[1] + $mtime[0];
	}

	// stop the count now
	function endtimecount()
	{
		if ( is_null($this->starttime) )
		{
			early_error( '{TEXT_CLASS_TIMECOUNT_ERROR}' );
		}
		$mtime = explode( ' ', microtime() );
		$this->tottime += ( $mtime[1] + $mtime[0] ) - $this->starttime;
		$this->starttime = NULL;
	}
}

// this class manages all the templates operations
class template
{
	var $template = '{ERROR}';
	var $text = array();
	var $text_adv = array();
	var $tooltips = array();
	var $text_content = array( '{ERROR}' => NULL );
	var $display = array();
	var $menu = array();
	var $tpl_time = NULL;

	// this function loads a template file. the parameter is relative to the templates directory
	function load_template( $filename )
	{
		if ( $this->template !== '{ERROR}' )
		{
			early_error( '{TEXT_LOADTEMPLATE_ERROR}' );
		}

		$file = $GLOBALS['tssettings']['Root_path'] . 'templates/' . ( ( !empty($GLOBALS['tssettings']['Template']) ) ? $GLOBALS['tssettings']['Template'] : 'Default' ) . '/' . $filename . '.html';

		if ( !compare_dir_string( $file, $GLOBALS['tssettings']['Root_path'] . 'templates/' ) )
		{
			$file = $GLOBALS['tssettings']['Root_path'] . 'templates/Default/' . $filename . '.html';
		}

		$this->template = file_get_contents( $file );
	}

	// This function loads a language file. the parameter is relative to the language directory
	function load_language( $filename )
	{
		$file = $GLOBALS['tssettings']['Root_path'] . 'languages/' . ( ( !empty($GLOBALS['tssettings']['Language']) ) ? $GLOBALS['tssettings']['Language'] : 'English' ) . '/' . $filename . '.php';

		if ( !compare_dir_string( $file, $GLOBALS['tssettings']['Root_path'] . 'languages/' ) )
		{
			$file = $GLOBALS['tssettings']['Root_path'] . 'languages/English/' . $filename . '.php';
		}

		require( $file );
	}

	function apply_utf8_charset()
	{
		// disabled until i find a way to convert TeamSpeak and Ventrilo Server data to proper utf8 characters
//		header('Content-type: text/html; charset=UTF-8');
//		$this->text += array( '{CHARSET}' => 'UTF-8' );
	}

	// This function adds text to the {ERROR} placeholder. This can be a normal message like "Login succesfull" till errors which are provided by the function early_error().
	// the $sql and $sqlerror parameter should only be used when outputting a fatal error type ( function early_error() ) because {TEXT_QUERY} and {TEXT_ERROR} are mostly only present in lng_earlyerrors.php
	function displaymessage( $message, $sql=NULL, $sqlerror=NULL )
	{
		$error = '<table border="0" align="center"><tr><td class="error"><p>' . nl2br( $message ) . '</p></td></tr>';
		if ( isset($sql) || isset($sqlerror) )
		{
			$error .= '<tr><td class="error2"><p>';
			if ( defined("SLG_DEBUG") )
			{
				$error .= nl2br( '{TEXT_QUERY}:
' . str_replace( array( ' ', "\t" ), array( '&nbsp;', '&nbsp;&nbsp;' ), htmlentities( $sql ) ) . '

' );
			}
			$error .= nl2br( '{TEXT_SQLERROR}: ' . htmlentities( $sqlerror ) . '
</p></td></tr>' );
		}
		$error .= '</table><p></p>';

		$this->text_content['{ERROR}'] .= $error;
	}

	// this function defines whether certain template parts should be displayed yes or no.
	// $name is the name of the template placeholder part in question. In the template it will have "_BEGIN" or "_END" behind the name to define a start and end point
	// $replace should be provided as true or false. if true the part in question will be displayed. if false the part will be removed from the template
	function insert_display( $name, $replace )
	{
		$name = rtrim( $name, '}' );
		$this->display['@' . $name . '_BEGIN}(.*?)' . $name . '_END}@s'] = ( ( (bool) $replace ) ? '$1' : NULL );
	}

	// this function allows you to insert content.
	// content = when the string includes more translation placeholder items
	// $search is the placeholder
	// $replace will be replace $search in the template
	function insert_content( $search, $replace )
	{
		$this->text_content[ $search ] = $replace;
	}

	// this function allows you to insert text
	// text = when the string doesn't include translation items
	// $search is the placeholder
	// $replace will be replace $search in the template
	function insert_text( $search, $replace )
	{
		$this->text[ $search ] = $replace;
	}

	// this function inserts the base of a menu
	// $menu is the placeholder name
	// $baseurl is the base of every link in the menu
	// $basevar is the first GET parameter for every link in the menu
	// $menutype allows you to define the type of menu (VERTICAL or HORIZONTAL) beforehand incase the menu will be inserted dynamically. incase the menutype is in the template don't fill this paramter
	// $seclevel secures the menu so that only members of that group can see the menu
	function insert_menubase( $menu, $baseurl, $basevar, $menutype=NULL, $seclevel=NULL )
	{
		$this->menu[ $menu ] = array(
			'baseurl'         => $baseurl,
			'basevar'         => $basevar,
			'menutype'        => ( ( is_null($menutype) || $menutype === 'HORIZONTAL' || $menutype === 'VERTICAL' ) ? $menutype : 'HORIZONTAL' ),
			'menuplaceholder' => $menu,
			'seclevel'        => $seclevel
		);
	}

	// this function inserts the actual items for the menu
	// $menu is the placeholder name
	// $base is the GET parameter this menuitem belongs to
	// $name is the name of the menuitem. (this is most likely allways a language placeholder)
	// $url is the followup GET parameter for $base which will be used by the submenu's if needed
	// $seclevel secures the menuitem so that only members of that group can see the menuitem
	function insert_menuitem( $menu, $base, $name, $url, $seclevel=NULL )
	{
		if ( $base !== $url )
		{
			$this->menu[ $menu ]['menuitems'][ $base ][ $url ] = array(
				'name'     => $name,
				'url'      => $url,
				'seclevel' => $seclevel
			);
		}
	}

	// processes the menu so that it can be inserted in the template
	// $menuname is the menu you want to process
	// $menuindex, $baseurl and $level are used for recursion of this function for the submenu's
	function process_menu( $menuname, $menuindex=0, $baseurl=NULL, $level=0 )
	{
		$parsedmenuitems = NULL;

		if ( empty($this->menu[ $menuname ]['seclevel']) || checkaccess($this->menu[ $menuname ]['seclevel']) )
		{
			if ( is_null($baseurl) )
			{
				$baseurl = $this->menu[ $menuname ]['baseurl'];
			}

			if ( $menuindex === 0 )
			{
				$menuindex = $this->menu[ $menuname ]['basevar'];
			}

			if ( $this->menu[ $menuname ]['menutype'] === 'HORIZONTAL' )
			{
				$encasebefore = '<tr><td class="menu_horizontal" nowrap align="center"><p>';
				$encaseafter = '</p></td></tr>';
				$addbefore = '[ ';
				$addafter = ' ] | ';
				$cleanafter = '| ';
				$parsedsubmenuitems = NULL;
				$submenuvar = 'parsedsubmenuitems';
			}
			elseif ( $this->menu[ $menuname ]['menutype'] === 'VERTICAL' )
			{
				$encasebefore = '<ul class="menu_vertical_' . ( ( $level === 0 ) ? 'root' : 'sublevel' ) . ' menu_vertical_L' . $level . '">';
				$encaseafter = '</ul>';
				$addbefore = '<li>';
				$addafter = '</li>';
				$submenuvar = 'parsedmenuitems';
			}

			while ( $menuitem = array_shift($this->menu[ $menuname ]['menuitems'][ $menuindex ]) )
			{
				if ( empty($menuitem['seclevel']) || checkaccess($menuitem['seclevel']) )
				{
					$parsedmenuitems .= $addbefore . '<a href="' . $baseurl . $menuindex . '=' . $menuitem['url'] . '" class="' . ( ( isset($_GET[ $menuindex ]) && $_GET[ $menuindex ] === $menuitem['url'] ) ? 'curmenulink' : 'menulink' ) . '">' . $menuitem['name'] . '</a>' . ( ( !empty($menuitem['subitems']) ) ? ' <span class="sublevel_notice">(s)</span>' : NULL );

					if ( isset($this->menu[ $menuname ]['menuitems'][ $menuitem['url'] ]) && isset($_GET[ $menuindex ]) && $_GET[ $menuindex ] === $menuitem['url'] )
					{
						$$submenuvar .= $this->process_menu( $menuname, $menuitem['url'], ( $baseurl . $menuindex . '=' . $menuitem['url'] . '&' ), ( $level + 1 ) );
					}

					$parsedmenuitems .= $addafter;
				}
			}

			if ( $this->menu[ $menuname ]['menutype'] === 'HORIZONTAL' )
			{
				$parsedmenuitems = rtrim( $parsedmenuitems, $cleanafter );
				$parsedmenuitems = $encasebefore . $parsedmenuitems . $encaseafter . $parsedsubmenuitems;
			}
			elseif ( $this->menu[ $menuname ]['menutype'] === 'VERTICAL' )
			{
				$parsedmenuitems = $encasebefore . $parsedmenuitems . $encaseafter;
			}
		}

		return( $parsedmenuitems );
	}

	// incase a menutype has been decided by the template we need to retrieve that decision from the template
	function find_menus()
	{
		$menus = array_keys( $this->menu );
		for ( $i=0, $max=count($menus); $i < $max; $i++ )
		{
			if ( $this->menu[ $menus[ $i ] ]['menutype'] !== 'VERTICAL' && $this->menu[ $menus[ $i ] ]['menutype'] !== 'HORIZONTAL' )
			{
				$menuname = trim( $menus[ $i ], '{}' );
				$pattern = '@{' . $menuname . ';(VERTICAL|HORIZONTAL);}@';
				$matches = preg_match( $pattern, $this->template, $foundmenu );
				if ( $matches < 1 )
				{
					$this->displaymessage( '{TEXT_MISSING_MENUTYPE;' . $menus[ $i ] . ';}' );
					unset( $this->menu[ $menus[ $i ] ] );
				}
				$this->menu[ $menus[ $i ] ]['menutype'] = $foundmenu[1];
				$this->menu[ $menus[ $i ] ]['menuplaceholder'] = $foundmenu[0];
			}
		}
	}

	// process all the placeholders
	function process()
	{
		if ( defined("SLG_DEBUG") )
		{
			if ( is_null($this->tpl_time) )
			{
				$this->tpl_time = new timecount;
			}
			$this->tpl_time->starttimecount();
		}

		/* prepare the menu */
		if ( !empty($this->menu) )
		{
			$this->find_menus();

			$menus = array_keys( $this->menu );
			for ( $i=0, $max=count($menus); $i < $max; $i++ )
			{
				$this->insert_content( $this->menu[ $menus[ $i ] ]['menuplaceholder'], $this->process_menu( $menus[ $i ] ) );
			}
			$this->menu = array();
		}

		/* text_adv is only applicable to content type insertable data */
		if ( !empty($this->text_adv) )
		{
			foreach ( $this->text_adv as $key => $value )
			{
				$text_adv['@' . rtrim( $key, '}' ) . ';(.*?);}@'] = nl2br( $value );
			}
			$this->text_adv = array();
			$this->text_content = preg_replace( array_keys( $text_adv ), $text_adv, $this->text_content );
			unset( $text_adv );
		}

		if ( !empty($this->display) )
		{
			$this->template = preg_replace( array_keys( $this->display ), $this->display, $this->template );
			$this->display = array();
		}

		$this->text = array_map( 'nl2br', $this->text );

		if ( !empty($this->tooltips) )
		{
			$this->tooltips = array_map( 'htmlentities', $this->tooltips );
			$this->tooltips = array_map( 'prep_tooltip', $this->tooltips );
		}

		$this->text = $this->text_content + $this->tooltips + $this->text;

		$this->text_content = array( '{ERROR}' => NULL );
		$this->tooltips = array();

		$this->template = str_replace( array_keys( $this->text ), $this->text, $this->template );
		$this->text = array();

		if ( defined("SLG_DEBUG") )
		{
			$this->tpl_time->endtimecount();
		}
	}

	// I havn't seen proof but it seems some systems have problems which exceptionally large echo's. this function fixes that by performing the echo in smaller pieces
	function echobig( $bufferSize=8192 )
	{
		for ( $i=0, $chars=strlen($this->template), $loops=0; $i < $chars; $i+=$bufferSize, $loops++ )
		{
			echo substr( $this->template, $i, $bufferSize );
		}
		if ( defined("SLG_DEBUG") )
		{
			// Because this is the function that actually outputs the template, it can not be integrated into one. This means no multi language support. Not really necessary anyway since the echo below is only executed in DEBUG mode which should never be used in public sites.
			echo '<table border="0" align="center"><tr><td class="error"><p>DEBUG: echobig() required ' . $loops . ' loop(s) to output the data.</p></td></tr><tr><td class="error"><p>DEBUG: Processing time required for this part of the template was: ' . round( $this->tpl_time->tottime, 4 ) . 's.</p></td></tr></table><p></p>';
		}
	}

	// output the template to the client
	function output()
	{
		$this->echobig();
		//echo $this->template;
		$this->template = NULL;
	}
}

// This class contains all the functions and variables shared by TeamSpeak and Ventrilo
class commsdata
{
	var $server = array();
	var $rawdata = NULL;
	var $serverinfo = array();
	var $channels = array();
	var $clients = array();
	var $calc_tot = array();
	var $connecterror = false;
	var $usecached = false;
	var $stall_update = false;
	var $connection_limit = 25; // if changed, the values in the language files need to be adjusted aswell
	var $connect_time_limit = 30;
	var $time_now = NULL;

	// this function inserts the needed data into the class so that the class can process it.
	// $server should be an array containing the following items
/*
array(
	'res_id'   => id of the server. Only applicable for database enabled servers. Leave it to 0 for all others
	'res_data' => IP:PORT:OPTIONAL (OPTIONAL could be a password (Ventrilo) or queryport (TeamSpeak))
	'res_type' => ("TeamSpeak" or "Ventrilo" or "TSViewer.com")
	'ventsort' => ("alpha" or "manual")
)
*/
	// ventsort is optional and not required. Also the ventsort here is only used when SLG Comms is operating in NO_DATABASE mode.
	function insert_server( $server )
	{
		$tmp = explode( ':', $server['res_data'], 3 );
		$server['res_data'] = array();
		$server['res_data']['ip'] = ( ( !empty($tmp[0]) ) ? $tmp[0] : NULL );
		$server['res_data']['port'] = ( ( !empty($tmp[1]) ) ? $tmp[1] : NULL );
		$server['res_data']['optional'] = ( ( !empty($tmp[2]) ) ? $tmp[2] : ( ( $server['res_type'] === 'TeamSpeak' ) ? $GLOBALS['tssettings']['Default_queryport'] : NULL ) );
		unset( $tmp );

		// Let's check whether the selected server (custom or predefined) is acceptable (ip / hostname, port and the optional queryport) - minimum amount of data available. More elaborate format check will be performed on a later stage.
		if ( ( $server['res_type'] === 'TSViewer.com' && !is_numeric($server['res_data']['ip']) ) || ( $server['res_type'] !== 'TSViewer.com' && !check_ip_port($server['res_data']['ip'], $server['res_data']['port'], ( ( $server['res_type'] === 'TeamSpeak' ) ? $server['res_data']['optional'] : NULL ) ) ) )
		{
			early_error( '{TEXT_IP_PORT_COMB_ERROR}' );
		}

		$this->server = $server;

		$ptime = explode( ' ', microtime() );
		$this->time_now = $ptime[1];
	}

	// this function makes the decisions whether live or cached data should be loaded and takes the actions
	function collect_data()
	{
		$this->check_cache_status();

		if ( isset($this->server['con_attempts']) && $this->server['con_attempts'] >= $this->connection_limit )
		{
			$this->get_lasterror();
			if ( $this->server['refreshcache'] != 0 )
			{
				$this->connecterror = true;
				if ( !$this->loadcache() )
				{
					early_error( array( '{TEXT_SERVERUPDATE_DISABLED}<br /><br />{TEXT_LAST_ERROR}: ' . $this->server['last_error'], '{TEXT_LOADCACHE_FAILED}' ) );
				}
				$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_ERROR}: {TEXT_SERVERUPDATE_DISABLED}<br /><br />{TEXT_LAST_ERROR}: ' . $this->server['last_error'] );
				$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_CACHED_LOADED}' );
			}
			else
			{
				early_error( '{TEXT_SERVERUPDATE_DISABLED}<br /><br />{TEXT_LAST_ERROR}: ' . $this->server['last_error'] );
			}
		}
		elseif ( ( $this->server['refreshcache'] != 0 && ( $this->server['timestamp'] + $this->server['refreshcache'] ) > $this->time_now ) || $this->stall_update )
		{
			if ( !$this->loadcache() )
			{
				if ( $this->stall_update )
				{
					early_error( array( '{TEXT_RETRIEVALBUSY}', '{TEXT_LOADCACHE_FAILED}' ) );
				}
				else
				{
					early_error( '{TEXT_LOADCACHE_FAILED}' );
				}
			}
		}
		else
		{
			$this->get_livedata();
		}

		$this->process_rawdata();
	}	

	// this function retrieves information on the server incase the server is database enabled. It also claims the live server data update is necessary
	function check_cache_status()
	{
		if ( !defined("NO_DATABASE") && $this->server['res_id'] !== 0 )
		{
			$sql = '
SELECT 
  `timestamp`,
  `update_attempt`,
  `refreshcache`,
  `con_attempts`' . ( ( $this->server['res_type'] === 'Ventrilo' ) ? ',
  `ventsort`' : NULL ) . '
FROM
  `%1$s`
WHERE
  `res_id` = %2$u
LIMIT 0,1';

			$getserverinfo = $GLOBALS['db']->execquery( 'getserverinfo', $sql, array(
				$GLOBALS['table']['cache'],
				$this->server['res_id']
			) );

			if ( $GLOBALS['db']->numrows( $getserverinfo ) > 0 )
			{
				$this->server += $GLOBALS['db']->getrow( $getserverinfo );
			}
			else
			{
				$this->server += array(
					'timestamp' => 0,
					'update_attempt' => 0,
					'refreshcache' => 0,
					'con_attempts' => 0
				);

				$sql = '
INSERT INTO `%1$s`
( `res_id` )
VALUES
( %2$u )';

				$GLOBALS['db']->execquery( 'preparenewcacherecord', $sql, array(
					$GLOBALS['table']['cache'],
					$this->server['res_id']
				) );
			}

			$GLOBALS['db']->freeresult( 'getserverinfo', $getserverinfo );

			if ( isset($this->server['refreshcache']) )
			{
				if ( $this->server['refreshcache'] != 0 && ( $this->server['timestamp'] + $this->server['refreshcache'] ) <= $this->time_now && $this->server['con_attempts'] < $this->connection_limit )
				{
					if ( $this->server['update_attempt'] < ( $this->time_now - $this->connect_time_limit ) )
					{
						$sql = '
UPDATE `%1$s`
SET
  `update_attempt` = %2$u
WHERE
  `res_id` = %3$u AND
  `timestamp` = %4$u AND
  `update_attempt` = %5$u
LIMIT 1';

						$GLOBALS['db']->execquery( 'claimupdate', $sql, array(
							$GLOBALS['table']['cache'],
							$this->time_now,
							$this->server['res_id'],
							$this->server['timestamp'],
							$this->server['update_attempt']
						) );
						if ( $GLOBALS['db']->affected_rows() === 0 )
						{
							$this->stall_update = true;
						}
					}
					else
					{
						$this->stall_update = true;
					}
				}
			}
			else
			{
				$this->server['refreshcache'] = 0;
			}
		}
		else
		{
			$this->server['refreshcache'] = 0;
		}
	}

	// this function saves the live server data which was just retrieved
	function savecache()
	{
		$sql = '
UPDATE `%1$s`
SET
  `data` = "%2$s",
  `timestamp` = %3$u,
  `update_attempt` = 0,
  `con_attempts` = 0,
  `last_error` = NULL
WHERE
  `res_id` = %4$u
LIMIT 1';

		/*compress and store the data*/
		$GLOBALS['db']->execquery( 'updatecachedata', $sql, array(
			$GLOBALS['table']['cache'],
			$GLOBALS['db']->escape_string( gzcompress( ( ( is_array($this->rawdata) ) ? implode( "\r\n", $this->rawdata ) : $this->rawdata ), 1 ) ),
			$this->time_now,
			$this->server['res_id']
		) );
	}

	// this function loads cached server data if needed. returns false on failure and true on success
	function loadcache()
	{
		if ( $this->server['timestamp'] != 0 )
		{
			$sql = '
SELECT
  `data`
FROM
  `%1$s`
WHERE
  `res_id` = %2$u
LIMIT 0,1';
			$cache = $GLOBALS['db']->execquery( 'getcache', $sql, array(
				$GLOBALS['table']['cache'],
				$this->server['res_id']
			) );

			$this->rawdata = $GLOBALS['db']->getrow( $cache );

			$GLOBALS['db']->freeresult( 'getcache', $cache );

			/*uncompress here -  TODO still needs a check whether the data is actually compressed data before trying to uncompress*/
			$this->rawdata['data'] = gzuncompress( $this->rawdata['data'] );

			if ( !empty($this->rawdata['data']) )
			{
				if ( $this->server['res_type'] !== 'TSViewer.com' )
				{
					$this->rawdata = explode( "\r\n", trim( $this->rawdata['data'] ) );
				}
				else
				{
					$this->rawdata = trim( $this->rawdata['data'] );
				}

				$this->usecached = true;

				// register a cache hit if the cache hits setting is enabled
				if ( $GLOBALS['tssettings']['Cache_hits'] )
				{
					$sql = '
UPDATE `%1$s`
SET
  `cachehits` = (`cachehits`+1)
WHERE 
  `res_id` = %2$u
LIMIT 1';
					$GLOBALS['db']->execquery( 'updatecachehits', $sql, array(
						$GLOBALS['table']['cache'],
						$this->server['res_id']
					) );
				}
			}

			return( $this->usecached );
		}
		else
		{
			return( false );
		}
	}

	// this function displays the retrieved data status
	function print_check_cache_lifetime()
	{
		if ( $this->usecached )
		{
			$cachelive = '{TEXT_CACHEDDATA}' . ( ( $this->stall_update ) ? ' {TEXT_UPDATEINPROGRESS}' : NULL ) . '<br />';
			if ( $this->connecterror || $this->stall_update )
			{
				$cachelive .= '{TEXT_CACHEOLD}: ' . formattime( $this->time_now - $this->server['timestamp'] );
			}
			else
			{
				$cachelive .= '{TEXT_DATAREFRESHIN}: ' . formattime( $this->server['timestamp'] + $this->server['refreshcache'] - $this->time_now );
			}
		}
		else
		{
			$cachelive = '{TEXT_LIVEDATA}<br />';
			if ( $this->server['res_id'] === 0 )
			{
				$cachelive .= '{TEXT_NOCUSTOMCACHE}';
			}
			elseif ( $this->server['refreshcache'] != 0 )
			{
				$cachelive .= '{TEXT_DATAREFRESHED}: ' . formattime( $this->server['refreshcache'] );
			}
			else
			{
				$cachelive .= '{TEXT_CACHEDISABLED}';
			}
		}
		return( $cachelive );
	}

	// if an error was encountered during live server data retrieval this function logs the error
	function register_error( $error )
	{
		if ( !defined("NO_DATABASE") && isset($this->server['con_attempts']) )
		{
			$sql = '
UPDATE `%1$s`
SET
  `con_attempts` = (`con_attempts`+1),
  `last_error` = "%2$s"
WHERE
  `res_id` = %3$u
LIMIT 1';

			$GLOBALS['db']->execquery( 'registererror', $sql, array(
				$GLOBALS['table']['cache'],
				$GLOBALS['db']->escape_string( $error ),
				$GLOBALS['db']->escape_string( $this->server['res_id'] )
			) );
		}
	}

	// this function retrieves the last_error that was logged in the database
	function get_lasterror()
	{
		if ( !defined("NO_DATABASE") )
			$sql = '
SELECT
  `last_error`
FROM
  `%1$s`
WHERE
  `res_id` = %2$u
LIMIT 0,1';
		$lasterror = $GLOBALS['db']->execquery( 'getlasterror', $sql, array(
			$GLOBALS['table']['cache'],
			$this->server['res_id']
		) );

		$this->server += $GLOBALS['db']->getrow( $lasterror );

		$GLOBALS['db']->freeresult( 'getlasterror', $lasterror );
	}
}

// This class contains all the TeamSpeak specific functions
class ts_commsdata extends commsdata
{
	// this function manages TeamSpeak live server data retrieval (has Ventrilo counterpart with the same name)
	function get_livedata()
	{
/*		$cmd = "use port=" . $this->server['res_data']['port'] . "
channellist
clientlist
hostinfo
serverinfo
quit
";*/

		$cmd = "sel " . $this->server['res_data']['port'] . "
cl
pl
gi
si
quit
";

		$errno = NULL;
		$errstr = NULL;

		if ( defined("SLG_DEBUG") )
		{
			$cnt_time = new timecount;
			$cnt_time->starttimecount();
		}

		ob_start();

		$connection = fsockopen( 'tcp://' . $this->server['res_data']['ip'], $this->server['res_data']['optional'], $errno, $errstr, 2 );

		if ( !is_resource($connection) && empty($errno) && empty($errstr) )
		{
			$errstr = trim( str_ireplace( array( '<br />', '<br>' ), '', ob_get_contents() ) );
			$errno = 'hidden';
		}
		ob_end_clean();

		if ( defined("SLG_DEBUG") )
		{
			$cnt_time->endtimecount();
		}

		if( !is_resource($connection) )
		{
			if ( $errno === 'hidden' )
			{
				$pre_error = $errstr;
			}
			else
			{
				$pre_error = trim( $errstr ) . ( ( !empty($errno) ) ? ' (' . $errno . ').' : NULL );
			}

			$this->register_error( $pre_error );

			if ( $this->server['refreshcache'] != 0 )
			{
				if ( !$this->loadcache() )
				{
					early_error( array( $pre_error, '{TEXT_LOADCACHE_FAILED}' ) );
				}

				$this->connecterror = true;
				$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_ERROR}: ' . $pre_error );
				$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_CACHED_LOADED}' );
			}
			else
			{
				early_error( $pre_error );
			}
		}
		else
		{
			unset( $errno, $errstr );
			
			if ( defined("SLG_DEBUG") )
			{
				$cnt_time = new timecount;
				$cnt_time->starttimecount();
			}

			if ( function_exists('stream_set_timeout') )
			{
				stream_set_timeout ( $connection, 20 );
			}

			fwrite( $connection, $cmd, strlen($cmd) );
			while ( $serverdata = fgets($connection, 4096) )
			{
				 $this->rawdata .= $serverdata;
			}
			if ( function_exists('stream_get_meta_data') )
			{
				$status = stream_get_meta_data( $connection );
			}
			fclose( $connection );

			if ( defined("SLG_DEBUG") )
			{
				$cnt_time->endtimecount();
			}

			if ( isset($status) && $status['timed_out'] )
			{
				early_error( '{TEXT_STREAM_TIMEOUT}' );
			}

			$this->rawdata = explode( "\r\n", trim( $this->rawdata ) );
		}

		if ( defined("SLG_DEBUG") )
		{
			// Because this is DEBUG only output it will be outputted outside of the template class.
			echo '<table border="0" align="center"><tr><td class="error"><p>DEBUG: Processing time required for retrieving the live server data: ' . round( $cnt_time->tottime, 4 ) . 's.</p></td></tr></table><p></p>';
		}

		if( !isset($this->rawdata[0]) || $this->rawdata[0] !== '[TS]' )
		{
			$this->register_error( $this->rawdata[0] );
			early_error( '{TEXT_NOTTEAMSPEAK}' );
		}

		if( !isset($this->rawdata[1]) || strncmp($this->rawdata[1], 'ERROR', 5) === 0 )
		{
			$this->register_error( $this->rawdata[1] );
			early_error( array( '{TEXT_TS_COMMAND_ERROR;select;}', '"' . $this->rawdata[1] . '"' . ( ( strcasecmp( $this->rawdata[1], 'ERROR, invalid id' ) === 0 ) ? '
{TEXT_TSINVALIDID_ERROR}' : NULL ) ) );
		}

		if ( $this->server['refreshcache'] != 0 && !$this->connecterror )
		{
			$this->savecache();
		}
	}

	// this function processes the raw TeamSpeak server data so that SLG Comms has access to nicely sorted and processed data. (has an Ventrilo counterpart with the same name)
	function process_rawdata()
	{
		if ( is_null($this->rawdata) )
		{
			early_error( '{TEXT_RAWDATA_UNAVAILABLE}' );
		}

		$type = array( 'channels', 'clients', 'gserver', 'vserver' );

		// used to decide on the array index line
		$counter = 0;

		// counts the Server Admins
		$this->calc_tot['SA'] = 0;

		// rechecking the first 2 array keys incase somebody has been messing with the cached data.
		if( !isset($this->rawdata[0]) || $this->rawdata[0] !== '[TS]' )
		{
			early_error( '{TEXT_NOTTEAMSPEAK}' );
		}
		
		if( !isset($this->rawdata[1]) || strncmp($this->rawdata[1], 'ERROR', 5) === 0 )
		{
			early_error( array( '{TEXT_TS_COMMAND_ERROR;select;}', '"' . $this->rawdata[1] . '"' . ( ( strcasecmp( $this->rawdata[1], 'ERROR, invalid id' ) === 0 ) ? '
{TEXT_TSINVALIDID_ERROR}' : NULL ) ) );
		}

		unset( $this->rawdata[0], $this->rawdata[1] );

		while( $rawline = array_shift($this->rawdata) )
		{
			$rawline = trim( $rawline );
			if ( $rawline === 'OK' )
			{
				$counter = 0;
				next( $type );
			}
			elseif( strncmp($rawline, 'ERROR', 5) === 0 )
			{
				early_error( array( '{TEXT_TS_COMMAND_ERROR;' . current( $type ) . ';}', '"' . $rawline . '"' ) );
			}
			else
			{
				switch ( current($type) )
				{
					case 'channels':
						if ( $counter === 0 )
						{
							$channelindex = explode( "\t", $rawline );
							$counter = count( $channelindex );
						}
						else
						{
							$channeldata = $this->quote_explode( "\t", $rawline, '"', $counter ); // normal explode wouldn't suffice because of the possibility of tabs within quoted namespaces of the "name" and "topic" columns

							$channel = array_combine( $channelindex, $channeldata );

							$channel['flags'] = ( ($channel['parent'] == -1) ? $this->breakdown_rights( $channel['flags'], 5 ) : NULL );
							$channel['name'] = substr( $channel['name'], 1, strlen( $channel['name'] ) - 2);
							$channel['topic'] = substr( $channel['topic'], 1, strlen( $channel['topic'] ) - 2);
							$channel['slg_sortname'] = $this->prepare_sort_name( $channel['name'] );

							$this->channels[ $channel['parent'] ][ $channel['id'] ] = $channel;
						}
						//$channels[] = explode( "\t", $this->rawdata[ $i ] );
						break;

					case 'clients':
						if ($counter === 0)
						{
							$clientindex = explode( "\t", $rawline );
							$counter = count( $clientindex );
						}
						else
						{
							$clientdata = $this->quote_explode( "\t", $rawline, '"', $counter ); // normal explode wouldn't suffice because of the possibility of tabs within quoted namespaces of the "nick" and "loginname" columns

							$client = array_combine( $clientindex, $clientdata );

							$client['pprivs'] = $this->breakdown_rights( $client['pprivs'], 5 );
							$client['cprivs'] = $this->breakdown_rights( $client['cprivs'], 5 );
							$client['pflags'] = $this->breakdown_rights( $client['pflags'], 7 );
							$client['nick'] = substr( $client['nick'], 1, strlen( $client['nick'] ) - 2);
//							$client['loginname'] = substr( $client['loginname'], 1, strlen( $client['loginname'] ) - 2); // not used so this line is disabled for performance purposes
							$client['slg_sortname'] = $this->prepare_sort_name( $client['nick'] );

							if ( $client['pprivs'][1] )
							{
								$this->calc_tot['SA']++;
							}

							$this->clients[ $client['c_id'] ][ $client['p_id'] ] = $client;
						}
						//$clients[] = explode( "\t", $this->rawdata[ $i ] );
						break;

					case 'gserver':
						$arrtmp = explode( '=', $rawline, 2 );
						$this->serverinfo[ $arrtmp[0] ] = $arrtmp[1];
						//$gserver[] = explode( "\t", $this->rawdata[ $i ] );
						break;

					case 'vserver':
						$arrtmp = explode( '=', $rawline, 2 );
						$this->serverinfo[ $arrtmp[0] ] = $arrtmp[1];
						//$vserver[] = explode( "\t", $this->rawdata[ $i ] );
						break;

					default:
						early_error( '{TEXT_DATA_TYPE_ERROR;' . current( $type ) . ';}' );
				}
			}
		}

		$this->rawdata = NULL;
	}

	// this function splits the TeamSpeak server data strings. As TeamSpeak has problems with the server data design it was needed to use more then one simple explode()
	// $encapsulator is the sign used for quoting.
	// $maxkeys is provided to make sure that one way or the other we get the right amount of values in the array
	function quote_explode( $separator, $string, $encapsulator, $maxkeys )
	{
		$string = explode( $separator, $string );

		$count_keys = count( $string );
		if ( $count_keys !== $maxkeys )
		{
			$foundone = false;
			$fix_keys = false;
			$count_removed = 0;
			for ( $i=0; $i < $count_keys; $i++ )
			{
				if ( $foundone === false && $string[ $i ]{0} === $encapsulator && $string[ $i ]{ ( strlen($string[ $i ]) - 1 ) } !== $encapsulator )
				{
					$foundone = $i;
				}
				elseif ( $foundone !== false )
				{
					if ( $string[ $i ]{ ( strlen($string[ $i ]) - 1 ) } === $encapsulator )
					{
						$string[ $foundone ] .= $string[ $i ];
						unset( $string[ $i ] );
						$count_removed++;
						$foundone = false;
					}
					else
					{
						$string[ $foundone ] .= $string[ $i ];
						unset( $string[ $i ] );
						$count_removed++;
					}
				}
			}
			$string = array_values( $string );

			$count_keys = count( $string );
			if ( $count_keys !== $maxkeys )
			{
				if ( $count_keys > $maxkeys )
				{
					$string[] = implode( '', array_splice( $string, ( $maxkeys - 1 ) ) );
				}
				elseif ( $count_keys < $maxkeys )
				{
					$string = array_pad( $string, $maxkeys, 0 );
				}
				$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_TS_RAWDATA_FLAW}' );
			}
		}

		return( $string );
	}

	// this function breaks down the rights of channels and clients so it's easily checkable whether somebody has certain rights
	// $rights is the bits number in the rawdata
	// $max allows you to limit the amount of times the for loop will be run since not all the rights even have 32 and 64 as a valid value
	function breakdown_rights( $rights, $max=7 )
	{
		$arrrights = array();
		$tmparr = array( 1, 2, 4, 8, 16, 32, 64 );
		for ( $i=0; $i < $max; $i++ )
		{
			$arrrights[ $tmparr[ $i ] ] = ( ( (int) $rights & $tmparr[ $i ] ) === $tmparr[ $i ] );
		}

		return( $arrrights );
	}

	// this function prepares a name for channels and clients which can be used to sort the channels and clients properly
	function prepare_sort_name( $name )
	{
		return( preg_replace( '/[^a-z0-9]/i', '', $name ) );
	}

	// client flags
	function pl_flags( $pl_flags, $ch_flags )
	{
		if( $pl_flags[4] )
		{
			$output = 'R';
		}
		else
		{
			$output = 'U';
		}

		if( $pl_flags[1] )
		{
			$output .= ' SA';
		}

		$tmparr_no = array( 1, 8, 16, 2, 4 );
		$tmparr_text = array( 'CA', 'AO', 'AV', 'O', 'V' );
		for ( $i=0, $max=5; $i < $max; $i++ )
		{
			if( $ch_flags[ $tmparr_no[ $i ] ] )
			{
				$output .= ' ' . $tmparr_text[ $i ];
			}
		}

		return( $output );
	}

	// client image
	function pl_img( $pflags )
	{
		/*
		IGNORED LIST - No special picture used by TeamSpeak
		2 - Voice Request
		4 - Doesnt Accept Whispers
		64 - Recording
		*/
		$tmparr = array( 8, 32, 16, 1 );
		for ( $i=0, $max=4; $i < $max; $i++ )
		{
			if( $pflags[ $tmparr[ $i ] ] )
			{
				return( $tmparr[ $i ] );
			}
		}

		return( 0 ); // in case no return was executed above
	}

	// client status
	function pl_status( $pflags )
	{
		$tmparr = array( 8, 1, 4, 16, 64, 32, 2 );
		$output = NULL;
		for ( $i=0, $max=7; $i < $max; $i++ )
		{
			if( $pflags[ $tmparr[ $i ] ] )
			{
				$output .= ( ( !empty($output) ) ? '<br />' : NULL ) . '{TEXT_PL_STATUS_' . $tmparr[ $i ] . '}';
			}
		}

		return( $output );
	}

	//Channel Flags
	function ch_flags( $chflags )
	{
		if( $chflags[1] )
		{
			$output = 'U';
		}
		else
		{
			$output = 'R';
		}

		$tmparr_no = array( 2, 4, 8, 16 );
		$tmparr_text = array( 'M', 'P', 'S', 'D' );
		for ( $i=0, $max=4; $i < $max; $i++ )
		{
			if( $chflags[ $tmparr_no[ $i ] ] )
			{
				$output .= $tmparr_text[ $i ];
			}
		}

		return( $output );
	}

	// return the proper codec name with the id provided
	function formatcodec( $codec )
	{
		switch ( $codec )
		{
			case 0: return( 'CELP 5.2 Kbit' );
			case 1: return( 'CELP 6.3 Kbit' );
			case 2: return( 'GSM 14.8 Kbit' );
			case 3: return( 'GSM 16.4 Kbit' );
			case 4: return( 'Windows CELP 5.2 Kbit' );
			case 5: return( 'Speex 3.4 Kbit' );
			case 6: return( 'Speex 5.2 Kbit' );
			case 7: return( 'Speex 7.2 Kbit' );
			case 8: return( 'Speex 9.3 Kbit' );
			case 9: return( 'Speex 12.3 Kbit' );
			case 10: return( 'Speex 16.3 Kbit' );
			case 11: return( 'Speex 19.5 Kbit' );
			case 12: return( 'Speex 25.9 Kbit' );
			default: return( '{TEXT_UNKNOWN_CODEC}' );
		}
	}

	// output ts channels - recursive function
	function output_ts_channels( $channel_id=-1, $is_subchannel=false )
	{
		usort( $this->channels[ $channel_id ], "SORT_CHANNELS" );
		reset( $this->channels[ $channel_id ] );

		$content = NULL;
		while( $channel = array_shift($this->channels[ $channel_id ]) )
		{
			if ( (bool) !$is_subchannel )
			{
				$chflags = $this->ch_flags( $channel['flags'] );
			}
			$div_content = '<table class=\'tooltip\' cellspacing=\'1\' cellpadding=\'0\'>
<tr><td nowrap valign=\'top\'>{TEXT_CHANNEL}:&nbsp;</td><td>' . htmlentities( $channel['name'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_TOPIC}:&nbsp;</td><td>' . htmlentities( $channel['topic'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PASSWORD_PROT}:&nbsp;</td><td>' . ( ( $channel['password'] ) ? '{TEXT_YES}' : '{TEXT_NO}' ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_CODEC}:&nbsp;</td><td>' . htmlentities( $this->formatcodec( $channel['codec'] ) ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_MAXCLIENTS}:&nbsp;</td><td>' . htmlentities( $channel['maxusers'] ) . '</td></tr>';
			if ( (bool) !$is_subchannel )
			{
				$div_content .= '
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_EXPLAIN_TSFLAGS_CHANNEL}:&nbsp;</td><td>' . $chflags . '</td></tr>';
				$exp_flags = explode( "\n", trim( chunk_split( $chflags, 1, "\n" ) ) );
				for( $i=0, $max=count($exp_flags); $i < $max ;$i++ )
				{
					$div_content .= '
<tr><td>&nbsp;</td><td>' . $exp_flags[ $i ] . ':&nbsp;{TEXT_EXPLAIN_TSFLAG_' . $exp_flags[ $i ] . '}</td></tr>';
				}
			}
			$div_content .= '</table>';

			$div_content = prep_tooltip( removechars( $div_content, array( "\r", "\n" ) ) );

			$content .= '
	<tr class="channel_row">
		<td nowrap onMouseOver="toolTip(\'' . $div_content . '\')" onMouseOut="toolTip()"><p>
			<img src="{BASE_URL}images/spacer.gif" width="' . ( ( (bool) $is_subchannel ) ? 32 : 16 ) . '" height="16" border="0" align="absmiddle" alt="" /><img width="16" height="16" src="{BASE_URL}templates/{TEMPLATE}/images/teamspeak/bullet_' . ( ( $channel['password'] ) ? 'p' : 'n' ) . 'channel.gif" align="absmiddle" alt="{TEXT_CHANNEL}" border="0" />&nbsp;' . htmlentities( $channel['name'] ) . ( ( (bool) !$is_subchannel ) ? '&nbsp;&nbsp;&nbsp;(' . $chflags . ')' : NULL ) . '
		</p></td>' . ( ( $GLOBALS['tssettings']['Display_ping'] ) ? '<td nowrap align="right"><p>&nbsp;</p></td>' : NULL ) . '
	</tr>';

			//Sub-Channel Data
			if ( $is_subchannel === false && isset($this->channels[ $channel['id'] ]) ) // incase TeamSpeak ever gets the ability to support subchannels within subchannels, most likely removing the check on "$is_subchannel" might go a long way in implementing the feature :) . ea. the indentations would still be wrong so thats one of the things that would need fixing but that would be easy aswell since we'll just change the usage of "$is_subchannel" to a similar way like the "$level" variable of the Ventrilo version of this function.
			{
				$content .= $this->output_ts_channels( $channel['id'], true );
			}

			if ( isset($this->clients[ $channel['id'] ]) )
			{
				$content .= $this->output_ts_clients( $channel['id'], $is_subchannel );
			} 
		}

		return( $content );
	}

	// output ts clients - recursive function
	function output_ts_clients( $channel_id, $in_subchannel )
	{
		usort( $this->clients[ $channel_id ], "SORT_CLIENTS" );
		reset( $this->clients[ $channel_id ] );

		$content = NULL;
		while( $client = array_shift($this->clients[ $channel_id ]) )
		{
			$plflags = $this->pl_flags( $client['pprivs'], $client['cprivs'] );
			$div_content = '<table class=\'tooltip\' cellspacing=\'1\' cellpadding=\'0\'>
<tr><td nowrap valign=\'top\'>{TEXT_CLIENT}:&nbsp;</td><td>' . htmlentities( $client['nick'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_IDLEFOR}:&nbsp;</td><td>' . htmlentities( formattime( $client['idletime'] ) ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_LOGGEDINFOR}:&nbsp;</td><td>' . htmlentities( formattime( $client['logintime'] ) ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PACKETLOSS}:&nbsp;</td><td>' . htmlentities( $client['pl'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PING}:&nbsp;</td><td>' . htmlentities( $client['ping'] ) . '{TEXT_MILLISECONDS}</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_STICKY}:&nbsp;</td><td>' . ( ( $client['pprivs'][16] ) ? '{TEXT_YES}' : '{TEXT_NO}' ) . '</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_EXPLAIN_TSFLAGS_CLIENT}:&nbsp;</td><td>' . $plflags . '</td></tr>';
			$exp_flags = explode( ' ', $plflags );
			for( $i=0, $max=count($exp_flags); $i < $max ; $i++ )
			{
				$div_content .= '
<tr><td>&nbsp;</td><td>' . $exp_flags[ $i ] . ':&nbsp;{TEXT_EXPLAIN_TSFLAG_' . $exp_flags[ $i ] . '}</td></tr>';
			}
			$plstatus = $this->pl_status( $client['pflags'] );
			if ( !is_null($plstatus) || $client['pprivs'][2] )
			{
				$div_content .= '
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_EXPLAIN_TSSTATUS_CLIENT}:&nbsp;</td><td>' . $plstatus . ( ( $client['pprivs'][2] ) ? ( ( !is_null($plstatus) ) ? '<br />' : NULL ) . '{TEXT_PL_RIGHT_2}' : NULL ) . '</td></tr>';
			}
			$div_content .= '</table>';

			$div_content = prep_tooltip( removechars( $div_content, array( "\r", "\n" ) ) );

			$content .= '
	<tr class="client_row">
		<td nowrap onMouseOver="toolTip(\'' . $div_content . '\')" onMouseOut="toolTip()"><p>
			<img src="{BASE_URL}images/spacer.gif" width="' . ( ( (bool) $in_subchannel ) ? 48 : 32 ) . '" height="16" border="0" align="absmiddle" alt="" /><img width="16" height="16" src="{BASE_URL}templates/{TEMPLATE}/images/teamspeak/bullet_' . $this->pl_img( $client['pflags'] ) . '.gif" align="absmiddle" alt="{TEXT_CLIENT}" border="0" />&nbsp;' . htmlentities( $client['nick'] ) . '&nbsp;&nbsp;&nbsp;(' . $plflags . ( ( $client['pflags'][64] ) ? ' Rec' : NULL ) . ')' . ( ( $client['pflags'][2] ) ? ' WV' : NULL ) . '
		</p></td>' . ( ( $GLOBALS['tssettings']['Display_ping'] ) ? '<td nowrap align="right"><p>' . htmlentities( $client['ping'] ) . '{TEXT_MILLISECONDS}</p></td>' : NULL ) . '
	</tr>';
		}
		return( $content );
	}
}

class vent_commsdata extends commsdata
{
	// this function manages Ventrilo live server data retrieval (has TeamSpeak counterpart with the same name)
	function get_livedata()
	{
		if ( empty($GLOBALS['tssettings']['Ventrilo_status_program']) )
		{
			early_error( '{TEXT_NOVENTRILO}' );
		}

		$program = realpath( $GLOBALS['tssettings']['Root_path'] . $GLOBALS['tssettings']['Ventrilo_status_program'] );

		if ( $program === false )
		{
			early_error( '{TEXT_DEFINED_VENT_PROG_INVALID}' );
		}

		if ( !compare_dir_string( $program, $GLOBALS['tssettings']['Root_path'] ) )
		{
			early_error( '{TEXT_VENTRILO_NOT_IN_SLG_DIR}' );
		}

		$ipstring = escapeshellarg( $program ) . ' -a2 -c2 -t' . escapeshellcmd( removechars( $this->server['res_data']['ip'] . ':' . $this->server['res_data']['port'] . ( ( isset( $this->server['res_data']['optional'] ) ) ? ':' . $this->server['res_data']['optional'] : NULL ), ' ' ) ) .' 2>&1';

		$execcmd = NULL;

		if ( defined("SLG_DEBUG") )
		{
			$cnt_time = new timecount;
			$cnt_time->starttimecount();
		}

		ob_start();

		exec( $ipstring, $this->rawdata, $execcmd );

		if ( empty($this->rawdata) )
		{
			$this->rawdata[0] = trim( str_ireplace( array( '<br />', '<br>' ), '', ob_get_contents() ) );
			if ( strlen( trim( $this->rawdata[0] ) ) === 0 )
			{
				early_error( '{TEXT_UNKNOWN_EXEC_ERROR}' );
			}
			$execcmd = 'hidden';
		}
		ob_end_clean();

		if ( defined("SLG_DEBUG") )
		{
			$cnt_time->endtimecount();
			// Because this is DEBUG only output it will be outputted outside of the template class.
			echo '<table border="0" align="center"><tr><td class="error"><p>DEBUG: Processing time required for retrieving the live server data: ' . round( $cnt_time->tottime, 4 ) . 's.</p></td></tr></table><p></p>';
		}

		if ( ( isset($execcmd) && $execcmd !== 0 ) || !isset($this->rawdata[0]) || strncmp($this->rawdata[0], "ERROR", 5) === 0 ) // ($execcmd) 0 = execution went OK. Be carefull though that there could still be an error message in the output. Also when $execcmd is filled with value 1 its possible safe mode is activated but since all of the return codes are not documented, and because of that unreliable across different platforms this will not be used.
		{
			if ( $execcmd === 'hidden' )
			{
				$pre_error = $this->rawdata[0];
			}
			else
			{
				$pre_error = trim( implode( ' ', $this->rawdata ) ) . ' (' . $execcmd . ')';
			}

			if ( strncmp($pre_error, "ERROR", 5) === 0 )
			{
				$pre_error = ltrim( substr( $pre_error, 5 ), ': ' );
			}

			$this->register_error( $pre_error );

			if ( $this->server['refreshcache'] != 0 )
			{
				if ( !$this->loadcache() )
				{
					early_error( array( $pre_error, '{TEXT_LOADCACHE_FAILED}' ) );
				}

				$this->connecterror = true;
				$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_ERROR}: ' . $pre_error );
				$GLOBALS[ $GLOBALS['template'] ]->displaymessage( '{TEXT_CACHED_LOADED}' );
			}
			else
			{
				early_error( $pre_error );
			}
		}
		elseif ( $this->server['refreshcache'] != 0 && !$this->connecterror )
		{
			$this->savecache();
		}
	}

	// this function processes the raw Ventrilo server data so that SLG Comms has access to nicely sorted and processed data. (has an TeamSpeak counterpart with the same name)
	function process_rawdata()
	{
		if ( is_null($this->rawdata) )
		{
			early_error( '{TEXT_RAWDATA_UNAVAILABLE}' );
		}

		// counts Admins, total clients and total channels. The last 2 are used to detect disabled data
		$this->calc_tot['ADMIN'] = 0;
		$this->calc_tot['TOT_CLIENTS'] = 0;
		$this->calc_tot['TOT_CHANNELS'] = 0;

		while( $rawline1 = array_shift($this->rawdata) )
		{
			$rawline1 = explode( ':', $rawline1, 2 );
			$rawline1[0] = trim( $rawline1[0] );
			$rawline1[1] = trim( $rawline1[1] );

			if ( trim($rawline1[0]) === 'CHANNEL' || trim($rawline1[0]) === 'CLIENT' )
			{
				$rawline1[1] = explode( ',', $rawline1[1] );
				for( $k=0, $kmax=count($rawline1[1]); $k < $kmax; $k++ )
				{
					$rawline1[1][ $k ] = explode( '=', $rawline1[1][ $k ], 2 );
					$rawline1[1][ $k ][0] = rawurldecode( trim( $rawline1[1][ $k ][0] ) );
					$rawline1[1][ $k ][1] = rawurldecode( trim( $rawline1[1][ $k ][1] ) );
					$ventdata[ $rawline1[1][ $k ][0] ] = $rawline1[1][ $k ][1];
				}
				if ( trim($rawline1[0]) === 'CHANNEL' )
				{
					$this->channels[ $ventdata['PID'] ][ $ventdata['CID'] ] = $ventdata;
					$this->calc_tot['TOT_CHANNELS']++;
				}
				elseif ( trim($rawline1[0]) === 'CLIENT' )
				{
					if ( $ventdata['ADMIN'] )
					{
						$this->calc_tot['ADMIN']++;
					}
					if ( !isset($this->serverinfo['CHANNELCOUNT']) || $this->serverinfo['CHANNELCOUNT'] != $this->calc_tot['TOT_CHANNELS'] )
					{
						$ventdata['CID'] = 0;
					}

					$this->clients[ $ventdata['CID'] ][] = $ventdata;
					$this->calc_tot['TOT_CLIENTS']++;
				}
			}

			elseif ( trim($rawline1[0]) === 'CHANNELFIELDS' )
			{
				continue;
//				$ventchannelindex = explode( ", ", $ext_line1[1] );
			}

			elseif ( trim($rawline1[0]) === 'CLIENTFIELDS' )
			{
				continue;
//				$ventclientindex = explode( ", ", $rawline1[1] );
			}
			else
			{
				if ( trim($rawline1[0]) === 'VOICECODEC' || trim($rawline1[0]) === 'VOICEFORMAT' )
				{
					$tmp = explode( ',', $rawline1[1], 2 );
					$rawline1[1] = array(
						'ID'   => $tmp[0],
						'NAME' => rawurldecode( $tmp[1] )
					);
				}
				else
				{
					$rawline1[1] = rawurldecode( $rawline1[1] );
				}
				$this->serverinfo[ rawurldecode( $rawline1[0] ) ] = $rawline1[1];
			}
		}
		$this->rawdata = NULL;
	}

	// output vent channels - recursive function
	function output_vent_channels( $pid=0, $level=0 )
	{
		if ( !isset($this->server['ventsort']) || $this->server['ventsort'] === 'alpha' )
		{
			usort( $this->channels[ $pid ], "SORT_VENTCHANCLI" );
		}

		$content = NULL;
		reset( $this->channels[ $pid ] );
		while( $channel = array_shift($this->channels[ $pid ]) )
		{
			$div_content = '<table class=\'tooltip\' cellspacing=\'1\' cellpadding=\'0\'>
<tr><td nowrap valign=\'top\'>{TEXT_CHANNEL}:&nbsp;</td><td>' . htmlentities( $channel['NAME'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PASSWORD_PROT}:&nbsp;</td><td>' . ( ( $channel['PROT'] == 1 ) ? '{TEXT_YES}' : ( ( $channel['PROT'] == 2 ) ? '{TEXT_USERAUTH}' : '{TEXT_NO}' ) ) . '</td></tr>' . ( ( !empty( $channel['COMM'] ) ) ? '
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_COMMENT}:&nbsp;</td><td>' . htmlentities( $channel['COMM'] ) . '</td></tr>' : NULL ) . '</table>';

			$div_content = prep_tooltip( removechars( $div_content, array( "\r", "\n" ) ) );

			$content .= '
	<tr class="channel_row">
		<td nowrap onMouseOver="toolTip(\'' . $div_content . '\')" onMouseOut="toolTip()"><p>
			' . ( ( $level !== 0 ) ? '<img src="{BASE_URL}images/spacer.gif" width="' . ( $level * 16 ) . '" height="16" border="0" align="absmiddle" alt="" />' : NULL ) . '<img width="16" height="16" src="{BASE_URL}templates/{TEMPLATE}/images/ventrilo/' . ( ( $channel['PROT'] == 1 ) ? 'p' : ( ( $channel['PROT'] == 2 ) ? 'a' : 'n' ) ) . 'channel' . ( ( isset( $this->clients[ $channel['CID'] ] ) || isset($this->channels[ $channel['CID'] ]) ) ? '-ext' : NULL ) . '.gif" align="absmiddle" alt="{TEXT_CHANNEL}" border="0" />&nbsp;' . htmlentities( $channel['NAME'] ) . ( ( !empty( $channel['COMM'] ) ) ? '&nbsp;&nbsp;&nbsp;(<span class="ventcomment">' . htmlentities( linewrap( $channel['COMM'], 30 ) ) . '</span>)' : NULL ) . '
		</p></td>' . ( ( $GLOBALS['tssettings']['Display_ping'] ) ? '<td nowrap align="right"><p>&nbsp;</p></td>' : NULL ) . '
	</tr>';
 
			//Clientdata...
			if ( isset($this->clients[ $channel['CID'] ]) )
			{
				$content .= $this->output_vent_clients( $channel['CID'], ( $level + 1 ) );
			}
			if ( isset($this->channels[ $channel['CID'] ]) )
			{
				$content .= $this->output_vent_channels( $channel['CID'], ( $level + 1 ) );
			}
		}

		return( $content );
	}

	// output vent clients - recursive function
	function output_vent_clients( $cid=0, $level=0 )
	{
		usort( $this->clients[ $cid ], "SORT_VENTCHANCLI" );
		reset( $this->clients[ $cid ] );

		$content = NULL;
		while( $client = array_shift($this->clients[ $cid ]) )
		{
			$div_content = '<table class=\'tooltip\' cellspacing=\'1\' cellpadding=\'0\'>
<tr><td nowrap valign=\'top\'>{TEXT_CLIENT}:&nbsp;</td><td>' . htmlentities( $client['NAME'] ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_ADMIN}:&nbsp;</td><td>' . ( ( $client['ADMIN'] ) ? '{TEXT_YES}' : '{TEXT_NO}' ) . '</td></tr>' . ( ( isset( $client['PHAN'] ) ) ? '
<tr><td nowrap valign=\'top\'>{TEXT_PHANTOM}:&nbsp;</td><td>' . ( ( $client['PHAN'] ) ? '{TEXT_YES}' : '{TEXT_NO}' ) . '</td></tr>' : NULL ) . '
<tr><td nowrap valign=\'top\'>{TEXT_LOGGEDINFOR}:&nbsp;</td><td>' . htmlentities( formattime( $client['SEC'] ) ) . '</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_PING}:&nbsp;</td><td>' . htmlentities( $client['PING'] ) . '{TEXT_MILLISECONDS}</td></tr>' . ( ( !empty( $client['COMM'] ) ) ? '
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td nowrap valign=\'top\'>{TEXT_COMMENT}:&nbsp;</td><td>' . htmlentities( $client['COMM'] ) . '</td></tr>' : NULL ) . '</table>';

			$div_content = prep_tooltip( removechars( $div_content, array( "\r", "\n" ) ) );

			$content .= '
	<tr class="client_row' . ( ( isset( $client['PHAN'] ) && $client['PHAN'] ) ? ' ventclient_phantom_row' : NULL ) . ( ( isset( $client['ADMIN'] ) && $client['ADMIN'] ) ? ' ventclient_admin_row' : NULL ) . '">
		<td nowrap onMouseOver="toolTip(\'' . $div_content . '\')" onMouseOut="toolTip()"><p>
			' . ( ( $level !== 0 ) ? '<img src="{BASE_URL}images/spacer.gif" width="' . ( $level * 16 ) . '" height="16" border="0" align="absmiddle" alt="" />' : NULL ) . '<img width="16" height="16" src="{BASE_URL}templates/{TEMPLATE}/images/ventrilo/client.gif" align="absmiddle" alt="{TEXT_CLIENT}" border="0" />&nbsp;' . htmlentities( $client['NAME'] ) . ( ( !empty( $client['COMM'] ) ) ? '&nbsp;&nbsp;&nbsp;(<span class="ventcomment">' . htmlentities( linewrap( $client['COMM'], 30 ) ) . '</span>)' : NULL ) . '
		</p></td>' . ( ( $GLOBALS['tssettings']['Display_ping'] ) ? '<td nowrap align="right"><p>' . htmlentities( $client['PING'] ) . '{TEXT_MILLISECONDS}</p></td>' : NULL ) . '
	</tr>';
		}
		return( $content );
	}
}

// This class contains all the TSViewer.com specific functions
class ts_viewerdata extends commsdata
{
	// this function manages TSViewer.com live server data retrieval
	function get_livedata()
	{
		if ( $this->server['res_data']['port'] === 'FULL' )
			$cmd = 'http://www.tsviewer.com/ts_viewer.php?ID=' . (int) $this->server['res_data']['ip'];
		else
			$cmd = 'http://www.tsviewer.com/ts_viewer_pur.php?ID=' . (int) $this->server['res_data']['ip'];

		$cmd .= '&bg=transparent&type=&type_size=11&type_family=1&info=1&channels=1&users=1&type_s_weight=normal&type_s_style=normal&type_s_variant=normal&type_s_decoration=none&type_s_weight_h=normal&type_s_style_h=normal&type_s_variant_h=normal&type_s_decoration_h=none&type_i_weight=normal&type_i_style=normal&type_i_variant=normal&type_i_decoration=none&type_i_weight_h=normal&type_i_style_h=normal&type_i_variant_h=normal&type_i_decoration_h=none&type_c_weight=normal&type_c_style=normal&type_c_variant=normal&type_c_decoration=none&type_c_weight_h=normal&type_c_style_h=normal&type_c_variant_h=normal&type_c_decoration_h=none&type_u_weight=normal&type_u_style=normal&type_u_variant=normal&type_u_decoration=none&type_u_weight_h=normal&type_u_style_h=normal&type_u_variant_h=normal&type_u_decoration_h=none';

		if ( defined("SLG_DEBUG") )
		{
			$cnt_time = new timecount;
			$cnt_time->starttimecount();
		}

		$this->rawdata = file_get_contents( $cmd );

		if ( defined("SLG_DEBUG") )
		{
			// Because this is DEBUG only output it will be outputted outside of the template class.
			echo '<table border="0" align="center"><tr><td class="error"><p>DEBUG: Processing time required for retrieving the live server data: ' . round( $cnt_time->tottime, 4 ) . 's.</p></td></tr></table><p></p>';
		}

		if ( $this->server['refreshcache'] != 0 )
		{
			$this->savecache();
		}
	}

	// this function processes the raw TeamSpeak server data so that SLG Comms has access to nicely sorted and processed data. (has an Ventrilo counterpart with the same name)
	function process_rawdata()
	{
		$search_array = array(
			'<body style="margin:0">',
//			'<link href="http://88.198.54.206/design.css" rel="stylesheet" type="text/css" />',
		);

		$replace_array = array(
			'<body>',
//			'',
		);

		$this->rawdata = str_replace( $search_array, $replace_array, $this->rawdata );
	}
}

?>

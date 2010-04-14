<?php
/***************************************************************************
 *                                install.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: install.php,v 1.52 2005/11/06 23:09:58 SC Kruiper Exp $
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

define("IN_SLG", 10);
$new_version = 'v2.2.2';
include('includes/config.inc.php');

// if step doesn't exist step must be 1
if (!isset($_GET['step']) || !is_numeric($_GET['step']) || !isset($_POST['variable']['install_type']) || ($_POST['variable']['install_type'] !== 'upgrade' && $_POST['variable']['install_type'] !== 'rescue' && !isset($_POST['variable']['Database']))){
	$_GET['step'] = 1;
	$_POST = array();
}

// download the dbsetings.inc.php file incase in couldn't be auto saved
elseif ($_GET['step'] == 8 && isset($_POST['updsetting'])){
	header("Content-type: text/x-plain");
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Content-Disposition: attachment; filename="'.$_POST['filename'].'"');

//	header('Content-Type: text/x-delimtext; name="'.$_POST['filename'].'"');
//	header('Content-disposition: attachment; filename="'.$_POST['filename'].'"');

	echo $_POST['content'];

	exit;
}

// lets fill some default values which will be used as default values
if (isset($tssettings['SLG version']) && isset($_POST['variable']['install_type'])){
	$old_version = $tssettings['SLG version'];
	if (version_compare($old_version, $new_version, '>=') && $_POST['variable']['install_type'] === 'upgrade'){
		early_error('{TEXT_SAMEVERSIONUPDATE}');
	}
	elseif (defined("NO_DATABASE")){
		$_POST['variable']['Database'] = 0;
	}
	else{
		$_POST['variable']['Database'] = 1;
	}
}
elseif (isset($tssettings['SLG version'])){
	$old_version = $tssettings['SLG version'];
}
$tssettings['SLG version'] = $new_version;
$tssettings['Page title'] = 'SLG Comms '.$tssettings['SLG version'].' - {TEXT_INSTALLATION}';

//If a language has been selected lets switch to that language instead of the default
if (isset($_POST['variable']['Language'])){
	$tssettings['Language'] = $_POST['variable']['Language'];
}
//If a template has been selected lets switch to that template instead of the default
if (isset($_POST['variable']['Template'])){
	$tssettings['Template'] = $_POST['variable']['Template'];
}
include_once('includes/header.inc.php');

include('includes/functions.secure.inc.php');

// initialise the template class
$install = new template;
$template = 'install';
if ($_GET['step'] == 1 || (($_GET['step'] == 2 || $_GET['step'] == 3 || $_GET['step'] == 4) && isset($_POST['updsetting']))){
	// with different $_GET['step'] values we get different variables in $configlist
	if ($_GET['step'] == 1){
		$configlist = array(
			array(
				'variable' => 'install_type',
				'value' => 'new'
			)
		);
		if (!isset($old_version)){
			$configlist = array_merge($configlist, array(
				array(
					'variable' => 'Database',
					'value' => NULL
				),
				array(
					'variable' => 'Language',
					'value' => 'English'
				),
				array(
					'variable' => 'Template',
					'value' => 'Default'
				)
			));
		}
	}
	elseif ($_GET['step'] == 2 && $_POST['variable']['Database']){
		if ($_POST['variable']['install_type'] === 'upgrade'){
			$_GET['step'] = 5;
		}
		$configlist = array(
			array(
				'variable' => 'db_type',
				'value' => 'mysql41'
			),
			array(
				'variable' => 'db_host',
				'value' => NULL
			),
			array(
				'variable' => 'db_name',
				'value' => NULL
			),
			array(
				'variable' => 'db_user',
				'value' => NULL
			),
			array(
				'variable' => 'db_passwd',
				'value' => NULL
			),
			array(
				'variable' => 'table_prefix',
				'value' => 'slg_'
			)
		);
	}
	elseif ($_GET['step'] == 3 || ($_GET['step'] == 2 && !$_POST['variable']['Database'])){
		if ($_GET['step'] == 2){
			if ($_POST['variable']['install_type'] === 'upgrade'){
				$_GET['step'] = 6;
			}
			else{
				$_GET['step'] = 3;
			}
		}
	  	// lets fill the requirements list
		$required_version = (($_POST['variable']['Database'] && $_POST['variable']['db_type'] === 'mysql') ? '4.2.0' : '4.1.0');
		$requirements = '{TEXT_PHPVERSION} >= '.$required_version.': <span class="'.((version_compare(phpversion(), $required_version, '>=')) ? 'greentext">{TEXT_YES}' : 'redtext">{TEXT_NO}, {TEXT_UPDATEPHP}' ).'</span>.<br />
{TEXT_PCREEXT}: <span class="'.((extension_loaded('pcre')) ? 'greentext">{TEXT_YES}' : 'redtext">{TEXT_NO}' ).'</span>.<br />';
		if ($_POST['variable']['Database']){
			// lets get our db class
			require('includes/db/'.$_POST['variable']['db_type'].'.inc.php');
			$requirements .= '{TEXT_MYSQLDATABASE}: ';

			/* Connect to mysql and database and test given information */
			$db = new db;
			$db->connect('pzinstallserverconnect', $_POST['variable']['db_host'], $_POST['variable']['db_user'], $_POST['variable']['db_passwd'], $_POST['variable']['db_name']);
			$requirements .= '<span class="greentext">{TEXT_YES}</span>.<br />
{TEXT_WORKINGFORUM}: <span class="orangetext">{TEXT_SELECTFORUM}</span>.';
		}
		else{
			$requirements .= '<br />{TEXT_IGNOREOPTIONS}';
		}

		// insert the data into the template
		$install->insert_content('{SHOW_REQUIREMENTS}', $requirements);

		$configlist = array(
			array(
				'variable' => 'Cache hits',
				'value' => NULL
			),
			array(
				'variable' => 'Custom servers',
				'value' => NULL
			),
			array(
				'variable' => 'Default queryport',
				'value' => 51234
			),
			array(
				'variable' => 'Forum relative path',
				'value' => NULL
			),
			array(
				'variable' => 'Forum type',
				'value' => NULL
			),
			array(
				'variable' => 'GZIP Compression',
				'value' => NULL
			),
			array(
				'variable' => 'Page refresh timer',
				'value' => 0
			),
			array(
				'variable' => 'Page title',
				'value' => 'SLG Comms'
			),
			array(
				'variable' => 'Retrieved data status',
				'value' => NULL
			),
			array(
				'variable' => 'Show server information',
				'value' => NULL
			),
			array(
				'variable' => 'TeamSpeak support',
				'value' => NULL
			),
			array(
				'variable' => 'Ventrilo status program',
				'value' => NULL
			),
			array(
				'variable' => 'Ventrilo support',
				'value' => NULL
			)
		);
	}
	elseif($_GET['step'] == 4){
		// lets get our db class
		require('includes/db/'.$_POST['variable']['db_type'].'.inc.php');

		// check whether the selected forum is correct
		if (forum_existence_check($_POST['variable']['Forum type'], $_POST['variable']['Forum relative path'])){
			// retrieve needed forum information
			$forumsettings = retrieve_forumsettings($_POST['variable']);
		}
		else{
			early_error('{TEXT_FORUM_NOT_FOUND_ERROR}');
		}

		if (isset($forumsettings['groups_sql'])){
			// lets connect to the forum
			$forumdatabase = 'dbforum';
			$$forumdatabase = new db;
			if ($forumsettings['otherdatabase']){
				$$forumdatabase->connect('pzforumserverconnect', $forumsettings['alt_db_host'], $forumsettings['alt_db_user'], $forumsettings['alt_db_passwd'], $forumsettings['alt_db_name']);
			}
			else{
				$$forumdatabase->connect('pzforumserverconnect', $_POST['variable']['db_host'], $_POST['variable']['db_user'], $_POST['variable']['db_passwd'], $_POST['variable']['db_name']);
			}
			// lets retrieve the forums groups
			$groupsquery = $$forumdatabase->execquery('getforumgroups',$forumsettings['groups_sql']);
		}
		else{
			$install->displaymessage('{TEXT_FORUM_GROUPS_ERROR}');
		}

		$configlist = array(
			array(
				'variable' => 'Forum group',
				'value' => NULL
			)
		);
	}
	// whether or not display the requirements table
	$install->insert_display('{REQUIREMENTS}', $_GET['step'] == 3);

	$install->insert_text('{INSTALLATIONSTEP}', $_GET['step']);

	// decide on the next step to take
	if ($_GET['step'] == 1){
		$nextstep = '2';
	}
	elseif ($_GET['step'] == 2){
		if ($_POST['variable']['install_type'] === 'upgrade'){
			$nextstep = '5';
		}
		else{
			$nextstep = '3';
		}
	}
	elseif ($_GET['step'] == 3 && $_POST['variable']['Database'] == 1){
		$nextstep = '4';
	}
	elseif ($_GET['step'] == 3 && $_POST['variable']['Database'] == 0){
		$nextstep = '6';
	}
	elseif ($_GET['step'] == 4){
		$nextstep = '5';
	}
	elseif ($_GET['step'] == 5){
		$nextstep = '5';
	}
	elseif ($_GET['step'] == 6){
		$nextstep = '6';
	}
	else{
		early_error('{TEXT_SETTINGFORM_ERROR}');
	}
	$install->insert_text('{NEXTSTEP}', $nextstep);

	// parse the configlist array
	$configrows = NULL;
	foreach ($configlist as $row){
		$row['helptext'] = '{TEXT_HELP_'.strtoupper(removechars($row['variable'], ' ')).'}';
		$row['text'] = '{TEXT_'.strtoupper(removechars($row['variable'], ' ')).'}';
		$row['variable'] = htmlspecialchars($row['variable']);
		$configrows .= '
  <tr>
    <td width="40%" nowrap onMouseOver="toolTip(\''.$row['helptext'].'\')" onMouseOut="toolTip()"><p class="para">'.$row['text'].':</p></td>
    <td width="60%" nowrap><p class="para">';
		switch ($row['variable']){
			case 'install_type':
				$configrows .= '<select name="variable['.$row['variable'].']" class="textline">
'.((!isset($old_version)) ? '<option value="new">{TEXT_NEW_INSTALL}</option>' : '<option value="upgrade">{TEXT_UPGRADE_INSTALL}</option>
'.((!defined("NO_DATABASE")) ? '<option value="rescue">{TEXT_RESCUE_INSTALL}</option>' : NULL ).'
' ).'
</select>';
				break;
			case 'Database': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"><label for="'.$row['variable'].'_enable">{TEXT_YES}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"><label for="'.$row['variable'].'_disable">{TEXT_NO}</label>
';
				break;
			case 'db_type': $configrows .= '<select name="variable['.$row['variable'].']" class="textline">
<option value="mysql">MySQL</option>
'.((extension_loaded('mysqli')) ? '<option value="mysql41">MySQL 4.1.x</option>' : NULL ).'
</select>';
				break;
			case 'db_passwd': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="password" id="set_value" size="25" maxlength="100">';
				break;
			case 'table_prefix': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" value="slg_" size="25" maxlength="20">';
				break;

			case 'Cache hits': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'><label for="'.$row['variable'].'_enable"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'>{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'><label for="'.$row['variable'].'_disable"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'>{TEXT_DISABLE}</label>
';
				break;
			case 'Custom servers': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
				break;
			case 'Default queryport': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" value="'.$row['value'].'" size="25" maxlength="5">';
				break;
			case 'Forum group':
				if (isset($forumsettings['groups_sql'])){
					if ($$forumdatabase->numrows($groupsquery) > 0){
						$configrows .= '<select name="variable['.$row['variable'].']" class="textline">';
						while($rowgroup = $$forumdatabase->getrow($groupsquery)){
							$configrows .= '<option value="'.$rowgroup['groupid'].'">'.$rowgroup['groupname'].'</option>';
						}
						$configrows .= '</select>';
					}
					else{
						early_error('{TEXT_NOGROUP}');
					}
					$$forumdatabase->freeresult('getforumgroups',$groupsquery);
					if ($forumsettings['otherdatabase']){
						$$forumdatabase->disconnect();
					}
				}
				else $install->displaymessage('{TEXT_MISSING_GROUP_QUERY_ERROR}');
				break;
			case 'Forum type': 
				$configrows .= '<select name="variable['.$row['variable'].']" class="textline"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'>
<option value="ipb131">Invision Power Board 1.3.1</option>
<option value="ipb204">Invision Power Board 2.0.3-2.1.0</option>
<option value="phpbb2015">PhpBB 2.0.9-2.0.17</option>
<option value="smf103">SMF (Simple Machines Forum) 1.0.3-1.0.5</option>
<option value="smf110">SMF (Simple Machines Forum) 1.1 rc1</option>
<option value="vb307">vBulletin v3.0.7</option>
</select>';
				break;
			case 'GZIP Compression': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
				break;
			case 'Language':
				$configrows .= '<select name="variable['.$row['variable'].']" class="textline">';

				$dir = 'languages';
				$d = scandir($dir);
				foreach ($d as $entry){
					if ($entry != '.' && $entry != '..' && is_dir($dir.'/'.$entry)){
						$configrows .= '<option value="'.$entry.'"'.(($row['value'] == $entry) ? ' selected' : NULL).'>'.$entry.'</option>';
					}
				}
				unset($d);

				$configrows .= '</select>';
				break;
			case 'Page refresh timer': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" value="'.$row['value'].'" size="25" maxlength="5">';
				break;
			case 'Page title': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" size="25" maxlength="30" value="'.$row['value'].'">';
				break;
			case 'Retrieved data status': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'><label for="'.$row['variable'].'_enable"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'>{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'><label for="'.$row['variable'].'_disable"'.(($_POST['variable']['Database'] == 0) ? ' disabled' : NULL).'>{TEXT_DISABLE}</label>
';
				break;
			case 'Show server information': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
				break;
			case 'Template':
				$configrows .= '<select name="variable['.$row['variable'].']" class="textline">';

				$dir = 'templates';
				$d = scandir($dir);
				foreach ($d as $entry){
					if ($entry != '.' && $entry != '..' && is_dir($dir.'/'.$entry)){
						$configrows .= '<option value="'.$entry.'"'.(($row['value'] == $entry) ? ' selected' : NULL).'>'.$entry.'</option>';
					}
				}
				unset($d);

				$configrows .= '</select>';
				break;
			case 'TeamSpeak support': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
				break;
			case 'Ventrilo support': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
				break;
			default: $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" size="25" maxlength="100" value="'.(($_POST['variable']['Database'] == 0 && $row['variable'] == 'Forum relative path') ? '{TEXT_DISABLED}" disabled' : $row['value'].'"').'>';
		}
		$configrows .= '
	  </p></td>
  </tr>';
	}
	// put all the variables from previous forms into this form
	if (isset($_POST['updsetting'])){
		reset($_POST['variable']);
		foreach ($_POST['variable'] as $variable => $value){
			$configrows .= '<input name="variable['.$variable.']" type="hidden" id="oldset_value" value="'.htmlspecialchars($value).'">';
		}
	}
	$install->insert_content('{SETTINGS}', $configrows);
}

if ($_GET['step'] == 5 && isset($_POST['updsetting'])){
	// lets insert everything into the database
	if (isset($_POST['variable']['db_type'])){
		require('includes/db/'.$_POST['variable']['db_type'].'.inc.php');
	
		$table['cache'] = $_POST['variable']['table_prefix'].'cache';
		$table['resources'] = $_POST['variable']['table_prefix'].'resources';
		$table['sessions'] = $_POST['variable']['table_prefix'].'sessions';
		$table['settings'] = $_POST['variable']['table_prefix'].'settings';
	
		/* Connect to mysql and database */
		$db = new db;
		$db->connect('pzinstallserverconnect', $_POST['variable']['db_host'], $_POST['variable']['db_user'], $_POST['variable']['db_passwd'], $_POST['variable']['db_name']);
		$install->displaymessage('{TEXT_SELECTDB_SUCCESS}');
	}

//---------------------------
	if ($_POST['variable']['install_type'] === 'new'){
	//resources table
		$sql = 'CREATE TABLE `'.$table['resources'].'` (
  `res_id` tinyint(3) unsigned NOT NULL auto_increment,
  `res_name` varchar(50) NOT NULL,
  `res_data` varchar(100) default NULL,
  `res_type` enum("TeamSpeak","Ventrilo") NOT NULL default "TeamSpeak",
  PRIMARY KEY  (`res_id`),
  UNIQUE KEY `res_name` (`res_name`,`res_data`,`res_type`),
  KEY `res_type` (`res_type`)
)'; 
		$createrestable = $db->execquery('createrestable',$sql);
		if ($createrestable === true){
			$install->displaymessage('{TEXT_TABLE_CREATE_SUCCESS;'.$table['resources'].';}');
			$sql = 'INSERT INTO `'.$table['resources'].'` VALUES
("", "Example TeamSpeak", "12.23.34.45:6464:51234", "TeamSpeak"),
("", "Example Ventrilo", "ventrilo.game-host.org:8767:45t8hg4", "Ventrilo")
';
			$insertresdata = $db->execquery('insertresdata',$sql);
			if ($insertresdata === true){
				$install->displaymessage('{TEXT_INSERT_DATA_SUCCESS;'.$table['resources'].';}');
			}
		}

	//sessions table
		$sql = 'CREATE TABLE `'.$table['sessions'].'` (
  `session_id` char(32) NOT NULL,
  `session_user_id` mediumint(8) NOT NULL default "0",
  `session_ip` char(8) NOT NULL default "0",
  PRIMARY KEY  (`session_id`),
  KEY `session_user_id` (`session_user_id`)
)'; 
		$createsestable = $db->execquery('createsestable',$sql);
		if ($createsestable === true){
			$install->displaymessage('{TEXT_TABLE_CREATE_SUCCESS;'.$table['sessions'].';}');
		}

	//server caching table
		$sql = 'CREATE TABLE `'.$table['cache'].'` (
  `cache_id` tinyint(3) unsigned NOT NULL default "0",
  `data` text,
  `timestamp` varchar(15) NOT NULL default "0",
  `refreshcache` smallint(5) unsigned NOT NULL default "0",
  `cachehits` mediumint(8) unsigned NOT NULL default "0",
  PRIMARY KEY  (`cache_id`),
  KEY `refreshcache` (`refreshcache`)
)'; 
		$createsertable = $db->execquery('createsertable',$sql);
		if ($createsertable === true){
			$install->displaymessage('{TEXT_TABLE_CREATE_SUCCESS;'.$table['cache'].';}');
		}
	}

	if ($_POST['variable']['install_type'] === 'rescue'){
	//settings table
		$sql = 'DROP TABLE IF EXISTS `'.$table['settings'].'`';
		$dropsettable = $db->execquery('dropsettable',$sql);
		if ($dropsettable === true){
			$install->displaymessage('{TEXT_TABLE_DROP_SUCCESS;'.$table['cache'].';}');
		}
	}

	if ($_POST['variable']['install_type'] === 'rescue' || $_POST['variable']['install_type'] === 'new'){
		$sql = 'CREATE TABLE `'.$table['settings'].'` (
  `variable` enum( "Cache hits", "Custom servers", "Default queryport", "Default server", "Forum group", "Forum relative path", "Forum type", "GZIP Compression", "Language", "Page generation time", "Page refresh timer", "Page title", "Retrieved data status", "Show server information", "SLG version", "TeamSpeak support", "Template", "Ventrilo status program", "Ventrilo support" ) NOT NULL default "Cache hits",
  `value` varchar(100) NOT NULL,
  PRIMARY KEY  (`variable`)
)'; 
		$createsettable = $db->execquery('createsettable',$sql);
		if ($createsettable === true){
			$install->displaymessage('{TEXT_TABLE_CREATE_SUCCESS;'.$table['settings'].';}');
			$sql = 'INSERT INTO `'.$table['settings'].'` VALUES
("Cache hits", "'.((!empty($_POST['variable']['Cache hits'])) ? $db->escape_string($_POST['variable']['Cache hits']) : '0').'"),
("Custom servers", "'.((!empty($_POST['variable']['Custom servers'])) ? $db->escape_string($_POST['variable']['Custom servers']) : '0').'"),
("Default queryport", "'.((!empty($_POST['variable']['Default queryport'])) ? $db->escape_string($_POST['variable']['Default queryport']) : '51234').'"),
("Default server", "0"),
("Forum group", "'.$db->escape_string($_POST['variable']['Forum group']).'"),
("Forum relative path", "'.$db->escape_string($_POST['variable']['Forum relative path']).'"),
("Forum type", "'.$db->escape_string($_POST['variable']['Forum type']).'"),
("GZIP Compression", "'.$db->escape_string($_POST['variable']['GZIP Compression']).'"),
("Language", "'.$db->escape_string($_POST['variable']['Language']).'"),
("Page generation time", "1"),
("Page refresh timer", "'.$db->escape_string($_POST['variable']['Page refresh timer']).'"),
("Page title", "'.((!empty($_POST['variable']['Page title'])) ? $db->escape_string($_POST['variable']['Page title']) : 'SLG '.$tssettings['SLG version']).'"),
("Retrieved data status", "'.((!empty($_POST['variable']['Retrieved data status'])) ? $db->escape_string($_POST['variable']['Retrieved data status']) : '0').'"),
("Show server information", "'.((!empty($_POST['variable']['Show server information'])) ? $db->escape_string($_POST['variable']['Show server information']) : '0').'"),
("SLG version", "'.$tssettings['SLG version'].'"),
("TeamSpeak support", "'.((!empty($_POST['variable']['TeamSpeak support'])) ? $db->escape_string($_POST['variable']['TeamSpeak support']) : '0').'"),
("Template", "'.$db->escape_string($_POST['variable']['Template']).'"),
("Ventrilo status program", "'.$db->escape_string($_POST['variable']['Ventrilo status program']).'"),
("Ventrilo support", "'.((!empty($_POST['variable']['Ventrilo support'])) ? $db->escape_string($_POST['variable']['Ventrilo support']) : '0').'")';
			$insertsetdata = $db->execquery('insertsetdata',$sql);
			if ($insertsetdata === true){
				$install->displaymessage('{TEXT_INSERT_DATA_SUCCESS;'.$table['settings'].';}');
			}
		}
	}
	
	if ($_POST['variable']['install_type'] === 'upgrade'){
		if (!isset($old_version)){
			early_error('{TEXT_OLDVERSION_UNAVAILABLE}');
		}
		/* SLG Comms versions before v2.2.0 */
		if (version_compare($old_version, 'v2.2.0', '<')){
			$sql = 'ALTER TABLE `slg_settings` CHANGE `variable` `variable` ENUM( "Cache hits", "Custom servers", "Default queryport", "Default server", "Forum group", "Forum relative path", "Forum type", "GZIP Compression", "Language", "Page generation time", "Page refresh timer", "Page title", "Retrieved data status", "Show server information", "SLG version", "TeamSpeak support", "Template", "Ventrilo status program", "Ventrilo support" ) NOT NULL DEFAULT "Cache hits"';
			$addvarsettings = $db->execquery('addvarsettings',$sql);
			if ($addvarsettings === true){
				$install->displaymessage('{TEXT_ADDVARSETTINGS_SUCCESS}');
				$sql = 'INSERT INTO `'.$table['settings'].'` VALUES
("Page generation time", "1"),
("TeamSpeak support", "1"),
("Ventrilo support", "1")';
				$addsettings = $db->execquery('addsettings',$sql);
				if ($addsettings === true){
					$install->displaymessage('{TEXT_ADDSETTINGS_SUCCESS}');
				}
			}
		}

		$sql = 'UPDATE `slg_settings`
SET
  `value` = "'.$tssettings['SLG version'].'"
WHERE
  `variable` = "SLG version"
LIMIT 1';
		$modifysettings = $db->execquery('modifysettings',$sql);
		if ($modifysettings === true){
			$install->displaymessage('{TEXT_UPGRADE_SUCCESS}');
		}
	}

	if (isset($createrestable, $insertresdata, $createsestable, $createsestable, $createsettable, $insertsetdata) && ($createrestable && $insertresdata) && $createsestable && $createsertable && ($createsettable && $insertsetdata)){
		$install->displaymessage('<span class="errorbig">{TEXT_INSTALL_SUCCESS}</span>');
		$_GET['step'] = 6;
	}
	elseif (isset($createsettable, $insertsetdata) && $createsettable && $insertsetdata){
		$install->displaymessage('<span class="errorbig">{TEXT_INSTALL_RESTORE_SUCCESS}</span>');
	}
//-----------------------------
}

if ($_GET['step'] == 6 && isset($_POST['updsetting'])){
	// display the finish installation information
	reset($_POST['variable']);
	$hidden_vars = NULL;
	foreach ($_POST['variable'] as $variable => $value){
		$hidden_vars .= '<input name="variable['.$variable.']" type="hidden" id="oldset_value" value="'.htmlspecialchars($value).'">';
	}
//	$install->insert_display('{SHOW_FINISH_INSTALL}', true);
	$install->insert_content('{TEXT_HIDDEN_VARIABLES}', $hidden_vars);
	$install->insert_content('{TEXT_FINISH_LARGE}', '{TEXT_FINISH_INSTALL_LARGE}');
	$install->insert_content('{TEXT_FINISH}', '{TEXT_FINISH_INSTALL}');
	$install->insert_content('{NEXTSTEP}', '7');
}
elseif ($_GET['step'] == 7 and isset($_POST['updsetting'])){
	// lets save dbsettings.inc.php
	$filename = 'dbsettings.inc.php';
	
	if ($_POST['variable']['Database']){
		$content = '<?php
//security through the use of define != defined
if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}
/* Config - Change this if necassary */
$tssettings[\'db_type\'] = \''.((isset($_POST['variable']['db_type'])) ? $_POST['variable']['db_type'] : NULL).'\';			/*The type of database to be used. This can be either "mysql" (MySQL version 3.23 or higher) or "mysql41" (MySQL version 4.1 or higher)*/
$tssettings[\'db_host\'] = \''.((isset($_POST['variable']['db_host'])) ? $_POST['variable']['db_host'] : NULL).'\';		/*host of the database server*/
$tssettings[\'db_name\'] = \''.((isset($_POST['variable']['db_name'])) ? $_POST['variable']['db_name'] : NULL).'\';			/*database to be used*/
$tssettings[\'db_user\'] = \''.((isset($_POST['variable']['db_user'])) ? $_POST['variable']['db_user'] : NULL).'\';	/*username for the database*/
$tssettings[\'db_passwd\'] = \''.((isset($_POST['variable']['db_passwd'])) ? $_POST['variable']['db_passwd'] : NULL).'\';		/*password for the database*/
$tssettings[\'table_prefix\'] = \''.((isset($_POST['variable']['table_prefix'])) ? $_POST['variable']['table_prefix'] : NULL).'\';		/*The table prefix thats being used*/
/* Don\'t change anything below this line unless you know what youre doing. */
?>
';
	}
	elseif ($_POST['variable']['install_type'] === 'upgrade'){
		if (!isset($old_version)){
			early_error('{TEXT_OLDVERSION_UNAVAILABLE}');
		}
		processincomingdata($_POST);
		$content = file_get_contents($filename);
		$content = str_replace($old_version, $tssettings['SLG version'], $content);
		if (version_compare($old_version, 'v2.2.0', '<')){
			$content .= '
<?php
$tssettings[\'Page generation time\'] = true;			/* Show or hide the Page generation times at the bottom of the pages. Default is allways enabled */
$tssettings[\'TeamSpeak support\'] = true;		/* Do you want support for TeamSpeak (true or false)*/
$tssettings[\'Ventrilo support\'] = true;		/* Do you want support for Ventrilo (true or false)*/
?>';
		}
	}
	else{
		processincomingdata($_POST);
		$content = '<?php
if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}

/* Config - Change this if necassary */
$tssettings[\'Custom servers\'] = '.$_POST['variable']['Custom servers'].';			/*This line enables (true) or disables (false) the custom server feature.*/
$tssettings[\'Default queryport\'] = '.$_POST['variable']['Default queryport'].';		/*For teamspeak servers the default TCP queryport to be used when not defined by res_data (Look below in the servers array for info on res_data) is 51234. Change this when you know what it is and why you need to change it.*/
$tssettings[\'Default server\'] = 0;				/*The unique id (This is res_id which you\'ll see again in the arrays a couple lines down) of the server you want to be the default server to be loaded. Zero disables the default loading of a server*/
$tssettings[\'GZIP Compression\'] = \''.$_POST['variable']['GZIP Compression'].'\';		/*Whether or not you want to use the internal GZIP compression engine.*/
$tssettings[\'Language\'] = \''.$_POST['variable']['Language'].'\';			/*This is the language you want to use with SLG */
$tssettings[\'Page generation time\'] = true;			/* Show or hide the Page generation times at the bottom of the pages. Default is allways enabled */
$tssettings[\'Page refresh timer\'] = \''.$_POST['variable']['Page refresh timer'].'\';
$tssettings[\'Page title\'] = \''.$_POST['variable']['Page title'].'\';		/*The title you want to be displayed in the head title tag and in the top of every page.*/
$tssettings[\'Retrieved data status\'] = false;	/*This line enables (true) or disables (false) the red text saying what kind of data is getting displayed (Data can be cached or live. Without a database the data is allways live so no need to display it.).*/
$tssettings[\'Show server information\'] = '.$_POST['variable']['Show server information'].';	/*This line enables (true) or disables (false) the server information pane. It\'s is not required to display this information because its also integrated in the Channel information pane if you click on the server name.*/
$tssettings[\'TeamSpeak support\'] = \''.$_POST['variable']['TeamSpeak support'].'\';		/* Do you want support for TeamSpeak (true or false)*/
$tssettings[\'Template\'] = \''.$_POST['variable']['Template'].'\';			/*This is the template you wish to use with SLG*/
$tssettings[\'Ventrilo status program\'] = \''.$_POST['variable']['Ventrilo status program'].'\';	/*Ventrilo status program to be used to get ventrilo server info. The unix version doesn\'t have an extension and the windows has an .exe extension. Whether there are one or two slashes between directory names doesn\'t matter, SLG Comms though will default to 2 slashes.*/
$tssettings[\'Ventrilo support\'] = \''.$_POST['variable']['Ventrilo support'].'\';		/* Do you want support for Ventrilo (true or false)*/

/*Here you can insert all the servers you want. These servers are all sorted alphabetically upon displayal.*/
$servers = array(

	array(  \'res_id\' => 1,									/*Here you enter a unique id for the server.*/
	        \'res_name\' => \'Example TeamSpeak\',				/*The name you want to be displayed in the drop down list.*/
	        \'res_data\' => \'12.23.34.45:6464:51234\',			/*Here you fill in the ip and port of the servers. For teamspeak servers optionally also the queryport of the server. The default queryport for a teamspeak server is 51234. For Ventrilo the queryport should be replaced with the password to join the server. format is [ip]:[port]:[optional requirements]*/
	        \'res_type\' => \'TeamSpeak\'						/*This is used for possibly extending this script to encompass support for other voice communication servers other then teamspeak. At the moment the only types are "TeamSpeak" and "Ventrilo".*/
	),															/*This comma is needed to imply that there is another server following after this one. The only server without a comma will be the last one.*/

	array(  \'res_id\' => 2,
	        \'res_name\' => \'Example Ventrilo\',
	        \'res_data\' => \'ventrilo.game-host.org:8767:45t8hg4\',
	        \'res_type\' => \'Ventrilo\'
	)															/*As you can see no comma this time as its the last server.*/

);
/* Don\'t change anything below this line unless you know what youre doing. */

define("NO_DATABASE", 10);						/*Disable the use of the database.*/
$cache[\'refreshcache\'] = 0;						/*This line disables caching of retrieved server data. As this data normally is stored in a database we must disable it in this situation. Don\'t change this as it will never work the way it should as there is no database available.*/
$tssettings[\'SLG version\'] = \''.$tssettings['SLG version'].'\';			/*The current version of this script. Please don\'t change it unless you have a good reason for it.*/
?>
';
	}

	$file_save = false;
	if (file_exists($filename)){
		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)){
			if (!$handle = @fopen($filename, 'w')){
				$install->displaymessage('{TEXT_OPENFILE_ERROR;'.$filename.';}');
			}
			// Write some content to our opened file.
			elseif (@fwrite($handle, $content) === FALSE){
				$install->displaymessage('{TEXT_FILEWRITE_ERROR;'.$filename.';}');
			}
			else{
				$install->displaymessage('{TEXT_FILEWRITE_SUCCESS;'.$filename.';}');
				fclose($handle);
				$file_save = true;
			}
		}
		else{
			$install->displaymessage('{TEXT_CANTWRITE_FILE;'.$filename.';}');
		}
	}
	else{
		$install->displaymessage('{TEXT_FILE_DOESNTEXIST;'.$filename.';}');
	}

	if (!$file_save){
		// auto save failed so lets download the file and let the user upload it himself
		reset($_POST['variable']);
		$hidden_vars = '
<input name="content" type="hidden" id="oldset_value" value="'.htmlspecialchars($content).'">
<input name="filename" type="hidden" id="oldset_value" value="'.$filename.'">';
//		$install->insert_display('{SHOW_FINISH_INSTALL}', true);
		$install->insert_content('{TEXT_HIDDEN_VARIABLES}', $hidden_vars);
		$install->insert_content('{TEXT_FINISH_LARGE}', '{TEXT_DOWNLOAD_FILE_LARGE;'.$filename.';}');
		$install->insert_content('{TEXT_FINISH}', '{TEXT_DOWNLOAD_FILE}');
		$install->insert_content('{NEXTSTEP}', '8');
	}
	if (!$_POST['variable']['Database']){
		$install->displaymessage('{TEXT_NO_DATABASE_SERVERLIST;'.$filename.';}');
	}
}

// we need to decide on which template is needed
if ($_GET['step'] >= 5 && isset($_POST['updsetting'])){
	// whether or not to display the finish installation information
	$install->insert_display('{FINISH}', ((isset($file_save) && !$file_save) || ($_GET['step'] == 6 && isset($_POST['updsetting']))));
	$install->load_template('admin/tpl_install_finish');
}
elseif (($_GET['step'] >= 2 && $_GET['step'] < 5 && isset($_POST['updsetting'])) || ($_GET['step'] == 1 && !isset($_POST['updsetting']))){
	$install->load_template('admin/tpl_install_settings');
}

// process template
$install->load_language('admin/lng_install');
$install->load_language('admin/lng_common');
$install->process();
$install->output();
unset($install);

include('includes/footer.inc.php');
?>

<?php
/***************************************************************************
 *                             settings.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: settings.inc.php,v 1.13 2005/10/21 14:29:26 SC Kruiper Exp $
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
if (!defined("IN_SLG") || !checkaccess($tssettings['Forum group'])){ 
	die("Hacking attempt.");
}

// this file manages the settings pages
if (isset($_POST['updsetting'])) {
	if (forum_existence_check($_POST['variable']['Forum type'], $_POST['variable']['Forum relative path'])){
		$new_forum_ok = true;
	}
	else{
		early_error('{TEXT_FORUM_NOT_FOUND_ERROR}');
	}
	
	reset($_POST['variable']);
	foreach ($_POST['variable'] as $variable => $value){
		if ($value != $tssettings[$variable]){
			$updatesetting = $db->execquery('updatesetting','UPDATE '.$table['settings'].' SET
  value = "'.$db->escape_string($value).'"
WHERE
  variable = "'.$db->escape_string($variable).'"');
			if ($updatesetting === true){
				if ($variable === 'Cache hits'){
					$db->execquery('resetcachehits','UPDATE `'.$table['cache'].'`
SET
  `cachehits` = 0');
				}
				if ($variable === 'Ventrilo status program' && !file_exists($_POST['variable']['Ventrilo status program'])){
					$admin->displaymessage('{TEXT_VENTRILO_STAT_PROG_CHECK}');
				}
				$admin->displaymessage('{TEXT_SETTINGUPDATE_SUCCESS;'.$variable.';}');
			}
		}
	}
}

$getconfig = $db->execquery('getconfig','SELECT 
  variable,
  value
FROM
  '.$table['settings']);

while ($row = $db->getrow($getconfig)) {
	$tssettings[$row['variable']] = $row['value'];
}
$db->freeresult('getconfig',$getconfig);

$forumsettings = retrieve_forumsettings($tssettings);

$getservers = $db->execquery('getservers','
SELECT res_id, res_name
FROM '.$table['resources'].' 
WHERE `res_type` IN ("TeamSpeak","Ventrilo")
ORDER BY res_name');

if (isset($forumsettings['groups_sql'])){
	if ($forumsettings['otherdatabase']){
		$forumdatabase = 'dbforum';
		$$forumdatabase = new db;
		$$forumdatabase->connect('pzforumserverconnect', $forumsettings['alt_db_host'], $forumsettings['alt_db_user'], $forumsettings['alt_db_passwd'], $forumsettings['alt_db_name']);
	}
	else{
		$forumdatabase = 'db';
	}
	$groupsquery = $$forumdatabase->execquery('getforumgroups',$forumsettings['groups_sql']);
}
else{
	$admin->displaymessage('{TEXT_FORUM_GROUPS_ERROR}');
}

$configrows = NULL;
foreach ($tssettings as $row['variable'] => $row['value']){
	$tmparr = array('db_type', 'db_host', 'db_name', 'db_user', 'db_passwd', 'table_prefix');
	if (in_array($row['variable'], $tmparr)){
		continue;
	}
	$row['helptext'] = '{TEXT_HELP_'.strtoupper(removechars($row['variable'], ' ')).'}';
	$row['text'] = '{TEXT_'.strtoupper(removechars($row['variable'], ' ')).'}';
	$row['variable'] = htmlspecialchars($row['variable']);
	$row['value'] = htmlspecialchars($row['value']);
	$configrows .= '
  <tr>
    <td width="40%" nowrap onMouseOver="toolTip(\''.$row['helptext'].'\')" onMouseOut="toolTip()"><p class="para">'.$row['text'].':</p></td>
    <td width="60%" nowrap><p class="para">';
	switch ($row['variable']){
		case 'Cache hits': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
			break;
		case 'Custom servers': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
			break;
		case 'Default queryport': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" value="'.$row['value'].'" size="25" maxlength="5">';
			break;
		case 'Default server':
			$configrows .= '<select name="variable['.$row['variable'].']" class="textline">
<option value="0">None</option>';
			while($rowts = $db->getrow($getservers)){
				$configrows .= '<option value="'.$rowts['res_id'].'"';
				if ($rowts['res_id'] == $row['value']){
					$configrows .= ' selected';
				}
				$configrows .= '>'.htmlspecialchars($rowts['res_name']).'</option>';
			}
			$configrows .= '</select>';
			$db->freeresult('getservers',$getservers);
			break;
		case 'Forum group':
			if (isset($forumsettings['groups_sql'])){
				if ($$forumdatabase->numrows($groupsquery) > 0){
					$configrows .= '<select name="variable['.$row['variable'].']" class="textline">';
					while($rowgroup = $$forumdatabase->getrow($groupsquery)){
						$configrows .= '<option value="'.$rowgroup['groupid'].'"';
						if ($rowgroup['groupid'] == $row['value']){
							$configrows .= ' selected';
						}
						$configrows .= '>'.$rowgroup['groupname'].'</option>';
					}
					$configrows .= '</select>';
				}
				else{
					$configrows .= '<span class="red">{TEXT_NOGROUP}</span>';
				}
				$$forumdatabase->freeresult('getforumgroups',$groupsquery);
				if ($forumsettings['otherdatabase']){
					$$forumdatabase->disconnect();
				}
			}
			else $admin->displaymessage('{TEXT_MISSING_GROUP_QUERY_ERROR}');
			break;
		case 'Forum type': 
			$configrows .= '<select name="variable['.$row['variable'].']" class="textline">
<option value="ipb131"'.(($row['value'] === 'ipb131') ? ' selected' : '').'>Invision Power Board 1.3.1</option>
<option value="ipb204"'.(($row['value'] === 'ipb204') ? ' selected' : '').'>Invision Power Board 2.0.3-2.1.0</option>
<option value="phpbb2015"'.(($row['value'] === 'phpbb2015') ? ' selected' : '').'>PhpBB 2.0.9-2.0.17</option>
<option value="smf103"'.(($row['value'] === 'smf103') ? ' selected' : '').'>SMF (Simple Machines Forum) 1.0.3-1.0.5</option>
<option value="smf110"'.(($row['value'] === 'smf110') ? ' selected' : '').'>SMF (Simple Machines Forum) 1.1 rc1</option>
<option value="vb307"'.(($row['value'] === 'vb307') ? ' selected' : '').'>vBulletin v3.0.7</option>
</select>';
			break;
		case 'GZIP Compression': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
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
		case 'Page generation time': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
			break;
		case 'Page refresh timer': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" value="'.$row['value'].'" size="25" maxlength="5">';
			break;
		case 'Page title': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" size="25" maxlength="30" value="'.$row['value'].'">';
			break;
		case 'Retrieved data status': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
			break;
		case 'Show server information': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
			break;
		case 'SLG version': $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" value="'.$row['value'].'" size="25" maxlength="100" disabled>';
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
		case 'TeamSpeak support': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
			break;
		case 'Ventrilo support': $configrows .= '<input id="'.$row['variable'].'_enable" name="variable['.$row['variable'].']" type="radio" value="1"'.(($row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_enable">{TEXT_ENABLE}</label>
<input id="'.$row['variable'].'_disable" name="variable['.$row['variable'].']" type="radio" value="0"'.((!$row['value']) ? ' checked' : '').'><label for="'.$row['variable'].'_disable">{TEXT_DISABLE}</label>
';
			break;
		default: $configrows .= '<input class="textline" name="variable['.$row['variable'].']" type="text" id="set_value" size="25" maxlength="100" value="'.$row['value'].'">';
	}
	$configrows .= '
	  </p></td>
  </tr>';
}
$admin->insert_content('{SETTINGS}', $configrows);
?>

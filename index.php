<?php
/***************************************************************************
 *                                index.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: index.php,v 1.16 2005/06/20 17:23:39 SC Kruiper Exp $
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
include('includes/config.inc.php');
include_once('includes/header.inc.php');

$index = new template;
$template = 'index';

if ( !defined('NO_DATABASE') ){
	$getservers = $db->execquery('getservers','
SELECT res_id, res_name, res_data, res_type
FROM '.$table['resources'].' 
WHERE `res_type` IN ("TeamSpeak","Ventrilo")
ORDER BY res_name');

	$servers = array();
	while($rowts = $db->getrow($getservers)){
		$servers[] = $rowts;
	}
	$db->freeresult('getservers',$getservers);
}

$Servertable = (count($servers) > 1) || $tssettings['Custom servers'];
$index->insert_display('{SELECT_SERVER}', $Servertable);
if ($Servertable){
	$ipbyname = ($tssettings['Custom servers']) ? '<option value="0">{TEXT_CUSTOM}</option>' : NULL;
	if ( defined('NO_DATABASE') ){
		uasort ( $servers, "SORT_SERVERS");
	}
	reset($servers);
	foreach($servers as $server){
		$ipbyname .= '<option value="'.$server['res_id'].'"';
		if (isset($_REQUEST['ipbyname']) && $_REQUEST['ipbyname'] == $server['res_id']){
			$ipbyname .= ' selected';
			$pice = explode(':',$server['res_data']);
			if (isset($pice[2])) $ts['queryport'] = $pice[2];
			$res_type = $server['res_type'];
			$ts['id'] = $server['res_id'];
		}
		elseif ($tssettings['Default server'] == $server['res_id'] && !isset($_REQUEST['ipbyname'])){
			$ipbyname .= ' selected';
			$pice = explode(':',$server['res_data']);
			if (isset($pice[2])) $ts['queryport'] = $pice[2];
			$res_type = $server['res_type'];
			$ts['id'] = $server['res_id'];
		}
		$ipbyname .= '>'.$server['res_name'].'</option>';
	}
	$index->insert_content('{IPBYNAME_OPTIONS}', $ipbyname);

	$index->insert_display('{CUSTOM_SERVER}', $tssettings['Custom servers']);
	if ($tssettings['Custom servers']){
		$typeopt = '
      <option value="TeamSpeak"'.((isset($_POST['type']) && $_POST['type'] == 'TeamSpeak') ? ' selected' : NULL ).'>TeamSpeak</option>
      <option value="Ventrilo"'.((isset($_POST['type']) && $_POST['type'] == 'Ventrilo') ? ' selected' : NULL ).'>Ventrilo</option>';
		$index->insert_content('{TYPE_OPTIONS}', $typeopt);
		$index->insert_text('{IPPORT_VALUE}', ((isset($_POST['ipport'])) ? $_POST['ipport'] : NULL));
	}
}
else{
	reset($servers);
	$server = current($servers);
	$pice = explode(':',$server['res_data']);
	if (isset($pice[2])) $ts['queryport'] = $pice[2];
	$res_type = $server['res_type'];
	$ts['id'] = $server['res_id'];
}

if(isset($_POST['ipport']) && $_POST['ipbyname'] == 0 && $tssettings['Custom servers']){
	$pice = explode(':',$_POST['ipport']);
	if (empty($pice[0]) || empty($pice[1])){
		$index->displaymessage('{TEXT_IP_PORT_COMB_ERROR}');
		unset($_POST);
		unset($res_type);
	}
}
$ts['ip'] = (isset($pice[0])) ? $pice[0] : NULL;
$ts['port'] = (isset($pice[1])) ? $pice[1] : NULL;

if(isset($ts['id']) && !isset($cache)){
	$cached = $db->execquery('getcached','
SELECT
  data,
  timestamp,
  refreshcache
FROM
  '.$table['cache'].'
WHERE
  cache_id = "'.$ts['id'].'" AND
  refreshcache != 0
LIMIT 0,1');
	/* AND
  timestamp > "'.$refreshtime.'"*/
	
	if ($db->numrows($cached) > 0){
		$cache = $db->getrow($cached);
	
		$ptime = explode(" ",microtime());
		$refreshtime = $ptime[1] - $cache['refreshcache'];
	
		if ($cache['timestamp'] < $refreshtime) $usecached = false;
		else{
			$usecached = true;
			if ($tssettings['Cache hits']){
				$db->execquery('updatecachehits','UPDATE `'.$table['cache'].'`
SET
  `cachehits` = (`cachehits`+1)
WHERE 
  `cache_id` = "'.$ts['id'].'"
LIMIT 1');
				$tuntilrefresh = $cache['timestamp'] - $refreshtime;
			}
		}
	}
	else{
		$cache['refreshcache'] = 0;
		$usecached = false;
	}
	$db->freeresult('getcached',$cached);
}
else $usecached = false;

$index->load_language('lng_index');
$index->load_template('tpl_index');
$index->process();
$index->output();
unset($index);

if ((isset($res_type) && $res_type == 'TeamSpeak') || (isset($_POST['type']) && $_POST['type'] == 'TeamSpeak' && $_POST['ipbyname'] == 0)){
	if (!isset($ts['queryport'])) $ts['queryport'] = $tssettings['Default queryport'];
	include('includes/teamspeak.inc.php');
}
elseif ((isset($res_type) && $res_type == 'Ventrilo') || (isset($_POST['type']) && $_POST['type'] == 'Ventrilo' && $_POST['ipbyname'] == 0)){
	include('includes/ventrilo.inc.php');
}

include('includes/footer.inc.php');
?>

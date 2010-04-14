<?php
/***************************************************************************
 *                              ventrilo.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: ventrilo.inc.php,v 1.32 2005/09/12 23:13:45 SC Kruiper Exp $
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

$ventrilo = new template;
$template = 'ventrilo';

include('includes/functions.vent.inc.php');

$ventserver = array();
$ventchannels = array();
$ventclients = array();
$ventchannelindex = array();
$ventclientindex = array();

//Connection with server...
$ipstring = $ts['ip'].':'.$ts['port'].((isset($ts['queryport'])) ? ':'.$ts['queryport'] : NULL);
if (!$usecached){
	exec($tssettings['Ventrilo status program'].' -a2 -c2 -t'.$ipstring .' 2>&1', $routput, $execcmd);
}
else{
	$routput = explode('/%/', $cache['data']);
	unset($cache['data']);
}

//print_r($execcmd);
//print_r($routput);

if (!isset($execcmd) || $execcmd == 0 || $execcmd == 3){ // 0 = everything went ok OR no response from server. 3 = unable to resolve hostname error.
	if (isset($routput[0]) && strncasecmp($routput[0], "ERROR", 5) == 0){
		$pre_error = implode("<br />", $routput);
		$venterror = true;
		if (!empty($cache['data'])){
			$usecached = true;
			$routput = explode("/%/", $cache['data']);
			unset($cache['data']);
			$ventrilo->displaymessage($pre_error.'<br /><br />{TEXT_CACHED_LOADED}');
			$tuntilrefresh = ($refreshtime + $cache['refreshcache']) - $cache['timestamp'];
		}
		else{
			early_error($pre_error);
		}
	}

	/*if ($usecached || !isset($venterror))*/{
		foreach($routput as $line1){
			$diffisor1 = strpos($line1,":");
			$start1 = substr($line1, 0 , $diffisor1);
			$end1 = strdecode(substr($line1, ($diffisor1+2)));
			if (trim($start1) == 'CHANNELFIELDS'){
				$ventchannelindex = explode(",",$end1);
			}

			elseif (trim($start1) == 'CHANNEL' || trim($start1) == 'CLIENT'){
				$loutput = explode(",",$end1);
				foreach($loutput as $line2){
					$diffisor2 = strpos($line2,"=");
					$start2 = substr($line2, 0 , $diffisor2);
					$end2 = strdecode(substr($line2, ($diffisor2+1)));
					$ventdata[$start2] = $end2;
				}
				if (trim($start1) == 'CLIENT'){
					$ventclients[$ventdata['CID']][] = $ventdata;
				}
				elseif (trim($start1) == 'CHANNEL'){
					$ventchannels[$ventdata['PID']][$ventdata['CID']] = $ventdata;
				}
			}

			elseif (trim($start1) == 'CLIENTFIELDS'){
				$ventclientindex = explode(",",$end1);
			}
			else{
				$ventserver[$start1] = $end1;
			}
		}

		if (!$usecached){
			if (isset($ts['id']) && $cache['refreshcache'] != 0){
				$db->execquery('updatecachedata',savecache($routput));
			}
		}
		unset($routput);

//		print_r($ventserver);
//		print_r($ventchannels);
//		print_r($ventclients);
//		print_r($ventchannelindex);
//		print_r($ventclientindex);

		$ventrilo->insert_display('{DATA_STATUS}', $tssettings['Retrieved data status']);
		if ($tssettings['Retrieved data status']){
			$cachelive = print_check_cache_lifetime();
			$ventrilo->insert_content('{DATA_STATUS}', $cachelive);
		}

		$ventrilo->insert_display('{SERVER_INFO}', $tssettings['Show server information']);

		$ventrilo->insert_text('{SERVER_NAME}', htmlspecialchars($ventserver['NAME']));
		$ventrilo->insert_text('{SERVER_PHONETIC}', htmlspecialchars($ventserver['PHONETIC']));
		$ventrilo->insert_text('{PLATFORM}', $ventserver['PLATFORM']);
		$ventrilo->insert_text('{VERSION}', $ventserver['VERSION']);
		$ventrilo->insert_text('{COMMENT}', htmlentities($ventserver['COMMENT']));
		$ventrilo->insert_text('{UDPPORT}', $ts['port']);
		$ventrilo->insert_text('{MAXCLIENTS}', $ventserver['MAXCLIENTS']);
		$ventrilo->insert_text('{CLIENTS_CON}', $ventserver['CLIENTCOUNT']);
		$ventrilo->insert_text('{CHANNEL_COUNT}', $ventserver['CHANNELCOUNT']);

		$uptime = formattime($ventserver['UPTIME']);
		$ventrilo->insert_content('{UPTIME}', $uptime);

		$password_prot = (($ventserver['AUTH']) ? '{TEXT_YES}' : '{TEXT_NO}');
		$ventrilo->insert_content('{PASSWORD_PROT}', $password_prot);

#############
## DISPLAY ##
#############

		$div_content = '{TEXT_SERVER_NAME}: '.$ventserver['NAME'].'
{TEXT_SERVER_PHONETIC}: '.$ventserver['PHONETIC'].'
{TEXT_PLATFORM}: '.$ventserver['PLATFORM'].'
{TEXT_VERSION}: '.$ventserver['VERSION'].'
{TEXT_UPTIME}: '.$uptime.'
{TEXT_PASSWORD_PROT}: '.$password_prot.'
{TEXT_UDPPORT}: '.$ts['port'].'
{TEXT_MAXCLIENTS}: '.$ventserver['MAXCLIENTS'].'
{TEXT_CLIENTS_CON}: '.$ventserver['CLIENTCOUNT'].'
{TEXT_CHANNEL_COUNT}: '.$ventserver['CHANNELCOUNT'].'

{TEXT_COMMENT}: '.$ventserver['COMMENT'];

		$div_content = prep_tooltip($div_content);

		$server_content = '
    <tr class="server_row">
	  <td width="90%" nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="16" height="16" src="images/vent/server.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($ventserver['NAME']) .'
      </p></td>
	  <td width="10%" nowrap><p>{TEXT_PING}</p></td>
	</tr>
';

		if (isset($ventclients[0])){
			uasort($ventclients[0], "SORT_VENTCHANNELS");
			reset($ventclients[0]);

			foreach($ventclients[0] as $player){ 
		//Informatie echo'en...
				$div_content = '{TEXT_NAME}: '.$player['NAME'].'
{TEXT_ADMIN}: '.(($player['ADMIN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'
{TEXT_LOGGEDINFOR}: '.formattime($player['SEC']).'

{TEXT_COMMENT}: '.$player['COMM'];

				$div_content = prep_tooltip($div_content);

				$server_content .= '    <tr class="client_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="16" height="16" src="images/vent/client.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['NAME']) .((!empty($player['COMM'])) ? ' (<span class="ventcomment">'.htmlentities(linewrap($player['COMM'], 30)).'</span>)' : NULL ).'
      </p></td>
	  <td nowrap><p>'.$player['PING'].'ms</p></td>
	</tr>
';
			}
		} 

		$server_content .= vent_channels($ventchannels, $ventclients);

		$ventrilo->insert_content('{CHANNEL_INFO_CONTENT}', $server_content);
	}
}
else{
	early_error('{TEXT_VENTRILO_EXEC_ERROR;'.$tssettings['Ventrilo status program'].';}<br /><br />
{TEXT_RETURNED_EXEC_ERROR;'.implode("<br />", $routput).' ('.$execcmd.')'.';}');
}

$ventrilo->load_language('lng_index_sub');
$ventrilo->load_template('tpl_ventrilo');
$ventrilo->process();
$ventrilo->output();
unset($ventrilo);
?>

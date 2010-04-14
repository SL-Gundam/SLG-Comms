<?php
/***************************************************************************
 *                              ventrilo.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: ventrilo.inc.php,v 1.30 2005/07/30 00:35:49 SC Kruiper Exp $
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
	exec($tssettings['Ventrilo status program'].' -c2 -t'.$ipstring .' 2>&1', $routput, $execcmd);
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
			$end1 = substr($line1, $diffisor1+2);
			if (trim($start1) == 'CHANNELFIELDS'){
				$ventchannelindex = explode(",",$end1);
			}

			elseif (trim($start1) == 'CHANNEL'){
				$loutput = explode(",",$end1);
				foreach($loutput as $line2){
					$diffisor2 = strpos($line2,"=");
					$start2 = substr($line2, 0 , $diffisor2);
					$end2 = substr($line2, $diffisor2+1);
					$ventchannel[$start2] = $end2;
				}
				$ventchannels[$ventchannel['PID']][$ventchannel['CID']] = $ventchannel;
			}

			elseif (trim($start1) == 'CLIENTFIELDS'){
				$ventclientindex = explode(",",$end1);
			}
			elseif (trim($start1) == 'CLIENT'){
				$loutput = explode(",",$end1);
				foreach($loutput as $line2){
					$diffisor2 = strpos($line2,"=");
					$start2 = substr($line2, 0 , $diffisor2);
					$end2 = substr($line2, $diffisor2+1);
					$ventclient[$start2] = $end2;
				}
				$ventclients[$ventclient['CID']][] = $ventclient;
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

		$alt_title_content = '{TEXT_SERVER_NAME}: {SERVER_NAME}
{TEXT_SERVER_PHONETIC}: {SERVER_PHONETIC}
{TEXT_PLATFORM}: {PLATFORM}
{TEXT_VERSION}: {VERSION}
{TEXT_UPTIME}: '.$uptime.'
{TEXT_PASSWORD_PROT}: '.$password_prot.'
{TEXT_UDPPORT}: {UDPPORT}
{TEXT_MAXCLIENTS}: {MAXCLIENTS}
{TEXT_CLIENTS_CON}: {CLIENTS_CON}
{TEXT_CHANNEL_COUNT}: {CHANNEL_COUNT}

'.wordwrap('{TEXT_COMMENT}: '.$ventserver['COMMENT'], 50, "\n");

		$alt_title_content = htmlentities($alt_title_content);

		$server_content = '
    <tr class="server_row">
	  <td width="90%" nowrap><p title="'.$alt_title_content.'"><a href="javascript:MM_popupMsg(\''.convert_jspoptext($alt_title_content).'\')" class="server_row" onMouseOver="MM_displayStatusMsg(\'{TEXT_SHOW_HELPTEXT_S}\');return document.MM_returnValue" onMouseOut="MM_displayStatusMsg(\'\');return document.MM_returnValue">
<img width="16" height="16" src="images/vent/server.gif" align="absmiddle" alt="'.$alt_title_content.'" title="'.$alt_title_content.'" border="0">&nbsp;'. htmlspecialchars($ventserver['NAME']) .'</a>
      </p></td>
	  <td width="10%" nowrap><p>{TEXT_PING}</p></td>
	</tr>
';

		if (isset($ventclients[0])){
			uasort($ventclients[0], "SORT_VENTCHANNELS");
			reset($ventclients[0]);

			foreach($ventclients[0] as $player){ 
		//Informatie echo'en...
				$alt_title_content = '{TEXT_NAME}: '.$player['NAME'].'
{TEXT_ADMIN}: '.(($player['ADMIN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'
{TEXT_LOGGEDINFOR}: '.formattime($player['SEC']).'

'.wordwrap('{TEXT_COMMENT}: '.$player['COMM'], 50, "\n");

				$alt_title_content = htmlentities($alt_title_content);

				$server_content .= '    <tr class="client_row">
	  <td nowrap><p title="'.$alt_title_content.'"><a href="javascript:MM_popupMsg(\''.convert_jspoptext($alt_title_content).'\')" class="client_row" onMouseOver="MM_displayStatusMsg(\'{TEXT_SHOW_HELPTEXT_PL}\');return document.MM_returnValue" onMouseOut="MM_displayStatusMsg(\'\');return document.MM_returnValue">
<img width="16" height="16" src="images/vent/client.gif" align="absmiddle" alt="'.$alt_title_content.'" title="'.$alt_title_content.'" border="0">&nbsp;'. htmlspecialchars($player['NAME']) .((!empty($player['COMM'])) ? ' (<span class="ventcomment">'.htmlentities(linewrap($player['COMM'], 30)).'</span>)' : NULL ).'</a>
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

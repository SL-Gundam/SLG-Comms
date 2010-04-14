<?php
/***************************************************************************
 *                              ventrilo.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: ventrilo.inc.php,v 1.33 2005/09/20 22:33:47 SC Kruiper Exp $
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
		$pre_error = implode(" ", $routput).' ('.$execcmd.')';
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
			$ext_line1 = explode(':', $line1, 2);
			$ext_line1[0] = trim($ext_line1[0]);
			$ext_line1[1] = trim($ext_line1[1]);

			if (trim($ext_line1[0]) == 'CHANNELFIELDS'){
				$ext_line1[1] = strdecode($ext_line1[1]);
				$ventchannelindex = explode(",",$ext_line1[1]);
			}

			elseif (trim($ext_line1[0]) == 'CHANNEL' || trim($ext_line1[0]) == 'CLIENT'){
				$loutput = explode(",",$ext_line1[1]);
				foreach($loutput as $line2){
					$ext_line2 = explode('=', $line2, 2);
					$ext_line2[0] = trim($ext_line2[0]);
					$ext_line2[1] = strdecode(trim($ext_line2[1]));
					$ventdata[$ext_line2[0]] = $ext_line2[1];
				}
				if (trim($ext_line1[0]) == 'CLIENT'){
					$ventclients[$ventdata['CID']][] = $ventdata;
				}
				elseif (trim($ext_line1[0]) == 'CHANNEL'){
					$ventchannels[$ventdata['PID']][$ventdata['CID']] = $ventdata;
				}
			}

			elseif (trim($ext_line1[0]) == 'CLIENTFIELDS'){
				$ext_line1[1] = strdecode($ext_line1[1]);
				$ventclientindex = explode(",",$ext_line1[1]);
			}
			else{
				$ext_line1[1] = strdecode($ext_line1[1]);
				$ventserver[$ext_line1[0]] = $ext_line1[1];
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

		$ventserver['VOICECODEC'] = explode(',', $ventserver['VOICECODEC'], 2);
		$ventserver['VOICEFORMAT'] = explode(',', $ventserver['VOICEFORMAT'], 2);
		$uptime = formattime($ventserver['UPTIME']);
		$password_prot = (($ventserver['AUTH']) ? '{TEXT_YES}' : '{TEXT_NO}');

		if ($tssettings['Show server information']){
			$ventrilo->insert_text('{SERVER_NAME}', htmlspecialchars($ventserver['NAME']));
			$ventrilo->insert_text('{SERVER_PHONETIC}', htmlspecialchars($ventserver['PHONETIC']));
			$ventrilo->insert_text('{PLATFORM}', $ventserver['PLATFORM']);
			$ventrilo->insert_text('{VERSION}', $ventserver['VERSION']);
			$ventrilo->insert_text('{COMMENT}', htmlentities($ventserver['COMMENT']));
			$ventrilo->insert_text('{UDPPORT}', $ts['port']);
			$ventrilo->insert_text('{MAXCLIENTS}', $ventserver['MAXCLIENTS']);
			$ventrilo->insert_text('{CLIENTS_CON}', $ventserver['CLIENTCOUNT']);
			$ventrilo->insert_text('{CHANNEL_COUNT}', $ventserver['CHANNELCOUNT']);
			$ventrilo->insert_text('{VOICECODEC}', $ventserver['VOICECODEC'][1]);
			$ventrilo->insert_text('{VOICEFORMAT}', $ventserver['VOICEFORMAT'][1]);
			$ventrilo->insert_content('{UPTIME}', $uptime);
			$ventrilo->insert_content('{PASSWORD_PROT}', $password_prot);
		}

#############
## DISPLAY ##
#############

		$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_SERVER_NAME}:&amp;nbsp;</td><td>'.prep_tooltip($ventserver['NAME']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_SERVER_PHONETIC}:&amp;nbsp;</td><td>'.prep_tooltip($ventserver['PHONETIC']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PLATFORM}:&amp;nbsp;</td><td>'.prep_tooltip($ventserver['PLATFORM']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_VERSION}:&amp;nbsp;</td><td>'.prep_tooltip($ventserver['VERSION']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_UPTIME}:&amp;nbsp;</td><td>'.prep_tooltip($uptime).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PASSWORD_PROT}:&amp;nbsp;</td><td>'.$password_prot.'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_UDPPORT}:&amp;nbsp;</td><td>'.prep_tooltip($ts['port']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_MAXCLIENTS}:&amp;nbsp;</td><td>'.$ventserver['MAXCLIENTS'].'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CLIENTS_CON}:&amp;nbsp;</td><td>'.$ventserver['CLIENTCOUNT'].'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CHANNEL_COUNT}:&amp;nbsp;</td><td>'.$ventserver['CHANNELCOUNT'].'</td></tr>
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_VOICECODEC}:&amp;nbsp;</td><td>'.prep_tooltip($ventserver['VOICECODEC'][1]).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_VOICEFORMAT}:&amp;nbsp;</td><td>'.prep_tooltip($ventserver['VOICEFORMAT'][1]).'</td></tr>'.((!empty($ventserver['COMMENT'])) ? '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_COMMENT}:&amp;nbsp;</td><td>'.prep_tooltip($ventserver['COMMENT']).'</td></tr>' : NULL ).'</table>';

//		$div_content = prep_tooltip($div_content);
		$div_content = str_replace("\n", '', $div_content);

		$server_content = '
    <tr class="server_row">
	  <td width="90%" nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="16" height="16" src="images/vent/server.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($ventserver['NAME']) .((!empty($ventserver['COMMENT'])) ? '&nbsp;&nbsp;&nbsp;(<span class="ventcomment">'.htmlentities(linewrap($ventserver['COMMENT'], 30)).'</span>)' : NULL ).'
      </p></td>
	  <td width="10%" nowrap><p>{TEXT_PING}</p></td>
	</tr>
';

		if (isset($ventclients[0])){
			uasort($ventclients[0], "SORT_VENTCHANNELS");
			reset($ventclients[0]);

			foreach($ventclients[0] as $player){ 
		//Informatie echo'en...
				$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_NAME}:&amp;nbsp;</td><td>'.prep_tooltip($player['NAME']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_ADMIN}:&amp;nbsp;</td><td>'.(($player['ADMIN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'</td></tr>'.((isset($player['PHAN'])) ? '
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PHANTOM}:&amp;nbsp;</td><td>'.(($player['PHAN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'</td></tr>' : NULL ).'
<tr><td nowrap valign=\\\'top\\\'>{TEXT_LOGGEDINFOR}:&amp;nbsp;</td><td>'.prep_tooltip(formattime($player['SEC'])).'</td></tr>'.((!empty($player['COMM'])) ? '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_COMMENT}:&amp;nbsp;</td><td>'.prep_tooltip($player['COMM']).'</td></tr>' : NULL ).'</table>';

//				$div_content = prep_tooltip($div_content);
				$div_content = str_replace("\n", '', $div_content);

				$server_content .= '    <tr class="'.((isset($player['PHAN']) && $player['PHAN']) ? 'ventclient_phantom_row' : 'client_row').'">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="16" height="16" src="images/vent/client.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['NAME']) .((!empty($player['COMM'])) ? '&nbsp;&nbsp;&nbsp;(<span class="ventcomment">'.htmlentities(linewrap($player['COMM'], 30)).'</span>)' : NULL ).'
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
{TEXT_RETURNED_EXEC_ERROR;'.implode(" ", $routput).' ('.$execcmd.')'.';}');
}

$ventrilo->load_language('lng_index_sub');
$ventrilo->load_template('tpl_ventrilo');
$ventrilo->process();
$ventrilo->output();
unset($ventrilo);
?>

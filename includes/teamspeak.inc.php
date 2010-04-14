<?php
/***************************************************************************
 *                             teamspeak.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: teamspeak.inc.php,v 1.31 2005/11/06 23:09:59 SC Kruiper Exp $
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

if (!$tssettings['TeamSpeak support']){
	early_error('{TEXT_SUPPORT_TS_DISABLED}');
}

$teamspeak = new template;
$template = 'teamspeak';

include('includes/functions.ts.inc.php');

$channels = array();
$subchannels = array();
$players = array();
$vserver = array();
$gserver = array();
$calc_values = array();

//Connection with server...
if (!$usecached){
	$cmd = "sel " . $ts['port'] . "
cl
pl
si
gi
quit\n";

	if (defined("DEBUG")){
		$cnt_time = new timecount;
		$cnt_time->starttimecount();
	}

	$connection = @fsockopen (addslashes($ts['ip']), addslashes($ts['queryport']), $errno, $errstr, 2);

	if (defined("DEBUG")){
		$cnt_time->endtimecount();
	}
}
else{
	$connection = true;
}

if (!$connection){
	$pre_error = $errstr.' ('.$errno.').';
	if (!empty($cache['data'])){
		$usecached = true;
		$teamspeak->displaymessage($pre_error.'<br /><br />{TEXT_CACHED_LOADED}');
		$tuntilrefresh = ($refreshtime + $cache['refreshcache']) - $cache['timestamp'];
	}
	else{
		early_error($pre_error);
	}
}

/*if ($usecached || $connection)*/{
	if (!$usecached){
		if (defined("DEBUG")){
			$cnt_time = new timecount;
			$cnt_time->starttimecount();
		}

		fwrite($connection,$cmd, strlen($cmd));
		$cache['data'] = NULL;
		while ($userdata = fgets($connection, 4096)){
			$cache['data'] .= $userdata;
		}
		fclose($connection);

		if (defined("DEBUG")){
			$cnt_time->endtimecount();
			// Because this is DEBUG only output it will be outputted outside of the template class.
			echo '<table border="0" align="center"><tr><td><p class="error">DEBUG: Processing time required for retrieving the live server data: '.round($cnt_time->tottime, 4).'s</p></td></tr></table><p></p>';
		}
	}
	$cache['data'] = explode("\r\n", $cache['data']);

	$type = array('select','channels','players','vserver','gserver');
	reset($type);
	reset($cache['data']);
	$counter = 0;
	$calc_values['SA'] = 0;
	while($userdata = current($cache['data'])){
//		echo $userdata.'<br />';
//		$userdatamerge[] = explode("\t", $userdata);
//		echo $userdata;
		$userdata = trim($userdata);
		if($userdata === '[TS]'){
			$counter = 0;
			$data_accept = true;
		}
		elseif (!isset($data_accept) || !$data_accept){
			early_error('{TEXT_NOTTEAMSPEAK}');
		}
		elseif ($userdata === 'OK'){
			next($type);
			$counter = 0;
		}
		elseif(!$usecached && strncasecmp($userdata, 'ERROR', 5) === 0){
			early_error('{TEXT_TS_COMMAND_ERROR;'.current($type).';}<br /><br />
{TEXT_RETURNED_ERROR}: "'.$userdata.'"'.((strcasecmp($userdata, 'ERROR, invalid id') === 0 && current($type) === 'select') ? '<br />
{TEXT_TSINVALIDID_ERROR}' : NULL ));
		}
		else{
			switch (current($type)){
				case 'channels':
					{
						if ($counter === 0){
							$indexchannel = explode("\t", $userdata);
							$counter++;
						}
						else{
							$channeldata = explode("\t", $userdata);

							$channel = array_combine($indexchannel, $channeldata);

							$channel['name'] = trim($channel['name'], "\x22\x27");
							$channel['topic'] = trim($channel['topic'], "\x22\x27");
							$channel['slg_sortname'] = prepare_sort_name($channel['name']);
							if($channel['parent'] > 0){
								$subchannels[$channel['parent']][$channel['id']] = $channel;
							}
							else{
								$channels[$channel['id']] = $channel;
							}
						}
						//$channels[] = explode("\t", $userdata);
					}
					break;
				case 'players':
					{
						if ($counter === 0){
							$indexplayer = explode("\t", $userdata);
							$counter++;
						}
						else{
							$playerdata = explode("\t", $userdata);

							$player = array_combine($indexplayer, $playerdata);

							$player['nick'] = trim($player['nick'], "\x22\x27");
							$player['loginname'] = trim($player['loginname'], "\x22\x27");
//							$player['ip'] = trim($player['ip'], "\x22\x27"); // not used yet so no need to process this line.
							$player['slg_sortname'] = prepare_sort_name($player['nick']);
							if (($player['pprivs'] & 1) == 1){
								$calc_values['SA']++;
							}
							$players[$player['c_id']][$player['p_id']] = $player;
						}
						//$players[] = explode("\t", $userdata);
					}
					break;
				case 'vserver':
					{
						$arrtmp = explode('=', $userdata, 2);
						$vserver[$arrtmp[0]] = $arrtmp[1];
						//$vserver[] = explode("\t", $userdata);
					}
					break;
				case 'gserver':
					{
						$arrtmp = explode('=', $userdata, 2);
						$gserver[$arrtmp[0]] = $arrtmp[1];
						//$gserver[] = explode("\t", $userdata);
					}
					break;
				default:
					early_error('{TEXT_DATA_TYPE_ERROR}'.current($type));
			}
		}
		next($cache['data']);
	}
	if (!$usecached && isset($ts['id']) && $cache['refreshcache'] != 0){
		$db->execquery('updatecachedata',savecache($cache['data']));
	}
	unset($cache['data']);
//}

//print_r($channels);
//print_r($subchannels);
//print_r($players);
//print_r($vserver);
//print_r($gserver);
//print_r($cache['data']);
//print_r($calc_values);

	$teamspeak->insert_display('{DATA_STATUS}', $tssettings['Retrieved data status']);
	if ($tssettings['Retrieved data status']){
		$cachelive = print_check_cache_lifetime($usecached, $cache, ((isset($tuntilrefresh)) ? $tuntilrefresh : NULL ), !$connection);
		$teamspeak->insert_content('{DATA_STATUS}', $cachelive);
	}

	$teamspeak->insert_display('{SERVER_INFO}', $tssettings['Show server information']);

	$uptime = formattime($vserver['server_uptime']);
	$password_prot = (($vserver['server_password']) ? '{TEXT_YES}' : '{TEXT_NO}');
	$clanserver = (($vserver['server_clan_server']) ? '{TEXT_YES}' : '{TEXT_NO}');
	$datasent = formatbytes($vserver['server_bytessend']);
	$datareceived = formatbytes($vserver['server_bytesreceived']);

	if ($tssettings['Show server information']){
		$teamspeak->insert_text('{SERVER_NAME}', htmlspecialchars($vserver['server_name']));
		$teamspeak->insert_text('{PLATFORM}', $vserver['server_platform']);
		$teamspeak->insert_text('{VERSION}', $gserver['total_server_version']);
		$teamspeak->insert_text('{WELCOME}', htmlentities($vserver['server_welcomemessage']));
		$teamspeak->insert_text('{MAXCLIENTS}', $vserver['server_maxusers']);
		$teamspeak->insert_text('{CLIENTS_CON}', $vserver['server_currentusers']);
		$teamspeak->insert_text('{ADMINS_CON}', $calc_values['SA']);
		$teamspeak->insert_text('{CHANNEL_COUNT}', $vserver['server_currentchannels']);
		$teamspeak->insert_text('{UDPPORT}', $vserver['server_udpport']);
		$teamspeak->insert_content('{UPTIME}', $uptime);
		$teamspeak->insert_content('{PASSWORD_PROT}', $password_prot);
		$teamspeak->insert_content('{CLANSERVER}', $clanserver);
		$teamspeak->insert_content('{DATASENT}', $datasent);
		$teamspeak->insert_content('{DATARECEIVED}', $datareceived);
		$teamspeak->insert_display('{PROVIDER}', (isset($gserver['isp_ispname']) || isset($vserver['isp_ispname'])));
	}

	if (isset($gserver['isp_ispname']) || isset($vserver['isp_ispname'])){
		$var = ((isset($vserver['isp_ispname'])) ? 'v' : 'g').'server';
		$isp_name = ${$var}['isp_ispname'];

		if (isset(${$var}['isp_linkurl'])){
			$isp_linkurl1 = ${$var}['isp_linkurl'];
			$isp_linkurl2 = prepare_http_link($isp_linkurl1);
			$isp_linkurl1 = prepare_http_link($isp_linkurl1, NULL, false);
		}

		if (isset(${$var}['isp_adminemail'])){
			$isp_adminemail1 = ${$var}['isp_adminemail'];
			$isp_adminemail2 = prepare_email_addr($isp_adminemail1);
			$isp_adminemail1 = prepare_email_addr($isp_adminemail1, NULL, false);
		}

		if ($tssettings['Show server information']){
			$teamspeak->insert_text('{PROVIDER}', ((isset($isp_name)) ? htmlspecialchars($isp_name) : NULL ));
			$teamspeak->insert_text('{PROVIDER_WEBSITE}', ((isset($isp_linkurl2)) ? $isp_linkurl2 : NULL ));
			$teamspeak->insert_text('{PROVIDER_EMAIL}', ((isset($isp_adminemail2)) ? $isp_adminemail2 : NULL ));
		}
	}

#############
## DISPLAY ##
#############

	$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_SERVER_NAME}:&amp;nbsp;</td><td>'.prep_tooltip($vserver['server_name']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PLATFORM}:&amp;nbsp;</td><td>'.prep_tooltip($vserver['server_platform']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_VERSION}:&amp;nbsp;</td><td>'.prep_tooltip($gserver['total_server_version']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_UPTIME}:&amp;nbsp;</td><td>'.prep_tooltip($uptime).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PASSWORD_PROT}:&amp;nbsp;</td><td>'.$password_prot.'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CLANSERVER}:&amp;nbsp;</td><td>'.$clanserver.'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_UDPPORT}:&amp;nbsp;</td><td>'.$vserver['server_udpport'].'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_DATASENT}:&amp;nbsp;</td><td>'.prep_tooltip($datasent).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_DATARECEIVED}:&amp;nbsp;</td><td>'.prep_tooltip($datareceived).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_MAXCLIENTS}:&amp;nbsp;</td><td>'.$vserver['server_maxusers'].'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CLIENTS_CON}:&amp;nbsp;</td><td>'.$vserver['server_currentusers'].'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_ADMINS_CON}:&amp;nbsp;</td><td>'.$calc_values['SA'].'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CHANNEL_COUNT}:&amp;nbsp;</td><td>'.$vserver['server_currentchannels'].'</td></tr>'.((isset($gserver['isp_ispname']) || isset($vserver['isp_ispname'])) ? '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PROVIDER}:&amp;nbsp;</td><td>'.((isset($isp_name)) ? prep_tooltip($isp_name).'</td></tr>' : NULL ).'
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PROVIDER_WEBSITE}:&amp;nbsp;</td><td>'.((isset($isp_linkurl1)) ? prep_tooltip($isp_linkurl1).'</td></tr>' : NULL ).'
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PROVIDER_EMAIL}:&amp;nbsp;</td><td>'.((isset($isp_adminemail1)) ? prep_tooltip($isp_adminemail1).'</td></tr>' : NULL ) : NULL ).((!empty($vserver['server_welcomemessage'])) ? '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_WELCOME}:&amp;nbsp;</td><td>'.prep_tooltip($vserver['server_welcomemessage']).'</td></tr>' : NULL ).'</table>';

//	$div_content = prep_tooltip($div_content);
	$div_content = str_replace("\n", '', $div_content);

	$server_content = '
    <tr class="server_row">
	  <td width="90%" nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="16" height="16" src="images/ts/bullet_server.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($vserver['server_name']) .'
      </p></td>
	  <td width="10%" nowrap><p>{TEXT_PING}</p></td>
	</tr>
';

	uasort($channels, "SORT_CHANNELS");
	reset($channels);
	foreach($channels as $channel){
		//Information echo'en...
		$chflags = ch_flags($channel['flags']);
		$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CHANNEL}:&amp;nbsp;</td><td>'.prep_tooltip($channel['name']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_TOPIC}:&amp;nbsp;</td><td>'.prep_tooltip($channel['topic']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PASSWORD_PROT}:&amp;nbsp;</td><td>'.(($channel['password']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CODEC}:&amp;nbsp;</td><td>'.prep_tooltip(formatcodec($channel['codec'])).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_MAXCLIENTS}:&amp;nbsp;</td><td>'.$channel['maxusers'].'</td></tr>
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_EXPLAIN_TSFLAGS_CHANNEL}:&amp;nbsp;</td><td>'.$chflags.'</td></tr>';
		$exp_flags = explode("\n",trim(chunk_split($chflags, 1, "\n")));
		foreach ($exp_flags as $flag){
			$div_content .= '
<tr><td>&amp;nbsp;</td><td>'.$flag.':&amp;nbsp;{TEXT_EXPLAIN_TSFLAG_'.$flag.'}</td></tr>';
		}
		$div_content .= '</table>';

//		$div_content = prep_tooltip($div_content);
		$div_content = str_replace("\n", '', $div_content);

		$server_content .= '    <tr class="channel_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="32" height="16" src="images/ts/bullet_channel.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($channel['name']).'&nbsp;&nbsp;&nbsp;('.$chflags.')
      </p></td>
	  <td nowrap><p>&nbsp;</p></td>
	</tr>
';
 
	//Sub-Channel Data
		if (isset($subchannels[$channel['id']])){
			uasort($subchannels[$channel['id']], "SORT_CHANNELS");
			reset($subchannels[$channel['id']]);

			foreach($subchannels[$channel['id']] as $subchannel){
				//Sub-Channel Informatie echo'en...
				$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_SUBCHANNEL}:&amp;nbsp;</td><td>'. prep_tooltip($subchannel['name']) .'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_TOPIC}:&amp;nbsp;</td><td>'.prep_tooltip($subchannel['topic']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PASSWORD_PROT}:&amp;nbsp;</td><td>'.(($subchannel['password']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CODEC}:&amp;nbsp;</td><td>'.prep_tooltip(formatcodec($subchannel['codec'])).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_MAXCLIENTS}:&amp;nbsp;</td><td>'.$subchannel['maxusers'].'</td></tr></table>';

//				$div_content = prep_tooltip($div_content);
				$div_content = str_replace("\n", '', $div_content);

				$server_content .= '    <tr class="channel_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img width="32" height="16" src="images/ts/bullet_channel.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($subchannel['name']) .'
      </p></td>
	  <td nowrap><p>&nbsp;</p></td>
	</tr>
';

				//Playerdata in subchannel...
				if (isset($players[$subchannel['id']])){
					uasort($players[$subchannel['id']], "SORT_PLAYERS");
					reset($players[$subchannel['id']]);

					foreach($players[$subchannel['id']] as $player){
						//Informatie echo'en...
						$plflags = pl_flags($player['pprivs'], $player['cprivs']);
						$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_NAME}:&amp;nbsp;</td><td>'.prep_tooltip($player['nick']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_LOGGEDINFOR}:&amp;nbsp;</td><td>'.prep_tooltip(formattime($player['logintime'])).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_IDLEFOR}:&amp;nbsp;</td><td>'.prep_tooltip(formattime($player['idletime'])).'</td></tr>
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_EXPLAIN_TSFLAGS_PLAYER}:&amp;nbsp;</td><td>'.$plflags.'</td></tr>';
						$exp_flags = explode(' ', $plflags);
						foreach ($exp_flags as $flag){
							$div_content .= '
<tr><td>&amp;nbsp;</td><td>'.$flag.':&amp;nbsp;{TEXT_EXPLAIN_TSFLAG_'.$flag.'}</td></tr>';
						}
						$div_content .= '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_EXPLAIN_TSSTATUS_PLAYER}:&amp;nbsp;</td><td>'.pl_status($player['pflags']).( (($player['pprivs'] & 2) == 2) ? '<br />{TEXT_PL_RIGHT_2}' : NULL ).'</td></tr></table>';

//						$div_content = prep_tooltip($div_content);
						$div_content = str_replace("\n", '', $div_content);

						$server_content .= '    <tr class="client_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img width="48" height="16" src="images/ts/bullet_'. pl_img($player['pflags']) .'.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['nick']) .'&nbsp;&nbsp;&nbsp;('. $plflags. ( (($player['pflags'] & 64) == 64) ? ' Rec' : NULL ) .')'.( (($player['pflags'] & 2) == 2) ? ' WV' : NULL ).'
      </p></td>
	  <td nowrap><p>'.$player['ping'].'ms</p></td>
	</tr>
';
					}
				}
			}
		}
 
		//Playerdata...
		if (isset($players[$channel['id']])){
			uasort($players[$channel['id']], "SORT_PLAYERS");
			reset($players[$channel['id']]);

			foreach($players[$channel['id']] as $player){ 
				//Informatie echo'en...
				$plflags = pl_flags($player['pprivs'], $player['cprivs']);
				$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_NAME}:&amp;nbsp;</td><td>'.prep_tooltip($player['nick']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_LOGGEDINFOR}:&amp;nbsp;</td><td>'.prep_tooltip(formattime($player['logintime'])).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_IDLEFOR}:&amp;nbsp;</td><td>'.prep_tooltip(formattime($player['idletime'])).'</td></tr>
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_EXPLAIN_TSFLAGS_PLAYER}:&amp;nbsp;</td><td>'.$plflags.'</td></tr>';
				$exp_flags = explode(' ', $plflags);
				foreach ($exp_flags as $flag){
					$div_content .= '
<tr><td>&amp;nbsp;</td><td>'.$flag.':&amp;nbsp;{TEXT_EXPLAIN_TSFLAG_'.$flag.'}</td></tr>';
				}
				$div_content .= '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_EXPLAIN_TSSTATUS_PLAYER}:&amp;nbsp;</td><td>'.pl_status($player['pflags']).( (($player['pprivs'] & 2) == 2) ? '<br />{TEXT_PL_RIGHT_2}' : NULL ).'</td></tr></table>';

//				$div_content = prep_tooltip($div_content);
				$div_content = str_replace("\n", '', $div_content);

				$server_content .= '    <tr class="client_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="48" height="16" src="images/ts/bullet_'. pl_img($player['pflags']) .'.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['nick']) .'&nbsp;&nbsp;&nbsp;('. $plflags. ( (($player['pflags'] & 64) == 64) ? ' Rec' : NULL ) .')'.( (($player['pflags'] & 2) == 2) ? ' WV' : NULL ).'
      </p></td>
	  <td nowrap><p>'.$player['ping'].'ms</p></td>
	</tr>
';
			}
		} 
	}
	$teamspeak->insert_content('{CHANNEL_INFO_CONTENT}', $server_content);
}

$teamspeak->load_language('lng_index_sub');
$teamspeak->load_language('lng_index_ts');
$teamspeak->load_template('tpl_teamspeak');
$teamspeak->process();
$teamspeak->output();
unset($teamspeak);
?>

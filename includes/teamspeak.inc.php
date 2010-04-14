<?php
/***************************************************************************
 *                             teamspeak.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: teamspeak.inc.php,v 1.26 2005/09/12 23:13:45 SC Kruiper Exp $
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

$teamspeak = new template;
$template = 'teamspeak';

include('includes/functions.ts.inc.php');

$channels = array();
$subchannels = array();
$players = array();
$vserver = array();
$gserver = array();

//Connection with server...
if (!$usecached){
	$cmd = "sel " . $ts['port'] . "
cl
pl
si
gi
quit\n";
	$connection = @fsockopen ($ts['ip'], $ts['queryport'], $errno, $errstr, 1);
}
else $connection = true;
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
		if (isset($ts['id']) && $cache['refreshcache'] != 0){
			$cacheuserdata = NULL;
		}
		fwrite($connection,$cmd, strlen($cmd));
	}
	else{
		$cachedata = explode("\n", $cache['data']);
		reset($cachedata);
	}
	if (isset($cache['data'])){
		unset($cache['data']);
	}
	$type = array('select','channels','players','vserver','gserver');
	reset($type);
	$counter = 0;
	while((!$usecached && ($userdata = fgets($connection, 4096))) || ($usecached && ($userdata = current($cachedata)))){
//		print $userdata;
		if (!$usecached && isset($ts['id']) && $cache['refreshcache'] != 0){
			$cacheuserdata .= $userdata;
		}
		$userdata = trim($userdata);
//		$userdatamerge[] = explode("	", $userdata);
//		print $userdata;
		if ($userdata == 'OK'){
			next($type);
			$counter = 0;
		}
		elseif($userdata == '[TS]'){
		}
		elseif(strncmp($userdata, "ERROR", 5) == 0){
			early_error('{TEXT_TS_COMMAND_ERROR;'.current($type).';}');
		}
		else{
			switch (current($type)){
				case 'channels':
					{
						if ($counter == 0){
							$indexchannel = explode("	", $userdata);
							$counter++;
						}
						else{
							$channeldata = explode("	", $userdata);
							reset($channeldata);
							reset($indexchannel);
							while (current($indexchannel)){
								$channel[current($indexchannel)] = current($channeldata);

								next($channeldata);
								next($indexchannel);
							}
							$channel['name'] = trim($channel['name'], "\x22\x27");
							$channel['topic'] = trim($channel['topic'], "\x22\x27");
							if($channel['parent'] > -1){
								$subchannels[$channel['parent']][] = $channel;
							}
							else{
								$channels[] = $channel;
							}
						}
						//$channels[] = explode("	", $userdata);
					}
					break;
				case 'players':
					{
						if ($counter == 0){
							$indexplayer = explode("	", $userdata);
							$counter++;
						}
						else{
							$playerdata = explode("	", $userdata);
							reset($playerdata);
							reset($indexplayer);
							while (current($indexplayer)){
								$player[current($indexplayer)] = current($playerdata);

								next($playerdata);
								next($indexplayer);
							}
							$player['nick'] = htmlspecialchars(trim($player['nick'], "\x22\x27"));
							$player['loginname'] = htmlspecialchars(trim($player['loginname'], "\x22\x27"));
							$players[$player['c_id']][] = $player;
						}
						//$players[] = explode("	", $userdata);
					}
					break;
				case 'vserver':
					{
						$diffisor = strpos($userdata,"=");
						$start = substr($userdata, 0 , $diffisor);
						$end = substr($userdata, $diffisor+1);
						$vserver[$start] = $end;
						//$vserver[] = explode("	", $userdata);
					}
					break;
				case 'gserver':
					{

						$diffisor = strpos($userdata,"=");
						$start = substr($userdata, 0 , $diffisor);
						$end = substr($userdata, $diffisor+1);
						$gserver[$start] = $end;
						//$gserver[] = explode("	", $userdata);
					}
					break;
				default:
					early_error('{TEXT_DATA_TYPE_ERROR}');
			}
		}
		if ($usecached){
			next($cachedata);
		}
//		print_r($userdatamerge);
	}
	if (!$usecached){
		fclose($connection);
		if (isset($ts['id']) && $cache['refreshcache'] != 0){
			$db->execquery('updatecachedata',savecache($cacheuserdata));
		}
	}
	unset($cacheuserdata);
	unset($cachedata);
//}

//print_r($channels);
//print_r($subchannels);
//print_r($players);
//print_r($vserver);
//print_r($gserver);
//print_r($userdatamerge);

	$teamspeak->insert_display('{DATA_STATUS}', $tssettings['Retrieved data status']);
	if ($tssettings['Retrieved data status']){
		$cachelive = print_check_cache_lifetime();
		$teamspeak->insert_content('{DATA_STATUS}', $cachelive);
	}

	$teamspeak->insert_display('{SERVER_INFO}', $tssettings['Show server information']);

	$teamspeak->insert_text('{SERVER_NAME}', htmlspecialchars($vserver['server_name']));
	$teamspeak->insert_text('{PLATFORM}', $vserver['server_platform']);
	$teamspeak->insert_text('{VERSION}', $gserver['total_server_version']);
	$teamspeak->insert_text('{WELCOME}', htmlentities($vserver['server_welcomemessage']));
	$teamspeak->insert_text('{MAXCLIENTS}', $vserver['server_maxusers']);
	$teamspeak->insert_text('{CLIENTS_CON}', $vserver['server_currentusers']);
	$teamspeak->insert_text('{CHANNEL_COUNT}', $vserver['server_currentchannels']);
	$teamspeak->insert_text('{UDPPORT}', $vserver['server_udpport']);

	$uptime = formattime($vserver['server_uptime']);
	$teamspeak->insert_content('{UPTIME}', $uptime);

	$password_prot = (($vserver['server_password']) ? '{TEXT_YES}' : '{TEXT_NO}');
	$teamspeak->insert_content('{PASSWORD_PROT}', $password_prot);

	$clanserver = (($vserver['server_clan_server']) ? '{TEXT_YES}' : '{TEXT_NO}');
	$teamspeak->insert_content('{CLANSERVER}', $clanserver);

	$datasent = formatbytes($vserver['server_bytessend']);
	$teamspeak->insert_content('{DATASENT}', $datasent);

	$datareceived = formatbytes($vserver['server_bytesreceived']);
	$teamspeak->insert_content('{DATARECEIVED}', $datareceived);

	$teamspeak->insert_display('{PROVIDER}', (isset($gserver['isp_ispname']) || isset($vserver['isp_ispname'])));
	if (isset($gserver['isp_ispname']) || isset($vserver['isp_ispname'])){
		$isp_name = ((isset($vserver['isp_ispname'])) ? $vserver['isp_ispname'] : $gserver['isp_ispname']);

		if (isset($gserver['isp_linkurl']) || isset($vserver['isp_linkurl'])){
			$isp_linkurl1 = ((isset($vserver['isp_linkurl'])) ? $vserver['isp_linkurl'] : $gserver['isp_linkurl'] );
			$checkurl = parse_url($isp_linkurl1);
			if (!isset($checkurl['scheme'])){
				$isp_linkurl1 = 'http://'.$isp_linkurl1;
			}
			$isp_linkurl2 = '<a href="'.$isp_linkurl1.'" target="_blank">'.$isp_linkurl1.'</a>';
		}

		if (isset($gserver['isp_adminemail']) || isset($vserver['isp_adminemail'])){
			$isp_adminemail1 = ((isset($vserver['isp_adminemail'])) ? $vserver['isp_adminemail'] : $gserver['isp_adminemail'] );
			$isp_adminemail2 = '<a href="mailto:'.$isp_adminemail1.'">'.$isp_adminemail1.'</a>';
		}

		$teamspeak->insert_text('{PROVIDER}', ((isset($isp_name)) ? htmlspecialchars($isp_name) : NULL ));
		$teamspeak->insert_text('{PROVIDER_WEBSITE}', ((isset($isp_linkurl2)) ? $isp_linkurl2 : NULL ));
		$teamspeak->insert_text('{PROVIDER_EMAIL}', ((isset($isp_adminemail2)) ? $isp_adminemail2 : NULL ));
	}

#############
## DISPLAY ##
#############

	uasort($channels, "SORT_CHANNELS");
	reset($channels);
	$div_content = '{TEXT_SERVER_NAME}: '.$vserver['server_name'].'
{TEXT_PLATFORM}: '.$vserver['server_platform'].'
{TEXT_VERSION}: '.$gserver['total_server_version'].'
{TEXT_UPTIME}: '.$uptime.'
{TEXT_PASSWORD_PROT}: '.$password_prot.'
{TEXT_CLANSERVER}: '.$clanserver.'
{TEXT_UDPPORT}: '.$vserver['server_udpport'].'
{TEXT_DATASENT}: '.$datasent.'
{TEXT_DATARECEIVED}: '.$datareceived.'
{TEXT_MAXCLIENTS}: '.$vserver['server_maxusers'].'
{TEXT_CLIENTS_CON}: '.$vserver['server_currentusers'].'
{TEXT_CHANNEL_COUNT}: '.$vserver['server_currentchannels'].'
'.((isset($gserver['isp_ispname']) || isset($vserver['isp_ispname'])) ? '
{TEXT_PROVIDER}: '.((isset($isp_name)) ? htmlspecialchars($isp_name) : NULL ).'
{TEXT_PROVIDER_WEBSITE}: '.((isset($isp_linkurl1)) ? $isp_linkurl1 : NULL ).'
{TEXT_PROVIDER_EMAIL}: '.((isset($isp_adminemail1)) ? $isp_adminemail1 : NULL ).'
' : NULL ).'
{TEXT_WELCOME}: '.$vserver['server_welcomemessage'];

	$div_content = prep_tooltip($div_content);

	$server_content = '
    <tr class="server_row">
	  <td width="90%" nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="16" height="16" src="images/ts/bullet_server.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($vserver['server_name']) .'
      </p></td>
	  <td width="10%" nowrap><p>{TEXT_PING}</p></td>
	</tr>
';

	foreach($channels as $channel){
		//Information echo'en...
		$chflags = ch_flags($channel['flags']);
		$div_content = '{TEXT_CHANNEL}: '. $channel['name'] .'
{TEXT_TOPIC}: '.$channel['topic'].'
{TEXT_PASSWORD_PROT}: '.(($channel['password']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'
{TEXT_CODEC}: '.formatcodec($channel['codec']).'
{TEXT_MAXCLIENTS}: '.$channel['maxusers'].'

{TEXT_EXPLAIN_TSFLAGS_CHANNEL}: '.$chflags;
		$exp_flags = str_split($chflags, 1);
		foreach ($exp_flags as $flag){
			$div_content .= '
'.$flag.': {TEXT_EXPLAIN_TSFLAG_'.$flag.'}';
		}

		$div_content = prep_tooltip($div_content);

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
				$div_content = '{TEXT_SUBCHANNEL}: '. $subchannel['name'] .'
{TEXT_TOPIC}: '.$subchannel['topic'].'
{TEXT_PASSWORD_PROT}: '.(($subchannel['password']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'
{TEXT_CODEC}: '.formatcodec($subchannel['codec']).'
{TEXT_MAXCLIENTS}: '.$subchannel['maxusers'];

				$div_content = prep_tooltip($div_content);

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
						$plflags = pl_flags($player['pprivs'],$player['cprivs']);
						$div_content = '{TEXT_NAME}: '.$player['nick'].'
{TEXT_LOGGEDINFOR}: '.formattime($player['logintime']).'
{TEXT_IDLEFOR}: '.formattime($player['idletime']).'

{TEXT_EXPLAIN_TSFLAGS_PLAYER}: '.$plflags;
						$exp_flags = explode(' ', $plflags);
						foreach ($exp_flags as $flag){
							$div_content .= '
'.$flag.': {TEXT_EXPLAIN_TSFLAG_'.$flag.'}';
						};

						$div_content = prep_tooltip($div_content);

						$server_content .= '    <tr class="client_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img width="48" height="16" src="images/ts/bullet_'. pl_img($player['pflags']) .'.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['nick']) .'&nbsp;&nbsp;('. $plflags .')
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
				$plflags = pl_flags($player['pprivs'],$player['cprivs']);
				$div_content = '{TEXT_NAME}: '.$player['nick'].'
{TEXT_LOGGEDINFOR}: '.formattime($player['logintime']).'
{TEXT_IDLEFOR}: '.formattime($player['idletime']).'

{TEXT_EXPLAIN_TSFLAGS_PLAYER}: '.$plflags;
				$exp_flags = explode(' ', $plflags);
				foreach ($exp_flags as $flag){
					$div_content .= '
'.$flag.': {TEXT_EXPLAIN_TSFLAG_'.$flag.'}';
				};

				$div_content = prep_tooltip($div_content);

				$server_content .= '    <tr class="client_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
<img width="48" height="16" src="images/ts/bullet_'. pl_img($player['pflags']) .'.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['nick']) .'&nbsp;&nbsp;('. $plflags .')
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
$teamspeak->load_template('tpl_teamspeak');
$teamspeak->process();
$teamspeak->output();
unset($teamspeak);
?>

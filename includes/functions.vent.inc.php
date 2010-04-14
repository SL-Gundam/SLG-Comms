<?php
/***************************************************************************
 *                           functions.vent.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.vent.inc.php,v 1.3 2005/07/30 00:10:43 SC Kruiper Exp $
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

function SORT_VENTCHANNELS($a, $b){
	if (strcasecmp($a['NAME'], $b['NAME']) == 0){
		return(0);
	}
	else{
		return((strcasecmp($a['NAME'], $b['NAME']) < 0) ? -1 : 1);
	}
}

function savecache(&$cacheddata){
	global $table, $ts;

	$rtime = explode(" ",microtime());
	$sql = 'UPDATE '.$table['cache'].'
SET
  data = "'.addslashes(implode('/%/', $cacheddata)).'",
  timestamp = "'.$rtime[1].'"
WHERE
  cache_id = "'.$ts['id'].'"
LIMIT 1';

	return($sql);
}

function vent_channels(&$channels, &$clients, $cid=0, $level=0){
	$server_content = NULL;

	uasort($channels[$cid], "SORT_VENTCHANNELS");
	reset($channels[$cid]);
	foreach($channels[$cid] as $channel){
		//Information echo'en...
		$alt_title_content = '{TEXT_CHANNEL}: '. $channel['NAME'].'
{TEXT_PASSWORD_PROT}: '.(($channel['PROT']) ? '{TEXT_YES}' : '{TEXT_NO}' );

		$alt_title_content = htmlentities($alt_title_content);

		$server_content .= '    <tr class="channel_row">
	  <td nowrap><p title="'.$alt_title_content.'"><a href="javascript:MM_popupMsg(\''.convert_jspoptext($alt_title_content).'\')" class="channel_row" onMouseOver="MM_displayStatusMsg(\'{TEXT_SHOW_HELPTEXT_CH}\');return document.MM_returnValue" onMouseOut="MM_displayStatusMsg(\'\');return document.MM_returnValue">
'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level).'<img width="16" height="16" src="images/vent/'. (($channel['PROT']) ? 'p' : 'n' ) .'channel.gif" align="absmiddle" alt="'.$alt_title_content.'" title="'.$alt_title_content.'" border="0">&nbsp;'. htmlspecialchars($channel['NAME']).'</a>
      </p></td>
	  <td nowrap><p>&nbsp;</p></td>
	</tr>
';
 
		//Playerdata...
		if (isset($clients[$channel['CID']])){
			uasort($clients[$channel['CID']], "SORT_VENTCHANNELS");
			reset($clients[$channel['CID']]);

			foreach($clients[$channel['CID']] as $player){ 
				//Informatie echo'en...
				$alt_title_content = '{TEXT_NAME}: '.$player['NAME'].'
{TEXT_ADMIN}: '.(($player['ADMIN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'
{TEXT_LOGGEDINFOR}: '.formattime($player['SEC']).'

'.wordwrap('{TEXT_COMMENT}: '.$player['COMM'], 50, "\n");

				$alt_title_content = htmlentities($alt_title_content);

				$server_content .= '    <tr class="client_row">
	  <td nowrap><p title="'.$alt_title_content.'"><a href="javascript:MM_popupMsg(\''.convert_jspoptext($alt_title_content).'\')" class="client_row" onMouseOver="MM_displayStatusMsg(\'{TEXT_SHOW_HELPTEXT_PL}\');return document.MM_returnValue" onMouseOut="MM_displayStatusMsg(\'\');return document.MM_returnValue">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level).'<img width="16" height="16" src="images/vent/client.gif" align="absmiddle" alt="'.$alt_title_content.'" title="'.$alt_title_content.'" border="0">&nbsp;'. htmlspecialchars($player['NAME']) .((!empty($player['COMM'])) ? ' (<span class="ventcomment">'.linewrap(htmlentities($player['COMM']), 30).'</span>)' : NULL ).'</a>
      </p></td>
	  <td nowrap><p>'.$player['PING'].'ms</p></td>
	</tr>
';
			}
		}
		if (isset($channels[$channel['CID']])){
			$server_content .= vent_channels($channels, $clients, $channel['CID'], ($level + 1));
		}
	}

	return($server_content);
}
?>

<?php
/***************************************************************************
 *                           functions.vent.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.vent.inc.php,v 1.4 2005/09/10 14:39:30 SC Kruiper Exp $
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
		$div_content = '{TEXT_CHANNEL}: '. $channel['NAME'].'
{TEXT_PASSWORD_PROT}: '.(($channel['PROT']) ? '{TEXT_YES}' : '{TEXT_NO}' );
		$div_content = prep_tooltip($div_content);

		$server_content .= '    <tr class="channel_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level).'<img width="16" height="16" src="images/vent/'. (($channel['PROT']) ? 'p' : 'n' ) .'channel.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($channel['NAME']).'
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
				$div_content = '{TEXT_NAME}: '.$player['NAME'].'
{TEXT_ADMIN}: '.(($player['ADMIN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'
{TEXT_LOGGEDINFOR}: '.formattime($player['SEC']).'

{TEXT_COMMENT}: '.$player['COMM'];

				$div_content = prep_tooltip($div_content);

				$server_content .= '    <tr class="client_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level).'<img width="16" height="16" src="images/vent/client.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['NAME']) .((!empty($player['COMM'])) ? ' (<span class="ventcomment">'.linewrap(htmlentities($player['COMM']), 30).'</span>)' : NULL ).'
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

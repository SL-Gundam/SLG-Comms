<?php
/***************************************************************************
 *                           functions.vent.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.vent.inc.php,v 1.8 2005/10/21 14:29:27 SC Kruiper Exp $
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

function SORT_VENTCHANNELS(&$a, &$b){
	$comp_result = strcasecmp($a['NAME'], $b['NAME']);
	if ($comp_result === 0){
		return(0);
	}
	else{
		return(($comp_result < 0) ? -1 : 1);
	}
}

function vent_channels(&$channels, &$clients, $cid=0, $level=0){
	$server_content = NULL;

	uasort($channels[$cid], "SORT_VENTCHANNELS");
	reset($channels[$cid]);
	foreach($channels[$cid] as $channel){
		//Information echo'en...
		$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_CHANNEL}:&amp;nbsp;</td><td>'.prep_tooltip($channel['NAME']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PASSWORD_PROT}:&amp;nbsp;</td><td>'.(($channel['PROT']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'</td></tr>'.((!empty($channel['COMM'])) ? '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_COMMENT}:&amp;nbsp;</td><td>'.prep_tooltip($channel['COMM']).'</td></tr>' : NULL ).'</table>';

//		$div_content = prep_tooltip($div_content);
		$div_content = str_replace("\n", '', $div_content);

		$server_content .= '    <tr class="channel_row">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level).'<img width="16" height="16" src="images/vent/'. (($channel['PROT']) ? 'p' : 'n' ) .'channel.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($channel['NAME']) .((!empty($channel['COMM'])) ? '&nbsp;&nbsp;&nbsp;(<span class="ventcomment">'.htmlentities(linewrap($channel['COMM'], 30)).'</span>)' : NULL ).'
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
				$div_content = '<table border=\\\'0\\\' class=\\\'tooltip\\\' cellspacing=\\\'1\\\' cellpadding=\\\'0\\\'>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_NAME}:&amp;nbsp;</td><td>'.prep_tooltip($player['NAME']).'</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_ADMIN}:&amp;nbsp;</td><td>'.(($player['ADMIN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'</td></tr>'.((isset($player['PHAN'])) ? '
<tr><td nowrap valign=\\\'top\\\'>{TEXT_PHANTOM}:&amp;nbsp;</td><td>'.(($player['PHAN']) ? '{TEXT_YES}' : '{TEXT_NO}' ).'</td></tr>' : NULL ).'
<tr><td nowrap valign=\\\'top\\\'>{TEXT_LOGGEDINFOR}:&amp;nbsp;</td><td>'.prep_tooltip(formattime($player['SEC'])).'</td></tr>'.((!empty($player['COMM'])) ? '
<tr><td>&amp;nbsp;</td><td>&amp;nbsp;</td></tr>
<tr><td nowrap valign=\\\'top\\\'>{TEXT_COMMENT}:&amp;nbsp;</td><td>'.prep_tooltip($player['COMM']).'</td></tr>' : NULL ).'</table>';

//				$div_content = prep_tooltip($div_content);
				$div_content = str_replace("\n", '', $div_content);

				$server_content .= '    <tr class="client_row'.((isset($player['PHAN']) && $player['PHAN']) ? ' ventclient_phantom_row' : NULL ).((isset($player['ADMIN']) && $player['ADMIN']) ? ' ventclient_admin_row' : NULL ).'">
	  <td nowrap onMouseOver="toolTip(\''.$div_content.'\')" onMouseOut="toolTip()"><p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level).'<img width="16" height="16" src="images/vent/client.gif" align="absmiddle" alt="" border="0">&nbsp;'. htmlspecialchars($player['NAME']) .((!empty($player['COMM'])) ? '&nbsp;&nbsp;&nbsp;(<span class="ventcomment">'.htmlentities(linewrap($player['COMM'], 30)).'</span>)' : NULL ).'
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

<?php
/***************************************************************************
 *                             rescache.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: rescache.inc.php,v 1.6 2005/10/03 10:55:53 SC Kruiper Exp $
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

// this file manages the resource cache pages
if (isset($_POST['updsetting'])) {
	foreach ($_POST['refreshcache'] as $variable => $value){
		if ($value != $_POST['oldrefreshcache'][$variable] && !($value == 0 && $_POST['oldrefreshcache'][$variable] == '-1')){
			if ($_POST['oldrefreshcache'][$variable] == '-1'){
				$sql = 'INSERT INTO '.$table['cache'].'
  ( `cache_id`, `refreshcache` )
VALUES (
  "'.$db->escape_string($variable).'", "'.$db->escape_string($value).'")';
			}
			else{
				$sql = 'UPDATE `'.$table['cache'].'`
SET
  `refreshcache` = "'.$db->escape_string($value).'"
WHERE `cache_id` = "'.$db->escape_string($variable).'" LIMIT 1;';
			}
			$updatecache = $db->execquery('updatecachesetting', $sql);
			if ($updatecache == true && (!isset($updatecacheall) || $updatecacheall == true)){
				$updatecacheall = true;
			}
			else{
				$updatecacheall = false;
			}
		}
	}
	if (isset($updatecacheall) && $updatecacheall == true){
		$admin->displaymessage('{TEXT_CACHESETTINGS_UPDATE_SUCCESS}');
	}
}
					
$querygetres = $db->execquery('querygetres-cached','
SELECT
  RES.`res_id`,
  RES.`res_name`,
  CACHE.`timestamp`,
  CACHE.`refreshcache`,
  CACHE.`cachehits`
FROM
  `'.$table['resources'].'` RES
  LEFT OUTER JOIN `'.$table['cache'].'` CACHE ON (RES.`res_id` = CACHE.`cache_id`)
WHERE
  RES.`res_type` IN ("TeamSpeak","Ventrilo")
ORDER BY
  RES.res_name');

$rescache_content = NULL;
while ($rowres = $db->getrow($querygetres)){
	if (isset($rowres['refreshcache']) && $rowres['refreshcache'] != 0){
		if ($rowres['timestamp'] == 0){
			$rescache_live = '{TEXT_DATA_CACHE_UNAVAILABLE}';
		}
		else{
			$ptime = explode(" ",microtime());
			$refreshtime = $ptime[1] - $rowres['refreshcache'];
			$tuntilrefresh = ($refreshtime + $rowres['refreshcache']) - $rowres['timestamp'];
			$rescache_live = formattime($tuntilrefresh);
		}
	}
	else{
		$rescache_live = '{TEXT_DATA_CACHING_DISABLED}';
	}
	$rescache_content .= '
  <tr>
    <td nowrap><p class="para">'.htmlspecialchars($rowres['res_name']).'</p></td>
	<td nowrap><p class="para">'.((isset($rowres['refreshcache']) && $rowres['refreshcache'] != 0) ? $rowres['cachehits'] : NULL).'</p></td>
	<td nowrap><p class="para">'.$rescache_live.'</p></td>
    <td nowrap><p class="para"><input name="oldrefreshcache['.$rowres['res_id'].']" type="hidden" value="'.((isset($rowres['refreshcache'])) ? $rowres['refreshcache'] : '-1').'">
	<input name="refreshcache['.$rowres['res_id'].']" type="text" value="'.((isset($rowres['refreshcache'])) ? $rowres['refreshcache'] : 0).'" size="5" maxlength="5"></p></td>
  </tr>';
}
$db->freeresult('querygetres-cached',$querygetres);

$admin->insert_content('{RESCACHE_CONTENT}', $rescache_content);
?>

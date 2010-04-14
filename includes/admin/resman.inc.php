<?php
/***************************************************************************
 *                               resman.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: resman.inc.php,v 1.11 2005/10/21 14:29:26 SC Kruiper Exp $
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

// this file manages the Resource manager page
if (isset($_GET['delete'])) {
	$querydeleteres = $db->execquery('querydeleteres','DELETE
FROM
  '.$table['resources'].'
WHERE
  res_id = "'.$db->escape_string($_GET['delete']).'"');
	if ($querydeleteres === true){
		$admin->displaymessage('{TEXT_RESOURCE_DELETE}');

		$querydeletecache = $db->execquery('querydeletecache','DELETE
FROM
  '.$table['cache'].'
WHERE
  cache_id = "'.$db->escape_string($_GET['delete']).'"');
		if ($querydeletecache === true){
			$admin->displaymessage('{TEXT_RESOURCECACHE_DELETE}');
		}
	}
}

$querygetres = $db->execquery('querygetres','
SELECT `res_id` , `res_name` , res_data, `res_type` 
FROM `'.$table['resources'].'`
ORDER BY
  res_type,
  res_name');

$resman_content = NULL;
while ($rowres = $db->getrow($querygetres)){
	$rowres['res_name_new'] = htmlspecialchars($rowres['res_name']);
	$rowres['res_data_new'] = htmlspecialchars($rowres['res_data']);
	$resman_content .= '
  <tr>
    <td nowrap><p class="para" title="'.$rowres['res_name_new'].'">'.htmlspecialchars(linewrap($rowres['res_name'], 20)).'</p></td>
    <td nowrap><p class="para" title="'.$rowres['res_data_new'].'">'.htmlspecialchars(linewrap($rowres['res_data'], 30)).'</p></td>
    <td nowrap><p class="para">'.$rowres['res_type'].'</p></td>
    <td nowrap><p class="para"><a href="admin.php?page=resources&resources=resman&edit='.$rowres['res_id'].'">{TEXT_EDIT}</a></p></td>
    <td nowrap><p class="para"><a href="admin.php?page=resources&resources=resman&delete='.$rowres['res_id'].'">{TEXT_DELETE}</a></p></td>
  </tr>
';
}
$db->freeresult('querygetres',$querygetres);
$admin->insert_content('{RESMAN_CONTENT}', $resman_content);
?>

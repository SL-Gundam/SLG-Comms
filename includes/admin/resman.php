<?php
/***************************************************************************
 *                                 resman.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: resman.php,v 1.5 2005/06/21 20:24:27 SC Kruiper Exp $
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

if (isset($_GET['delete'])) {
	$querydeleteres = $db->execquery('querydeleteres','DELETE
FROM
  '.$table['resources'].'
WHERE
  res_id = "'.$_GET['delete'].'"');
	if ($querydeleteres == true){
		$admin->displaymessage('{TEXT_RESOURCE_DELETE}');

		$querydeletecache = $db->execquery('querydeletecache','DELETE
FROM
  '.$table['cache'].'
WHERE
  cache_id = "'.$_GET['delete'].'"');
		if ($querydeletecache == true){
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
	$resman_content .= '
  <tr>
    <td nowrap><p class="para" title="'.htmlspecialchars($rowres['res_name']).'">'.linewrap($rowres['res_name'], 20).'</p></td>
    <td nowrap><p class="para" title="'.htmlspecialchars($rowres['res_data']).'">'.linewrap($rowres['res_data'], 30).'</p></td>
    <td nowrap><p class="para">'.$rowres['res_type'].'</p></td>
    <td nowrap><p class="para"><a href="admin.php?page=resources&resources=resman&edit='.$rowres['res_id'].'">{TEXT_EDIT}</a></p></td>
    <td nowrap><p class="para"><a href="admin.php?page=resources&resources=resman&delete='.$rowres['res_id'].'">{TEXT_DELETE}</a></p></td>
  </tr>
';
}
$db->freeresult('querygetres',$querygetres);
$admin->insert_content('{RESMAN_CONTENT}', $resman_content);
?>
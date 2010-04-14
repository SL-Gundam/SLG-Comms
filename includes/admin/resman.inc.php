<?php
/***************************************************************************
 *                               resman.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: resman.inc.php,v 1.22 2006/06/04 15:43:47 SC Kruiper Exp $
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
if ( !defined("IN_SLG") || !checkaccess($tssettings['Forum_group']) )
{ 
	die( "Hacking attempt." );
}

// this file manages the Resource manager page
if ( isset($_GET['delete']) )
{
	$sql = '
DELETE FROM `%1$s`
WHERE
  `res_id` = %2$u
LIMIT 1';
	$db->execquery( 'querydeleteres', $sql,  array(
		$table['resources'],
		$_GET['delete']
	) );
	$admin->displaymessage( '{TEXT_RESOURCE_DELETE}' );

	$sql = '
DELETE FROM `%1$s`
WHERE
  `res_id` = %2$u
LIMIT 1';
	$db->execquery( 'querydeletecache', $sql, array(
		$table['cache'],
		$_GET['delete']
	) );
	$admin->displaymessage( '{TEXT_RESOURCECACHE_DELETE}' );
}

$sql = '
SELECT `res_id` , `res_name` , `res_data`, `res_type` 
FROM `%1$s`
ORDER BY
  `res_type`,
  `res_name`';
$querygetres = $db->execquery( 'querygetres', $sql, $table['resources'] );
unset( $sql );

$resman_content = NULL;
while ( $rowres = $db->getrow($querygetres) )
{
	$resman_content .= '
	<tr>
		<td nowrap><p class="para" title="' . htmlentities( $rowres['res_name'] ) . '">' . ( ( $rowres['res_type'] === 'TeamSpeak' || $rowres['res_type'] === 'Ventrilo' ) ? '<a href="index.php?ipbyname=' . $rowres['res_id'] . '" target="_blank">' : NULL ) . htmlentities( linewrap( $rowres['res_name'], 20 ) ) . ( ( $rowres['res_type'] === 'TeamSpeak' || $rowres['res_type'] === 'Ventrilo' ) ? '</a>' : NULL ) . '</p></td>
		<td nowrap><p class="para" title="' . htmlentities( $rowres['res_data'] ) . '">' . htmlentities( linewrap( $rowres['res_data'], 30 ) ) . '</p></td>
		<td nowrap><p class="para">' . $rowres['res_type'] . '</p></td>
		<td nowrap><p class="para"><a href="admin.php?page=resources&resources=resedit&edit=' . $rowres['res_id'] . '">{TEXT_EDIT}</a></p></td>
		<td nowrap><p class="para"><a href="admin.php?page=resources&resources=resman&delete=' . $rowres['res_id'] . '">{TEXT_DELETE}</a></p></td>
	</tr>
';
}
$db->freeresult( 'querygetres', $querygetres );

$admin->insert_content( '{RESMAN_CONTENT}', $resman_content );
unset( $resman_content, $querygetres, $rowres );
?>

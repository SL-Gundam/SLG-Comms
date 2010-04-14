<?php
/***************************************************************************
 *                             rescache.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: rescache.inc.php,v 1.28 2006/06/11 20:32:43 SC Kruiper Exp $
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

// this file manages the resource cache pages

if ( isset($_GET['enable']) )
{
	$sql = '
UPDATE `%1$s`
SET
  `con_attempts` = 0,
  `last_error` = NULL
WHERE `res_id` = %2$u
LIMIT 1';
	$db->execquery( 'updatecachesetting', $sql, array(
		$table['cache'],
		$_GET['enable']
	) );
	$admin->displaymessage( '{TEXT_SERVER_ENABLED}' );
}

$sql = '
SELECT
  RES.`res_id`,
  RES.`res_name`,
  RES.`res_type`,
  CACHE.`timestamp`,
  CACHE.`refreshcache`,
  CACHE.`con_attempts`,
  CACHE.`last_error`,
  CACHE.`ventsort`,
  CACHE.`cachehits`
FROM
  `%1$s` AS RES
  LEFT OUTER JOIN `%2$s` AS CACHE ON ( RES.`res_id` = CACHE.`res_id` )
WHERE
  RES.`res_type` IN ( "TeamSpeak", "Ventrilo" )
ORDER BY
  RES.`res_name`';
$querygetres = $db->execquery( 'querygetres-cached', $sql, array(
	$table['resources'],
	$table['cache']
) );
unset( $sql );

$rescache_content = NULL;
while ( $rowres = $db->getrow($querygetres) )
{
	if ( isset($_POST['updsetting']) && isset($_POST['refreshcache'][ $rowres['res_id'] ]) )
	{
		$oldrefreshcache = ( ( isset( $rowres['refreshcache'] ) ) ? $rowres['refreshcache'] : -1 );
		if ( 
			( $_POST['refreshcache'][ $rowres['res_id'] ] != $oldrefreshcache ) || 
			( $rowres['res_type'] === 'Ventrilo' && $_POST['ventsort'][ $rowres['res_id'] ] !== $rowres['ventsort'] ) 
		)
		{
			if ( $oldrefreshcache === -1 )
			{
				$sql = '
INSERT INTO `%1$s`
  ( `res_id`, `refreshcache`, `ventsort` )
VALUES
(%4$u, %2$u, %3$s)'; // number %3 not quoted because we need to be able to set that one to NULL
			}
			else
			{
				$sql = '
UPDATE `%1$s`
SET
  `refreshcache` = %2$u,
  `ventsort` = %3$s
WHERE `res_id` = %4$u
LIMIT 1'; // number %3 not quoted because we need to be able to set that one to NULL
			}
			$db->execquery( 'updatecachesetting', $sql, array(
				$table['cache'],
				$db->escape_string( $_POST['refreshcache'][ $rowres['res_id'] ] ),
				( ( $rowres['res_type'] === 'Ventrilo' ) ? '"' . $db->escape_string( $_POST['ventsort'][ $rowres['res_id'] ] ) . '"' : 'NULL' ),
				$rowres['res_id']
			) );
			unset( $sql );

			$admin->displaymessage( '{TEXT_CACHESETTING_UPDATE_SUCCESS;' . htmlentities( $rowres['res_name'] ) . ';}' );

			$rowres['refreshcache'] = ( ( $_POST['refreshcache'][ $rowres['res_id'] ] != 0 ) ? $_POST['refreshcache'][ $rowres['res_id'] ] : NULL );
			$rowres['ventsort'] = ( ( $rowres['res_type'] === 'Ventrilo' && $_POST['ventsort'][ $rowres['res_id'] ] !== 'alpha' ) ? $_POST['ventsort'][ $rowres['res_id'] ] : NULL );
		}
	}
					
	if ( isset($rowres['refreshcache']) && $rowres['refreshcache'] != 0 )
	{
		if ( $rowres['timestamp'] == 0 )
		{
			$rescache_live = '{TEXT_DATA_CACHE_UNAVAILABLE}';
		}
		else
		{
			$ptime = explode( ' ', microtime() );
			$rescache_live = formattime( $ptime[1] - $rowres['timestamp'] );
			unset( $ptime );
		}
	}
	else
	{
		$rescache_live = '{TEXT_DATA_CACHING_DISABLED}';
	}

	$rescache_content .= '
	<tr>
		<td nowrap>' . ( ( $rowres['con_attempts'] >= 25 ) ? '<a href="admin.php?page=resources&resources=rescache&enable=' . $rowres['res_id'] . '"><img src="{BASE_URL}templates/{TEMPLATE}/images/icon_exclaim.gif" width="19" height="19" border="0" alt="{TEXT_SERVERUPDATE_DISABLED}" onMouseOver="toolTip(\'{TEXT_SERVERUPDATE_DISABLED}<br /><br />{TEXT_LAST_ERROR}: ' . $rowres['last_error'] . '\')" onMouseOut="toolTip()" /></a>' : NULL ) . '</td>
		<td nowrap><p class="para" title="' . htmlentities( $rowres['res_name'] ) . '"><a href="index.php?ipbyname=' . $rowres['res_id'] . '" target="_blank">' . htmlentities( linewrap( $rowres['res_name'], 20 ) ) . '</a></p></td>
		<td nowrap><p class="para">' . ( ( isset($rowres['refreshcache'] ) && $rowres['refreshcache'] != 0 ) ? $rowres['cachehits'] : NULL ) . '</p></td>
		<td nowrap><p class="para">' . $rescache_live . '</p></td>
		<td nowrap><p class="para"><input name="refreshcache[' . $rowres['res_id'] . ']" type="text" value="' . ( ( isset($rowres['refreshcache']) ) ? $rowres['refreshcache'] : 0 ) . '" size="5" maxlength="5"></p></td>
		<td nowrap><p class="para">' . ( ( $rowres['res_type'] === 'Ventrilo' ) ? '
			<input id="ventsort_alpha[' . $rowres['res_id'] . ']" name="ventsort[' . $rowres['res_id'] . ']" type="radio" value="alpha"' . ( ( !isset($rowres['ventsort']) || $rowres['ventsort'] === 'alpha' ) ? ' checked' : NULL ) . '>
			<label for="ventsort_alpha[' . $rowres['res_id'] . ']">{TEXT_ALPHABETICALLY}</label>
			<input id="ventsort_manual[' . $rowres['res_id'] . ']" name="ventsort[' . $rowres['res_id'] . ']" type="radio" value="manual"' . ( ( isset($rowres['ventsort']) && $rowres['ventsort'] === 'manual' ) ? ' checked' : NULL ) . '>
			<label for="ventsort_manual[' . $rowres['res_id'] . ']">{TEXT_MANUALLY}</label>' : NULL ) . '
		</p></td>
	</tr>';
}
$db->freeresult( 'querygetres-cached', $querygetres );
unset( $querygetres, $rowres, $rescache_live, $updated, $oldrefreshcache );

$admin->insert_content( '{RESCACHE_CONTENT}', $rescache_content );
unset( $rescache_content );
?>

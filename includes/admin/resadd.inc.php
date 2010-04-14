<?php
/***************************************************************************
 *                               resadd.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: resadd.inc.php,v 1.17 2006/06/11 20:32:43 SC Kruiper Exp $
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

// this file manages the resource add pages
if ( isset($_POST['baddres']) )
{
	if ( !empty($_POST['resname']) && !empty($_POST['restype']) )
	{
		$sql = '
INSERT INTO `%1$s`
( `res_name` , `res_data` , `res_type` )
VALUES 
("%2$s", %3$s, "%4$s")';// number %3 not quoted because we need to be able to set that one to NULL

		$db->execquery( 'queryinsertres', $sql, array(
			$table['resources'],
			$db->escape_string( $_POST['resname'] ),
			( ( empty($_POST['resdata']) ) ? 'NULL' : '"' . $db->escape_string( $_POST['resdata'] ) . '"' ),
			$db->escape_string( $_POST['restype'] )
		) );

		if ( $_POST['restype'] === 'TeamSpeak' || $_POST['restype'] === 'Ventrilo' )
		{
			$sql = '
INSERT INTO `%1$s`
( `res_id` )
(
  SELECT `res_id`
  FROM `%2$s`
  WHERE
    `res_name` = "%3$s" AND
    `res_data` = %4$s AND
    `res_type` = "%5$s"
  LIMIT 0,1
)';// number %4 not quoted because we need to be able to set that one to NULL

			$db->execquery( 'queryinsertrescache', $sql, array(
				$table['cache'],
				$table['resources'],
				$db->escape_string( $_POST['resname'] ),
				( ( empty($_POST['resdata']) ) ? 'NULL' : '"' . $db->escape_string( $_POST['resdata'] ) . '"' ),
				$db->escape_string( $_POST['restype'] )
			) );
		}

		$admin->displaymessage( '{TEXT_RESOURCE_ADD_SUCCESS}' );
	}
	else
	{
		$admin->displaymessage( '{TEXT_MISSING_FORMDATA}' );
	}
}
elseif ( isset($_POST['beditres']) )
{
	if ( !empty($_POST['resname']) && !empty($_POST['restype']) )
	{
		$sql = '
UPDATE `%1$s` SET
  res_name = "%2$s",
  res_data = %3$s,
  res_type = "%4$s"
WHERE
  `res_id` = %5$u
LIMIT 1'; // number %3 not quoted because we need to be able to set that one to NULL

		$db->execquery( 'queryupdateres', $sql, array(
			$table['resources'],
			$db->escape_string( $_POST['resname'] ),
			( ( empty($_POST['resdata']) ) ? 'NULL' : '"' . $db->escape_string( $_POST['resdata'] ) . '"' ),
			$db->escape_string( $_POST['restype'] ),
			$_GET['edit']
		) );
		$admin->displaymessage( '{TEXT_RESOURCE_UPDATE_SUCCESS}' );
	}
	else
	{
		$admin->displaymessage( '{TEXT_MISSING_FORMDATA}' );
	}
}

if ( $_GET['resources'] === 'resedit' && isset($_GET['edit']) )
{
	$sql = '
SELECT `res_name`, `res_data`, `res_type`
FROM `%1$s`
WHERE
  `res_id` = %2$u
LIMIT 0,1';

	$queryeditres = $db->execquery( 'queryeditres', $sql, array(
		$table['resources'],
		$_GET['edit']
	) );

	$roweditres = $db->getrow( $queryeditres );

	$db->freeresult( 'queryeditres', $queryeditres );
	unset( $queryeditres );

	$form_action_type = 'resedit&edit=' . $_GET['edit'];
	$actiontype = '{TEXT_RESOURCE_UPDATE}';
	$resname = htmlentities( $roweditres['res_name'] );
	$resdata = htmlentities( $roweditres['res_data'] );
	$button_action = 'beditres';
	$button_text = '{TEXT_RESOURCE_UPDATE}';
}
else
{
	$form_action_type = 'resadd';
	$actiontype = '{TEXT_RESOURCE_ADD}';
	$resname = NULL;
	$resdata = NULL;
	$button_action = 'baddres';
	$button_text = '{TEXT_RESOURCE_ADD}';
}

$admin->insert_text( '{FORM_ACTION_TYPE}', $form_action_type );
$admin->insert_content( '{ACTION_TYPE}', $actiontype );
$admin->insert_text( '{VALUE_RESNAME}', $resname );
$admin->insert_text( '{VALUE_RESDATA}', $resdata );
$admin->insert_text( '{FORM_SUBMIT_TYPE}', $button_action );
$admin->insert_text( '{SUBMIT_TYPE}', $button_text );
unset( $form_action_type, $actiontype, $resname, $resdata, $button_action, $button_text );

$sql = '
SHOW COLUMNS
FROM 
  `%1$s`
LIKE
  "res_type"';
$resfields = $db->execquery( $table['resources'] . '-fieldsquery', $sql, $table['resources'] );
unset( $sql );

$field = $db->getrow( $resfields );

$fieldtype = explode( ',', removechars( substr( $field['Type'], 5, -1 ), "'" ) );

$db->freeresult( $table['resources'] . '-fieldsquery', $resfields );
unset( $resfields, $field );

reset( $fieldtype );
$type_options = NULL;
while ( $field = array_shift($fieldtype) )
{
	$type_options .= '<option value="' . $field . '"' . ( ( $_GET['resources'] === 'resedit' && isset($_GET['edit']) && $roweditres['res_type'] == $field ) ? ' selected' : NULL ) . '>' . $field . '</option>';
}
unset( $fieldtype, $field, $roweditres );

$admin->insert_text( '{TYPE_OPTIONS}', $type_options );
unset( $type_options );
?>

<?php
/***************************************************************************
 *                                 resadd.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: resadd.php,v 1.2 2005/06/21 19:15:28 SC Kruiper Exp $
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

if (isset($_POST['baddres'])) {
	if (!empty($_POST['resname']) && !empty($_POST['restype'])){
		processincomingdata($_POST);
		$sql = 'INSERT INTO '.$table['resources'].'
( `res_name` , `res_data` , `res_type` )
VALUES (
"'.$_POST['resname'].'", ';
		if (empty($_POST['resdata'])){
			$sql .= 'NULL';
		}
		else{
			$sql .= '"'.$_POST['resdata'].'"';
		}
		$sql .= ', "'.$_POST['restype'].'")';
		$queryinsertres = $db->execquery('queryinsertres',$sql);
		if ($queryinsertres == true){
			$admin->displaymessage('{TEXT_RESOURCE_ADD_SUCCESS}');
		}
	}
	else{
		$admin->displaymessage('{TEXT_MISSING_FORMDATA}');
	}
}
elseif (isset($_POST['beditres'])) {
	if (!empty($_POST['resname']) && !empty($_POST['restype'])) {
		processincomingdata($_POST);
		$sql = 'UPDATE '.$table['resources'].' SET
  res_name = "'.$_POST['resname'].'",';
		if (empty($_POST['resdata'])){
			$sql .= 'res_data = NULL,';
		}
		else{
			$sql .= 'res_data = "'.$_POST['resdata'].'",';
		}
		$sql .= 'res_type = "'.$_POST['restype'].'"
WHERE
  (res_id = '.$_GET['edit'].')';
		$queryupdateres = $db->execquery('queryupdateres',$sql);
		if ($queryupdateres == true){
			$admin->displaymessage('{TEXT_RESOURCE_UPDATE_SUCCESS}');
		}
	}
	else{
		$admin->displaymessage('{TEXT_MISSING_FORMDATA}');
	}
}

if ($_GET['resources'] == 'resman' && isset($_GET['edit'])) {
	$queryeditres = $db->execquery('queryeditres','
SELECT res_name, res_data, res_type 
FROM `'.$table['resources'].'`
WHERE
  res_id = '.$_GET['edit']);
	$roweditres = $db->getrow($queryeditres);
	$db->freeresult('queryeditres',$queryeditres);
}

$resfields = $db->execquery($table['resources'].'-fieldsquery','SHOW COLUMNS
FROM 
  '.$table['resources'].'
LIKE
  "res_type"');

if ($_GET['resources'] == 'resman' && isset($_GET['edit'])){
	$form_action_type = 'resman&edit='.$_GET['edit'];
	$actiontype = '{TEXT_RESOURCE_EDIT}';
	$resname = htmlspecialchars($roweditres['res_name']);
	$resdata = htmlspecialchars($roweditres['res_data']);
	$button_action = 'beditres';
	$button_text = '{TEXT_RESOURCE_UPDATE}';
}
else{
	$form_action_type = 'resadd';
	$actiontype = '{TEXT_RESOURCE_ADD}';
	$resname = NULL;
	$resdata = NULL;
	$button_action = 'baddres';
	$button_text = '{TEXT_RESOURCE_ADD}';
}

$admin->insert_text('{FORM_ACTION_TYPE}', $form_action_type);
$admin->insert_content('{ACTION_TYPE}', $actiontype);
$admin->insert_text('{VALUE_RESNAME}', $resname);
$admin->insert_text('{VALUE_RESDATA}', $resdata);

while ($field = $db->getrow($resfields)){
	if ($field['Field'] == 'res_type'){
		$fieldtype = explode(',',removechars(substr($field['Type'], 5, -1), "'"));
	}
}
$db->freeresult($table['resources'].'-fieldsquery',$resfields);

reset($fieldtype);
$type_options = NULL;
foreach ($fieldtype as $field){
	$type_options .= '<option value="'.$field.'"';
	if ($_GET['resources'] == 'resman' && isset($_GET['edit']) && $roweditres['res_type'] == $field){
		$type_options .= ' selected';
	}
	$type_options .= '>'.$field.'</option>';
}

$admin->insert_text('{TYPE_OPTIONS}', $type_options);
$admin->insert_text('{FORM_SUBMIT_TYPE}', $button_action);
$admin->insert_text('{SUBMIT_TYPE}', $button_text);
?>

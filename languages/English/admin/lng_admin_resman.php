<?php
/***************************************************************************
 *                            lng_admin_resman.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_resman.php,v 1.3 2005/06/30 19:40:05 SC Kruiper Exp $
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
if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}

$this->text += array(
'{TEXT_RESOURCE_NAME}' => 'Resource name',
'{TEXT_RESOURCE_DATA}' => 'Resource data',
'{TEXT_RESOURCE_TYPE}' => 'Resource type',
'{TEXT_EDIT}' => 'edit',
'{TEXT_DELETE}' => 'delete',
'{TEXT_RESOURCE_DELETE}' => 'Resource successfully deleted.',
'{TEXT_RESOURCECACHE_DELETE}' => 'Resource cache successfully deleted.'
);
?>

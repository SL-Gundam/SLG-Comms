<?php
/***************************************************************************
 *                            lng_admin_resadd.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_resadd.php,v 1.9 2006/06/11 20:32:44 SC Kruiper Exp $
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
if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

$this->text += array(
'{TEXT_MISSING_FORMDATA}'          => 'Not all of the necessary fields were filled in.',

'{TEXT_RESOURCE_ADD_SUCCESS}'      => 'Resource successfully added.',
'{TEXT_RESOURCE_UPDATE_SUCCESS}'   => 'Resource successfully updated.',

'{TEXT_RESOURCE_ADD}'              => 'Add resource',
'{TEXT_RESOURCE_UPDATE}'           => 'Update resource',

'{TEXT_HELP}'                      => 'HELP',
'{TEXT_RESOURCE_DATA}'             => 'Resource data',
'{TEXT_RESOURCE_NAME}'             => 'Resource name',
'{TEXT_RESOURCE_TYPE}'             => 'Resource type',
);
?>

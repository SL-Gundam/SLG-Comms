<?php
/***************************************************************************
 *                            lng_admin_resadd.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_resadd.php,v 1.2 2007/04/22 22:26:10 SC Kruiper Exp $
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
'{TEXT_MISSING_FORMDATA}'           => 'Toutes les zones n�cessaires n\' ont pas �t� remplies.',

'{TEXT_RESOURCE_ADD_SUCCESS}'       => 'Ressource ajout�e avec succ�s.',
'{TEXT_RESOURCE_UPDATE_SUCCESS}'    => 'Ressource mise � jour avec succ�s.',

'{TEXT_RESOURCE_ADD}'               => 'Ressource ajouter',
'{TEXT_RESOURCE_UPDATE}'            => 'Ressource de mise � jour',

'{TEXT_HELP}'                       => 'AIDE',
'{TEXT_RESOURCE_DATA}'              => 'donn�es de ressource',
'{TEXT_RESOURCE_NAME}'              => 'Nom de ressource',
'{TEXT_RESOURCE_TYPE}'              => 'Type de ressource',
);
?>

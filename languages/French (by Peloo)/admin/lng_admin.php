<?php
/***************************************************************************
 *                               lng_admin.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin.php,v 1.2 2007/04/22 22:26:10 SC Kruiper Exp $
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
'{TEXT_LOGIN}'                 => 'Connecxion',
'{TEXT_LOGOUT}'                => 'Deconnecxion',

'{TEXT_USERNAME}'              => 'Non d\'utilisateur',
'{TEXT_PASSWORD}'              => 'Mots de passe',

'{TEXT_LOGIN_FAILURE}'         => 'Cette combinaison de username et de mot de passe ne correspond a aucun de nos enregistrements.
S\'il vous pla�t essayez encore.',
'{TEXT_LOGIN_SUCCESS}'         => 'Connecxion r�ussi.',
'{TEXT_LOGOUT_SUCCESS}'        => 'Deconnecxion r�ussi.',

'{TEXT_RESOURCES}'             => 'Resources',
'{TEXT_SETTINGS}'              => 'Configurations',

'{TEXT_ADD_RESOURCES}'         => 'Ajout� une resources',
'{TEXT_CACHE_RESOURCES}'       => 'Controler les serveurs',
'{TEXT_MANAGE_RESOURCES}'      => 'Controler les resources',

'{TEXT_ADMIN_WELCOME}'         => 'Bienvenue � la section d\'admin.',

'{TEXT_INSTALL_FILE_PRESENT}'  => 'AVERTISSEMENT : Le fichier install.php est encore pr�sent dans le r�pertoire d\'installation. 
Il est fortement recommander de le retirer en tant que son un risque de s�curit�.',

'{TEXT_SESSION_VIOLATION}'     => 'Votre session a �t� termin�e parce qu\'une r�gle de s�curit� a �t� viol�e. 
Ne vous inqui�tez pas continuer juste ce que vous faisiez. 
Au besoin vous pouvez a la connecxion.',

'{TEXT_NAVIGATION}'            => 'Menu de navigation',
);

$this->text_adv += array(
'{TEXT_MISSING_MENUTYPE}'      => 'N\'a pas pu trouver le support d\'endroit de menu "$1" dans le template.',
);
?>

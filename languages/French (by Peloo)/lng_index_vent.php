<?php
/***************************************************************************
 *                             lng_index_vent.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_vent.php,v 1.2 2007/11/17 00:18:18 SC Kruiper Exp $
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
'{TEXT_ADMIN}'                  => 'Administrateur?',
'{TEXT_ADMINS_CON}'             => 'Administrateurs en ligne',
'{TEXT_CHANNELDATA_DISABLED}'   => 'L\'information du canal invalidée par l\'administrateur du serveur Ventrilo.',
'{TEXT_CLIENTDATA_DISABLED}'    => 'L\'information sur le client invalidée par l\'administrateur du serveur Ventrilo .',
'{TEXT_COMMENT}'                => 'Commentaire',
'{TEXT_PHANTOM}'                => 'Fantôme?',
'{TEXT_SERVER_PHONETIC}'        => 'Serveur phonétique',
'{TEXT_UNKNOWN}'                => 'Inconnu',
'{TEXT_VOICECODEC}'             => 'Codec de voix',
'{TEXT_VOICEFORMAT}'            => 'Format du codec de voix',
'{TEXT_USERAUTH}'               => 'Authentification d\'utilisateur'
);
?>

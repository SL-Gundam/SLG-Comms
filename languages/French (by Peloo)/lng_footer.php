<?php
/***************************************************************************
 *                              lng_footer.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_footer.php,v 1.1 2007/04/22 19:23:40 SC Kruiper Exp $
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
'{TEXT_POWEREDBY}'        => 'Crée par ',

'{TEXT_BUGNOTIFY_P1}'     => 'Vous avez rencontré une anomalie dans SLG Comms. Veuillez informer le webmaster aussitôt que possible et dites-lui ce qui s\'est produit.',
'{TEXT_BUGNOTIFY_P2}'     => 'Dites-lui la page que vous visitiez et ce que l\'action que vous avez exécuté là. Copiez en outre l\'information en bas de la page au sujet des temps de génération de page, l\'information dans ce cadre et toutes les autres erreurs données dans la page et l\'élasticité qui au webmaster.',
'{TEXT_DATANOTFOUND}'     => 'Données non trouvées',
'{TEXT_DBCLOSED}'         => 'DB:MySQL connecxion fermer.',
'{TEXT_DBOPEN}'           => 'DB:MySQL connecxion ouvert.',
'{TEXT_DBFORUMCLOSED}'    => 'DBFORUM:MySQL connecxion fermer.',
'{TEXT_DBFORUMOPEN}'      => 'DBFORUM:MySQL connecxion ouvert.',
'{TEXT_THANKS}'           => 'Merci à d\'avance.',
'{TEXT_CLOSE}'            => 'Fermer',

'{TEXT_UNKNOWN_VERSION}'  => 'Version inconue',

'{TEXT_FREE}'             => 'Gratuit',
'{TEXT_NONEED}'           => 'No need.',
);
?>

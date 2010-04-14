<?php
/***************************************************************************
 *                           lng_admin_settings.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_settings.php,v 1.3 2008/08/10 20:23:37 SC Kruiper Exp $
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
'{TEXT_SETTINGS_NOTE_P1}'           => 'si vous projetez de changer les configurations pour le forum que vous voulez utiliser avec SLG Comms,utilisez s\'il vous pla�t les �tapes suivantes. Une fois que ce des proces a �t� commenc�s on l\'exige pour le terminer. Vous pouvez changer en arri�re apr�s si vous changiez d\'avis.',
'{TEXT_SETTINGS_NOTE_P2}'           => 'note : Assurez-vous que vous faites ceci en une session de proc�dure de connexion (la signification ne se d�connectent sous aucun circonstance except� la derni�re �tape). Vous ne pourriez pas pouvoir en mesure � la proc�dure de connexion encore si vous vous d�connectez. La seule voie de corriger la situation si vous ne pouvez pas proc�dure de connexion est encore de faire le suivant.', 
'{TEXT_SETTINGS_NOTE_P3}'           => 'le d�but install.php. Il est possible vous doivent t�l�charger install.php encore si vous l\'effaciez. Choisissez le suivant en tant que "type d\'installation": "restaurez les configurations". Remplissez tout d\'information correcte une fois demand� lui. Vous devriez �tre de sauvegarde et fonctionnement bient�t assez.',
'{TEXT_SETTINGS_NOTE_L1}'           => 'Changemer de voie d\'acc�s "relative forum" et "type de forum".', 
'{TEXT_SETTINGS_NOTE_L2}'           => 'Sauvegarder les configurations.', 
'{TEXT_SETTINGS_NOTE_L3}'           => 'Changemer de "groupe de forum".', 
'{TEXT_SETTINGS_NOTE_L4}'           => 'D�connexion et proc�dure de connecxion.',

'{TEXT_UPDATE_SETTINGS}'            => 'Configurations mise � jour',

'{TEXT_NEW_FORUM_INVALID}'          => 'Les nouvelles configurations de forum sont incorrectes et donc ne seront pas sauvegarder.',
'{TEXT_NOGROUP}'                    => 'Groupe appropri� n\'a pas trouv�. Veuillez cr�er un.',
);

$this->text_adv += array(
'{TEXT_SETTINGUPDATE_SUCCESS}'      => 'La configuration "$1" a �t� avec succ�s mise � jour.',
);
?>

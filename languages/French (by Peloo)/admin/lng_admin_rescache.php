<?php
/***************************************************************************
 *                            lng_admin_cached.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_rescache.php,v 1.2 2007/04/22 22:26:10 SC Kruiper Exp $
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
'{TEXT_RESOURCE_CACHEHITS}'            => 'Hit de la cache',
'{TEXT_RESOURCE_LASTUPDATE}'           => 'Temps de ressource ',
'{TEXT_RESOURCE_NAME}'                 => 'Nom de ressource',
'{TEXT_VENT_CHANNEL_SORTING}'          => 'Tri de canal Ventrilo',

'{TEXT_DATA_CACHE_UNAVAILABLE}'        => 'Donn�es de cachent pas le pr�sent.',
'{TEXT_DATA_CACHING_DISABLED}'         => 'Donn�es de cache est d�sactiv� .',

'{TEXT_AND}'                           => 'et',
'{TEXT_DAY}'                           => 'Jour',
'{TEXT_DAYS}'                          => 'Jours',
'{TEXT_HOUR}'                          => 'Heure',
'{TEXT_HOURS}'                         => 'Heures',
'{TEXT_MINUTE}'                        => 'Minute',
'{TEXT_MINUTES}'                       => 'Minutes',
'{TEXT_SECOND}'                        => 'Seconde',
'{TEXT_SECONDS}'                       => 'Secondes',
'{TEXT_YEAR}'                          => 'Ann�e',
'{TEXT_YEARS}'                         => 'Ann�es',

'{TEXT_UPDATE_CACHESETTINGS}'          => 'Les configurations de m�moire cache mise � jour',

'{TEXT_SERVER_ENABLED}'                => 'Serveur permis.',

'{TEXT_RESCACHE_NOTE}'                 => 'ici vous d�finissez la quantit� de secondes o� un serveur devrait �tre cach� avant qu\'il r�g�n�re ses donn�es. Si vous placez la quantit� de secondes � z�ro, cacher sera handicap� pour ce serveur sp�cifique. On lui informe aux allways permettent cacher quoique les justes pour 1 seconde. La raison de ceci est que SLG Comms pourra prot�ger le webserver sur des sites Web avec de hauts visiteurs de montants contre obtenir inond� avec des demandes de phase de donn�es.

Vous pouvez �galement d�finir si vous voulez les canaux de Ventrilo tri�s d\'une voie particuli�re. C\'est seulement disponible pour des serveurs de Ventrilo parce que SLG Comms ne peut pas d�cider seule ce que la bonne m�thode de tri pour des canaux de Ventrilo est due au fait que cette information est manquante des donn�es recherch�es de serveur.

Emballez un serveur a �t� invalid� parce qu\'il a �chou� 25 tentatives cons�cutives de connexion que vous pouvez permettre ce serveur encore en cliquant sur le graphisme de marque d\'exclamation devant le serveur en question ',
);

$this->text_adv += array(
'{TEXT_CACHESETTING_UPDATE_SUCCESS}'   => 'les configurations pour "$1" ont �t� avec succ�s mises � jour ',
);

$this->tooltips += array(
'{TEXT_LAST_ERROR}'                    => 'Derniere ERREUR',
'{TEXT_SERVERUPDATE_DISABLED}'         => 'les mises � jour de serveur ont invalid� (en raison de 25 pannes cons�cutives de tentative de connexion) - Cliquent sur le graphisme d\'exclamation pour permettre des mises � jour de serveur encore pour ce serveur ',
);
?>

<?php
/***************************************************************************
 *                             lng_index_sub.php
 *                            -------------------
 *   begin 		           : Saturday, March 13, 2005
 *   copyright            	: (C) 2005 Soul--Reaver
 *   email                            : slgundam@gmail.com
*
 *   $Id: lng_index_sub.php,v 1.1 2007/04/22 19:23:40 SC Kruiper Exp $
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
'{TEXT_CHANNEL_INFO}'            => 'Information du Channel',
'{TEXT_SERVER_INFO}'             => 'Information du Server',

'{TEXT_CACHED_LOADED}'           => 'Les données de la caches ont été chargé.',
'{TEXT_ERROR}'                   => 'ERREUR',
'{TEXT_LAST_ERROR}'              => 'DERNIÈRE ERREUR',
'{TEXT_SERVERUPDATE_DISABLED}'   => 'LES MISES À JOUR DE SERVEUR SONT INVALIDÉ.
Ce serveur a échoué 25 tentatives consécutives de connexion. Parconséquent probable que se serveur n\'existe plus.',

'{TEXT_CACHEDDATA}'              => 'Données de la caches.',
'{TEXT_CACHEDISABLED}'           => 'Les données de la caches sont invalide',
'{TEXT_CACHEOLD}'                => 'Cache age',
'{TEXT_DATAREFRESHED}'           => 'Les données régénèrent intervalle',
'{TEXT_DATAREFRESHIN}'           => 'Les données régénèrent dans',
'{TEXT_LIVEDATA}'                => 'Données de phase.',
'{TEXT_NOCUSTOMCACHE}'           => 'Des données ne seront pas cachées pour les serveurs faits sur commande',
'{TEXT_UPDATEINPROGRESS}'        => 'Mise à jour en cour.',

'{TEXT_CHANNEL}'                 => 'Nom du channel',
'{TEXT_CHANNEL_COUNT}'           => 'Channels',
'{TEXT_CLIENT}'                  => 'Nom du client',
'{TEXT_CLIENTS_CON}'             => 'Clients en ligne',
'{TEXT_LOGGEDINFOR}'             => 'Connecté pour',
'{TEXT_MAXCLIENTS}'              => 'Clients maximum',
'{TEXT_MILLISECONDS}'            => 'ms',
'{TEXT_PASSWORD_PROT}'           => 'Protegé par mots de pass?',
'{TEXT_PING}'                    => 'Ping',
'{TEXT_PLATFORM}'                => 'Platform',
'{TEXT_SERVER_NAME}'             => 'Nom du server',
'{TEXT_VERSION}'                 => 'Version',
'{TEXT_UDPPORT}'                 => 'Port UDP',
'{TEXT_UPTIME}'                  => 'Temps de fonction',

'{TEXT_YES}'                     => 'Oui',
'{TEXT_NO}'                      => 'Non',

'{TEXT_AND}'                     => 'et',
'{TEXT_DAY}'                     => 'Jour',
'{TEXT_DAYS}'                    => 'Jours',
'{TEXT_HOUR}'                    => 'Heure',
'{TEXT_HOURS}'                   => 'Heures',
'{TEXT_MINUTE}'                  => 'Minute',
'{TEXT_MINUTES}'                 => 'Minutes',
'{TEXT_SECOND}'                  => 'Seconde',
'{TEXT_SECONDS}'                 => 'Secondes',
'{TEXT_YEAR}'                    => 'Année',
'{TEXT_YEARS}'                   => 'Années',
);
?>

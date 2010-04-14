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

'{TEXT_CACHED_LOADED}'           => 'Les donn�es de la caches ont �t� charg�.',
'{TEXT_ERROR}'                   => 'ERREUR',
'{TEXT_LAST_ERROR}'              => 'DERNI�RE ERREUR',
'{TEXT_SERVERUPDATE_DISABLED}'   => 'LES MISES � JOUR DE SERVEUR SONT INVALID�.
Ce serveur a �chou� 25 tentatives cons�cutives de connexion. Parcons�quent probable que se serveur n\'existe plus.',

'{TEXT_CACHEDDATA}'              => 'Donn�es de la caches.',
'{TEXT_CACHEDISABLED}'           => 'Les donn�es de la caches sont invalide',
'{TEXT_CACHEOLD}'                => 'Cache age',
'{TEXT_DATAREFRESHED}'           => 'Les donn�es r�g�n�rent intervalle',
'{TEXT_DATAREFRESHIN}'           => 'Les donn�es r�g�n�rent dans',
'{TEXT_LIVEDATA}'                => 'Donn�es de phase.',
'{TEXT_NOCUSTOMCACHE}'           => 'Des donn�es ne seront pas cach�es pour les serveurs faits sur commande',
'{TEXT_UPDATEINPROGRESS}'        => 'Mise � jour en cour.',

'{TEXT_CHANNEL}'                 => 'Nom du channel',
'{TEXT_CHANNEL_COUNT}'           => 'Channels',
'{TEXT_CLIENT}'                  => 'Nom du client',
'{TEXT_CLIENTS_CON}'             => 'Clients en ligne',
'{TEXT_LOGGEDINFOR}'             => 'Connect� pour',
'{TEXT_MAXCLIENTS}'              => 'Clients maximum',
'{TEXT_MILLISECONDS}'            => 'ms',
'{TEXT_PASSWORD_PROT}'           => 'Proteg� par mots de pass?',
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
'{TEXT_YEAR}'                    => 'Ann�e',
'{TEXT_YEARS}'                   => 'Ann�es',
);
?>

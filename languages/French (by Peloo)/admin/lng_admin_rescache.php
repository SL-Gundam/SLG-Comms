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

'{TEXT_DATA_CACHE_UNAVAILABLE}'        => 'Données de cachent pas le présent.',
'{TEXT_DATA_CACHING_DISABLED}'         => 'Données de cache est désactivé .',

'{TEXT_AND}'                           => 'et',
'{TEXT_DAY}'                           => 'Jour',
'{TEXT_DAYS}'                          => 'Jours',
'{TEXT_HOUR}'                          => 'Heure',
'{TEXT_HOURS}'                         => 'Heures',
'{TEXT_MINUTE}'                        => 'Minute',
'{TEXT_MINUTES}'                       => 'Minutes',
'{TEXT_SECOND}'                        => 'Seconde',
'{TEXT_SECONDS}'                       => 'Secondes',
'{TEXT_YEAR}'                          => 'Année',
'{TEXT_YEARS}'                         => 'Années',

'{TEXT_UPDATE_CACHESETTINGS}'          => 'Les configurations de mémoire cache mise à jour',

'{TEXT_SERVER_ENABLED}'                => 'Serveur permis.',

'{TEXT_RESCACHE_NOTE}'                 => 'ici vous définissez la quantité de secondes où un serveur devrait être caché avant qu\'il régénère ses données. Si vous placez la quantité de secondes à zéro, cacher sera handicapé pour ce serveur spécifique. On lui informe aux allways permettent cacher quoique les justes pour 1 seconde. La raison de ceci est que SLG Comms pourra protéger le webserver sur des sites Web avec de hauts visiteurs de montants contre obtenir inondé avec des demandes de phase de données.

Vous pouvez également définir si vous voulez les canaux de Ventrilo triés d\'une voie particulière. C\'est seulement disponible pour des serveurs de Ventrilo parce que SLG Comms ne peut pas décider seule ce que la bonne méthode de tri pour des canaux de Ventrilo est due au fait que cette information est manquante des données recherchées de serveur.

Emballez un serveur a été invalidé parce qu\'il a échoué 25 tentatives consécutives de connexion que vous pouvez permettre ce serveur encore en cliquant sur le graphisme de marque d\'exclamation devant le serveur en question ',
);

$this->text_adv += array(
'{TEXT_CACHESETTING_UPDATE_SUCCESS}'   => 'les configurations pour "$1" ont été avec succès mises à jour ',
);

$this->tooltips += array(
'{TEXT_LAST_ERROR}'                    => 'Derniere ERREUR',
'{TEXT_SERVERUPDATE_DISABLED}'         => 'les mises à jour de serveur ont invalidé (en raison de 25 pannes consécutives de tentative de connexion) - Cliquent sur le graphisme d\'exclamation pour permettre des mises à jour de serveur encore pour ce serveur ',
);
?>

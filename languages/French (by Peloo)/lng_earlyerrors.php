<?php
/***************************************************************************
 *                            lng_earlyerrors.php
 *                            -------------------
*   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_earlyerrors.php,v 1.3 2008/03/24 16:07:51 SC Kruiper Exp $
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
'{TEXT_ERROR}'                      => 'ERREUR',
'{TEXT_LAST_ERROR}'                 => 'DERNIERE ERREUR',
'{TEXT_QUERY}'                      => 'QUERY',
'{TEXT_SQLERROR}'                   => 'ERREUR SQL',

'{TEXT_NOINSTALL}'                  => 'Par tous les comptes il semble que vous n\'avez pas encore exécutéinstall.php .
<a href="install.php">Cliquer ici pour lancer l\'installation</a>.',
'{TEXT_NO_DATABASE_MODE_ACT}'       => 'SLG Comms fonctionne en prétendu mode "AUCUNE BASE DE DONNÉES" qui signifie que son ne pas travailler avec le support d\'une base de données. Le résultat est que cette page est desactivé.
Toutes les configurations à votre disposition sont dans le fichier"dbsettings.inc.php"',

'{TEXT_LOADTEMPLATE_ERROR}'         => 'On ne lui permet pas de charger puis un descripteur dans la même classe.
Informer le webmaster.',

'{TEXT_CONF_FILE_NOT_IN_DOCROOT}'   => 'Le fichier de configuration de forum doit être dans la racine de document de votre webserver.',
'{TEXT_FORUM_NOT_FOUND_ERROR}'      => 'SLG Comms n\'a pas pu trouver le forum dans le répertoire indiqué.
S\'il vous plaît remplissez l\'information correctement et réessayer.',
'{TEXT_FORUMTYPE_COMBI_ERROR}'      => 'La combinaison actuelle du "type de forum" et "de la voie d\'accès relative du forum" est incorrecte.
Informer le webmaster.',
'{TEXT_INVALID_CONF_FILE}'          => 'Fichier incorrect de configuration de forum.',
'{TEXT_MISSING_GROUP_QUERY_ERROR}'  => 'SLG Comms n\'a pas pu trouver la requête requise pour rechercher les groupes de forum. Veuillez informer le webmaster.',
'{TEXT_NOGROUP_INSTALL}'            => 'Il semble que le forum que vous avez choisi n\'a aucun groupe. Veuillez créer on contenant les comptes d\'utilisateur qui sont permis d\'accéder à la section d\'administrateur de SLG Comms.',
'{TEXT_UNKNOWN_FORUMTYPE_ERROR}'    => 'Un type inconnu de forum a été produit dans les configurations.
Informer le webmaster.',

'{TEXT_CLASS_TIMECOUNT_ERROR}'      => 'Anomalie produite dans la classe "timecount". Veuillez informer le webmaster.',

'{TEXT_SETTINGFORM_ERROR}'          => 'Il y a une anomalie sous la forme de configurations. Veuillez informer le webmaster.',

'{TEXT_SUPPORT_VENT_DISABLED}'      => 'Le soutien de Ventrilo a été invalidé.',
'{TEXT_SUPPORT_TS_DISABLED}'        => 'Le soutien de TeamSpeak a été invalidé.',

'{TEXT_OLDVERSION_UNAVAILABLE}'     => 'SLG Comms essayé pour exécuter une mise à jour d\'une versionne rencente de SLG Comms mais n\'a pas pu trouver l\'ancien numero de version. Il est fortement probable que vous ne fassiez pas installer une version plus recnente encore.',
'{TEXT_SAMEVERSIONUPDATE}'          => 'La mise à jour a été annulé parce que SLG Comms a été déjà été mis à jour avec cette version ou plus recente.',
'{TEXT_SLGVERSIONCONFLICT}'         => 'La version interne de fichier n\'est pas identique que la version installée.
S\'il vous plaît exécution la mise à jour SLG Comms par <a href="install.php">install.php</a>.',

'{TEXT_DEFINED_VENT_PROG_INVALID}'  => 'Le programme défini de mode de Ventrilo est incorrect. Veuillez contrôler la configuration et corrigez-la.',
'{TEXT_NOVENTRILO}'                 => 'Vous avez défini un programme de mode de Ventrilo. En raison de cette version de ventrilo n\'est pas encore disponible.',
'{TEXT_VENTRILO_NOT_IN_SLG_DIR}'    => 'Le programme Ventrilo doit être situé dans le répertoireracine de SLG Comms ou un répertoire secondaire du répertoire racine de SLG Comms. Le nom du répertoire secondaire n\'importe pas.',
'{TEXT_UNKNOWN_EXEC_ERROR}'         => 'L\'erreur de fonction inconnue EXEC() à été rencontré.  
Son possible que le mode n\'est pas activé sur votre PHP.  
Voir la documentation sur cette fonction <a href="http://www.php.net/exec">PHP EXEC()</a> et <a href="http://www.php.net/safe+mode+functions">PHP fonction restricted/desactivé sur le safe mode</a>.',

'{TEXT_NOTTEAMSPEAK}'               => 'Données altérées ou incorrectes du serveur de TeamSpeak produites..',
'{TEXT_STREAM_TIMEOUT}'             => 'Extraction de données de serveur de TeamSpeak hors delai.',
'{TEXT_TSINVALIDID_ERROR}'          => 'SLG Comms relié à un serveur valide de TeamSpeak mais le nombre gauche fourni n\'a pas appartenu à un serveur accueilli par ce serveur de TeamSpeak.',

'{TEXT_CONNECT_ALLREADY}'           => 'Il y a déjà une connexion établie dans ce cas de la classe de base de données. La fonction de disconnect() devrait etre appele avant d\'ouvrir une nouvelle connexion à la base de données.',
'{TEXT_DB_DISCONNECT_ERROR}'        => 'N\'a pas fermé la connexion de serveur de base de données. Très probablement la connexion donnée n\'a pas existé en raison des erreurs antérieures.',
'{TEXT_DIFFERENT_DB_INFO}'          => 'L\'information différente de base de données a rempli dedans comparé à l\'information stockée dans "dbsettings.inc.php". Cette information doit être exactement identique pour continuer.',
'{TEXT_NO_CONNECT_AVAILABLE}'       => 'Til fonction de "Database::connect()" doit s\'appeler avant que les autres fonctions puissent être utilisées.',
'{TEXT_UNACCEPTABLE_TABLEPREFIX}'   => 'Préfixe inacceptable de table découvert',

'{TEXT_IP_PORT_COMB_ERROR}'         => 'Complété la combinaison d\'IP car elle est incorrect.',

'{TEXT_LOADCACHE_FAILED}'           => 'Données caches du serveur non trouvées',
'{TEXT_RAWDATA_UNAVAILABLE}'        => 'Aucunes données brutes disponibles.',
'{TEXT_RETRIEVALBUSY}'              => 'Recherche des données de phase du serveur en cours.',
'{TEXT_SERVERUPDATE_DISABLED}'      => 'MISES À JOUR DU SERVEUR INVALIDÉES.
Ce serveur a échoué a 25 tentatives consécutives de connexion. Par conséquent sais probable que ce serveur n\'existe plus.',

'{TEXT_NO_RESOURCE}'                => 'Cette fonction de BASE DE DONNÉES exige du paramètre d\'être une ressource, il n\'ait pas.',

'{TEXT_RECURSIVE_FUNC_PROT}'        => 'Une fonction récursive a été exécutée à beaucoup de fois et a fait arrêter SLG Comms l\'exécution de la séquence type comme mesure de sauvegarde.',

'{TEXT_NOSERVERS}'                  => 'Aucuns serveurs disponibles.',
'{TEXT_NOCUSTOMSERVERS}'            => 'Capacités faites sur commande du serveur invalidées.',

'{TEXT_NOTHINGTODO}'                => 'En raison d\'une combinaison des erreurs (affichées ci-dessus) que SLG Comms ne peut pas exécuter charge. Veuillez lire les erreurs précédentes et reparé les',
);

$this->text_adv += array(
'{TEXT_DATA_TYPE_ERROR}'            => 'Un type de données inconnu ($1) rencontré pendant le traitement des données recu du serveur.',

'{TEXT_DB_CONNECT_ERROR}'           => 'Ne peu se relier au serveur de base de données ($1).Veuillez informer le webmaster.',
'{TEXT_DB_DATASEEK_FAILED}'         => 'N\'a pas exécuté une recherche de données avec l\'identifer suivant de résultat: "$1"',
'{TEXT_DB_FREEQUERY_FAILED}'        => 'N\'a pas libéré l\'identifer suivant de résultat: "$1"',
'{TEXT_DB_QUERY_FAILED}'            => 'Requête "$1" Échoués.',
'{TEXT_DB_SELECT_ERROR}'            => 'Ne pourrait pas choisir la base de données ($1). Veuillez informer le webmaster.',

'{TEXT_EXTNOTLOAD}'                 => 'extension $1 non chargée. Veuillez s\'assurer que votre PHP a l\'extension $1 chargée.',

'{TEXT_TS_COMMAND_ERROR}'           => 'La commande "$1" envoyée au serveur TeamSpeak a produit d\'une erreur.
L\'erreur exacte retournée par le serveur TeamSpeak peut être trouvée ci-dessous. Le message d\'erreur retourné est souvent très peu clair au sujet de l\'erreur spécifique cependant.',

'{TEXT_UNKNOWNSETTING}'             => 'Type de réglage inconnu produit: "$1".',
);
?>

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

'{TEXT_NOINSTALL}'                  => 'Par tous les comptes il semble que vous n\'avez pas encore ex�cut�install.php .
<a href="install.php">Cliquer ici pour lancer l\'installation</a>.',
'{TEXT_NO_DATABASE_MODE_ACT}'       => 'SLG Comms fonctionne en pr�tendu mode "AUCUNE BASE DE DONN�ES" qui signifie que son ne pas travailler avec le support d\'une base de donn�es. Le r�sultat est que cette page est desactiv�.
Toutes les configurations � votre disposition sont dans le fichier"dbsettings.inc.php"',

'{TEXT_LOADTEMPLATE_ERROR}'         => 'On ne lui permet pas de charger puis un descripteur dans la m�me classe.
Informer le webmaster.',

'{TEXT_CONF_FILE_NOT_IN_DOCROOT}'   => 'Le fichier de configuration de forum doit �tre dans la racine de document de votre webserver.',
'{TEXT_FORUM_NOT_FOUND_ERROR}'      => 'SLG Comms n\'a pas pu trouver le forum dans le r�pertoire indiqu�.
S\'il vous pla�t remplissez l\'information correctement et r�essayer.',
'{TEXT_FORUMTYPE_COMBI_ERROR}'      => 'La combinaison actuelle du "type de forum" et "de la voie d\'acc�s relative du forum" est incorrecte.
Informer le webmaster.',
'{TEXT_INVALID_CONF_FILE}'          => 'Fichier incorrect de configuration de forum.',
'{TEXT_MISSING_GROUP_QUERY_ERROR}'  => 'SLG Comms n\'a pas pu trouver la requ�te requise pour rechercher les groupes de forum. Veuillez informer le webmaster.',
'{TEXT_NOGROUP_INSTALL}'            => 'Il semble que le forum que vous avez choisi n\'a aucun groupe. Veuillez cr�er on contenant les comptes d\'utilisateur qui sont permis d\'acc�der � la section d\'administrateur de SLG Comms.',
'{TEXT_UNKNOWN_FORUMTYPE_ERROR}'    => 'Un type inconnu de forum a �t� produit dans les configurations.
Informer le webmaster.',

'{TEXT_CLASS_TIMECOUNT_ERROR}'      => 'Anomalie produite dans la classe "timecount". Veuillez informer le webmaster.',

'{TEXT_SETTINGFORM_ERROR}'          => 'Il y a une anomalie sous la forme de configurations. Veuillez informer le webmaster.',

'{TEXT_SUPPORT_VENT_DISABLED}'      => 'Le soutien de Ventrilo a �t� invalid�.',
'{TEXT_SUPPORT_TS_DISABLED}'        => 'Le soutien de TeamSpeak a �t� invalid�.',

'{TEXT_OLDVERSION_UNAVAILABLE}'     => 'SLG Comms essay� pour ex�cuter une mise � jour d\'une versionne rencente de SLG Comms mais n\'a pas pu trouver l\'ancien numero de version. Il est fortement probable que vous ne fassiez pas installer une version plus recnente encore.',
'{TEXT_SAMEVERSIONUPDATE}'          => 'La mise � jour a �t� annul� parce que SLG Comms a �t� d�j� �t� mis � jour avec cette version ou plus recente.',
'{TEXT_SLGVERSIONCONFLICT}'         => 'La version interne de fichier n\'est pas identique que la version install�e.
S\'il vous pla�t ex�cution la mise � jour SLG Comms par <a href="install.php">install.php</a>.',

'{TEXT_DEFINED_VENT_PROG_INVALID}'  => 'Le programme d�fini de mode de Ventrilo est incorrect. Veuillez contr�ler la configuration et corrigez-la.',
'{TEXT_NOVENTRILO}'                 => 'Vous avez d�fini un programme de mode de Ventrilo. En raison de cette version de ventrilo n\'est pas encore disponible.',
'{TEXT_VENTRILO_NOT_IN_SLG_DIR}'    => 'Le programme Ventrilo doit �tre situ� dans le r�pertoireracine de SLG Comms ou un r�pertoire secondaire du r�pertoire racine de SLG Comms. Le nom du r�pertoire secondaire n\'importe pas.',
'{TEXT_UNKNOWN_EXEC_ERROR}'         => 'L\'erreur de fonction inconnue EXEC() � �t� rencontr�.  
Son possible que le mode n\'est pas activ� sur votre PHP.  
Voir la documentation sur cette fonction <a href="http://www.php.net/exec">PHP EXEC()</a> et <a href="http://www.php.net/safe+mode+functions">PHP fonction restricted/desactiv� sur le safe mode</a>.',

'{TEXT_NOTTEAMSPEAK}'               => 'Donn�es alt�r�es ou incorrectes du serveur de TeamSpeak produites..',
'{TEXT_STREAM_TIMEOUT}'             => 'Extraction de donn�es de serveur de TeamSpeak hors delai.',
'{TEXT_TSINVALIDID_ERROR}'          => 'SLG Comms reli� � un serveur valide de TeamSpeak mais le nombre gauche fourni n\'a pas appartenu � un serveur accueilli par ce serveur de TeamSpeak.',

'{TEXT_CONNECT_ALLREADY}'           => 'Il y a d�j� une connexion �tablie dans ce cas de la classe de base de donn�es. La fonction de disconnect() devrait etre appele avant d\'ouvrir une nouvelle connexion � la base de donn�es.',
'{TEXT_DB_DISCONNECT_ERROR}'        => 'N\'a pas ferm� la connexion de serveur de base de donn�es. Tr�s probablement la connexion donn�e n\'a pas exist� en raison des erreurs ant�rieures.',
'{TEXT_DIFFERENT_DB_INFO}'          => 'L\'information diff�rente de base de donn�es a rempli dedans compar� � l\'information stock�e dans "dbsettings.inc.php". Cette information doit �tre exactement identique pour continuer.',
'{TEXT_NO_CONNECT_AVAILABLE}'       => 'Til fonction de "Database::connect()" doit s\'appeler avant que les autres fonctions puissent �tre utilis�es.',
'{TEXT_UNACCEPTABLE_TABLEPREFIX}'   => 'Pr�fixe inacceptable de table d�couvert',

'{TEXT_IP_PORT_COMB_ERROR}'         => 'Compl�t� la combinaison d\'IP car elle est incorrect.',

'{TEXT_LOADCACHE_FAILED}'           => 'Donn�es caches du serveur non trouv�es',
'{TEXT_RAWDATA_UNAVAILABLE}'        => 'Aucunes donn�es brutes disponibles.',
'{TEXT_RETRIEVALBUSY}'              => 'Recherche des donn�es de phase du serveur en cours.',
'{TEXT_SERVERUPDATE_DISABLED}'      => 'MISES � JOUR DU SERVEUR INVALID�ES.
Ce serveur a �chou� a 25 tentatives cons�cutives de connexion. Par cons�quent sais probable que ce serveur n\'existe plus.',

'{TEXT_NO_RESOURCE}'                => 'Cette fonction de BASE DE DONN�ES exige du param�tre d\'�tre une ressource, il n\'ait pas.',

'{TEXT_RECURSIVE_FUNC_PROT}'        => 'Une fonction r�cursive a �t� ex�cut�e � beaucoup de fois et a fait arr�ter SLG Comms l\'ex�cution de la s�quence type comme mesure de sauvegarde.',

'{TEXT_NOSERVERS}'                  => 'Aucuns serveurs disponibles.',
'{TEXT_NOCUSTOMSERVERS}'            => 'Capacit�s faites sur commande du serveur invalid�es.',

'{TEXT_NOTHINGTODO}'                => 'En raison d\'une combinaison des erreurs (affich�es ci-dessus) que SLG Comms ne peut pas ex�cuter charge. Veuillez lire les erreurs pr�c�dentes et repar� les',
);

$this->text_adv += array(
'{TEXT_DATA_TYPE_ERROR}'            => 'Un type de donn�es inconnu ($1) rencontr� pendant le traitement des donn�es recu du serveur.',

'{TEXT_DB_CONNECT_ERROR}'           => 'Ne peu se relier au serveur de base de donn�es ($1).Veuillez informer le webmaster.',
'{TEXT_DB_DATASEEK_FAILED}'         => 'N\'a pas ex�cut� une recherche de donn�es avec l\'identifer suivant de r�sultat: "$1"',
'{TEXT_DB_FREEQUERY_FAILED}'        => 'N\'a pas lib�r� l\'identifer suivant de r�sultat: "$1"',
'{TEXT_DB_QUERY_FAILED}'            => 'Requ�te "$1" �chou�s.',
'{TEXT_DB_SELECT_ERROR}'            => 'Ne pourrait pas choisir la base de donn�es ($1). Veuillez informer le webmaster.',

'{TEXT_EXTNOTLOAD}'                 => 'extension $1 non charg�e. Veuillez s\'assurer que votre PHP a l\'extension $1 charg�e.',

'{TEXT_TS_COMMAND_ERROR}'           => 'La commande "$1" envoy�e au serveur TeamSpeak a produit d\'une erreur.
L\'erreur exacte retourn�e par le serveur TeamSpeak peut �tre trouv�e ci-dessous. Le message d\'erreur retourn� est souvent tr�s peu clair au sujet de l\'erreur sp�cifique cependant.',

'{TEXT_UNKNOWNSETTING}'             => 'Type de r�glage inconnu produit: "$1".',
);
?>

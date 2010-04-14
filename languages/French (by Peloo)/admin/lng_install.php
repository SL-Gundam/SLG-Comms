<?php
/***************************************************************************
 *                              lng_install.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_install.php,v 1.2 2007/04/22 22:26:10 SC Kruiper Exp $
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
'{TEXT_YES}'                       => 'ouis', 
'{TEXT_NO}'                        => 'aucun', 

'{TEXT_INSTALL_TYPE}'              => 'type d\'installation',
'{TEXT_NEW_INSTALL}'               => 'nouvelle installation',
'{TEXT_RESCUE_INSTALL}'            => 'des configurations de restauration',
'{TEXT_UPGRADE_INSTALL}'           => 'mise à niveau d\'une version plus ancienne', 

'{TEXT_DATABASE}'                  => 'vous voulez utiliser une base de données avec SLG Comms?', 
'{TEXT_DB_HOST}'                   => 'hostname de serveur de base de données', 
'{TEXT_DB_NAME}'                   => 'nom de base de données', 
'{TEXT_DB_PASSWD}'                 => 'mot de passe de base de données',
'{TEXT_DB_TYPE}'                   => 'type de base de données', 
'{TEXT_DB_USER}'                   => 'username de base de données', 
'{TEXT_MYSQLDATABASE}'             => 'base de données de MySQL', 
'{TEXT_TABLE_PREFIX}'              => 'préfixe de Tableau',

'{TEXT_PCREEXT}'                   => 'extension de PCRE', 
'{TEXT_PHPVERSION}'                => 'version de PHP', 
'{TEXT_REQUIREMENTS}'              => 'conditions',
'{TEXT_SELECTFORUM}'               => 'choisissez s\'il vous plaît un des forum supportés à partir de la baisse enferment dans une boîte vers le bas.', 
'{TEXT_UPDATEPHP}'                 => 'mettent à jour s\'il vous plaît PHP', 
'{TEXT_WORKINGFORUM}'              => 'forum fonctionnant', 
'{TEXT_GZIPEXT}'                   => 'extension de ZLib',

'{TEXT_SELECTDB_SUCCESS}'          => 'a avec succès choisi la base de données', 
'{TEXT_INSTALL_SUCCESS}'           => 'installation avec succès terminée', 
'{TEXT_INSTALL_RESTORE_SUCCESS}'   => 'la tentative de régénérer les configurations était successfull. Veuillez retirer install.php encore', 

'{TEXT_FINISH_INSTALL_LARGE}'      => 'cliquent s\'il vous plaît sur le bouton "d\'installation de finition" pour terminer l\'installation. Après que vous soyez fait avec install.php s\'il vous plaît effacez-le du centre serveur de Web. Si vous ne l\'effacez pas vous avez un risque de sécurité de maire sur vos mains', 
'{TEXT_FINISH_INSTALL}'            => 'installation de finition', 

'{TEXT_INSTALLATIONSTEP}'          => 'étape d\'installation',
'{TEXT_NEXT_STEP}'                 => 'prochaine étape', 

'{TEXT_DOWNLOAD_FILE}'             => 'fichier de téléchargement', 

'{TEXT_ADDEDNEWSETTINGS}'          => 'avez ajouté de nouvelles configurations au fichier de configuration',
'{TEXT_MODDEDNEWSETTINGS}'         => 'des noms variables modifiés dans le fichier de configuration', 
'{TEXT_UPGRADE_SUCCESS}'           => 'SLG avec succès amélioré Comms.
<a href="index.php">Please continue au page principal</a>.',
);

$this->text_adv += array(
'{TEXT_UPGRADING_FROM}'            => 'améliorant de: $1.', 
'{TEXT_UPGRADING_TO}'              => 'améliorant à: $1.',

'{TEXT_ADDCOL_SUCCESS}'            => 'a avec succès ajouté une nouvelle colonne à la table : "$1".', 
'{TEXT_CHANGE_SUCCESS}'            => 'a avec succès mis à jour la table : "$1".', 
'{TEXT_INSERT_DATA_SUCCESS}'       => 'données avec succès insérées de défaut dans la table : "$1".',
'{TEXT_TABLE_CREATE_SUCCESS}'      => 'a avec succès créé la table :"$1".', 
'{TEXT_TABLE_DROP_SUCCESS}'        => 'a avec succès relâché la table : "$1".', 
'{TEXT_UPDATE_SUCCESS}'            => 'a avec succès mis à jour les données dans la table : "$1".', 
'{TEXT_VERSION_SUCCESS}'           => 'terminé appliquant des modifications de table pour les versions puis $1. plus anciens.', 

'{TEXT_CANTWRITE_FILE}'            => 'le fichier "$1" n\'est pas à affichage', 
'{TEXT_FILE_DOESNTEXIST}'          => 'le fichier "$1" n\'existe pas', 
'{TEXT_FILEWRITE_ERROR}'           => 'ne peut pas écrire au fichier "$1".', 
'{TEXT_FILEWRITE_SUCCESS}'         => 'a avec succès écrit l\'information à "$1".
Vous pouvez maintenant commencer à utiliser SLG Comms en ouvrant <a href="index.php">index.php</a>.',
'{TEXT_OPENFILE_ERROR}'            => 'ne peut pas ouvrir le fichier "$1".',

'{TEXT_NO_DATABASE_SERVERLIST}'    => 'puisque vous ne voulez pas utiliser une base de données que vous devrez manuellement modifier "$1" pour installer la liste de serveur.', 

'{TEXT_DOWNLOAD_FILE_LARGE}'       => 'il semble que SLG Comms ne pourrait pas sauvegarder le fichier automatiquement. S\'il vous plaît clic sur le bouton "fichier de téléchargement". Téléchargez ce fichier et téléchargez-l\'au serveur dans le même répertoire que SLG Comms sous le nom de fichier "$1".

Tout devrait fonctionner ensuite cela.

N\'oubliez pas de retirer install.php après que vous soyez fait.'
);

$this->tooltips += array(
'{TEXT_HELP_DATABASE}'             => 'voulez-vous utiliser une base de données avec SLG Comms ? Avec une base de données SLG Comms sera beaucoup plus powerfull. La raison principale pourquoi son possible d\'invalider l\'utilisation d\'une base de données devait supporter des centres serveurs où il n\'y avait pas une base de données disponible.', 

'{TEXT_HELP_DB_HOST}'              => 'le hostname, IP address ou plot du serveur de base de données. Sur option vous devrez également ajouter le nombre gauche si c\'est différent du port "3306" de défaut, ayant pour résultat quelque chose comme ceci : "localhost:3305".

Si vous choisissiez MySQL 4.1.x comme votre type de base de données alors cette configuration DOIT être un hostname ou un IP address avec un nombre gauche facultatif. Les plots ne fonctionneront pas correctement.', 

'{TEXT_HELP_DB_NAME}'              => 'le nom des thats de base de données allant être utilisé pour enregistrer les tables. Assurez-vous que cette base de données déjà existe et a les droits installés correctement.', 

'{TEXT_HELP_DB_PASSWD}'            => 'le mot de passe avec lequel SLG Comms obtiendra l\'accès au serveur de base de données.', 

'{TEXT_HELP_DB_TYPE}'              => 'le type de base de données que vous voulez utiliser. Ceci peut être "MySQL" ou "MySQL 4.1.x/5.0.x". Le support 4.1/5.0 de MySQL est seulement disponible quand l\'extension de MySQLi est incluse dans votre installation de php. "MySQL 4.1.x/5.0.x" est le choix recommandé pour des raisons de compatibilité.

Puisque MySQL 4.1/5.0 utilisations un nouvel algorithme de chiffrement de mot de passe. La vieille extension de MySQL a fourni en PHP ne supporte pas l\'authentification avec ce nouvel algorithme de chiffrement et ou le dira ne supporte pas la nouvelle méthode d\'authentification ou que votre combinaison de mot de passe + de username est incorrecte. Il est possible de créer l\'utilisateur de MySQL que les comptes avec le vieux mot de passe hache mais vous devez explicitement dire MySQL de faire ceci quand vous définissez le mot de passe. Veuillez examiner www.mysql.com pour assurer plus d\'information si nécessaire.', 

'{TEXT_HELP_DB_USER}'              => 'le username avec lequel SLG Comms obtiendra l\'accès au serveur de base de données.', 

'{TEXT_HELP_TABLE_PREFIX}'         => 'le préfixe qui sera placé devant les noms de table pour éviter des conflits avec les tables existantes.', 

'{TEXT_HELP_INSTALL_TYPE}'         => 'ceci par "nouvelle installation", une "mise à niveau d\'une version plus ancienne" ou vous veulent "restaurer des configurations". 

"La restauration de configurations" est seulement quand vous utilisez la version supportée par base de données de SLG Comms comme les ses utilisés exécutent une restauration de la table de configurations quand vous ne pouvez plus procédure de connexion dans ;la section de section d\'administrateur.',
);
?>

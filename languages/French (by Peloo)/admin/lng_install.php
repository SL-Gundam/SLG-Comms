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
'{TEXT_UPGRADE_INSTALL}'           => 'mise � niveau d\'une version plus ancienne', 

'{TEXT_DATABASE}'                  => 'vous voulez utiliser une base de donn�es avec SLG Comms?', 
'{TEXT_DB_HOST}'                   => 'hostname de serveur de base de donn�es', 
'{TEXT_DB_NAME}'                   => 'nom de base de donn�es', 
'{TEXT_DB_PASSWD}'                 => 'mot de passe de base de donn�es',
'{TEXT_DB_TYPE}'                   => 'type de base de donn�es', 
'{TEXT_DB_USER}'                   => 'username de base de donn�es', 
'{TEXT_MYSQLDATABASE}'             => 'base de donn�es de MySQL', 
'{TEXT_TABLE_PREFIX}'              => 'pr�fixe de Tableau',

'{TEXT_PCREEXT}'                   => 'extension de PCRE', 
'{TEXT_PHPVERSION}'                => 'version de PHP', 
'{TEXT_REQUIREMENTS}'              => 'conditions',
'{TEXT_SELECTFORUM}'               => 'choisissez s\'il vous pla�t un des forum support�s � partir de la baisse enferment dans une bo�te vers le bas.', 
'{TEXT_UPDATEPHP}'                 => 'mettent � jour s\'il vous pla�t PHP', 
'{TEXT_WORKINGFORUM}'              => 'forum fonctionnant', 
'{TEXT_GZIPEXT}'                   => 'extension de ZLib',

'{TEXT_SELECTDB_SUCCESS}'          => 'a avec succ�s choisi la base de donn�es', 
'{TEXT_INSTALL_SUCCESS}'           => 'installation avec succ�s termin�e', 
'{TEXT_INSTALL_RESTORE_SUCCESS}'   => 'la tentative de r�g�n�rer les configurations �tait successfull. Veuillez retirer install.php encore', 

'{TEXT_FINISH_INSTALL_LARGE}'      => 'cliquent s\'il vous pla�t sur le bouton "d\'installation de finition" pour terminer l\'installation. Apr�s que vous soyez fait avec install.php s\'il vous pla�t effacez-le du centre serveur de Web. Si vous ne l\'effacez pas vous avez un risque de s�curit� de maire sur vos mains', 
'{TEXT_FINISH_INSTALL}'            => 'installation de finition', 

'{TEXT_INSTALLATIONSTEP}'          => '�tape d\'installation',
'{TEXT_NEXT_STEP}'                 => 'prochaine �tape', 

'{TEXT_DOWNLOAD_FILE}'             => 'fichier de t�l�chargement', 

'{TEXT_ADDEDNEWSETTINGS}'          => 'avez ajout� de nouvelles configurations au fichier de configuration',
'{TEXT_MODDEDNEWSETTINGS}'         => 'des noms variables modifi�s dans le fichier de configuration', 
'{TEXT_UPGRADE_SUCCESS}'           => 'SLG avec succ�s am�lior� Comms.
<a href="index.php">Please continue au page principal</a>.',
);

$this->text_adv += array(
'{TEXT_UPGRADING_FROM}'            => 'am�liorant de: $1.', 
'{TEXT_UPGRADING_TO}'              => 'am�liorant �: $1.',

'{TEXT_ADDCOL_SUCCESS}'            => 'a avec succ�s ajout� une nouvelle colonne � la table : "$1".', 
'{TEXT_CHANGE_SUCCESS}'            => 'a avec succ�s mis � jour la table : "$1".', 
'{TEXT_INSERT_DATA_SUCCESS}'       => 'donn�es avec succ�s ins�r�es de d�faut dans la table : "$1".',
'{TEXT_TABLE_CREATE_SUCCESS}'      => 'a avec succ�s cr�� la table :"$1".', 
'{TEXT_TABLE_DROP_SUCCESS}'        => 'a avec succ�s rel�ch� la table : "$1".', 
'{TEXT_UPDATE_SUCCESS}'            => 'a avec succ�s mis � jour les donn�es dans la table : "$1".', 
'{TEXT_VERSION_SUCCESS}'           => 'termin� appliquant des modifications de table pour les versions puis $1. plus anciens.', 

'{TEXT_CANTWRITE_FILE}'            => 'le fichier "$1" n\'est pas � affichage', 
'{TEXT_FILE_DOESNTEXIST}'          => 'le fichier "$1" n\'existe pas', 
'{TEXT_FILEWRITE_ERROR}'           => 'ne peut pas �crire au fichier "$1".', 
'{TEXT_FILEWRITE_SUCCESS}'         => 'a avec succ�s �crit l\'information � "$1".
Vous pouvez maintenant commencer � utiliser SLG Comms en ouvrant <a href="index.php">index.php</a>.',
'{TEXT_OPENFILE_ERROR}'            => 'ne peut pas ouvrir le fichier "$1".',

'{TEXT_NO_DATABASE_SERVERLIST}'    => 'puisque vous ne voulez pas utiliser une base de donn�es que vous devrez manuellement modifier "$1" pour installer la liste de serveur.', 

'{TEXT_DOWNLOAD_FILE_LARGE}'       => 'il semble que SLG Comms ne pourrait pas sauvegarder le fichier automatiquement. S\'il vous pla�t clic sur le bouton "fichier de t�l�chargement". T�l�chargez ce fichier et t�l�chargez-l\'au serveur dans le m�me r�pertoire que SLG Comms sous le nom de fichier "$1".

Tout devrait fonctionner ensuite cela.

N\'oubliez pas de retirer install.php apr�s que vous soyez fait.'
);

$this->tooltips += array(
'{TEXT_HELP_DATABASE}'             => 'voulez-vous utiliser une base de donn�es avec SLG Comms ? Avec une base de donn�es SLG Comms sera beaucoup plus powerfull. La raison principale pourquoi son possible d\'invalider l\'utilisation d\'une base de donn�es devait supporter des centres serveurs o� il n\'y avait pas une base de donn�es disponible.', 

'{TEXT_HELP_DB_HOST}'              => 'le hostname, IP address ou plot du serveur de base de donn�es. Sur option vous devrez �galement ajouter le nombre gauche si c\'est diff�rent du port "3306" de d�faut, ayant pour r�sultat quelque chose comme ceci : "localhost:3305".

Si vous choisissiez MySQL 4.1.x comme votre type de base de donn�es alors cette configuration DOIT �tre un hostname ou un IP address avec un nombre gauche facultatif. Les plots ne fonctionneront pas correctement.', 

'{TEXT_HELP_DB_NAME}'              => 'le nom des thats de base de donn�es allant �tre utilis� pour enregistrer les tables. Assurez-vous que cette base de donn�es d�j� existe et a les droits install�s correctement.', 

'{TEXT_HELP_DB_PASSWD}'            => 'le mot de passe avec lequel SLG Comms obtiendra l\'acc�s au serveur de base de donn�es.', 

'{TEXT_HELP_DB_TYPE}'              => 'le type de base de donn�es que vous voulez utiliser. Ceci peut �tre "MySQL" ou "MySQL 4.1.x/5.0.x". Le support 4.1/5.0 de MySQL est seulement disponible quand l\'extension de MySQLi est incluse dans votre installation de php. "MySQL 4.1.x/5.0.x" est le choix recommand� pour des raisons de compatibilit�.

Puisque MySQL 4.1/5.0 utilisations un nouvel algorithme de chiffrement de mot de passe. La vieille extension de MySQL a fourni en PHP ne supporte pas l\'authentification avec ce nouvel algorithme de chiffrement et ou le dira ne supporte pas la nouvelle m�thode d\'authentification ou que votre combinaison de mot de passe + de username est incorrecte. Il est possible de cr�er l\'utilisateur de MySQL que les comptes avec le vieux mot de passe hache mais vous devez explicitement dire MySQL de faire ceci quand vous d�finissez le mot de passe. Veuillez examiner www.mysql.com pour assurer plus d\'information si n�cessaire.', 

'{TEXT_HELP_DB_USER}'              => 'le username avec lequel SLG Comms obtiendra l\'acc�s au serveur de base de donn�es.', 

'{TEXT_HELP_TABLE_PREFIX}'         => 'le pr�fixe qui sera plac� devant les noms de table pour �viter des conflits avec les tables existantes.', 

'{TEXT_HELP_INSTALL_TYPE}'         => 'ceci par "nouvelle installation", une "mise � niveau d\'une version plus ancienne" ou vous veulent "restaurer des configurations". 

"La restauration de configurations" est seulement quand vous utilisez la version support�e par base de donn�es de SLG Comms comme les ses utilis�s ex�cutent une restauration de la table de configurations quand vous ne pouvez plus proc�dure de connexion dans ;la section de section d\'administrateur.',
);
?>

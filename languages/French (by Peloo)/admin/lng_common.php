<?php
/***************************************************************************
 *                              lng_common.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_common.php,v 1.2 2007/04/22 22:26:10 SC Kruiper Exp $
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
'{TEXT_ENABLE}'                            => 'permettez', 
'{TEXT_DISABLE}'                           => 'invalident',

'{TEXT_BASE_URL}'                          => 'URL de base', 
'{TEXT_CACHE_HITS}'                        => 'dans présences dans l\'antémémoire', 
'{TEXT_CUSTOM_SERVERS}'                    => 'les serveurs faits sur commande', 
'{TEXT_DEFAULT_QUERYPORT}'                 => 'queryport de défaut', 
'{TEXT_DEFAULT_SERVER}'                    => 'serveur de défaut',
'{TEXT_DISPLAY_PING}'                      => 'cinglement d\'affichage',
'{TEXT_DISPLAY_SERVER_INFORMATION}'        => 'l\'information de serveur d\'affichage', 
'{TEXT_FORUM_GROUP}'                       => 'groupe de forum',
'{TEXT_FORUM_RELATIVE_PATH}'               => 'la voie d\'accès relative de forum',
'{TEXT_FORUM_TYPE}'                        => 'type de forum', 
'{TEXT_GZIP_COMPRESSION}'                  => 'compactage de GZIP', 
'{TEXT_LANGUAGE}'                          => 'langage',
'{TEXT_PAGE_GENERATION_TIME}'              => 'temps de génération de page',
'{TEXT_PAGE_REFRESH_TIMER}'                => 'page régénèrent le temporisateur', 
'{TEXT_PAGE_TITLE}'                        => 'titre de page',
'{TEXT_RETRIEVED_DATA_STATUS}'             => 'des modes recherchés de données',
'{TEXT_ROOT_PATH}'                         => 'répertoire racine', 
'{TEXT_SLG_VERSION}'                       => 'version de SLG', 
'{TEXT_TEAMSPEAK_SUPPORT}'                 => 'support de TeamSpeak', 
'{TEXT_TEMPLATE}'                          => 'descripteur',
'{TEXT_VENTRILO_STATUS_PROGRAM}'           => 'programme de mode de Ventrilo',
'{TEXT_VENTRILO_SUPPORT}'                  => 'support de Ventrilo',
);

$this->tooltips += array(
'{TEXT_HELP_BASE_URL}'                     => 'c\'est le URL complet à l\'exclusion de "HTTP ://" comme vu d\'un point de vue de navigateurs. C\'est la pièce commençant par le hostname jusqu\'à ce que le répertoire SLG Comms soit localisé dedans.

Exemple: 
Emplacement SLG Comms: http://www.test.com/website/SLG Comms/ 
alors que ce qui suit devrait être présenté dans cette configuration: www.test.com/website/SLG Comms/ 

cette configuration est employé pour créer les liens appropriés pour des images, des stylesheets et quelques hyperliens. 

Veuillez corriger cette configuration si les images et le montrent correctement.',

'{TEXT_HELP_CACHE_HITS}'                   => 'ceci permet ou des débronchements la capacité de compter le nombre de des données cachées par fois ont été recherchés au lieu des données de phase.',

'{TEXT_HELP_CUSTOM_SERVERS}'               => 'ceci permet ou invalide la capacité de rechercher des données des serveurs faits sur commande fournis par le client. Si vous n\'avez pas besoin de ce dispositif pour une bonne raison on lui informe que pour l\'invalider.',

'{TEXT_HELP_DEFAULT_QUERYPORT}'            => 'c\'est le queryport de TeamSpeak TCP de défaut à utiliser une fois non défini par la ressource.

TeamSpeak a normalement le port 51234 comme queryport de TCP de défaut mais ceci pourrait être différent si changé par la compagnie/personne accueillant le serveur de TeamSpeak. Il pourrait avoir été également changé par une nouvelle version du serveur de TeamSpeak. De toute façon son non adviseable pour changer cette configuration à moins que le serveur de TeamSpeak utilise un autre queryport de défaut sur une nouvelle installation.

Si vous avez besoin d\'un queryport différent pour un certain usage de serveur de TeamSpeak le troisième paramètre facultatif de la zone "de données de ressource". Si vous avez besoin de plus d\'information sur ce paramètre facultatif disparaissent "ajoutent des ressources" et planent la souris l\'"AIDE" au-dessus de textes.',

'{TEXT_HELP_DEFAULT_SERVER}'               => 'le serveur de défaut à charger si le client n\'a pas choisi un serveur pourtant.',

'{TEXT_HELP_DISPLAY_PING}'                 => 'ceci a permis ou invalide l\'affichage du cinglement derrière des noms de client.

Le but de cette configuration est de vous permettre de libérer un certain espace si vous utilisez SLG Comms dans côté-bloquez d\'un système de gestion content.

Si vous invalidez cette configuration le cinglement sera encore affiché dans le tooltip des clients.',

'{TEXT_HELP_DISPLAY_SERVER_INFORMATION}'   => 'ceci permet ou invalide la capacité d\'afficher le carreau de l\'information de serveur.

Cette information est toujours disponible en planant au-dessus du carreau de l\'information de la Manche sur le nom de serveur.',

'{TEXT_HELP_FORUM_GROUP}'                  => 'c\'est le groupe de forum qui a accès à la section d\'administrateur de SLG Comms.',

'{TEXT_HELP_FORUM_RELATIVE_PATH}'          => 'ceci est la voie d\'accès relative (signification que vous devez travailler à partir du répertoire actuel et monter un répertoire vous devez utiliser "../") au forum choisi dans le "forum tapez" la configuration.

Ne commencez pas cette configuration avec une barre de fraction.

Maintenez s\'il vous plaît dans l\'esprit que ce répertoire doit être d\'un point de vue de serveurs et pas du URL d\'un navigateur.

Vous assurez vous extrémité avec une barre de fraction.

Pour meilleur le support en travers de plateforme l\'utilisation réduit seulement pour les séparateurs de répertoire.

Cette configuration est cas sensible.',

'{TEXT_HELP_FORUM_TYPE}'                   => 'ceci définit le forum SLG Comms utilisera comme circuit principal pour la gestion d\'utilisateur. Actuel SLG Comms supporte une quantité limitée de forum (certains ont des profils multiples en raison de différentes versions) et son non possible de l\'exécuter autonome à moins que vous ayez décidé de ne pas utiliser une base de données. En outre les forum supportés ont des nombres de version parce que SLG Comms a été testé sur ces versions. L\'utilisation de SLG Comms avec d\'autres versions est non soutenue ainsi procédez à votre propre risque.

Si vous voudriez voir le support pour un forum qui est encore sans support ajoutez le support vous-même ou ajoutez une demande de dispositif sur le site Web de SLG Comms. Le URL est dans la notification de copyright en bas de chaque page.', 

'{TEXT_HELP_GZIP_COMPRESSION}'             => 'cette configuration permet ou moteur de compactage du GZIP de PHP handicapé.

Ceci rend les fichiers envoyés aux clients plus petits en échange d\'un bit minuscule de temps machine.

On lui informe que vous permettez le compactage de GZIP.

Bien que le permettant cette voie devrait être la dernière option.',







'{TEXT_HELP_LANGUAGE}'                     => 'Ici vous pouvez sélectionner le langue de votre choix pour utiliser avec SLG Comms.

Vous pouvez ajouter plus de langages en les ajoutant dans le répertoire de "languages" dans le répertoire de SLG Comms.',

'{TEXT_HELP_PAGE_GENERATION_TIME}'         => 'ceci permet ou invalide le texte "de temps de génération de page" en bas des pages.',

'{TEXT_HELP_PAGE_REFRESH_TIMER}'           => 'ici vous pouvez définir une quantité de secondes après quoi la page d\'incrément devrait être automatiquement régénérée. Placez cette configuration à zéro ,pour invalider ce dispositif et pour cacher l\'horloge. Si vous permettez ce dispositif, vous pouvez toujours commencer et arrêter ce temporisateur tandis que le you\'re visualisant la page en cliquant sur l\'horloge dans le bon coin supérieur de la page.', 

'{TEXT_HELP_PAGE_TITLE}'                   => 'ceci est le titre des pages. Il sera utilisé dans l\'en-tête et dans la partie supérieure des pages.',

'{TEXT_HELP_RETRIEVED_DATA_STATUS}'        => 'ceci permet ou invalide la capacité d\'afficher si l\'information actuelle qui est affichée est cachée ou vivre des données.', 

'{TEXT_HELP_ROOT_PATH}'                    => 'ceci est le répertoire racine de SLG Comms comme vu du point de vue de webservers. De sorte que moyens aucune substance de http:// etc.. Selon le système d\'exploitation où le webserver fonctionne sur le format différera.

Normalement cette valeur devrait ne jamais être changée. Si vous la changez que SLG Comms ne pourrait fonctionner correctement plus et vous devriez employer des "configurations de restauration" dans install.php pour fixer le problème.

Exemples:
Win32: H:/apache/htdocs/SLG Comms/
Unix/Linux: /user/test/htdocs/SLG Comms/

emballent la valeur actuelle sont incorrects il sont fortement informés pour le corriger.',

'{TEXT_HELP_SLG_VERSION}'                  => 'c\'est la version en cours de SLG Comms. Cette configuration ne peut pas être changée.',

'{TEXT_HELP_TEAMSPEAK_SUPPORT}'            => 'ceci permet ou soutien de débronchements des serveurs de TeamSpeak.', 

'{TEXT_HELP_TEMPLATE}'                     => 'ici vous pouvez choisir que le descripteur vous voudrait utiliser avec SLG Comms. Les 3 descripteurs fournis avec ces version. "Default" et "Default 2" sont presque exactement identiques excepté le ,menu dans les pages d\'admin. Choisissez ainsi la version avec le menu que vous aimez mieux.
 
le "Default 2" a cependant seulement un but et thats de prouver qu\'il est possible de créer un descripteur avec un menu vertical au lieu de l\'horizontal dans le descripteur de "Default". Le menu vertical fonctionnera sans problèmes bien que la disposition pourrait être un bit irritant parfois.

le "Default compact" est fondamentalement identique que le "Default" sauf qu\'on se base le sur l\'idée qu\'il sera utilisé dans côté-bloquent d\'un système de gestion content. Pour l\'obtenir aux la plupart de ce descripteur est informé pour placer les configurations suivantes aux valeurs suivantes: 
Les serveurs -> invalident
le cinglement d\'affichage -> invalident
l\'information de serveur d\'affichage -> invalident
le temps de génération de page -> 0
le temporisateur -> invalident
le mode recherché de données -> invalident

Quelque chose que vous devriez également savoir le "Default" le descripteur compact est que le descripteur n\'a aucune forme où vous pouvez choisir un serveur. Pour obtenir un serveur spécifique que vous devez utiliser le URL vous obtenez quand vous cliquez sur le nom de ce serveur spécifique dans "Controler les resources" et "Controler les serveurs" pages.',

'{TEXT_HELP_VENTRILO_STATUS_PROGRAM}'      => 'c\'est la voie d\'accès relative (signification vous devez travailler à partir du répertoire actuel et monter un répertoire vous devez utiliser "../" ou "..") au programme de mode de Ventrilo qui est nécessaire pour rechercher des données d\'un serveur de Ventrilo.

Il y a différentes versions pour différents systèmes d\'exploitation, une pour des plateformes d\'Unix/Linux, une pour des plateformes de Windows et divers autres systèmes d\'exploitation. La version de Windows a normalement une extension d\'exe et l\'Unix/Linux aucun. Choisissez le fichier correct pour le système d\'exploitation SLG Comms sera exécuté en fonction.

Ne commencez pas cette configuration avec une barre de fraction ou un antislash.

Maintenez s\'il vous plaît dans l\'esprit que ce répertoire doit être d\'un point de vue de serveurs et pas du URL d\'un navigateur. 

On lui informe pour utiliser seulement des barres de fraction pour des séparateurs de répertoire mais avec Windows l\'antislash est également disponible. 

La version 2.2.0 et 2.3.0 du programme de mode de Ventrilo ont été testées sur la version 2.1.2, 2.2.0 et 2.3.0 du serveur de Ventrilo.

Puisque le programme de mode de ventrilo est copyright protégé je ne peux pas l\'emballer dans cette version. Veuillez télécharger une version du serveur de Ventrilo de www.ventrilo.com et placez le ;"ventrilo_status.exe" (Win32 exécutable), "ventrilo_status" ;(Unix/Linux exécutable) ou le mode de Ventrilo exécutable pour votre système d\'exploitation dans une chemise accessible par SLG Comms.

s\'assurent que les fichiers ont de pleins droits particulièrement sous Unix/Linux. Il exige lu, écrit et exécute l\'accès (de 777). Écrivez pourrait sembler étrange mais avec lue juste et l\'exécuter parfois n\'a pas travaillé correctement sur quelques systèmes. Il y a ,également des versions du programme de mode de Ventrilo disponible pour d\'autres systèmes d\'exploitation, mais ces démuni testé. Ils devraient fonctionner très bien mais il n\'y a pas aucune garantie. 

Cette configuration est cas sensible.',

'{TEXT_HELP_VENTRILO_SUPPORT}'             => 'ceci permet ou le soutien de débronchements des serveurs de Ventrilo.',
);
?>

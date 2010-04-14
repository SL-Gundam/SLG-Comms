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
'{TEXT_CACHE_HITS}'                        => 'dans pr�sences dans l\'ant�m�moire', 
'{TEXT_CUSTOM_SERVERS}'                    => 'les serveurs faits sur commande', 
'{TEXT_DEFAULT_QUERYPORT}'                 => 'queryport de d�faut', 
'{TEXT_DEFAULT_SERVER}'                    => 'serveur de d�faut',
'{TEXT_DISPLAY_PING}'                      => 'cinglement d\'affichage',
'{TEXT_DISPLAY_SERVER_INFORMATION}'        => 'l\'information de serveur d\'affichage', 
'{TEXT_FORUM_GROUP}'                       => 'groupe de forum',
'{TEXT_FORUM_RELATIVE_PATH}'               => 'la voie d\'acc�s relative de forum',
'{TEXT_FORUM_TYPE}'                        => 'type de forum', 
'{TEXT_GZIP_COMPRESSION}'                  => 'compactage de GZIP', 
'{TEXT_LANGUAGE}'                          => 'langage',
'{TEXT_PAGE_GENERATION_TIME}'              => 'temps de g�n�ration de page',
'{TEXT_PAGE_REFRESH_TIMER}'                => 'page r�g�n�rent le temporisateur', 
'{TEXT_PAGE_TITLE}'                        => 'titre de page',
'{TEXT_RETRIEVED_DATA_STATUS}'             => 'des modes recherch�s de donn�es',
'{TEXT_ROOT_PATH}'                         => 'r�pertoire racine', 
'{TEXT_SLG_VERSION}'                       => 'version de SLG', 
'{TEXT_TEAMSPEAK_SUPPORT}'                 => 'support de TeamSpeak', 
'{TEXT_TEMPLATE}'                          => 'descripteur',
'{TEXT_VENTRILO_STATUS_PROGRAM}'           => 'programme de mode de Ventrilo',
'{TEXT_VENTRILO_SUPPORT}'                  => 'support de Ventrilo',
);

$this->tooltips += array(
'{TEXT_HELP_BASE_URL}'                     => 'c\'est le URL complet � l\'exclusion de "HTTP ://" comme vu d\'un point de vue de navigateurs. C\'est la pi�ce commen�ant par le hostname jusqu\'� ce que le r�pertoire SLG Comms soit localis� dedans.

Exemple: 
Emplacement SLG Comms: http://www.test.com/website/SLG Comms/ 
alors que ce qui suit devrait �tre pr�sent� dans cette configuration: www.test.com/website/SLG Comms/ 

cette configuration est employ� pour cr�er les liens appropri�s pour des images, des stylesheets et quelques hyperliens. 

Veuillez corriger cette configuration si les images et le montrent correctement.',

'{TEXT_HELP_CACHE_HITS}'                   => 'ceci permet ou des d�bronchements la capacit� de compter le nombre de des donn�es cach�es par fois ont �t� recherch�s au lieu des donn�es de phase.',

'{TEXT_HELP_CUSTOM_SERVERS}'               => 'ceci permet ou invalide la capacit� de rechercher des donn�es des serveurs faits sur commande fournis par le client. Si vous n\'avez pas besoin de ce dispositif pour une bonne raison on lui informe que pour l\'invalider.',

'{TEXT_HELP_DEFAULT_QUERYPORT}'            => 'c\'est le queryport de TeamSpeak TCP de d�faut � utiliser une fois non d�fini par la ressource.

TeamSpeak a normalement le port 51234 comme queryport de TCP de d�faut mais ceci pourrait �tre diff�rent si chang� par la compagnie/personne accueillant le serveur de TeamSpeak. Il pourrait avoir �t� �galement chang� par une nouvelle version du serveur de TeamSpeak. De toute fa�on son non adviseable pour changer cette configuration � moins que le serveur de TeamSpeak utilise un autre queryport de d�faut sur une nouvelle installation.

Si vous avez besoin d\'un queryport diff�rent pour un certain usage de serveur de TeamSpeak le troisi�me param�tre facultatif de la zone "de donn�es de ressource". Si vous avez besoin de plus d\'information sur ce param�tre facultatif disparaissent "ajoutent des ressources" et planent la souris l\'"AIDE" au-dessus de textes.',

'{TEXT_HELP_DEFAULT_SERVER}'               => 'le serveur de d�faut � charger si le client n\'a pas choisi un serveur pourtant.',

'{TEXT_HELP_DISPLAY_PING}'                 => 'ceci a permis ou invalide l\'affichage du cinglement derri�re des noms de client.

Le but de cette configuration est de vous permettre de lib�rer un certain espace si vous utilisez SLG Comms dans c�t�-bloquez d\'un syst�me de gestion content.

Si vous invalidez cette configuration le cinglement sera encore affich� dans le tooltip des clients.',

'{TEXT_HELP_DISPLAY_SERVER_INFORMATION}'   => 'ceci permet ou invalide la capacit� d\'afficher le carreau de l\'information de serveur.

Cette information est toujours disponible en planant au-dessus du carreau de l\'information de la Manche sur le nom de serveur.',

'{TEXT_HELP_FORUM_GROUP}'                  => 'c\'est le groupe de forum qui a acc�s � la section d\'administrateur de SLG Comms.',

'{TEXT_HELP_FORUM_RELATIVE_PATH}'          => 'ceci est la voie d\'acc�s relative (signification que vous devez travailler � partir du r�pertoire actuel et monter un r�pertoire vous devez utiliser "../") au forum choisi dans le "forum tapez" la configuration.

Ne commencez pas cette configuration avec une barre de fraction.

Maintenez s\'il vous pla�t dans l\'esprit que ce r�pertoire doit �tre d\'un point de vue de serveurs et pas du URL d\'un navigateur.

Vous assurez vous extr�mit� avec une barre de fraction.

Pour meilleur le support en travers de plateforme l\'utilisation r�duit seulement pour les s�parateurs de r�pertoire.

Cette configuration est cas sensible.',

'{TEXT_HELP_FORUM_TYPE}'                   => 'ceci d�finit le forum SLG Comms utilisera comme circuit principal pour la gestion d\'utilisateur. Actuel SLG Comms supporte une quantit� limit�e de forum (certains ont des profils multiples en raison de diff�rentes versions) et son non possible de l\'ex�cuter autonome � moins que vous ayez d�cid� de ne pas utiliser une base de donn�es. En outre les forum support�s ont des nombres de version parce que SLG Comms a �t� test� sur ces versions. L\'utilisation de SLG Comms avec d\'autres versions est non soutenue ainsi proc�dez � votre propre risque.

Si vous voudriez voir le support pour un forum qui est encore sans support ajoutez le support vous-m�me ou ajoutez une demande de dispositif sur le site Web de SLG Comms. Le URL est dans la notification de copyright en bas de chaque page.', 

'{TEXT_HELP_GZIP_COMPRESSION}'             => 'cette configuration permet ou moteur de compactage du GZIP de PHP handicap�.

Ceci rend les fichiers envoy�s aux clients plus petits en �change d\'un bit minuscule de temps machine.

On lui informe que vous permettez le compactage de GZIP.

Bien que le permettant cette voie devrait �tre la derni�re option.',







'{TEXT_HELP_LANGUAGE}'                     => 'Ici vous pouvez s�lectionner le langue de votre choix pour utiliser avec SLG Comms.

Vous pouvez ajouter plus de langages en les ajoutant dans le r�pertoire de "languages" dans le r�pertoire de SLG Comms.',

'{TEXT_HELP_PAGE_GENERATION_TIME}'         => 'ceci permet ou invalide le texte "de temps de g�n�ration de page" en bas des pages.',

'{TEXT_HELP_PAGE_REFRESH_TIMER}'           => 'ici vous pouvez d�finir une quantit� de secondes apr�s quoi la page d\'incr�ment devrait �tre automatiquement r�g�n�r�e. Placez cette configuration � z�ro ,pour invalider ce dispositif et pour cacher l\'horloge. Si vous permettez ce dispositif, vous pouvez toujours commencer et arr�ter ce temporisateur tandis que le you\'re visualisant la page en cliquant sur l\'horloge dans le bon coin sup�rieur de la page.', 

'{TEXT_HELP_PAGE_TITLE}'                   => 'ceci est le titre des pages. Il sera utilis� dans l\'en-t�te et dans la partie sup�rieure des pages.',

'{TEXT_HELP_RETRIEVED_DATA_STATUS}'        => 'ceci permet ou invalide la capacit� d\'afficher si l\'information actuelle qui est affich�e est cach�e ou vivre des donn�es.', 

'{TEXT_HELP_ROOT_PATH}'                    => 'ceci est le r�pertoire racine de SLG Comms comme vu du point de vue de webservers. De sorte que moyens aucune substance de http:// etc.. Selon le syst�me d\'exploitation o� le webserver fonctionne sur le format diff�rera.

Normalement cette valeur devrait ne jamais �tre chang�e. Si vous la changez que SLG Comms ne pourrait fonctionner correctement plus et vous devriez employer des "configurations de restauration" dans install.php pour fixer le probl�me.

Exemples:
Win32: H:/apache/htdocs/SLG Comms/
Unix/Linux: /user/test/htdocs/SLG Comms/

emballent la valeur actuelle sont incorrects il sont fortement inform�s pour le corriger.',

'{TEXT_HELP_SLG_VERSION}'                  => 'c\'est la version en cours de SLG Comms. Cette configuration ne peut pas �tre chang�e.',

'{TEXT_HELP_TEAMSPEAK_SUPPORT}'            => 'ceci permet ou soutien de d�bronchements des serveurs de TeamSpeak.', 

'{TEXT_HELP_TEMPLATE}'                     => 'ici vous pouvez choisir que le descripteur vous voudrait utiliser avec SLG Comms. Les 3 descripteurs fournis avec ces version. "Default" et "Default 2" sont presque exactement identiques except� le ,menu dans les pages d\'admin. Choisissez ainsi la version avec le menu que vous aimez mieux.
 
le "Default 2" a cependant seulement un but et thats de prouver qu\'il est possible de cr�er un descripteur avec un menu vertical au lieu de l\'horizontal dans le descripteur de "Default". Le menu vertical fonctionnera sans probl�mes bien que la disposition pourrait �tre un bit irritant parfois.

le "Default compact" est fondamentalement identique que le "Default" sauf qu\'on se base le sur l\'id�e qu\'il sera utilis� dans c�t�-bloquent d\'un syst�me de gestion content. Pour l\'obtenir aux la plupart de ce descripteur est inform� pour placer les configurations suivantes aux valeurs suivantes: 
Les serveurs -> invalident
le cinglement d\'affichage -> invalident
l\'information de serveur d\'affichage -> invalident
le temps de g�n�ration de page -> 0
le temporisateur -> invalident
le mode recherch� de donn�es -> invalident

Quelque chose que vous devriez �galement savoir le "Default" le descripteur compact est que le descripteur n\'a aucune forme o� vous pouvez choisir un serveur. Pour obtenir un serveur sp�cifique que vous devez utiliser le URL vous obtenez quand vous cliquez sur le nom de ce serveur sp�cifique dans "Controler les resources" et "Controler les serveurs" pages.',

'{TEXT_HELP_VENTRILO_STATUS_PROGRAM}'      => 'c\'est la voie d\'acc�s relative (signification vous devez travailler � partir du r�pertoire actuel et monter un r�pertoire vous devez utiliser "../" ou "..") au programme de mode de Ventrilo qui est n�cessaire pour rechercher des donn�es d\'un serveur de Ventrilo.

Il y a diff�rentes versions pour diff�rents syst�mes d\'exploitation, une pour des plateformes d\'Unix/Linux, une pour des plateformes de Windows et divers autres syst�mes d\'exploitation. La version de Windows a normalement une extension d\'exe et l\'Unix/Linux aucun. Choisissez le fichier correct pour le syst�me d\'exploitation SLG Comms sera ex�cut� en fonction.

Ne commencez pas cette configuration avec une barre de fraction ou un antislash.

Maintenez s\'il vous pla�t dans l\'esprit que ce r�pertoire doit �tre d\'un point de vue de serveurs et pas du URL d\'un navigateur. 

On lui informe pour utiliser seulement des barres de fraction pour des s�parateurs de r�pertoire mais avec Windows l\'antislash est �galement disponible. 

La version 2.2.0 et 2.3.0 du programme de mode de Ventrilo ont �t� test�es sur la version 2.1.2, 2.2.0 et 2.3.0 du serveur de Ventrilo.

Puisque le programme de mode de ventrilo est copyright prot�g� je ne peux pas l\'emballer dans cette version. Veuillez t�l�charger une version du serveur de Ventrilo de www.ventrilo.com et placez le ;"ventrilo_status.exe" (Win32 ex�cutable), "ventrilo_status" ;(Unix/Linux ex�cutable) ou le mode de Ventrilo ex�cutable pour votre syst�me d\'exploitation dans une chemise accessible par SLG Comms.

s\'assurent que les fichiers ont de pleins droits particuli�rement sous Unix/Linux. Il exige lu, �crit et ex�cute l\'acc�s (de 777). �crivez pourrait sembler �trange mais avec lue juste et l\'ex�cuter parfois n\'a pas travaill� correctement sur quelques syst�mes. Il y a ,�galement des versions du programme de mode de Ventrilo disponible pour d\'autres syst�mes d\'exploitation, mais ces d�muni test�. Ils devraient fonctionner tr�s bien mais il n\'y a pas aucune garantie. 

Cette configuration est cas sensible.',

'{TEXT_HELP_VENTRILO_SUPPORT}'             => 'ceci permet ou le soutien de d�bronchements des serveurs de Ventrilo.',
);
?>

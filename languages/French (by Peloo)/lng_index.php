<?php
/***************************************************************************
 *                               lng_index.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index.php,v 1.3 2008/08/10 15:08:35 SC Kruiper Exp $
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
'{TEXT_CUSTOM_SERVER}'             => 'Serveur custom',
'{TEXT_CUSTOM_SERVER_TYPE}'        => 'Type de server',
'{TEXT_CUSTOM_SERVER_VENT_SORT}'   => 'Trier de canal Ventrilo',
'{TEXT_IP_PORT}'                   => 'IP:PORT',
'{TEXT_SERVER}'                    => 'Serveur',
'{TEXT_SUBMIT_SERVERFORM}'         => 'Envoyer',

'{TEXT_ALPHABETICALLY}'            => 'Alphabetical',
'{TEXT_MANUALLY}'                  => 'Aucun tri',
);

$this->tooltips += array(
'{TEXT_HELPTEXT}'                  => 'Le format pour les serveurs TeamSpeak et de Ventrilo est tout � fait simple.

D\'abord vous saisissez le IP address suivi de ":" (sans guillemet), suivis du port.

La troisi�me valeur est facultative mais parfois requis pour s\'assurer les donn�es est recherch� correctement ou recherch� du tout pour cette mati�re. Si la valeur est exig�e vous si le premier type  ":" (sans guillemet).

En cas de connexion Teamspeak nous avons besoin du queryport de TCP. Le d�faut est 51234 ainsi cette valeur est seulement exig�e quand elle est diff�rente de la valeur par d�faut.
Pour Ventrilo, si le protocole de mode est mot de passe prot�g� vous le besoin de le compl�ter ici. Maintenez dans l\'esprit que ce n\'est pas n�cessairement le m�me mot de passe employ� pour joindre le serveur. L\'information �crite dans ce domaine n\'est pas stock�e n\'importe o� dans la version non modifi�e initiale de SLG Comms (seulement applicable pour les capacit�s faites sur commande de serveur sur le frontpage). La personne qui accueille cette s�quence type pourrait avoir chang� le comportement en question cependant.

Tellement fondamental nous obtenons ce format pour Ventrilo:
Avec le mot de passe : "192.168.120.250:3784:1g2a34d5" 
Sans mot de passe : "192.168.120.250:3784"

Et ce format pour TeamSpeak : 
Avec le queryport :"192.168.120.250:6464:41234" 
Sans queryport : "192.168.120.250:6464"

Pour int�gr� le TSViewer.com, vous de juste compl�ter le nombre d\'identification de votre serveur. Si n�cessaire vous devrez enregistrer votre serveur de TeamSpeak sur www.TSViewer.com.',
);
?>

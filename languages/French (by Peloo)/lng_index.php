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
'{TEXT_HELPTEXT}'                  => 'Le format pour les serveurs TeamSpeak et de Ventrilo est tout à fait simple.

D\'abord vous saisissez le IP address suivi de ":" (sans guillemet), suivis du port.

La troisième valeur est facultative mais parfois requis pour s\'assurer les données est recherché correctement ou recherché du tout pour cette matière. Si la valeur est exigée vous si le premier type  ":" (sans guillemet).

En cas de connexion Teamspeak nous avons besoin du queryport de TCP. Le défaut est 51234 ainsi cette valeur est seulement exigée quand elle est différente de la valeur par défaut.
Pour Ventrilo, si le protocole de mode est mot de passe protégé vous le besoin de le compléter ici. Maintenez dans l\'esprit que ce n\'est pas nécessairement le même mot de passe employé pour joindre le serveur. L\'information écrite dans ce domaine n\'est pas stockée n\'importe où dans la version non modifiée initiale de SLG Comms (seulement applicable pour les capacités faites sur commande de serveur sur le frontpage). La personne qui accueille cette séquence type pourrait avoir changé le comportement en question cependant.

Tellement fondamental nous obtenons ce format pour Ventrilo:
Avec le mot de passe : "192.168.120.250:3784:1g2a34d5" 
Sans mot de passe : "192.168.120.250:3784"

Et ce format pour TeamSpeak : 
Avec le queryport :"192.168.120.250:6464:41234" 
Sans queryport : "192.168.120.250:6464"

Pour intégré le TSViewer.com, vous de juste compléter le nombre d\'identification de votre serveur. Si nécessaire vous devrez enregistrer votre serveur de TeamSpeak sur www.TSViewer.com.',
);
?>

<?php
/***************************************************************************
 *                             lng_index_ts.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_ts.php,v 1.2 2008/08/15 17:14:25 SC Kruiper Exp $
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
'{TEXT_ADMINS_CON}'              => 'Admins Server en ligne',
'{TEXT_CLANSERVER}'              => 'Clanserver?',
'{TEXT_CODEC}'                   => 'Codec',
'{TEXT_DATARECEIVED}'            => 'Donn�e recu',
'{TEXT_DATASENT}'                => 'Donn�e envoy�',
'{TEXT_IDLEFOR}'                 => 'Idle for',
'{TEXT_PACKETLOSS}'              => 'Paquet loss',
'{TEXT_PROVIDER}'                => 'Fournisseur',
'{TEXT_PROVIDER_EMAIL}'          => 'Addresse-mail fournisseur',
'{TEXT_PROVIDER_WEBSITE}'        => 'Website du fournisseur',
'{TEXT_STICKY}'                  => 'Coller',
'{TEXT_TOPIC}'                   => 'Sujet',
'{TEXT_UNKNOWN_CODEC}'           => 'Codec inconnu',
'{TEXT_WELCOME}'                 => 'Message de bienvenue',

'{TEXT_BYTES}'                   => 'Bytes',
'{TEXT_KB}'                      => 'KiB',
'{TEXT_MB}'                      => 'MiB',
'{TEXT_GB}'                      => 'GiB',
'{TEXT_TB}'                      => 'TiB',

'{TEXT_TS_RAWDATA_FLAW}'         => 'Donn�es alt�r�es du serveur TeamSpeak d�tect�es.
En raison d\'une imperfection dans la disposition des donn�es du serveur de TeamSpeak, l\'utilisation d\'une combinaison des tabulation et double-cite le rendent litt�ralement impossible de le traiter correctement. SLG Comms a essay� la difficult� les donn�es en question au meilleur de ses capacit�s. Si vous voyez des canaux et/ou des clients avec l\'information incorrecte ce sera probablement en raison de cette imperfection.',
);

$this->tooltips += array(
'{TEXT_EXPLAIN_TSFLAG_U}'        => 'Pas engistrer',
'{TEXT_EXPLAIN_TSFLAG_R}'        => 'Engistrer',
'{TEXT_EXPLAIN_TSFLAG_M}'        => 'Moderateur',
'{TEXT_EXPLAIN_TSFLAG_P}'        => 'Prot�g� par mot de passe',
'{TEXT_EXPLAIN_TSFLAG_S}'        => 'Sous-canaux permis',
'{TEXT_EXPLAIN_TSFLAG_D}'        => 'Canal par d�faut',
'{TEXT_EXPLAIN_TSFLAG_SA}'       => 'Administrateur de Serveur',
'{TEXT_EXPLAIN_TSFLAG_CA}'       => 'Administrateur de Canal',
'{TEXT_EXPLAIN_TSFLAG_AO}'       => 'Auto-Op�rateur',
'{TEXT_EXPLAIN_TSFLAG_AV}'       => 'Auto-Voix',
'{TEXT_EXPLAIN_TSFLAG_O}'        => 'Op�rateur',
'{TEXT_EXPLAIN_TSFLAG_V}'        => 'Voix',
'{TEXT_EXPLAIN_TSFLAGS_CHANNEL}' => 'Configurations du canal',
'{TEXT_EXPLAIN_TSFLAGS_CLIENT}'  => 'Client Droits',
'{TEXT_EXPLAIN_TSSTATUS_CLIENT}' => 'Client status',
'{TEXT_PL_STATUS_8}'             => 'Absent',
'{TEXT_PL_STATUS_1}'             => 'Commandant du canal',
'{TEXT_PL_STATUS_4}'             => 'N\'autorise pas les chuchotements',
'{TEXT_PL_STATUS_16}'            => 'Microphone coup�',
'{TEXT_PL_STATUS_64}'            => 'Enregistrement',
'{TEXT_PL_STATUS_32}'            => 'Son coup�',
'{TEXT_PL_STATUS_2}'             => 'Demandes la parole',
'{TEXT_PL_RIGHT_2}'              => 'Autorise l\'enregistrement',
);
?>

<?php
/***************************************************************************
 *                               lng_index.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index.php,v 1.17 2008/08/10 20:34:05 SC Kruiper Exp $
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
'{TEXT_CUSTOM_SERVER}'             => 'Custom server',
'{TEXT_CUSTOM_SERVER_TYPE}'        => 'Server type',
'{TEXT_CUSTOM_SERVER_VENT_SORT}'   => 'Ventrilo channel sorting',
'{TEXT_IP_PORT}'                   => 'IP:PORT',
'{TEXT_SERVER}'                    => 'Server',
'{TEXT_SUBMIT_SERVERFORM}'         => 'Submit',

'{TEXT_ALPHABETICALLY}'            => 'Alphabetically',
'{TEXT_MANUALLY}'                  => 'No sorting',
);

$this->tooltips += array(
'{TEXT_HELPTEXT}'                  => 'The format for the TeamSpeak and Ventrilo servers is quite simple.

First you type in the ip address followed by ":" (without the quotes), followed by the port number.

The third value is optional but sometimes required to make sure the data is retrieved correctly or retrieved at all for that matter. If the value is required you should ofcourse first type ":" (without the quotes).

In case of an Teamspeak connection we need the TCP queryport. The default is 51234 so this value is only required when it\'s different from the default value.

For Ventrilo, if the status protocol is password protected you need to fill it in here. Keep in mind that this is not necessarily the same password used to join the server. The information entered in this field is not stored anywhere in the original unmodified version of SLG Comms (only applicable for the custom server ability on the frontpage). The person who hosts this script might have changed the behaviour in question though.

So basically we get this format for Ventrilo:
With password: "192.168.120.250:3784:1g2a34d5"
Without password: "192.168.120.250:3784"

And this format for TeamSpeak:
With queryport: "192.168.120.250:6464:41234"
Without queryport: "192.168.120.250:6464"

Incase of TSViewer.com, just fill in the Registration ID of your server. Your TeamSpeak server needs to be in the TSViewer.com database, so it\'s possible you still need to register your TeamSpeak server on www.TSViewer.com. TSViewer.com does not support the "Custom server" feature',
);
?>

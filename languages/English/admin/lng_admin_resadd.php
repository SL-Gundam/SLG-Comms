<?php
/***************************************************************************
 *                            lng_admin_resadd.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_admin_resadd.php,v 1.4 2005/10/03 10:55:56 SC Kruiper Exp $
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
if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}

$this->text += array(
'{TEXT_RESOURCE_ADD_SUCCESS}' => 'Resource successfully added.',
'{TEXT_MISSING_FORMDATA}' => 'Not all of the necassary fields were filled in.',
'{TEXT_RESOURCE_UPDATE_SUCCESS}' => 'Resource successfully updated.',
'{TEXT_RESOURCE_ADD}' => 'Add resource',
'{TEXT_RESOURCE_UPDATE}' => 'Update resource',
'{TEXT_RESOURCE_EDIT}' => 'Modify resource',
'{TEXT_RESOURCE_NAME}' => 'Resource name',
'{TEXT_RESOURCE_DATA}' => 'Resource data',
'{TEXT_HELP}' => 'HELP',
'{TEXT_RESOURCE_TYPE}' => 'Resource type'
);

$this->tooltips += array(
'{TEXT_HELPTEXT}' => 'The format for the TeamSpeak and Ventrilo servers is quite simple.

First you type in the ip number followed by ":" (without the quotes), followed by the port number.

The third value is optional but sometimes required to make sure the data is retrieved correctly or retrieved at all for that matter. If the value is required you should ofcourse first type ":" (without the quotes).

In case of an Teamspeak connection we need the TCP queryport. The default is 51234 so this value is only required when it\'s different from the default value.

For Ventrilo, if the status protocol is password protected you need to fill it in here. Keep in mind that this is not neccasarily the same password used to join the server.

So basically we get this format for Ventrilo:
With password: "192.168.120.250:3784:1g2a34d5"
Without password: "192.168.120.250:3784"

And this format for TeamSpeak:
With queryport: "192.168.120.250:6464:41234"
Without queryport: "192.168.120.250:6464"'
);
?>

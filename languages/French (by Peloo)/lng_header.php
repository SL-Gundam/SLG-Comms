<?php
/***************************************************************************
 *                              lng_header.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_header.php,v 1.2 2007/11/17 00:18:18 SC Kruiper Exp $
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
'{TEXT_ADMIN}'                => 'Administration',
'{TEXT_INDEX}'                => 'Frontpage',
'{TEXT_INSTALLATION}'         => 'Installation',
'{TEXT_UNKNOWN_TITLE}'        => 'SLG Comms - Pad de titre de page present',
);

$this->tooltips += array(
'{TEXT_SHOW_HELPTEXT_TIMER}'  => 'Cliquez pour permettre ou invalider la page automatique régénérez le temporisateur.',
);
?>

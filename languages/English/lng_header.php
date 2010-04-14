<?php
/***************************************************************************
 *                              lng_header.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_header.php,v 1.13 2006/06/11 20:32:45 SC Kruiper Exp $
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
'{CHARSET}'                   => 'iso-8859-1',
'{TEXT_ADMIN}'                => 'Administration',
'{TEXT_INDEX}'                => 'Frontpage',
'{TEXT_INSTALLATION}'         => 'Installation',
'{TEXT_UNKNOWN_TITLE}'        => 'SLG Comms - No page title present',
);

$this->tooltips += array(
'{TEXT_SHOW_HELPTEXT_TIMER}'  => 'Click to enable or disable the automatic page refresh timer.',
);
?>

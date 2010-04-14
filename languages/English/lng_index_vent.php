<?php
/***************************************************************************
 *                             lng_index_vent.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_vent.php,v 1.7 2007/11/17 00:18:18 SC Kruiper Exp $
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
'{TEXT_ADMIN}'                  => 'Administrator?',
'{TEXT_ADMINS_CON}'             => 'Administrators online',
'{TEXT_CHANNELDATA_DISABLED}'   => 'Channel information disabled by Ventrilo server administrator.',
'{TEXT_CLIENTDATA_DISABLED}'    => 'Client information disabled by Ventrilo server administrator.',
'{TEXT_COMMENT}'                => 'Comment',
'{TEXT_PHANTOM}'                => 'Phantom?',
'{TEXT_SERVER_PHONETIC}'        => 'Server phonetic',
'{TEXT_UNKNOWN}'                => 'Unknown',
'{TEXT_VOICECODEC}'             => 'Voice codec',
'{TEXT_VOICEFORMAT}'            => 'Voice codec format',
'{TEXT_USERAUTH}'               => 'User Authentication',
);
?>

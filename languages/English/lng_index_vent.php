<?php
/***************************************************************************
 *                             lng_index_vent.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: lng_index_vent.php,v 1.1 2005/10/21 14:29:28 SC Kruiper Exp $
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
'{TEXT_VOICECODEC}' => 'Voice codec',
'{TEXT_VOICEFORMAT}' => 'Voice codec format',
'{TEXT_SERVER_PHONETIC}' => 'Server phonetic',
'{TEXT_COMMENT}' => 'Comment',
'{TEXT_ADMINS_CON}' => 'Administrators present',
);

$this->tooltips += array(
'{TEXT_ADMIN}' => 'Administrator?',
'{TEXT_PHANTOM}' => 'Phantom?',
);
?>

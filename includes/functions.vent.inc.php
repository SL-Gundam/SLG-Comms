<?php
/***************************************************************************
 *                           functions.vent.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.vent.inc.php,v 1.2 2005/06/22 20:24:43 SC Kruiper Exp $
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

if (!defined("IN_SLG")){ 
	die("Hacking attempt.");
}

function SORT_VENTCHANNELS($a, $b){
	if (strcasecmp($a['NAME'], $b['NAME']) == 0){
		return(0);
	}
	else{
		return((strcasecmp($a['NAME'], $b['NAME']) < 0) ? -1 : 1);
	}
}

function savecache(&$cacheddata){
	global $table, $ts;

	$rtime = explode(" ",microtime());
	$sql = 'UPDATE '.$table['cache'].'
SET
  data = "'.addslashes(implode('/%/', $cacheddata)).'",
  timestamp = "'.$rtime[1].'"
WHERE
  cache_id = "'.$ts['id'].'"
LIMIT 1';

	return($sql);
}
?>

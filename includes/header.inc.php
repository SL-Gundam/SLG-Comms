<?php
/***************************************************************************
 *                              header.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: header.inc.php,v 1.14 2005/10/03 10:55:55 SC Kruiper Exp $
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

if (!empty($_POST)){
	processincomingdata($_POST, true);
}

$header = new template;
$template = 'header';

$header->insert_content('{PAGE_TITLE}', ((isset($tssettings['Page title'])) ? $tssettings['Page title'] : '{TEXT_UNKNOWN_TITLE}'));

if (!defined("NO_DATABASE")){
	$header_link = '<a href="'.((checkfilelock('index.php')) ? 'admin.php">{TEXT_ADMIN}' : 'index.php">{TEXT_INDEX}' ).'</a>';
}
else{
	$header_link = NULL;
}
$header->insert_content('{LINK_ADMIN_INDEX}', $header_link);

$curfile = checkfilelock('index.php') && (!isset($_REQUEST['ipbyname']) || $_REQUEST['ipbyname'] != 0) && $tssettings['Page refresh timer'] != 0;
$header->insert_display('{REFRESHSCRIPT}', $curfile);
if ($curfile){
	$header->insert_text('{REFRESH_URI}', 'index.php'.((isset($_REQUEST['ipbyname'])) ? '?ipbyname='.$_REQUEST['ipbyname'] : ''));
	$header->insert_text('{REFRESH_SECS}', $tssettings['Page refresh timer']);
}

$header->load_language('lng_header');
$header->load_template('tpl_header');
$header->process();
$header->output();
unset($header);
?>

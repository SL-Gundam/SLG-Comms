<?php
/***************************************************************************
 *                                admin.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: admin.php,v 1.22 2005/10/03 10:55:53 SC Kruiper Exp $
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

define("IN_SLG", 10);
include('includes/config.inc.php');

$admin = new template;
$template = 'admin';

include('includes/secure.inc.php');

$page_title = $tssettings['Page title']; // backup of name before modification by header.inc.php.
$tssettings['Page title'] .= ' - {TEXT_ADMIN}';
include_once('includes/header.inc.php');
$template = 'admin'; //the include_once for header.inc.php resetted this variable to another value so we set it back to the correct one for further use in this script. this can not be any other order because secure.inc.php requires to be within a template but before any output to the client. So it should be before header.inc.php but after the start of the template of the main working file.
$tssettings['Page title'] = $page_title; // settings.inc.php checks this value for changes so we need to set it back to the value in the database. This is only necassary in the admin pages. more precise it's only needed for the settings page within the admin pages.

// disable thid page if NO_DATABASE mode is activated
if (defined("NO_DATABASE")){
	early_error('{TEXT_NO_DATABASE_MODE_ACT}');
}

// lets see if we have logged in
if (isset($_SESSION['username'])){
	// lets create navigation menu
	$menuarray = array(
		'baseurl' => 'admin.php?',
		'basevar' => 'page',
		'menuitems' => array(
			array(
				'name' => '{TEXT_RESOURCES}', 
				'url' => 'resources',
				'seclevel' => $tssettings['Forum group'],
				'subitems' => array(
					array(
						'name' => '{TEXT_ADD_RESOURCES}', 
						'url' => 'resadd',
						'seclevel' => $tssettings['Forum group'],
						'subitems' => NULL
					),
					array(
						'name' => '{TEXT_MANAGE_RESOURCES}', 
						'url' => 'resman',
						'seclevel' => $tssettings['Forum group'],
						'subitems' => NULL
					),
					array(
						'name' => '{TEXT_CACHE_RESOURCES}', 
						'url' => 'rescache',
						'seclevel' => $tssettings['Forum group'],
						'subitems' => NULL
					)
				)
			),
			array(
				'name' => '{TEXT_SETTINGS}', 
				'url' => 'settings',
				'seclevel' => $tssettings['Forum group'],
				'subitems' => NULL
			),
			array(
				'name' => '{TEXT_LOGOUT} (<i>'.$_SESSION['realname'].'</i>)', 
				'url' => 'logout',
				'seclevel' => NULL,
				'subitems' => NULL
			)
		)
	);

	if (isset($_GET['page']) && checkaccess($tssettings['Forum group'])){
		// which files should we load?
		switch ($_GET['page']){
			case 'resources':
//--------------------------
				if (isset($_GET['resources'])){
					if ($_GET['resources'] == 'resadd' || ($_GET['resources'] == 'resman' && isset($_GET['edit']))) {
						include('includes/admin/resadd.inc.php');
						$admin->load_template('admin/tpl_admin_resadd');
						$admin->load_language('admin/lng_admin_resadd');
					}
					elseif ($_GET['resources'] == 'resman') {
						include('includes/admin/resman.inc.php');
						$admin->load_template('admin/tpl_admin_resman');
						$admin->load_language('admin/lng_admin_resman');
					}
					elseif ($_GET['resources'] == 'rescache'){
						include('includes/admin/rescache.inc.php');
						$admin->load_template('admin/tpl_admin_rescache');
						$admin->load_language('admin/lng_admin_rescache');
					}
				}
				else{
					$load_def_tpl = true;
				}
//--------------------------
				break;
			case 'settings':
				include('includes/admin/settings.inc.php');
				$admin->load_template('admin/tpl_admin_settings');
				$admin->load_language('admin/lng_admin_settings');
				$admin->load_language('admin/lng_common');
				break;
			default: $load_def_tpl = true;
		}
	}
	else{
		$load_def_tpl = true;
	}
	if (isset($load_def_tpl) && $load_def_tpl === true){
		$admin->load_template('admin/tpl_admin');
	}
	// parse and insert menu into template
	$admin->insert_menu('{MENU}', $menuarray);
}
else{
	$admin->load_template('admin/tpl_login');
}

// process template
$admin->load_language('admin/lng_admin');
$admin->process();
$admin->output();
unset($admin);

include('includes/footer.inc.php');
?>

<?php
/***************************************************************************
 *                                admin.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: admin.php,v 1.42 2007/01/30 16:16:46 SC Kruiper Exp $
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

define( "IN_SLG", 10 );
$tssettings['Root_path'] = './';

require( $tssettings['Root_path'] . 'includes/config.inc.php' );

$admin = new template;
$template = 'admin';

require( $tssettings['Root_path'] . 'includes/secure.inc.php' );

$tssettings['Page_title'] .= ' - {TEXT_ADMIN}';
require_once( $tssettings['Root_path'] . 'includes/header.inc.php' );
$template = 'admin'; //the require_once for header.inc.php resetted this variable to another value so we set it back to the correct one for further use in this script. this can not be any other order because secure.inc.php requires to be within a template but before any output to the client. So it should be before header.inc.php but after the start of the template of the main working file.

// disable this page if NO_DATABASE mode is activated
if ( defined("NO_DATABASE") )
{
	early_error( '{TEXT_NO_DATABASE_MODE_ACT}' );
}

// lets see if we have logged in
if ( isset($_SESSION['username']) )
{
	if ( isset($_GET['page']) && checkaccess($tssettings['Forum_group']) )
	{
		// which files should we load?
		switch ( $_GET['page'] )
		{
			case 'resources':
//--------------------------
				if ( isset($_GET['resources']) )
				{
					if ( $_GET['resources'] === 'resadd' || ( $_GET['resources'] === 'resedit' && isset($_GET['edit']) ) )
					{
						require( $tssettings['Root_path'] . 'includes/admin/resadd.inc.php' );
						$admin->load_template( 'admin/tpl_admin_resadd' );
						$admin->load_language( 'admin/lng_admin_resadd' );
						$admin->load_language( 'lng_index' );
					}
					elseif ( $_GET['resources'] === 'resman' )
					{
						require( $tssettings['Root_path'] . 'includes/admin/resman.inc.php' );
						$admin->load_template( 'admin/tpl_admin_resman' );
						$admin->load_language( 'admin/lng_admin_resman' );
					}
					elseif ( $_GET['resources'] === 'rescache' )
					{
						require( $tssettings['Root_path'] . 'includes/admin/rescache.inc.php' );
						$admin->load_template( 'admin/tpl_admin_rescache' );
						$admin->load_language( 'admin/lng_admin_rescache' );
						$admin->load_language( 'lng_index' );
					}
				}
				else
				{
					$load_def_tpl = true;
				}
//--------------------------
				break;

			case 'settings':
				require( $tssettings['Root_path'] . 'includes/admin/settings.inc.php' );
				$admin->load_template( 'admin/tpl_admin_settings' );
				$admin->load_language( 'admin/lng_admin_settings' );
				$admin->load_language( 'admin/lng_common' );
				break;

			default: $load_def_tpl = true;
		}
	}
	else
	{
		$load_def_tpl = true;
	}
	if ( isset($load_def_tpl) && $load_def_tpl === true )
	{
		$admin->load_template( 'admin/tpl_admin' );
	}
	unset( $load_def_tpl );

	//create the navigation menu
	$admin->insert_menubase( '{MENU}', 'admin.php?', 'page' );

	$admin->insert_menuitem( '{MENU}', 'page', '{TEXT_RESOURCES}', 'resources', $tssettings['Forum_group'] );
	$admin->insert_menuitem( '{MENU}', 'resources', '{TEXT_ADD_RESOURCES}', 'resadd', $tssettings['Forum_group'] );
	$admin->insert_menuitem( '{MENU}', 'resources', '{TEXT_MANAGE_RESOURCES}', 'resman', $tssettings['Forum_group'] );
	$admin->insert_menuitem( '{MENU}', 'resources', '{TEXT_CACHE_RESOURCES}', 'rescache', $tssettings['Forum_group'] );

	$admin->insert_menuitem( '{MENU}', 'page', '{TEXT_SETTINGS}', 'settings', $tssettings['Forum_group'] );

	$admin->insert_menuitem( '{MENU}', 'page', '{TEXT_LOGOUT} (<i>' . $_SESSION['realname'] . '</i>)', 'logout' );
}
else
{
	$admin->load_template( 'admin/tpl_login' );
}

// process template
$admin->insert_text( '{BASE_URL}', ( !empty( $tssettings['Base_url'] ) ? 'http://' . $tssettings['Base_url'] : NULL ) );
$admin->insert_text( '{TEMPLATE}', ( !empty( $tssettings['Template'] ) ? $tssettings['Template'] : 'Default' ) );
$admin->load_language( 'admin/lng_admin' );
$admin->process();
$admin->output();
unset( $admin, $template );

require_once( $tssettings['Root_path'] . 'includes/footer.inc.php' );
?>

<?php
/***************************************************************************
 *                             settings.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: settings.inc.php,v 1.35 2006/06/11 20:32:43 SC Kruiper Exp $
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
if ( !defined("IN_SLG") || !checkaccess($tssettings['Forum_group']) )
{ 
	die( "Hacking attempt." );
}

// this file manages the settings pages

$sql = '
SELECT 
  `variable`,
  `value`
FROM
  `%1$s`
WHERE `variable` != "SLG_version"';
$getconfig = $db->execquery( 'getconfig', $sql, $table['settings'] );

$settings = array();
while ( $row = $db->getrow($getconfig) )
{
	$settings[] = $row;
}
$db->freeresult( 'getconfig', $getconfig );
unset( $row, $getconfig );

if ( isset($_POST['updsetting']) )
{
	if ( isset($_POST['variable']['Forum_type'], $_POST['variable']['Forum_relative_path']) && !test_new_forum($_POST['variable']['Forum_type'], prep_dir_string(ltrim($_POST['variable']['Forum_relative_path'], '/'))) )
	{
		$admin->displaymessage( '{TEXT_NEW_FORUM_INVALID}' );
		unset( $_POST['variable']['Forum_type'], $_POST['variable']['Forum_relative_path'] );
	}

	for ( $i=0, $max=count($settings); $i < $max; $i++ )
	{
		$var_name = $settings[ $i ]['variable'];
		if ( isset($_POST['variable'][ $var_name ]) )
		{
			$param = check_setting( $var_name, $_POST['variable'][ $var_name ] );

			if ( $settings[ $i ]['value'] != $param['value'] )
			{
				$sql = '
UPDATE `%1$s` SET
  `value` = "%2$' . $param['sprinter'] . '"
WHERE
  `variable` = "%3$s"';

				$db->execquery( 'updatesetting', $sql, array(
					$table['settings'],
					$db->escape_string( $param['value'] ),
					$db->escape_string( $var_name )
				) );

				$admin->displaymessage( '{TEXT_SETTINGUPDATE_SUCCESS;{TEXT_' . strtoupper( $var_name ) . '};}' );
				$settings[ $i ]['value'] = $param['value'];
			}
		}
	}
	unset( $i, $max, $var_name, $param );
}
unset( $sql );

$admin->insert_content( '{SETTINGS}', create_settingslist( $settings ) );
unset( $settings );
?>

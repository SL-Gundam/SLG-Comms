<?php
/***************************************************************************
 *                             functions.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.inc.php,v 1.19 2005/06/21 19:15:28 SC Kruiper Exp $
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

function processincomingdata(&$data, $strip=false){
	if (!function_exists('processaddslashes')){
		function processaddslashes(&$value, $key=NULL){
			if(is_array($value)){
				array_walk($value, 'processaddslashes');
			}
			else{
				$value = addslashes($value);
			}
		}
	}

	if (!function_exists('processstripslashes')){
		function processstripslashes(&$value, $key=NULL){
			if(is_array($value)){
				array_walk($value, 'processstripslashes');
			}
			else{
				$value = stripslashes($value);
			}
		}
	}

	$magicgpc = get_magic_quotes_gpc();
	if (!$magicgpc && !$strip){
		processaddslashes($data);
	}
	elseif ($magicgpc && $strip){
		processstripslashes($data);
	}
}

function SORT_SERVERS($a, $b){
	if (strcasecmp($a['res_name'], $b['res_name']) == 0){
		return(0);
	}
	else{
		return((strcasecmp($a['res_name'], $b['res_name']) < 0) ? -1 : 1);
	}
}

function removechars($str, $char){
	return(str_replace($char, "", $str));
}

function convert_jspoptext($str){
	return(str_replace("\n", '\r', addslashes($str)));
}

function early_error($message, $sql=NULL, $sqlerror=NULL){
	$error = new template;
	$error->load_language('lng_earlyerrors');

	$error->displaymessage($message, true, $sql, $sqlerror);

//	$error->process();
//	$error->output();
}

function formatbytes($bytes){
	$units = array(' {TEXT_BYTES}', ' {TEXT_KB}', ' {TEXT_MB}', ' {TEXT_GB}', ' {TEXT_TB}');
	for ($i = 0; $bytes > 1024; $i++){
		$bytes /= 1024;
	}
	return(round($bytes, 2).$units[$i]);
}

function formattime($seconds){
	$divider_units = array(86400, 3600, 60, 1);
	$divider_var = array('DAYS', 'HOURS', 'MINUTES', 'SECONDS');
	for ($i = 0, $k = (count($divider_units) - 1); $i <= $k; $i++){
		$time[$divider_var[$i]] = floor($seconds / $divider_units[$i]);
		$seconds -= $time[$divider_var[$i]] * $divider_units[$i];

		switch ($time[$divider_var[$i]]){
			case '1': $time[$divider_var[$i]] .= ' {TEXT_'.rtrim($divider_var[$i], 'S').'}';
				break;
			case '0': $time[$divider_var[$i]] = NULL;
				break;
			default: $time[$divider_var[$i]] .= ' {TEXT_'.$divider_var[$i].'}';
		}

		$count[$divider_var[$i]] = (isset($time[$divider_var[$i]])) ? 1 : 0;
	}

	$tcount = array_sum($count);
	if ($tcount > 1){
		if ($count['SECONDS'] == 1){
			$time['SECONDS'] = '{TEXT_AND} '.$time['SECONDS'];
		}
		elseif ($count['MINUTES'] == 1){
			$time['MINUTES'] = '{TEXT_AND} '.$time['MINUTES'];
		}
		elseif ($count['HOURS'] == 1){
			$time['HOURS'] = '{TEXT_AND} '.$time['HOURS'];
		}
	}
	elseif ($tcount == 0){
		$time['SECONDS'] = '0 {TEXT_SECONDS}';
	}

	return(trim(implode(' ', $time)));
}

function linewrap($str, $maxlength){
	if (strlen($str) > $maxlength){
		$str = substr($str, 0, ($maxlength-2)).'...';
	}
	return($str);
}

function print_check_cache_lifetime(){
	global $usecached, $cache, $tuntilrefresh;

	if ($usecached){
		$cachelive = '{TEXT_CACHEDDATA}<br />';
		if ($tuntilrefresh >= $cache['refreshcache']){
			$cachelive .= '{TEXT_CACHEOLD;'.formattime($tuntilrefresh).';}';
		}
		else{
				$cachelive .= '{TEXT_DATAREFRESHIN;'.formattime($tuntilrefresh).';}';
		}
	}
	else{
		$cachelive = '{TEXT_LIVEDATA}<br />';
		if (isset($_POST['ipbyname']) && $_POST['ipbyname'] == 0){
			$cachelive .= '{TEXT_NOCUSTOMCACHE}';
		}
		elseif ($cache['refreshcache'] != 0){
			$cachelive .= '{TEXT_DATAREFRESHED;'.formattime($cache['refreshcache']).';}';
		}
		else{
			$cachelive .= '{TEXT_CACHEDISABLED}';
		}
	}
	return($cachelive);
}

function echobig($string, $bufferSize = 4096){
	for ($i = 0, $chars = strlen($string), $current = 0; $current < $chars; $current += $bufferSize) {
		echo substr($string, $current, $bufferSize);
		$i++;
	}
	if (defined("DEBUG")){
		// Because this is the function that actually outputs the template, it can not be integrated into one. This means no multi language support. Not really neccasary anyway since the echo below is only executed in DEBUG mode which should never be used in public sites.
		echo '<table border="0" align="center"><tr><td><p class="error">DEBUG: echobig required '.$i.' loop(s) to output the data.</p></td></tr></table><p></p>';
	}
}

function checkfilelock($files){
	$path_parts1 = pathinfo($_SERVER['PHP_SELF']);
	$files = explode(',', $files);
	return(in_array($path_parts1['basename'], $files));
}
?>

<?php
/***************************************************************************
 *                             functions.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.inc.php,v 1.26 2005/11/18 13:38:29 SC Kruiper Exp $
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

function processaddslashes(&$value, $key=NULL){
	if(is_array($value)){
		array_walk($value, 'processaddslashes');
	}
	else{
		$value = addslashes($value);
	}
}

function processstripslashes(&$value, $key=NULL){
	if(is_array($value)){
		array_walk($value, 'processstripslashes');
	}
	else{
		$value = stripslashes($value);
	}
}

function processincomingdata(&$data, $strip=false){
	$magicgpc = get_magic_quotes_gpc();
	if (!$magicgpc && !$strip){
		processaddslashes($data);
	}
	elseif ($magicgpc && $strip){
		processstripslashes($data);
	}
}

function SORT_SERVERS($a, $b){
	$comp_result = strcasecmp($a['res_name'], $b['res_name']);
	if ($comp_result == 0){
		return(0);
	}
	else{
		return(($comp_result < 0) ? -1 : 1);
	}
}

function removechars($str, $char){
	return(str_replace($char, "", $str));
}

function early_error($message, $sql=NULL, $sqlerror=NULL){
	$error = new template;
	$error->load_language('lng_earlyerrors');

	$error->displaymessage($message, true, $sql, $sqlerror);

//	$error->process();
//	$error->output();
}

function formattime($seconds){
	static $divider_units = array(86400, 3600, 60, 1);
	static $divider_var = array('DAYS', 'HOURS', 'MINUTES', 'SECONDS');
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
		if ($count['SECONDS'] === 1){
			$time['SECONDS'] = '{TEXT_AND} '.$time['SECONDS'];
		}
		elseif ($count['MINUTES'] === 1){
			$time['MINUTES'] = '{TEXT_AND} '.$time['MINUTES'];
		}
		elseif ($count['HOURS'] === 1){
			$time['HOURS'] = '{TEXT_AND} '.$time['HOURS'];
		}
	}
	elseif ($tcount === 0){
		$time['SECONDS'] = '0 {TEXT_SECONDS}';
	}

	return(trim(implode(' ', $time)));
}

function linewrap($str, $maxlength){
	if (strlen($str) > $maxlength){
		$str = trim(substr($str, 0, ($maxlength-2))).'...';
	}
	return($str);
}

function prep_tooltip($msg){
	return(str_replace("\n", '<br />', addslashes(str_replace('&', '&amp;', (htmlentities($msg))))));
}

function print_check_cache_lifetime($usecached, &$cache, $tuntilrefresh, $errors){
	if ($usecached){
		$cachelive = '{TEXT_CACHEDDATA}<br />';
		if ($errors){
			$cachelive .= '{TEXT_CACHEOLD}: '.formattime($tuntilrefresh);
		}
		else{
			$cachelive .= '{TEXT_DATAREFRESHIN}: '.formattime($tuntilrefresh);
		}
	}
	else{
		$cachelive = '{TEXT_LIVEDATA}<br />';
		if (isset($_POST['ipbyname']) && $_POST['ipbyname'] == 0){
			$cachelive .= '{TEXT_NOCUSTOMCACHE}';
		}
		elseif ($cache['refreshcache'] != 0){
			$cachelive .= '{TEXT_DATAREFRESHED}: '.formattime($cache['refreshcache']);
		}
		else{
			$cachelive .= '{TEXT_CACHEDISABLED}';
		}
	}
	return($cachelive);
}

function checkfilelock($files){
	$path_parts1 = pathinfo($_SERVER['PHP_SELF']);
	return(in_array($path_parts1['basename'], explode(',', $files)));
}

function savecache(&$cacheddata){
	global $table, $ts, $db;

	$rtime = explode(" ",microtime());
	$sql = 'UPDATE '.$table['cache'].'
SET
  data = "'.$db->escape_string(implode("\r\n", $cacheddata)).'",
  timestamp = "'.$rtime[1].'"
WHERE
  cache_id = "'.$ts['id'].'"
LIMIT 1';

	return($sql);
}

function check_ip_port($ip, $port, $queryport=NULL){
	$testip = ip2long($ip);
	if ($testip === -1 || $testip === FALSE){
		$ip = gethostbyname($ip);
		$testip = ip2long($ip);
	}
	$ipv4_parts = count(explode('.', $ip));
	return( ( $port > 0 && $port < 65535 && is_numeric($port) ) && ( $testip !== -1 && $testip !== FALSE && $ipv4_parts === 4 ) && ( is_null($queryport) || ( $queryport > 0 && $queryport < 65535 && is_numeric($queryport) ) ) );
}

if(!function_exists('file_get_contents')){
	function file_get_contents($filename){
		$file = file($filename);
		return(implode('', $file));
	}
}
?>

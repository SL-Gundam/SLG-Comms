<?php
/***************************************************************************
 *                           functions.ts.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.ts.inc.php,v 1.6 2005/10/24 14:08:13 SC Kruiper Exp $
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

//Player Flags
function prepare_sort_name($name){
	return(preg_replace('/[^a-z0-9]/i', '', $name));
}

function SORT_PLAYERS(&$a, &$b){
	if ( (($a['pprivs'] & 1) == 1) && (($b['pprivs'] & 1) == 1) ){
		$comp_result = strcasecmp($a['slg_sortname'], $b['slg_sortname']);
		if ($comp_result === 0){
			return(0);
		}
		else{
			return(($comp_result < 0) ? -1 : 1);
		}
	}
	elseif ( !(($a['pprivs'] & 1) == 1) && !(($b['pprivs'] & 1) == 1) ){
		if ( ( (($a['cprivs'] & 1) == 1) && (($b['cprivs'] & 1) == 1) ) || ( !(($a['cprivs'] & 1) == 1) && !(($b['cprivs'] & 1) == 1) ) ){
			$comp_result = strcasecmp($a['slg_sortname'], $b['slg_sortname']);
			if ($comp_result === 0){
				return(0);
			}
			else{
				return(($comp_result < 0) ? -1 : 1);
			}
		}
		else{
			return( (($a['cprivs'] & 1) == 1) ? -1 : 1);
		}
	}
	else{
		return( (($a['pprivs'] & 1) == 1) ? -1 : 1);
	}
}

function SORT_CHANNELS(&$a, &$b){
	if ($a['order'] === $b['order']){
		$comp_result = strcasecmp($a['slg_sortname'], $b['slg_sortname']);
		if ($comp_result === 0){
			return(0);
		}
		else{
			return(($comp_result < 0) ? -1 : 1);
		}
	}
	else{
		return(($a['order'] < $b['order']) ? -1 : 1);
	}
}

function pl_flags($pl_flags, $ch_flags)
{
	if(($pl_flags & 4) == 4){
		$output = 'R';
	}
	else{
		$output = 'U';
	}

	if(($pl_flags & 1) == 1){
		$output .= ' SA';
	}

	if(($ch_flags & 1) == 1){
		$output .= ' CA';
	}

	if(($ch_flags & 8) == 8){
		$output .= ' AO';
	}

	if(($ch_flags & 16) == 16){
		$output .= ' AV';
	}

	if(($ch_flags & 2) == 2){
		$output .= ' O';
	}

	if(($ch_flags & 4) == 4){
		$output .= ' V';
	}

	return($output);
}

function pl_img($pflags)
{
/*
IGNORED LIST - No special picture used by TeamSpeak
2 - Voice Request
4 - Doesnt Accept Whispers
64 - Recording
*/
	static $tmparr = array(8,32,16,1);
	if ($pflags == 0){
		return(0);
	}
	else{
		foreach ($tmparr as $flag){
			if(($pflags & $flag) == $flag){
				return($flag);
			}
		}
	}

	return(0); // incase no return was executed above
}

function pl_status($pflags)
{
	static $tmparr = array(8,1,4,16,64,32,2);
	if ($pflags == 0){
		$output = '{TEXT_PL_STATUS_0}';
	}
	else{
		$output = NULL;
		foreach ($tmparr as $flag){
			if(($pflags & $flag) == $flag){
				if (!empty($output)){
					$output .= '<br />';
				}
				$output .= '{TEXT_PL_STATUS_'.$flag.'}';
			}
		}
	}

	return($output);
}
 
//Channel Flags
function ch_flags($chflags)
{
	if(($chflags & 1) == 1){
		$output = 'U';
	}
	else{
		$output = 'R';
	}

	if(($chflags & 2) == 2){
		$output .= 'M';
	}
	if(($chflags & 4) == 4){
		$output .= 'P';
	}
	if(($chflags & 8) == 8){
		$output .= 'S';
	}
	if(($chflags & 16) == 16){
		$output .= 'D';
	}

	return($output);
}

function formatbytes($bytes){
	static $units = array(' {TEXT_BYTES}', ' {TEXT_KB}', ' {TEXT_MB}', ' {TEXT_GB}', ' {TEXT_TB}');
	for ($i = 0; $bytes > 1024; $i++){
		$bytes /= 1024;
	}
	return(round($bytes, 2).$units[$i]);
}

function formatcodec($codec){
	switch ($codec){
		case 0: return('CELP 5.2 Kbit');
		case 1: return('CELP 6.3 Kbit');
		case 2: return('GSM 14.8 Kbit');
		case 3: return('GSM 16.4 Kbit');
		case 4: return('Windows CELP 5.2 Kbit');
		case 5: return('Speex 3.4 Kbit');
		case 6: return('Speex 5.2 Kbit');
		case 7: return('Speex 7.2 Kbit');
		case 8: return('Speex 9.3 Kbit');
		case 9: return('Speex 12.3 Kbit');
		case 10: return('Speex 16.3 Kbit');
		case 11: return('Speex 19.5 Kbit');
		case 12: return('Speex 25.9 Kbit');
		default: return('{TEXT_UNKNOWN_CODEC}');
	}
}

function prepare_email_addr($address, $text=NULL, $hyperlink=true){
	if (preg_match("#([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", $address)){
		$search = array('@', '.');
		if (empty($text)){
			$replace2 = array(' [at] ', ' [dot] ');
			$text = str_replace($search, $replace2, $address);
		}
		if ($hyperlink){
			$replace1 = array('&#64;', '&#46;');
			$address = str_replace($search, $replace1, $address);
		}
		return( (($hyperlink) ? '<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#58;'.$address.'">' : NULL ) . $text . (($hyperlink) ? '</a>' : NULL ) );
	}
	return(NULL);
}

function prepare_http_link($url, $text=NULL, $hyperlink=true){
	if (preg_match("#([\w]+?://[^ \"\n\r\t<]*)#is", $url) || preg_match("#((www)\.[^ \"\t\n\r<]*)#is", $url)){
		$checkurl = parse_url($url);
		if (!isset($checkurl['scheme'])){
			$url = 'http://'.$url;
		}
		return( (($hyperlink) ? '<a href="'.$url.'" target="_blank">' : NULL ) . ((empty($text)) ? $url : $text ) . (($hyperlink) ? '</a>' : NULL ) );
	}
	return(NULL);
}

if (!function_exists('array_combine')){
	function array_combine(&$keys, &$values){
		$key_count = count($keys);
	
		$combined = array();
		for ($i = 0; $i < $key_count; $i++) {
			$combined[$keys[$i]] = $values[$i];
		}
	
		return $combined;
	}
}
?>

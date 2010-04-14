<?php
/***************************************************************************
 *                           functions.ts.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.ts.inc.php,v 1.4 2005/10/03 10:55:55 SC Kruiper Exp $
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

//rights breakdown
function breakdown_rights($priv)
{
	$rightset_array = array(64,32,16,8,4,2,1,0);
	if (in_array($priv, $rightset_array)){
		$br_priv[0] = $priv;
	}
	else{
		foreach ($rightset_array as $right){
			if ($right <= $priv && $priv != 0){
				$br_priv[] = $right;
				$priv -= $right;
			}
		}
	}
	return($br_priv);
}

//Player Flags
function prepare_sort_name($name){
	return(preg_replace('/[^a-z0-9]/i', '', $name));
}

function SORT_PLAYERS(&$a, &$b){
	$a_nick = prepare_sort_name($a['nick']);
	$b_nick = prepare_sort_name($b['nick']);

	if (in_array(1, $a['pprivs']) && in_array(1, $b['pprivs'])){
		if (strcasecmp($a_nick, $b_nick) == 0){
			return(0);
		}
		else{
			return((strcasecmp($a_nick, $b_nick) < 0) ? -1 : 1);
		}
	}
	elseif (!in_array(1, $a['pprivs']) && !in_array(1, $b['pprivs'])){
		if ((in_array(1, $a['cprivs']) && in_array(1, $b['cprivs'])) || (!in_array(1, $a['cprivs']) && !in_array(1, $b['cprivs']))){
			if (strcasecmp($a_nick, $b_nick) == 0){
				return(0);
			}
			else{
				return((strcasecmp($a_nick, $b_nick) < 0) ? -1 : 1);
			}
		}
		else{
			return((in_array(1, $a['cprivs'])) ? -1 : 1);
		}
	}
	else{
		return((in_array(1, $a['pprivs'])) ? -1 : 1);
	}
}

function SORT_CHANNELS(&$a, &$b){
	if ($a['order'] == $b['order']){
		if (strcasecmp($a['name'], $b['name']) == 0){
			return(0);
		}
		else{
			return((strcasecmp($a['name'], $b['name']) < 0) ? -1 : 1);
		}
	}
	else{
		return(($a['order'] < $b['order']) ? -1 : 1);
	}
}

function pl_flags(&$pl_flags, &$ch_flags)
{
	if(in_array(4, $pl_flags)){
		$output = 'R';
	}
	else{
		$output = 'U';
	}

	if(in_array(1, $pl_flags)){
		$output .= ' SA';
	}

	if(in_array(1, $ch_flags)){
		$output .= ' CA';
	}

	if(in_array(16, $ch_flags)){
		$output .= ' AO';
	}

	if(in_array(8, $ch_flags)){
		$output .= ' AV';
	}

	if(in_array(2, $ch_flags)){
		$output .= ' O';
	}

	if(in_array(4, $ch_flags)){
		$output .= ' V';
	}

	return($output);
}

function pl_img(&$pflags)
{
/*
IGNORED LIST - No special picture used by TeamSpeak
2 - Voice Request
4 - Doesnt Accept Whispers
64 - Recording
*/
	$tmparr = array(8,32,16,1);
	foreach ($tmparr as $flag){
		if(in_array($flag, $pflags)){
			return($flag);
		}
	}

	return(0); // incase no return was executed above
}

function pl_status(&$pflags)
{
	$output = NULL;
	
	$tmparr = array(8,1,4,16,64,32,2);
	if (in_array(0, $pflags)){
		$output = '{TEXT_PL_STATUS_0}';
	}
	else{
		foreach ($tmparr as $flag){
			if(in_array($flag, $pflags)){
				if (!empty($output) && $flag != 8){
					$output .= '<br />';
				}
				$output .= '{TEXT_PL_STATUS_'.$flag.'}';
			}
		}
	}

	return($output);
}
 
//Channel Flags
function ch_flags(&$chflags)
{
	if(in_array(1, $chflags)){
		$output = 'U';
	}
	else{
		$output = 'R';
	}

	if(in_array(2, $chflags)){
		$output .= 'M';
	}
	if(in_array(4, $chflags)){
		$output .= 'P';
	}
	if(in_array(8, $chflags)){
		$output .= 'S';
	}
	if(in_array(16, $chflags)){
		$output .= 'D';
	}

	return($output);
}

function formatcodec($codec){
	switch ($codec){
		case 0: return('CELP 5.2');
		case 1: return('CELP 6.3');
		case 2: return('GSM 14.8');
		case 3: return('GSM 16.4');
		case 4: return('Windows CELP 5.2');
		case 5: return('Speex 3.4');
		case 6: return('Speex 5.2');
		case 7: return('Speex 7.2');
		case 8: return('Speex 9.3');
		case 9: return('Speex 12.3');
		case 10: return('Speex 16.3');
		case 11: return('Speex 19.5');
		case 12: return('Speex 25.9');
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
?>

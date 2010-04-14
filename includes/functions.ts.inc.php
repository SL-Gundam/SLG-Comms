<?php
/***************************************************************************
 *                           functions.ts.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: functions.ts.inc.php,v 1.3 2005/06/21 19:15:29 SC Kruiper Exp $
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

function SORT_PLAYERS($a, $b){
	$a['pprivs'] = breakdown_rights($a['pprivs']);
	$b['pprivs'] = breakdown_rights($b['pprivs']);

	$a['cprivs'] = breakdown_rights($a['cprivs']);
	$b['cprivs'] = breakdown_rights($b['cprivs']);

	$a['nick'] = prepare_sort_name($a['nick']);
	$b['nick'] = prepare_sort_name($b['nick']);

	if (in_array(1, $a['pprivs']) && in_array(1, $b['pprivs'])){
		if (strcasecmp($a['nick'], $b['nick']) == 0){
			return(0);
		}
		else{
			return((strcasecmp($a['nick'], $b['nick']) < 0) ? -1 : 1);
		}
	}
	elseif (!in_array(1, $a['pprivs']) && !in_array(1, $b['pprivs'])){
		if ((in_array(1, $a['cprivs']) && in_array(1, $b['cprivs'])) || (!in_array(1, $a['cprivs']) && !in_array(1, $b['cprivs']))){
			if (strcasecmp($a['nick'], $b['nick']) == 0){
				return(0);
			}
			else{
				return((strcasecmp($a['nick'], $b['nick']) < 0) ? -1 : 1);
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

function SORT_CHANNELS($a, $b){
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

function pl_flags($pl_flags,$ch_flags)
{
	$pl_flags = breakdown_rights($pl_flags);
	$ch_flags = breakdown_rights($ch_flags);

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

	return(trim($output));
}

function pl_img(&$pflags)
{
	$pflags = breakdown_rights($pflags);

	if(in_array(8, $pflags)){
		$output = '8';
	}
	elseif(in_array(32, $pflags)){
		$output = '32';
	}
	elseif(in_array(16, $pflags)){
		$output = '16';
	}
	elseif(in_array(1, $pflags)){
		$output = '1';
	}
	elseif(in_array(2, $pflags)){
		$output = '0';
	}
	elseif(in_array(4, $pflags)){
		$output = '0';
	}
	elseif(in_array(64, $pflags)){
		$output = '0';
	}
	elseif(in_array(0, $pflags)){
		$output = '0';
	}
	else{
		$output = '0';
	}

	return($output);
}
 
//Channel Flags
function ch_flags($chflags)
{
	$chflags = breakdown_rights($chflags);

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
		case 0:
			$codectext = 'CELP 5.2';
			break;
		case 1:
			$codectext = 'CELP 6.3';
			break;
		case 2:
			$codectext = 'GSM 14.8';
			break;
		case 3:
			$codectext = 'GSM 16.4';
			break;
		case 4:
			$codectext = 'Windows CELP 5.2';
			break;
		case 5:
			$codectext = 'Speex 3.4';
			break;
		case 6:
			$codectext = 'Speex 5.2';
			break;
		case 7:
			$codectext = 'Speex 7.2';
			break;
		case 8:
			$codectext = 'Speex 9.3';
			break;
		case 9:
			$codectext = 'Speex 12.3';

			break;
		case 10:
			$codectext = 'Speex 16.3';
			break;
		case 11:
			$codectext = 'Speex 19.5';
			break;
		case 12:
			$codectext = 'Speex 25.9';
			break;
		default: $codectext = '{TEXT_UNKNOWN_CODEC}';
	}
	return($codectext);
}

function savecache($cacheddata){
	global $table, $ts;

	$rtime = explode(" ",microtime());
	$sql = 'UPDATE '.$table['cache'].'
SET
  data = "'.addslashes($cacheddata).'",
  timestamp = "'.$rtime[1].'"
WHERE
  cache_id = "'.$ts['id'].'"
LIMIT 1';

	return($sql);
}

if (!function_exists('str_split')){
	function str_split($string, $max_length = 1){
		for($i = 0, $cur_length = 0, $cur_array = 0, $spl_string = array(0 => NULL); isset($string{$i}); $i++, $cur_length++){
			if ($cur_length >= $max_length){
				$cur_length = 0;
				$cur_array++;
				$spl_string[$cur_array] = $string{$i};
			}
			else{
				$spl_string[$cur_array] .= $string{$i};
			}
		}
		return($spl_string);
	}
}
?>

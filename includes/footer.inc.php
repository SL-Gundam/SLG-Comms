<?php
/***************************************************************************
 *                              footer.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: footer.inc.php,v 1.19 2005/12/25 20:18:12 SC Kruiper Exp $
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

$footer = new template;
$template = 'footer';

$footer->insert_text('{SLG_VERSION}', ((isset($tssettings['SLG version'])) ? $tssettings['SLG version'] : '{TEXT_UNKNOWN_VERSION}'));

if (isset($db)){
	$db->disconnect();
}

//it seems php 5.0.0 or higher has a bug with windows versions of apache 1.3 and the function apache_get_modules(). It says the function exists but hangs the connection when the function is called. This behaviour has only been tested with Apache for windows. It probably works fine for Linux / Unix but i'm not sure.
$gzipheaders = ((function_exists('apache_response_headers')) ? apache_response_headers() : array() );
if (function_exists('apache_get_modules') && (version_compare(phpversion(), '5.0.0', '<'))){
	$gzipmod = apache_get_modules();
}
else{
	$gzipmod = array();
}
$gzip_text = ((isset($gzipheaders['vary']) || isset($gzipheaders['Vary'])) || ini_get('zlib.output_compression') || (in_array('mod_deflate',$gzipmod) || in_array('mod_gzip',$gzipmod))) ? '{TEXT_GZIP_ENABLED}' : '{TEXT_GZIP_DISABLED}';

$debug_text = (defined("DEBUG")) ? '{TEXT_DEBUG_ON}' : '{TEXT_DEBUG_OFF}';

$executed_queries = (isset($db)) ? $db->num_queries : 0;
$free_queries = (isset($db)) ? $db->num_freequeries : 0;
$nofree_queries = (isset($db)) ? $db->num_nofreequeries : 0;
$sql_time = round((isset($db)) ? $db->sqltime->tottime : 0, 4 );

if (isset($otherdatabase, $forumdatabase, $$forumdatabase) && $otherdatabase){
	$executed_queries += (isset($$forumdatabase)) ? $$forumdatabase->num_queries : 0;
	$free_queries += (isset($$forumdatabase)) ? $$forumdatabase->num_freequeries : 0;
	$nofree_queries += (isset($$forumdatabase)) ? $$forumdatabase->num_nofreequeries : 0;
	$sql_time += round((isset($$forumdatabase)) ? $$forumdatabase->sqltime->tottime : 0, 4 );
}

if (($free_queries + $nofree_queries) != $executed_queries && defined("DEBUG")){
	$divbugfound = '
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_changeProp(objName,x,theProp,theValue) { //v6.0
  var obj = MM_findObj(objName);
  if (obj && (theProp.indexOf("style.")==-1 || obj.style)){
    if (theValue == true || theValue == false)
      eval("obj."+theProp+"="+theValue);
    else eval("obj."+theProp+"=\'"+theValue+"\'");
  }
}
//-->
</script>

<div id="errorlayer" style="padding: 20px; position:Absolute; width:500px; height:175px; z-index:1; left: 200px; top: 150px; overflow: visible;" class="error2">
  <p>{TEXT_BUGNOTIFY_P1}</p>
  <p>{TEXT_BUGNOTIFY_P2}</p>
  <pre>'.((isset($db)) ? print_r($db->queries, true) : '{TEXT_DATANOTFOUND}').'</pre>
  <pre>'.((isset($otherdatabase, $forumdatabase, $$forumdatabase) && $otherdatabase) ? print_r($$forumdatabase->queries, true) : NULL).'</pre>
  <p>{TEXT_THANKS}</p>
  
    <input align="center" name="Close thingy" type="button" id="Close thingy" onClick="MM_changeProp(\'errorlayer\',\'\',\'style.visibility\',\'hidden\',\'LAYER\')" value="{TEXT_CLOSE}">
  
</div>
';
}
else{
	$divbugfound = NULL;
}

$footer->insert_content('{BUG_FINDER}', $divbugfound);

if (isset($starttime) && (!isset($tssettings['Page generation time']) || $tssettings['Page generation time'])){
	$mtime = explode(" ",microtime());
	$endtime = $mtime[1] + $mtime[0];

	$gentime = round(($endtime - $starttime), 4);

	$sql_part = round($sql_time / $gentime * 100);
	$php_part = 100 - $sql_part;

	$generation = '<table border="0" align="center"><div style="font-family: Verdana; font-size: 10px; color: #000000; letter-spacing: -1px" align="center">{TEXT_PAGEGEN}: '. $gentime .'s (PHP: '. $php_part .'% - SQL: '. $sql_part .'%) - {TEXT_SQL_QUERIES}: '. $executed_queries;
	if (defined("DEBUG")){

		$generation .= ' ('. $free_queries .' + '. $nofree_queries .')';
	}
	$generation .= ' - '. $gzip_text .' - '. $debug_text .'</div></table>';
}
else{
	$generation = NULL;
}

$footer->insert_content('{PAGE_GENERATION}', $generation);

$footer->load_language('lng_footer');
$footer->load_template('tpl_footer');
$footer->process();
$footer->output();
unset($footer);

exit;
?>

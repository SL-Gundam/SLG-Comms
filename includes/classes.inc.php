<?php
/***************************************************************************
 *                              classes.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: classes.inc.php,v 1.23 2005/10/24 14:08:13 SC Kruiper Exp $
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

class timecount{
	var $starttime = 0;
	var $tottime = 0;
	
	function starttimecount(){
		if ($this->starttime === 0){
			$mtime = explode(" ",microtime());
			$this->starttime = $mtime[1] + $mtime[0];
		}
	}

	function endtimecount(){
		if ($this->starttime === 0){
			early_error('{TEXT_CLASS_TIMECOUNT_ERROR}');
		}
		$mtime = explode(" ",microtime());
		$this->tottime += ($mtime[1] + $mtime[0]) - $this->starttime;
		$this->starttime = 0;
	}
}

class template{
	var $template = NULL;
	var $text = array();
	var $text_adv = array();
	var $tooltips = array();
	var $text_search = array('{ERROR}' => NULL);
	var $display = array();
	var $tpl_time = NULL;

	function __construct(){
		$this->tpl_time = new timecount;
	}

	function template(){
		$this->__construct();
	}

	function load_template($filename){
		if (!isset($this->template)){
			global $tssettings;

			$this->template = file_get_contents('templates/'.((isset($tssettings['Template'])) ? $tssettings['Template'] : 'Default' ).'/'.$filename.'.html');
		}
		else{
			early_error('{TEXT_LOADTEMPLATE_ERROR}');
		}
	}

	function load_language($filename){
		global $tssettings;

		include('languages/'.((isset($tssettings['Language'])) ? $tssettings['Language'] : 'English' ).'/'.$filename.'.php');
	}

	function displaymessage($message, $dead=false, $sql=NULL, $sqlerror=NULL){
		$error = '<table border="0" align="center"><tr><td><p class="error">'.$message.'</p>';
		if (isset($sql)){
			$error .= '<pre class="error2">';
			if (defined("DEBUG")){
				$error .= '
Query: '.wordwrap(htmlentities($sql), 100).'

';
			}
			$error .= 'Error: '.wordwrap(htmlentities($sqlerror), 100).'
</pre>';
		}
		$error .= '</td></tr></table><p></p>';
		if ($dead){
			global $tssettings, $starttime, $db, $otherdatabase, $forumdatabase, $$forumdatabase;

			include_once('includes/header.inc.php');

			$this->template = '{ERROR}';
			$this->insert_content('{ERROR}', $error);
			$this->process();
			$this->output();
			include('includes/footer.inc.php');
		}
		else{
			$this->text_search['{ERROR}'] .= $error;
		}
	}

	function insert_display($name, $replace){
		$name = rtrim($name, '}');
		$this->display['@'.$name.'_BEGIN}(.*?)'.$name.'_END}@s'] = (($replace) ? '$1' : NULL);
	}

	// content is when the string includes more translation items
	function insert_content($search, $replace){
		$this->text_search[$search] = $replace;
	}

	// text is when the string doesn't include translation items
	function insert_text($search, $replace){
		$this->text[$search] = $replace;
	}

	function processmenu(&$menuitems, $menutype, $baseurl, $checkvar, $submenu=0){
		$parsedmenuitems = NULL;
		if ($menutype === 'HORIZONTAL'){
			$encasebefore = '<p class="menu_horizontal">';
			$encaseafter = '</p>';
			$addbefore = '[ ';
			$addafter = ' ] | ';
			$cleanafter = '| ';
			$parsedsubmenuitems = NULL;
			$submenuvar = 'parsedsubmenuitems';
		}
		elseif ($menutype === 'VERTICAL'){
			$encasebefore = '<ul class="menu_vertical_L'.$submenu.'">';
			$encaseafter = '</ul>';
			$addbefore = '<li>';
			$addafter = '</li>';
			$submenuvar = 'parsedmenuitems';
		}

		foreach ($menuitems as $menuitem){
			if (empty($menuitem['seclevel']) || checkaccess($menuitem['seclevel'])){
				$parsedmenuitems .= $addbefore.'<a href="'.$baseurl.$checkvar.'='.$menuitem['url'].'" class="'.((isset($_GET[$checkvar]) && $_GET[$checkvar] == $menuitem['url']) ? 'curmenulink' : 'menulink').'">'.$menuitem['name'].'</a>'.$addafter;

				if (isset($menuitem['subitems']) && isset($_GET[$checkvar]) && $_GET[$checkvar] == $menuitem['url']){
					$$submenuvar .= $this->processmenu($menuitem['subitems'], $menutype, ($baseurl.$checkvar.'='.$menuitem['url'].'&'), $menuitem['url'], ($submenu + 1));
				}
			}
		}

		if ($menutype === 'HORIZONTAL'){
			$parsedmenuitems = rtrim($parsedmenuitems, $cleanafter);
			$parsedmenuitems = $encasebefore.$parsedmenuitems.$encaseafter.$parsedsubmenuitems;
		}
		elseif ($menutype === 'VERTICAL'){
			$parsedmenuitems = $encasebefore.$parsedmenuitems.$encaseafter;
		}

		return($parsedmenuitems);
	}

	function insert_menu($menu, &$menuarray){
		$menu = rtrim($menu, '}');
		$pattern = $menu.';(VERTICAL|HORIZONTAL);}';
		preg_match($pattern, $this->template, $foundmenu);
		if (count($foundmenu) > 0){
			$parsedmenu = $this->processmenu($menuarray['menuitems'], $foundmenu[1], $menuarray['baseurl'], $menuarray['basevar']);
			$this->insert_text('{'.$foundmenu[0].'}', $parsedmenu);
		}
		else{
			$this->displaymessage('{TEXT_MISSING_MENUTYPE}');
		}
	}

	function process(){
		$this->tpl_time->starttimecount();

		if (!empty($this->display)){
			$this->template = preg_replace(array_keys($this->display), $this->display, $this->template);
			$this->display = array();
		}

		/* text_adv is only applicable to content type insertable data */
		if (!empty($this->text_adv)){
			foreach ($this->text_adv as $key => $value){
				$key = rtrim($key, '}');
				$text_adv_keys[] = '@'.$key.';(.*?);}@';
				$text_adv[] = nl2br($value);
			}
			$this->text_search = preg_replace($text_adv_keys, $text_adv, $this->text_search);
			$this->text_adv = array();
		}

		foreach ($this->text as $key => $value){
			$text_keys[] = $key;
			$text[] = nl2br($value);
		}

		if (!empty($this->tooltips)){
			foreach ($this->tooltips as $key => $value){
				$tooltip_keys[] = $key;
				$tooltips[] = prep_tooltip($value);
			}
//			$this->template = str_replace($tooltip_keys, $tooltips, $this->template);
			$text_keys = array_merge(array_keys($this->text_search), $tooltip_keys, $text_keys);
			$text = array_merge($this->text_search, $tooltips, $text);
			$this->tooltips = array();
		}
		else{
			$text_keys = array_merge(array_keys($this->text_search), $text_keys);
			$text = array_merge($this->text_search, $text);
		}
		$this->text = array();

//		$this->template = str_replace(array_keys($this->text_search), $this->text_search, $this->template);
		$this->text_search = array('{ERROR}' => NULL);

		$this->template = str_replace($text_keys, $text, $this->template);

		$this->tpl_time->endtimecount();
	}

	function echobig(&$string, $bufferSize=8192){
		for ($i = 0, $chars = strlen($string), $current = 0; $current < $chars; $current += $bufferSize) {
			echo substr($string, $current, $bufferSize);
			$i++;
		}
		if (defined("DEBUG")){
			// Because this is the function that actually outputs the template, it can not be integrated into one. This means no multi language support. Not really necassary anyway since the echo below is only executed in DEBUG mode which should never be used in public sites.
			echo '<table border="0" align="center"><tr><td><p class="error">DEBUG: echobig() required '.$i.' loop(s) to output the data.</p></td></tr><tr><td><p class="error">DEBUG: Processing time required for this part of the template was: '.round($this->tpl_time->tottime, 4).'s</p></td></tr></table><p></p>';
		}
	}

	function output(){
		$this->echobig($this->template);
		$this->template = NULL;
	}
}
?>

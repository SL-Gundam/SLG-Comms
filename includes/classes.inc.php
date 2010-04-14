<?php
/***************************************************************************
 *                              classes.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: classes.inc.php,v 1.16 2005/06/22 01:22:37 SC Kruiper Exp $
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
		$mtime = explode(" ",microtime());
		$this->starttime = $mtime[1] + $mtime[0];
	}

	function endtimecount(){
		if ($this->starttime == 0){
			global $template, $$template;
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
	var $popup = array();
	var $text_search = array('{ERROR}' => NULL);
	var $display_search = array();
	var $display_replace = array();
	
	function load_template($filename){
		if(!function_exists('file_get_contents')){
			function file_get_contents($filename){
				$file = file($filename);
				return(implode('', $file));

		/*		$handle = fopen($filename, "r");
				$contents = fread($handle, filesize($filename));
				fclose($handle);
				return($contents);*/
			}
		}

		if (!isset($this->template)){
			global $tssettings;

			$this->template = file_get_contents('templates/'.((isset($tssettings['Template'])) ? $tssettings['Template'] : 'Default' ).'/'.$filename.'.html');

/*			if (!isset($_GET['nicesource']) || $_GET['nicesource'] != true){
				$this->template = str_replace(array("\n", "\t"), '', $this->template); //compress template
			}*/ // might see about some more possibilities into this later
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
Query: '.wordwrap($sql, 125).'

';
			}
			$error .= 'Error: '.wordwrap($sqlerror, 125).'
</pre>';
		}
		$error .= '</td></tr></table><p></p>';
		if ($dead){
			global $tssettings, $starttime;

			include_once('includes/header.inc.php');

			$this->template = $error;
			$this->process();
			$this->output();
			die(include('includes/footer.inc.php'));
		}
		else{
			$this->text_search['{ERROR}'] .= $error;
		}
	}

	function insert_display($name, $replace){
		$name = rtrim($name, '}');
		$this->display_search[] = '@'.$name.'_BEGIN}(.*?)'.$name.'_END}@s';
		$this->display_replace[] = (($replace) ? '$1' : NULL);
	}

	function insert_content($search, $replace){
		$this->text_search[$search] = $replace;
	}

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
		if (!empty($this->display_search) && !empty($this->display_replace)){
			$this->template = preg_replace($this->display_search, $this->display_replace, $this->template);
			$this->display_search = array();
			$this->display_replace = array();
		}

		if (!empty($this->text_search)){
			$text_search_keys = array_keys($this->text_search);
			$this->template = str_replace($text_search_keys, $this->text_search, $this->template);
			$this->text_search = array('{ERROR}' => NULL);
		}

		if (!empty($this->text_adv)){
			foreach ($this->text_adv as $key => $value){
				$key = rtrim($key, '}');
				$text_adv_keys[] = '@'.$key.';(.*?);}@';
				$text_adv[] = nl2br($value);
			}
			$this->template = preg_replace($text_adv_keys, $text_adv, $this->template);
			$this->text_adv = array();
		}

		if (!empty($this->popup)){
			foreach ($this->popup as $key => $value){
				$key = rtrim($key, '}');
				$value = htmlentities(wordwrap($value, 50, "\n"));
				$popup_keys[] = $key.'_POPUP}';
				$popups[] = convert_jspoptext($value);
				$popup_keys[] = $key.'_NORMAL}';
				$popups[] = $value;
			}
			$this->template = str_replace($popup_keys, $popups, $this->template);
			$this->popup = array();
		}

		foreach ($this->text as $key => $value){
			$text_keys[] = $key;
			$text[] = nl2br($value);
		}
		$this->template = str_replace($text_keys, $text, $this->template);
		$this->text = array();
	}

	function output(){
		echobig($this->template);
		$this->template = NULL;
	}
}
?>

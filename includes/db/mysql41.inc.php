<?php
/***************************************************************************
 *                              mysql41.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: mysql41.inc.php,v 1.15 2005/12/25 20:18:12 SC Kruiper Exp $
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

class db {
	var $num_queries = 0;
	var $num_freequeries = 0;
	var $num_nofreequeries = 0;
	var $queries = NULL;
	var $sqltime = NULL;
	var $sqlconnectid = NULL;
   
	function __construct(){
		$this->sqltime = new timecount;

		if (!extension_loaded('mysqli')){
			early_error('{TEXT_MYSQLIEXTNOTLOAD}');
		}
	}

	function db(){
		$this->__construct();
	}

	function connect($serverid, $db_server, $db_user, $db_passwd, $db_name){
		$this->sqltime->starttimecount();
		
		$db_server = explode(':', $db_server, 2);
		
		$this->sqlconnectid = @mysqli_connect($db_server[0], $db_user, $db_passwd, $db_name, ((isset($db_server[1])) ? $db_server[1] : NULL )) OR early_error('{TEXT_DB_CONNECT_ERROR;'.$serverid.';}', '{TEXT_DB_CONNECT_FAILED}', $this->getconnecterror());

		$this->sqltime->endtimecount();
		
//		return($result);
	}

	function disconnect(){
		$this->sqltime->starttimecount();

		@mysqli_close($this->sqlconnectid) OR early_error('{TEXT_DB_DISCONNECT_ERROR;'.$serverid.';}', '{TEXT_DB_DISCONNECT_FAILED}', $this->geterror());

		$this->sqltime->endtimecount();

//		return($result);
    }
   
	function selectdb($dbid, $db_name){
		$this->sqltime->starttimecount();

		$result = @mysqli_select_db($this->sqlconnectid, $db_name) OR early_error('{TEXT_DB_SELECT_ERROR;'.$dbid.';}', '{TEXT_DB_SELECT_FAILED}', $this->geterror());

		$this->sqltime->endtimecount();

		return($result);
    }
   
	function execquery($queryid, $sql){
		$this->sqltime->starttimecount();

		$result = @mysqli_query($this->sqlconnectid, $sql) OR early_error('{TEXT_DB_QUERY_FAILED;'.$queryid.';}', $sql, $this->geterror());

		$this->num_queries++;
		if (isset($this->queries[$queryid])){
			$this->queries[$queryid] .= '[{TEXT_FREE}?]-';
		}
		else{
			$this->queries[$queryid] = '[{TEXT_FREE}?]-';
		}

		if ($result === true && (strncasecmp($sql, "create", 6) === 0 || strncasecmp($sql, "insert", 6) === 0 || strncasecmp($sql, "delete", 6) === 0 || strncasecmp($sql, "update", 6) === 0 || strncasecmp($sql, "drop", 4) === 0 || strncasecmp($sql, "alter", 5) === 0)){
			$this->num_nofreequeries++;
			$this->queries[$queryid] .= '[{TEXT_NONEED}]';
		}

		$this->sqltime->endtimecount();

		return($result);
	}
	
	function freeresult($queryid, $resultid){
		$this->sqltime->starttimecount();

		@mysqli_free_result($resultid);// OR early_error('{TEXT_DB_FREEQUERY_FAILED;'.$queryid.';}'); it seems that mysqli doesn't give a return code anymore.

		$this->sqltime->endtimecount();

		if (is_null($this->numrows($resultid))){
			$this->num_freequeries++;
			$this->queries[$queryid] .= '[{TEXT_FREE}.]';
		}

	}

	function getrow($resultset){
		$this->sqltime->starttimecount();

		$result = @mysqli_fetch_assoc($resultset);

		$this->sqltime->endtimecount();

		return($result);
	}

	function numrows($resultset){
		$this->sqltime->starttimecount();

		$result = @mysqli_num_rows($resultset);

		$this->sqltime->endtimecount();

		return($result);
	}

	function dataseek($resultset, $position){
		$this->sqltime->starttimecount();

		@mysqli_data_seek($resultset, $position) OR early_error('{TEXT_DB_DATASEEK_FAILED;'.$resultset.';}');

		$this->sqltime->endtimecount();
	}

	function escape_string($str){
		$this->sqltime->starttimecount();

		$str = mysqli_real_escape_string($this->sqlconnectid, $str);

		$this->sqltime->endtimecount();

		return($str);
	}

	function geterror(){
//		$this->sqltime->starttimecount(); Has allready been called by the function which calls this one.
		
		$result = @mysqli_error($this->sqlconnectid).' ('.@mysqli_errno($this->sqlconnectid).')';

//		$this->sqltime->endtimecount();

		return($result);
	}

	function getconnecterror(){
//		$this->sqltime->starttimecount(); Has allready been called by the function which calls this one.
		
		$result = @mysqli_connect_error().' ('.@mysqli_connect_errno().')';

//		$this->sqltime->endtimecount();

		return($result);
	}
}
?>

<?php
/***************************************************************************
 *                               mysql.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: mysql.inc.php,v 1.32 2007/01/28 17:50:42 SC Kruiper Exp $
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

if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

// this class manages the mysql db functions
class database
{
	var $num_queries = 0;
	var $num_freequeries = 0;
	var $num_nofreequeries = 0;
	var $queries = NULL;
	var $sqltime = NULL;
	var $sqlconnectid = NULL;
   
	function connect( $serverid, $db_server, $db_user, $db_passwd, $db_name )
	{
		if ( !extension_loaded('mysql') )
		{
			early_error( '{TEXT_EXTNOTLOAD;MySQL;}' );
		}

		if ( !is_null($this->sqlconnectid) )
		{
			early_error( '{TEXT_CONNECT_ALLREADY}' );
		}

		if ( is_null($this->sqltime) )
		{
			$this->sqltime = new timecount;
		}

		$this->sqltime->starttimecount();

		$result = @mysql_connect( $db_server, $db_user, $db_passwd, true );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_CONNECT_ERROR;' . $serverid . ';}', NULL, $this->getconnecterror() );
		}
		
		$this->sqlconnectid = $result;

		$this->selectdb( $serverid, $db_name );
	}

	function disconnect()
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysql_close( $this->sqlconnectid );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_DISCONNECT_ERROR}', NULL, $this->geterror() );
		}

		$this->sqlconnectid = NULL;
    }
   
	function selectdb( $dbid, $db_name )
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysql_select_db( $db_name, $this->sqlconnectid );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_SELECT_ERROR;' . $dbid . ';}', NULL, $this->geterror() );
		}
    }
   
	function execquery( $queryid, $sql, $parameters=array() )
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$sql = vsprintf( trim( $sql ), array_values( (array) $parameters ) );

		$this->sqltime->starttimecount();

		$result = @mysql_query( $sql, $this->sqlconnectid );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_QUERY_FAILED;' . $queryid . ';}', $sql, $this->geterror() );
		}

		$this->num_queries++;
		if ( isset($this->queries[ $queryid ]) )
		{
			$this->queries[ $queryid ] .= '[{TEXT_FREE}?]->';
		}
		else
		{
			$this->queries[ $queryid ] = '[{TEXT_FREE}?]->';
		}

		if ( $result === true )
		{
			$this->num_nofreequeries++;
			$this->queries[ $queryid ] .= '[{TEXT_NONEED}]';
		}

		return( $result );
	}
	
	function freeresult( $queryid, $resultid )
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_resource($resultid) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysql_free_result( $resultid );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_FREEQUERY_FAILED;' . $queryid . ';}' );
		}
		elseif ( !is_resource($resultid) )
		{
			$this->num_freequeries++;
			$this->queries[ $queryid ] .= '[{TEXT_FREE}.]';
		}
	}

	function getrow( $resultset )
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_resource($resultset) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysql_fetch_assoc( $resultset );

		$this->sqltime->endtimecount();

		return( $result );
	}

	function numrows( $resultset )
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_resource($resultset) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysql_num_rows( $resultset );

		$this->sqltime->endtimecount();

		return( $result );
	}

	function dataseek( $resultset, $position )
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_resource($resultset) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysql_data_seek( $resultset, $position );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_DATASEEK_FAILED;' . $resultset . ';}' );
		}
	}

	function affected_rows()
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysql_affected_rows( $this->sqlconnectid );

		$this->sqltime->endtimecount();

		return( $result );
	}

	function escape_string( $str )
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$str = ( ( function_exists('mysql_real_escape_string') ) ? mysql_real_escape_string( $str, $this->sqlconnectid ) : mysql_escape_string( $str ) );

		$this->sqltime->endtimecount();

		return( $str );
	}

	function geterror()
	{
		if ( !is_resource($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();
		
		$result = mysql_error( $this->sqlconnectid ) . ' (' . mysql_errno( $this->sqlconnectid ) . ')';

		$this->sqltime->endtimecount();

		return( $result );
	}

	function getconnecterror()
	{
		$this->sqltime->starttimecount();
		
		$result = mysql_error() . ' (' . mysql_errno() . ')';

		$this->sqltime->endtimecount();

		return( $result );
	}
}
?>

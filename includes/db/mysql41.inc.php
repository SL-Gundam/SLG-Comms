<?php
/***************************************************************************
 *                              mysql41.inc.php
 *                            -------------------
 *   begin                : Saturday, March 13, 2005
 *   copyright            : (C) 2005 Soul--Reaver
 *   email                : slgundam@gmail.com
 *
 *   $Id: mysql41.inc.php,v 1.31 2007/01/28 17:50:43 SC Kruiper Exp $
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

// this class manages the mysqli db functions
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
		if ( !extension_loaded('mysqli') )
		{
			early_error( '{TEXT_EXTNOTLOAD;MySQLi;}' );
		}

		if ( !is_null($this->sqlconnectid) )
		{
			early_error( '{TEXT_CONNECT_ALLREADY}' );
		}

		if ( is_null($this->sqltime) )
		{
			$this->sqltime = new timecount;
		}

		$db_server = explode( ':', $db_server, 2 );

		$this->sqltime->starttimecount();
		
		$result = @mysqli_connect( $db_server[0], $db_user, $db_passwd, $db_name, ( ( isset($db_server[1]) ) ? $db_server[1] : NULL ) );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_CONNECT_ERROR;' . $serverid . ';}', NULL, $this->getconnecterror() );
		}

		$this->sqlconnectid = $result;
	}

	function disconnect()
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysqli_close( $this->sqlconnectid );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_DISCONNECT_ERROR}', NULL, $this->geterror() );
		}

		$this->sqlconnectid = NULL;
    }
   
	function selectdb( $dbid, $db_name )
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysqli_select_db( $this->sqlconnectid, $db_name );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_SELECT_ERROR;' . $dbid . ';}', NULL, $this->geterror() );
		}
    }
   
	function execquery( $queryid, $sql, $parameters=array() )
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$sql = vsprintf( trim( $sql ), array_values( (array) $parameters ) );

		$this->sqltime->starttimecount();

		$result = @mysqli_query( $this->sqlconnectid, $sql );

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
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_object($resultid) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		@mysqli_free_result( $resultid );// it seems that mysqli doesn't give a return code anymore for this function.

		$this->sqltime->endtimecount();
		
		/*if ( $result === false )
		{
			early_error( '{TEXT_DB_FREEQUERY_FAILED;' . $queryid . ';}' );
		}*/

		if ( is_null($this->numrows($resultid)) )
		{
			$this->num_freequeries++;
			$this->queries[ $queryid ] .= '[{TEXT_FREE}.]';
		}
	}

	function getrow( $resultset )
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_object($resultset) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysqli_fetch_assoc( $resultset );

		$this->sqltime->endtimecount();

		return( $result );
	}

	function numrows( $resultset )
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_object($resultset) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysqli_num_rows( $resultset );

		$this->sqltime->endtimecount();

		return( $result );
	}

	function dataseek( $resultset, $position )
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		if ( !is_object($resultset) )
		{
			early_error( '{TEXT_NO_RESOURCE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysqli_data_seek( $resultset, $position );

		$this->sqltime->endtimecount();

		if ( $result === false )
		{
			early_error( '{TEXT_DB_DATASEEK_FAILED;' . $resultset . ';}' );
		}
	}

	function affected_rows()
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$result = @mysqli_affected_rows( $this->sqlconnectid );

		$this->sqltime->endtimecount();

		return( $result );
	}

	function escape_string( $str )
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();

		$str = mysqli_real_escape_string( $this->sqlconnectid, $str );

		$this->sqltime->endtimecount();

		return( $str );
	}

	function geterror()
	{
		if ( !is_object($this->sqlconnectid) )
		{
			early_error( '{TEXT_NO_CONNECT_AVAILABLE}' );
		}

		$this->sqltime->starttimecount();
		
		$result = mysqli_error( $this->sqlconnectid ) . ' (' . mysqli_errno( $this->sqlconnectid ) . ')';

		$this->sqltime->endtimecount();

		return( $result );
	}

	function getconnecterror()
	{
		$this->sqltime->starttimecount();
		
		$result = mysqli_connect_error() . ' (' . mysqli_connect_errno() . ')';

		$this->sqltime->endtimecount();

		return( $result );
	}
}
?>

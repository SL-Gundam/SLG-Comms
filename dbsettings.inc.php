<?php
//security through the use of define != defined
if ( !defined("IN_SLG") )
{ 
	die( "Hacking attempt." );
}

define( "NO_DATABASE", 10 );
if ( !checkfilelock('install.php') )
{
	early_error( '{TEXT_NOINSTALL}' );
}
?>

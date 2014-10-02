<?php

// no direct access
defined('_JEXEC') or die;

// http://de1.php.net/trigger_error

if (_DEBUG_) {
    set_error_handler ('debug_error_handler');
}
else {
    set_error_handler ('nice_error_handler');
}

/*------------------------------------------------------------------------------------
rsg2_LibError
------------------------------------------------------------------------------------
Replaces deprecated Joomla API JError (Borrows from interface description)
------------------------------------------------------------------------------------*/
class rsg2_LibError
{
/*
	protected static $levels = array(
		E_NOTICE => 'Notice',
		E_WARNING => 'Warning',
		E_ERROR => 'Error'
	);
*/


	/*------------------------------------------------------------------------------------
	raiseError
	------------------------------------------------------------------------------------
	Parameter 	Type 	Default 	Description
	$code 	string 		The application-internal error code for this error
	$msg 	string 		The error message, which may also be shown the user if need be.
	$info 	mixed 	null 	Optional: Additional error information (usually only developer-relevant information that the user should never see, like a database DSN).
	------------------------------------------------------------------------------------*/
	public static function raiseError (
        $code,
        $msg,
        $info=null)
	{


/* ????
        trigger_error (
        trigger_error("Kann nicht durch 0 teilen", E_USER_ERROR),
        bool trigger_error ( string $error_msg [, int $error_type = E_USER_NOTICE ] ));
*/
	}


	/*----------------------------------------------------------------------------------
	raiseWarning
	------------------------------------------------------------------------------------
	Parameter 	Type 	Default 	Description
	$code 	string 		The application-internal error code for this error
	$msg 	string 		The error message, which may also be shown the user if need be.
	$info 	mixed 	null 	Optional: Additional error information (usually only 
	       developer-relevant information that the user should never see, like a 
		   database DSN).	
    ----------------------------------------------------------------------------------*/
	public static function raiseWarning (
		$code,
		$msg,
		$info=null)	
	{




	}

	/*----------------------------------------------------------------------------------
	raiseNotice
	------------------------------------------------------------------------------------
	Parameter 	Type 	Default 	Description
	$code 	string 		The application-internal error code for this error
	$msg 	string 		The error message, which may also be shown the user if need be.
	$info 	mixed 	null 	Optional: Additional error information (usually only 
	       developer-relevant information that the user should never see, like a 
		   database DSN).	
    ----------------------------------------------------------------------------------*/
	public static function raiseNotice (
        $code,
        $msg,
        $info=null)
	{




	}

} // class

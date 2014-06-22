<?php

// no direct access
defined('_JEXEC') or die;

class rsg2_LibPaths
{
	/*------------------------------------------------------------------------------------
	joinPaths
	------------------------------------------------------------------------------------
	Shall work similar to python os.path.join but regard DIRECTORY_SEPARATOR
	origin:
	http://stackoverflow.com/questions/1091107/how-to-join-filesystem-path-strings-in-php	
	------------------------------------------------------------------------------------*/
	/*
	function join_paths() {
		$paths = array();

		foreach (func_get_args() as $arg) {
			if ($arg !== '') { $paths[] = $arg; }
		}

		return preg_replace('#/+#','/',join('/', $paths));
		
		DIRECTORY_SEPARATOR ?
	}
	*/

	static function join_paths() {
		return preg_replace('~[/\\\]+~', DIRECTORY_SEPARATOR, implode(DIRECTORY_SEPARATOR, func_get_args()));
	}
	
	
	
	
	
} // class

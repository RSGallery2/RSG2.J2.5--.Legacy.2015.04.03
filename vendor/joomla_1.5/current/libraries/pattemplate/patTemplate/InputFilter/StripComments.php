<?PHP
/**
 * patTemplate StripComments input filter
 *
 * $Id: StripComments.php 7710 2007-06-09 21:56:28Z friesengeist $
 *
 * Will remove all HTML comments.
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * patTemplate StripComments output filter
 *
 * $Id: StripComments.php 7710 2007-06-09 21:56:28Z friesengeist $
 *
 * Will remove all HTML comments.
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_InputFilter_StripComments extends patTemplate_InputFilter
{
   /**
	* filter name
	*
	* @access	protected
	* @abstract
	* @var	string
	*/
	var	$_name	=	'StripComments';

   /**
	* compress the data
	*
	* @access	public
	* @param	string		data
	* @return	string		data without whitespace
	*/
	function apply( $data )
	{
		$data = preg_replace( '°<!--.*-->°msU', '', $data );
		$data = preg_replace( '°/\*.*\*/°msU', '', $data );

		return $data;
	}
}
?>

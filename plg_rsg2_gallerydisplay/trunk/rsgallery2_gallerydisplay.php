<?php
/**
 * @version		$Id$
 * @package		RSGallery2
 * @subpackage	Content
 * @copyright	Copyright (C) 2008 - 2012 RSGallery2
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die('Restricted');

// Import the general plugin file of Joomla!'s library
jimport( 'joomla.plugin.plugin' );

// Load RSGallery2 component (site) language file
$lang = JFactory::getLanguage();
$lang->load('com_rsgallery2', JPATH_SITE);

// Initialize RSGallery2 
require_once( JPATH_ROOT.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );


class plgContentRsgallery2_gallerydisplay extends JPlugin {

	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		// Simple performance check to determine whether bot should process further.
		if (JString::strpos($article->text, 'rsg2_display') === false) {
			return true;
		}
	
		// Define the regular expression for the bot.
		$regex = "#{rsg2_display\:*(.*?)}#s";

		// Perform the replacement.
		$article->text = preg_replace_callback($regex, array(&$this, '_replacer'), $article->text);

		return true;
	}	

	/**
	 * Replaces the matched tags.
	 *
	 * @param	array	An array of matches (see preg_match_all)
	 * @return	string
	 */
	function _replacer ( $matches ) {
		global $rsgConfig;
		$app =& JFactory::getApplication();
		
		// Get the plugin parameters
		$pluginName = 'rsgallery2_gallerydisplay';
		$plugin =& JPluginHelper::getPlugin('content', $pluginName);
		// Parameters
		$pluginParams = new JParameter( $plugin->params );
		$pluginDebug = $pluginParams->get('debug', '0');
		
		// Save the default configuration because a user might change the 
		// parameters via the plugin but can also use the plugin multiple
		// times on one page (use "clone" because in PHP5 objects are passed 
		// by reference).
		$original_rsgConfig = clone $rsgConfig;	

		if( $matches ) {
			// Get attributes from matches and create array
			$attribs = explode(",",$matches[1]);
			if ( is_array( $attribs ) ) {
				$clean_attribs = array ();
				foreach ( $attribs as $attrib ) {
					// Remove spaces (&nbsp;) from attributes and trim whith space
					$clean_attrib = $this->plg_rsg2_display_replacer_clean_data ( $attrib );
					array_push( $clean_attribs, $clean_attrib );
				}
			} else {
				return false;
			}

			// Go over attribs to get template, gid and possible parameters
			foreach ($clean_attribs as $key => $value) {//$key is 0, 1, etc. $value is semantic, etc.
				switch ($key) {
					case 0:	// This is the (required) template, e.g. semantic
						if (isset( $clean_attribs[0]) AND (string) $clean_attribs[0]){
							$template = strtolower( $clean_attribs[0] );
						} else {
							$template = Null;
						}			
					break;
					case 1: // This is the (required) gallery id, e.g. 2
						if (isset( $clean_attribs[1]) AND (int) $clean_attribs[1]){
							$gallery_id = $clean_attribs[1];
						} else {
							$gallery_id = Null;
						}			
					break;
					default: //These are parameters like displaySearch=0;
						$pieces = explode("=",$clean_attribs[$key]);
						// Change the configuration parameter with the value
						$rsgConfig->$pieces[0] = $pieces[1];
				}
			}

			// Several checks on template and gallery id - start
			// Check we have a template name
			if (!isset($template)) {
				if ($pluginDebug) {
					$msg = JText::_('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_NO_TEMPLATE_NAME_GIVEN');
					$app->enqueueMessage($msg,'message');
				}
				return false;
			}
			// Check the template is indeed installed
			$templateLocation = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $template . DS . 'index.php';
			if( !file_exists( $templateLocation )) {
				if ($pluginDebug) {
					$msg = JText::sprintf('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_TEMPLATE_DIRECTORY_NOT_FOUND', $template);
					$app->enqueueMessage($msg,'message');
				}
				return false;
			}			
			// Check we have a gallery id
			if (!isset($gallery_id)){
				if ($pluginDebug) {
					$msg = JText::_('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_NO_GALLERY_ID_GIVEN');
					$app->enqueueMessage($msg,'message');
				}
				return false;
			}
			// Check a gallery with gallery id exists
			// Get gallery details first
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id, name, published'); // Perhaps access could be checked as well
			$query->from('#__rsgallery2_galleries');
			$query->where('id = '. (int) $gallery_id);
			$db->setQuery($query);
			$galleryDetails = $db->loadAssoc();
			// Does the gallery exist?
			if (!$galleryDetails) {
				if ($pluginDebug) {
					$msg = JText::sprintf('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_NO_SUCH_GALLERY_ID_EXISTS',$gallery_id);
					$app->enqueueMessage($msg,'message');
				}
				return false;
			}
			// Is the gallery published?
			if (!$galleryDetails['published']) {
				if ($pluginDebug) {
					$msg = JText::sprintf('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_GALLERY_UNPUBLISHED',$galleryDetails['name'],$gallery_id);
					$app->enqueueMessage($msg,'message');
				}
				return false;
			}
			// Several checks on template and gallery id - end

			// Cache the current request array to a var before doing anyting
			$original_request 	= $_REQUEST;
			$original_get 		= $_GET;
			$original_post 		= $_POST;

			//The article has lang, language, Itemid, option, view, catid and id
			//Get rid of catid and id, change option and view, set gallery_id (gid).
			//JRequest::setVar('catid',Null);	//Is there a way to unset this?
			//JRequest::setVar('id',Null);	//Is there a way to unset this?
			//JRequest::setVar('option','com_rsgallery2');
			//JRequest::setVar('view', 'gallery');
			JRequest::setVar('gid', $gallery_id);
			JRequest::setVar('rsgTemplate', $template);

			// Get the RSGallery2 gallery HTML!
			ob_start();
			rsgInstance::instance();
			$content_output = ob_get_contents();
			ob_end_clean();	
			
			// Reset the original request array when finished
			$_REQUEST 	= $original_request;
			$_GET 		= $original_get;
			$_POST 		= $original_post;
			$rsgConfig	= clone $original_rsgConfig;

			return $content_output;
		}
	}

	/**
	 * Removed spaces and trim white space
	 *
	 * @param	array	An array of attributes
	 * @return	array
	 */
	function plg_rsg2_display_replacer_clean_data ( $attrib ) {
		$attrib = str_replace( "&nbsp;", '', "$attrib" );
		return trim( $attrib );
	}	
}
<?php
/**
* Tab to display the link to the RSGallery2 edit page in Community Builder (User profile, only published galleries owned by the user that is logged in)
* Author: Dave Ludwigsen (palamangelus) dave@daveludwigsen.com
* Changes for Joomla 1.5 Native by RSGallery2 Team (9 January 2012)
* Changes for Joomla 1.7 by RSGallery2 Team (9 January 2012)
*/

class getrsgallery2Tab extends cbTabHandler 
{
	function getrsgallery2Tab() 
	{
		$this->cbTabHandler();
	}
	
	function getDisplayTab($tab,$user,$ui) 
	{
		$return=$this->ShowUserGalleries($user);
		return $return;
	}

	function ShowUserGalleries($user)
	{
		$database = JFactory::getDBO();
		$my =& JFactory::getUser();
		// Get the Itemid of the active menu item (currently the Community Builder item, not the RSGallery2 menu-item (since that can be multiple items and there is no way to tell which one the user wants)
		$menu = &JSite::getMenu();
		$active = $menu->getActive();
		$Itemid = $active->id;

		// Get the RSGallery2 configuration
		require_once(JPATH_ROOT. DS .'administrator' . DS . 'components' . DS . 'com_rsgallery2' . DS . 'includes' . DS . 'config.class.php');
		$rsgConfig = new rsgConfig();

		// Check if My Galleries is enabled
		$myGalleries = $rsgConfig->get('show_mygalleries');

		//Build the tab for Community Builder
		$htmlText = "";
		$htmlText .= "<div class=\"sectiontableheader\" style=\"text-align:left;padding-left:0px;padding-right:0px;margin:0px 0px 10px 0px;height:auto;width:100%;\">";		

		//if this user, show edit galleries link AND if My Galleries is enabled
		if (($my->id == $user->id) AND $myGalleries) {
			$htmlText.="<div align=left><a href=\"".JUri::base()."index.php?option=com_rsgallery2&Itemid=".$Itemid."&rsgOption=myGalleries\">Edit Gallery Items</a></div>";
		} else {
			$htmlText.="<div align=left>Gallery Items</div><br>";
		}

		//Prepare query for gallery list
		$query = $database->getQuery(true);
		$query->select('*');
		$query->from('#__rsgallery2_galleries');
		$query->where('published = 1');
		$query->where('uid = '. (int) $user->id);
		$query->order('ordering ASC');
		$database->setQuery((string)$query);
		//Gallery list
		$galleries = $database->loadObjectList();
		
		//Loop through the galleries
		$i = 2;	//Help for alternating styling: sectiontableentry1 and sectiontableentry2
		foreach ($galleries as $gallery) {
			$i = ($i==1) ? 2 : 1;
			$htmlText.= "<div class=\"sectiontableentry$i\">";
			$htmlText.="<a href=\"index.php?option=com_rsgallery2&Itemid=".$Itemid."&gid=".$gallery->id."\">";

			// Get either a random image or the assigned gallery thumbnail			
			$query = $database->getQuery(true);
			$query->select('*');	//
			$query->from('#__rsgallery2_files');
			$query->where('gallery_id = '. (int) $gallery->id);
			if ($gallery->thumb_id) {
				$query->where('id = '. (int) $gallery->thumb_id);	//assigned thumbnail
			} else {
				$query->order('RAND()');	//random image
			}
			$database->setQuery((string)$query, 0, 1);	//Limit the list to one item
			// Get the thumbnail
			$thumbnails=$database->loadObjectList();

			//leave room in case we later want to expand for more thumbnails.
			foreach ($thumbnails as $thumbnail) {
				if ($thumbnail->name == "") {
					break;
				} else {
					$htmlText.="<img src=\"images/rsgallery/thumb/".$thumbnail->name.".jpg\" border=0 alt=\"".$thumbnail->descr."\" valign=absmiddle> ";
					break;
				}
			}

			$htmlText.=$gallery->name;
			$htmlText.="</a></div>";
		}
		$htmlText.="</div>";	
		
		return $htmlText;
	}
}	
?>
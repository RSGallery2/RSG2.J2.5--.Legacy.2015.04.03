<?php
/**
* Tab to display the link to the RSGallery2 edit page in Community Builder
* Author: Dave Ludwigsen (palamangelus) dave@daveludwigsen.com
*/

class getrsgallery2Tab extends cbTabHandler 
{
	function getrsgallery2Tab() 
	{
		$this->cbTabHandler();
	}
	
	function getDisplayTab($tab,$user,$ui) 
	{
		global $database, $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_lang;
	
		//$params = $this->params;
		//$eventLimit = $params->get('rsgallery2Link','');

		//$query = "SELECT id FROM #__uhp WHERE user_id='".$user->id."'";
		//$database->setQuery($query);
		//$uhpid = $database->loadResult();
	
		$return=$this->ShowUserGalleries($user);

		return $return;
	}

	function ShowUserGalleries($user)
	{
		global $my, $database, $mosConfig_live_site, $Itemid;

		$htmlText = "";

		$htmlText .= "<div class=\"sectiontableheader\" style=\"text-align:left;padding-left:0px;padding-right:0px;margin:0px 0px 10px 0px;height:auto;width:100%;\">";		
		
		//if this user, show edit galleries link
		if ($my->id == $user->id)
		{
			$htmlText.="<div align=left><a href=\"".$mosConfig_live_site."/index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries\">Edit Gallery Items</a></div><br>";
		}
		else
		{
			$htmlText.="<div align=left>Gallery Items</div><br>";
		}

		
		//Prepare query for gallery list

		//$htmlText .= "<div class=\"sectiontableheader\" style=\"text-align:left;padding-left:0px;padding-right:0px;margin:0px 0px 10px 0px;height:auto;width:100%;\">";
		//$htmlText .= "Galleries<br>";
		
		$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE published=1 AND uid=".$user->id." ORDER BY ordering ASC");
		//$htmlText.="SELECT * FROM #__rsgallery2_galleries WHERE published=1 AND parent=0 AND uid=".$my->id." ORDER BY ordering ASC";
		//Loop through the galleries
     		$galleries=$database->loadObjectList();

			$i = 2;
			
	        foreach ($galleries as $gallery) 
		{
			$i = ($i==1) ? 2 : 1;
			$htmlText.= "<div class=\"sectiontableentry$i\">";
			$htmlText.="<a href=\"index.php?option=com_rsgallery2&Itemid=".$Itemid."&catid=".$gallery->id."\">";

			//get the first image for the gallery
			//images/rsgallery/thumb/
			$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE gallery_id=".$gallery->id." LIMIT 1");
			//$htmlText.="SELECT * FROM #__rsgallery2_files WHERE gallery_id=".$gallery->id." LIMIT 1";
			$thumbnails=$database->loadObjectList();

			//leave room in case we later want to expand for more thumbnails.
			foreach ($thumbnails as $thumbnail)
			{
				if ($thumbnail->name == "")
					break;
				else
				{
					$htmlText.="<img src=\"images/rsgallery/thumb/".$thumbnail->name.".jpg\" border=0 alt=\"".$thumbnail->descr."\" valign=absmiddle> ";
					break;
				}
			}

			$htmlText.=$gallery->name;
			$htmlText.="</a><br />";
		}
		$htmlText.="</div></div></div>";	
		return $htmlText;
	}

}	
?>
<?php
/*
 * sh404SEF support for RSGallery2 1.14.1 component (not 1.13!). 
 * Working menutitle, one category or multiple categories (depending on $rsInsertMultipleCategories), 
 * 	and id-name or filename (depending on $rsImageTitleNotFilename). 
 * Menuname, galleryname(s) and image descriptons are translated in URL when using JoomFish 
 *	(tested with v1.8; option to turn this off is back-end does not work because it's not in this code)
*	BUT THIS GIVES SOME STRANGE BEHAVIOUR!!! [cause unknown to me at this point]
 * An option $RSdatabaseConsolidated is present (v0.2): when true you have to "consolidate database" in RSGallery2 and "purge 
 *	SEF URLs" in sh404SEF (both in back-end). When false you don't have to. 
 *	If $RSdatabaseConsolidated is true then: After adding/removing images in RSGallery2 please "consolidate database" 
 *	in RSGallery2 (not supported in SVN 573 yet) to get order ok in database and "purge SEF URLs" in sh404SEF (both in back-end)
 * By Kaizer, M (Mirjam)
 * Tested with RSGallery2 1.14.1 Alpha - SVN: 618 and sh404SEF Version_1.3_RC - build_150 & build 223 and Joom!Fish v1.8.2 (2007-12-16).
 * An example/guide in writing this was com_docman.php by  Yannick Gaultier (shumisha) [2007-09-19 18:35:29Z].
* @version 0.2 for 1.14 $Id: com_rsgallery2.php 2008-02-01 17:11
 * License : GNU/GPL 
 */
/* RSGallery2 uses $option (refers to com_rsgallery2), $Itemid (refers to link in menu), $gid (refers to galery name),
* $id (refers to filename), $limitstart (==(order - 1) in database when $limit==1), $page (either inline or slideshow; other options not used here),
* $task=downloadfile and $limit.
*/
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//introduce variables for different options
$rsInsertMultipleCategories 	= 1; // 1 = true = multiple categories in URL; 0 = false = single category in URL
$rsImageTitleNotFilename 		= 1; // 1 = true = use image id and its title; 0 = false = use filename
$RSdatabaseConsolidated			= 0; // in RSGallery2 1.14 the database can NOT YET be consolidated in the back-end to get 'ordering' right
									 // 1 = true = assumes that order in table #__rsgallery2_files is nicely 1, 2, 3, 4, 5, etc. (database is consolidated)
									 // 0 = false = assumes that there are missing numbers in order, e.g. 3, 8, 16, 2, and so on. (database not consolidated)
// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig;
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
$shLangIso = shLoadPluginLanguage( 'com_rsgallery2', $shLangIso, '_COM_SEF_SH_CREATE_NEW');
//MK arguments: filename in administrator\components\com_sef\language\plugins; $shLangIso; 1st textstringvariable in the file per language
//MK there is no language file (yet)
// ------------------  load language file - adjust as needed ----------------------------------------
// ------------------ Language related stuff ----------------------------------- not everything is translated
switch ($shLangIso) {						// switch/case statement as long as the language file is not used
	case en:								// english
		$page_slideshow 	= 'slideshow';
		$task_downloadfile	= 'downloadfile';
		break;
	case nl:								// dutch
		$page_slideshow 	= 'diashow';	
		$task_downloadfile	= 'downloadbestand';
		break;
	case de:								// german
		$page_slideshow 	= 'diashow';	
		$task_downloadfile	= 'downloadfile';			// not translated yet
		break;
	case fr:								// french
		$page_slideshow 	= 'diaporama';
		$task_downloadfile	= 'downloadfile';			// not translated yet
		break;
	default:								// for all languages not in the case statement
		$page_slideshow 	= 'slideshow';
		$task_downloadfile	= 'downloadfile';
}
// ----- End of language related stuff ---------------------------------------

//--------MK: FUNCTIONS
//--------MK: FUNCTION COMPONENT NAME FROM MENU	 -> $shRsGallery2Name
//MK get the name with shGetComponentPrefix, if this is empty then with getMenuTitle, if this is empty or just '/' then the name 'RSGallery2' is given
if (!function_exists('shRSGallery2MenuName')) {
	function shRSGallery2MenuName($Itemid, $option, $shLangName) {
		$shRsGallery2Name = shGetComponentPrefix($option);
		$shRsGallery2Name = empty($shRsGallery2Name) ?  getMenuTitle($option, null, $Itemid, null, $shLangName ) : $shRsGallery2Name;
		$shRsGallery2Name = (empty($shRsGallery2Name) || $shRsGallery2Name == '/') ? 'RSGallery2':$shRsGallery2Name;
		return $shRsGallery2Name;
		}
	}
//--------MK:FUNCTION GALLERY category NAME -> $gallery
// this returns the last category, not the parent categories
if (!function_exists('shRSCategoryName')) {
	function shRSCategoryName($gallery_id) {
		global $database;
		static $RSGalleryTree = null;
		$query_gal = "
			SELECT `id`, `name`
			FROM `#__rsgallery2_galleries`
			WHERE `id` = $gallery_id
			";
		$database->setQuery($query_gal);
		$RSgalleryTree = $database->loadObjectList('id');
		$RSgalleryname = $RSgalleryTree[$gallery_id]->name;
		return $RSgalleryname;
		}
	}
//--------MK:FUNCTION GALERY multiple category NAMEs -> $galleries 
/*Function is based on com_docman.php's function dm_sef_get_category_array() which was (according to that file) based on
 * Mark Fabrizio, Joomlicious, fabrizim@owlwatch.com, http://www.joomlicious.com
 */
if( !function_exists( 'shRSCategoryNames' ) ){
	function shRSCategoryNames( $gallery_id ){
		global $database;
		static $RSGalleryTree = null;
		$q  = "
			SELECT id, name, parent
			FROM #__rsgallery2_galleries";
		$database->setQuery( $q );
		$RSGalleryTree = $database->loadObjectList( 'id' );
		$title=array();
		do {
			$RSname[] = 	$RSGalleryTree[ $gallery_id ]->name;
			$gallery_id = 	$RSGalleryTree[ $gallery_id ]->parent;
		} 	while($gallery_id != 0);
		return array_reverse($RSname);
		}
	}
//--------MK:FUNCTION extract image id, title and filename from database -> id, title and name in #__rsgallery2_files
// 'id' is unique number refering to an images;  `name` is the filename (unique);  `title` is the title given by the user during upload (not unique)
// A combination of 'id' and 'title' is used here, or 'name' depending on $rsImageTitleNotFilename (set at top)
// Assumed here: RSGallery database is consolidated: ordering in #__rsgallery2_files in consecutive per gallery, e.g. 1, 2, 3, 4, etc.
if (!function_exists( 'shRSimage')) {
	function shRSimage( $order, $gallery_id, $rsImageTitleNotFilename ){
	//$order is always $limitstart + 1 when $limit == 1 AND "consolidate database" is used in RSGallery2's back-end after (re)moving images; $gallery_id in table is $gid in URL
		global $database, $sefConfig;
		$RSGalleryImageTree = null;
		$q  = "
			SELECT id, name, title, gallery_id
			FROM #__rsgallery2_files
			WHERE gallery_id = " . $gallery_id . " AND ordering = " . $order;
		$database->setQuery( $q );
		$RSGalleryImageTree = $database->loadObjectList('gallery_id');
		$rsId 	 = $RSGalleryImageTree[$gallery_id]->id;	// id, don't use $id from URL as this does not always refer to the correct image when $limit==1
		$rsName  = $RSGalleryImageTree[$gallery_id]->name;	// name
		$rsTitle = $RSGalleryImageTree[$gallery_id]->title;	// title
		if ($rsImageTitleNotFilename) {
			$title= $rsId. $sefConfig->replacement .$rsTitle;
			} else {
			$title= $rsName;
			}
		return $title;
		}
	}
// Assumed here: RSGallery database is NOT consolidated: ordering in #__rsgallery2_files in NOT consecutive per gallery, e.g. 11, 3, 17, 8, etc.
if (!function_exists( 'shRSimageNotConsolidated')) {
	function shRSimageNotConsolidated( $limitstart, $gallery_id, $rsImageTitleNotFilename ){
		global $database, $sefConfig;
		$RSGalleryImageTree = null;
		$q  = "
			SELECT ordering, id, name, title, gallery_id
			FROM #__rsgallery2_files
			WHERE gallery_id = " . $gallery_id;
		$database->setQuery( $q );
		$RSGalleryImageTree = $database->loadObjectList('');// this is array with elements ordering, id, name, title, gallery_id from $q
		sort($RSGalleryImageTree);							// the array will be sorted on the first element: ordering
		$rsId 	 = $RSGalleryImageTree[$limitstart]->id;
		$rsName  = $RSGalleryImageTree[$limitstart]->name;
		$rsTitle = $RSGalleryImageTree[$limitstart]->title;
		if ($rsImageTitleNotFilename) {
			$title= $rsId. $sefConfig->replacement .$rsTitle;
			} else {
			$title= $rsName;
			}
		return $title;
		}
	}	
//--------MK:FUNCTION to get filename and title when only $id is in URL	
if (!function_exists( 'shRSimagenameFromIdOnly')) {
	function shRSimagenameFromIdOnly($id, $rsImageTitleNotFilename){
		global $database, $sefConfig;
		$RSGalleryImageTree = null;
		$q  = "
			SELECT id, name, title
			FROM #__rsgallery2_files
			WHERE id = " . $id;
		$database->setQuery( $q );
		$RSGalleryImageTree = $database->loadObjectList('id');
		$rsName  = $RSGalleryImageTree[$id]->name;	// name
		$rsTitle = $RSGalleryImageTree[$id]->title;	// title
		if ($rsImageTitleNotFilename) {
			$title= $id. $sefConfig->replacement .$rsTitle;
			} else {
			$title= $rsName;
			}
		return $title;
		}
	}
// -------MK Utility function: When there is an id get the gid	
if (!function_exists('rsGetGidFromId')) {
	function rsGetGidFromId($image_id) {
		global $database;
		$query_gid = "
			SELECT `gallery_id`, `id`
			FROM `#__rsgallery2_files`
			WHERE `id` = $image_id
			";
		$database->setQuery($query_gid);
		$RSgid = $database->loadResult();
		return $RSgid;
		}
	}
//--------MK end of FUNCTIONS
	
//--------MK FILL TITLE[] with menuname, category name(s) and image title
// -------Menuname----------------------------------- [alway get the menuname]
$title[] = shRSGallery2MenuName($Itemid, $option, $shLangName);
// -------When there is an id, get the gid now -- [to be able to get the galleryname below]
if (isset($id)) {
	$gid = rsGetGidFromId($id);
	}
// -------Category name(s)--------------------------- [gid is needed for this]
if (isset($gid) && ($gid != 0)) {
	if ($rsInsertMultipleCategories) {
		$title = array_merge($title, shRSCategoryNames($gid));
	} else {
		$title[] = shRSCategoryName($gid);
	}
	shRemoveFromGETVarsList('gid');
	}
//-------Insert trailing / after Menuname or Categoryname when there is no image to avoid .html
if (empty($id)) {
	$title[] = '/';
	}
// -------Image (id and) title or filename
if (isset($id)) {
	$title[] = shRSimagenameFromIdOnly($id, $rsImageTitleNotFilename);
	shRemoveFromGETVarsList('id');
	if ($page == 'inline') {
		shRemoveFromGETVarsList('page');
		}
	$title[] = "Page-0"; // fix for consequent URLs as this is the only case where sh404SEF does not add "Page-i" to the URL
	}
if (($limit == 1) && isset($gid)) {
	//In the pagination the 2nd link has limitstart=1 in the URL, the 3rd link has limitstart=2, etc. but the 1st has no limitstart in the URL. Bug? Test for 0 limitstart to fix.
	if (empty($limitstart)){
		$limitstart = 0;	
		}
	if ($RSdatabaseConsolidated) {
		//$order is always $limitstart + 1; $gallery_id in table is $gid in URL
		$title[] = shRSimage($limitstart + 1, $gid, $rsImageTitleNotFilename); 
	} else {
		//$order is NOT $limitstart + 1; $gallery_id in table is $gid in URL, correct image is found with sort in this function
		$title[] = shRSimageNotConsolidated($limitstart, $gid, $rsImageTitleNotFilename); 
	}
	if (empty($limitstart)){ //fix for consequent URLs as here sh404SEF does not add "Page-1" to the URL as there is no limitstart for image 1
		$title[] = "Page-1";
		}
	if (isset($limitstart)){
		shRemoveFromGETVarslist('limitstart');
		}
	shRemoveFromGETVarslist('limit');
	if ($page == 'inline') {
		shRemoveFromGETVarsList('page');
		}
	}

//-------special case for slideshow ( shLangIso=en)
if ($page == 'slideshow') {
	//SPECIAL CASE: for RSGallery2 1.14 SVN 618 where SLIDESHOW does not work even when there is no plugin file; does work with sh404SEF disabled like this
	$dosef = false;  //  --> don't do SEF (works!)
	/*  The following code is for if SEF will ever work with this slideshow:
		$title[] = $page;
		//when a language file is present (JoomFish thing) (in /administrator\components\com_sef\language\plugins) it's something like this (not tested):
		//$title[] = $sh_LANG[$shLangIso]['_SH404SEF_SLIDESHOW']; 
		shRemoveFromGETVarsList('page');//page needs to be removed from VarsList to work!
	*/
	}
	
//-------special case for downloadfile,
if ($task == 'downloadfile') {
	$title[] = $task;
	shRemoveFromGETVarsList('task');
	}
//------- end of special cases
//--------end of FILL TITLE[]


/* sh404SEF extension plugin : remove vars from URL we have used--*/
shRemoveFromGETVarsList('option');
if (isset($lang))
  shRemoveFromGETVarsList('lang');
if (isset($Itemid))
  shRemoveFromGETVarsList('Itemid');
/* sh404SEF extension plugin : end of remove vars we have used -------------*/

// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString,
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
      (isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------
?>
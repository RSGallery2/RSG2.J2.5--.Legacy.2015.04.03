<?php
/*
 * sh404SEF support for RSGallery2 1.13.1 component (not 1.14) (and, if installed, with JoomFish translations).
 * By Kaizer, M (Mirjam)
 * Working menutitle, one category or multiple categories (depending on $rsInsertMultipleCategories; new in v0.2), and id-name
 * 	or filename (depending on $rsImageTitleNotFilename) (also when $limit == 1, new in v0.3)
 * 	Menuname, galleryname(s) and image descriptions are translated when using JoomFish (tested with v1.8). (In v0.4 loadResult 
 *	and loadRow were replaced by loadObjectList to achieve this.)
 * An option $RSdatabaseConsolidated is present (v0.4): when true you have to "consolidate database"  in RSGallery2 and "purge 
 *	SEF URLs" in sh404SEF (both in back-end). When false you don't have to. (The 'false' option was tested better)
 * Tested with RSGallery2 1.13.1 Alpha - SVN: 289 and sh404SEF Version_1.3_RC - build_150 and Joom!Fish v1.8.2 (2007-12-16).
 * An example/guide in writing this was com_docman.php by  Yannick Gaultier (shumisha) [2007-09-19 18:35:29Z].
* @version 0.4 $Id: com_rsgallery2.php 2008-01-28 16:35
 * License : GNU/GPL 
 */
/* RSGallery2 uses $option (refers to com_rsgallery2), $Itemid (refers to link in menu), $catid (refers to galery/catogory name),
* 	$id (refers to filename in the category when $limit != 1), $limitstart (==(order - 1) in consolidated database when $limit==1), 
*	$page (either inline or slideshow; other options not used here), $task=downloadfile and $limit.
*/
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// Introduce variables for different options
$rsInsertMultipleCategories 	= 1; // 1 = true = multiple categories in URL; 0 = false = single category in URL
$rsImageTitleNotFilename 		= 1; // 1 = true = use image id and its title; 0 = false = use filename
$RSdatabaseConsolidated			= 1; // in RSGallery2 1.13 the database can be consolidated in the back-end to get 'ordering' right
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

// ------------------ load language file - adjust as needed ----------------------------------------
$shLangIso = shLoadPluginLanguage( 'com_rsgallery2', $shLangIso, '_COM_SEF_SH_CREATE_NEW');
//MK arguments: filename in administrator\components\com_sef\language\plugins; $shLangIso; 1st textstringvariable in the file per language
//MK there is no language file (yet)
// ------------------ load language file - adjust as needed ----------------------------------------
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
//--------MK:FUNCTION GALERY category NAME -> $gallery
// this returns the last category, not the parent categories
if (!function_exists('shRSCategoryName')) {//v0.2 used loadRow, v0.4 uses loadObjectList for JoomFish
	function shRSCategoryName($catid) {
		global $database;
		static $RSGalleryTree = null;
		$query_gal = "
			SELECT `id`,`name`
			FROM `#__rsgallery2_galleries`
			WHERE `id` = $catid
			";
		$database->setQuery($query_gal);
		$RSgalleryTree = $database->loadObjectList('id'); 
		$RSgalleryname = $RSgalleryTree[ $catid ]->name;
		return $RSgalleryname;
		}
	}
//--------MK:FUNCTION GALERY multiple category NAMEs -> $galleries (new in v0.2)
/*Function is based on com_docman.php's function dm_sef_get_category_array() which was (according to that file) based on
 * Mark Fabrizio, Joomlicious, fabrizim@owlwatch.com, http://www.joomlicious.com
 * BUT with the joomfish language things taken out (should not be that hard to put it back in)
 */
if( !function_exists( 'shRSCategoryNames' ) ){
	function shRSCategoryNames( $category_id ){
		global $database;
		static $RSGalleryTree = null;
		$q  = "
			SELECT id, name, parent
			FROM #__rsgallery2_galleries";
		$database->setQuery( $q );
		$RSGalleryTree = $database->loadObjectList( 'id' );
		$title=array();
		do {
			$RSname[] = 	$RSGalleryTree[ $category_id ]->name;
			$category_id = 	$RSGalleryTree[ $category_id ]->parent;
		} 	while($category_id != 0);
		return array_reverse($RSname);
		}
	}
//--------MK:FUNCTION extract image id, title and filename from database -> id, title and name in #__rsgallery2_files
// 'id' is unique number refering to an images;  `name` is the filename (unique);  `title` is the title given by the user during upload (not unique)
// A combination of 'id' and 'title' is used here, or 'name' depending on $rsImageTitleNotFilename (set at top)
// Assumed here: RSGallery database is consolidated: ordering in #__rsgallery2_files in consecutive per gallery, e.g. 1, 2, 3, 4, etc.
if (!function_exists( 'shRSimage')) {//v0.4 uses loadObjectList instead of loadRow for Joomla to translate things
	function shRSimage( $order, $gallery_id, $rsImageTitleNotFilename ){
	//$order is always $limitstart + 1 when $limit == 1 AND "consolidate database" is used in RSGallery2's back-end after (re)moving images; $gallery_id in table is $catid in URL
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
		static $RSGalleryImageTree = null;
		$q  = "
			SELECT ordering, id, name, title, gallery_id
			FROM #__rsgallery2_files
			WHERE gallery_id = " . $gallery_id;
		$database->setQuery( $q );
		$RSGalleryImageTree = $database->loadObjectList();	// this is array with elements ordering, id, name, title, gallery_id from $q
		sort($RSGalleryImageTree);							// the array will be sorted on the first element: ordering
		$rsId 	 = $RSGalleryImageTree[$limitstart]->id;	// id, don't use $id from URL as this does not always refer to the correct image whene $limit==1
		$rsName  = $RSGalleryImageTree[$limitstart]->name;	// name
		$rsTitle = $RSGalleryImageTree[$limitstart]->title;	// title
		if ($rsImageTitleNotFilename) {
			$title= $rsId. $sefConfig->replacement .$rsTitle;
			} else {
			$title= $rsName;
			}
		return $title;
		}
	}
//--------MK end of FUNCTIONS
	
//--------MK fix: in case of Random Images and Latest Images modules, URL gets no Itemid -> don't do SEF
// This is a bug in these RSGallery2 modules (see readme file enclosed in com_sef_ext_rsgallery2_v0_4.zip on how to fix this)
// I prefer not to do SEF in these cases as I think that this should be handled in RSGallery2' modules, not in a sef extension file
if (empty($Itemid)) {
	$dosef = false;
} else {
	$dosef = true; //if $dosef true: do SEF URLs
}
//--------MK end of fix

//--------MK FILL TITLE[] with menuname, category name(s) and image title
// -------Menuname
$title[] = shRSGallery2MenuName($Itemid, $option, $shLangName);
// -------Category name(s) (when there is a $catid (!= 0))
if (isset($catid) && ($catid != 0) ) {
	if ($rsInsertMultipleCategories) {
		// For multiple categories in URL use: (new in v0.2)
		$title = array_merge($title, shRSCategoryNames( $catid));
	} else {
		// For single category (last one only) use:
		$title[] = shRSCategoryName($catid);
	}
	shRemoveFromGETVarsList('catid');
	}
//-------Insert trailing / after Menuname or Categoryname when there is no image to avoid .html
if (!isset($id)) {	// $id exists, not equal to 0, 1 etc.
	$title[] = '/';
	}
// -------Image (id and) title or filename
// There is a bug (IMHO) that results in $id being equal to 0. See readme.txt for tip on how to fix this in the code. In this case I don't want to get the imagename. 
if (!empty($id) && isset($catid)) { 	// when $id = 0 don't do the following
	if ($RSdatabaseConsolidated) {		// is the database consolidated in RSGallery2? (new in v0.4)
		//$order is always $limitstart + 1; $gallery_id in table is $catid in URL when RSGallery database is consolidated
		$title[] = shRSimage($limitstart + 1, $catid, $rsImageTitleNotFilename); // new in v0.3
		} else { // when RSGallery database is NOT consolidated ($order != ($limitstart + 1)): different way of finding image name:
		$title[] = shRSimageNotConsolidated( $limitstart, $catid, $rsImageTitleNotFilename ); // new in v0.4
		}
	// fix for consequent URLs as this is the only case where sh404SEF does not add "Page-i" to the URL
	if (($limitstart	 == 0) && ($id != 0)) {	
		$title[] = "Page-0"; 
		} // end of fix
	shRemoveFromGETVarsList('id');
	shRemoveFromGETVarslist('limitstart');
	if ($limit == 1 ){
		shRemoveFromGETVarslist('limit');
		}
	if ($page == 'inline') {
		shRemoveFromGETVarsList('page');
		}
	}

//-------special case for slideshow(new in v0.4)
if ($page == 'slideshow') {
	$title[] = $page_slideshow;
	//when a language file is present (JoomFish thing) (in /administrator\components\com_sef\language\plugins) it's something like this:
	//$title[] = $sh_LANG[$shLangIso]['_SH404SEF_SLIDESHOW']; 
	shRemoveFromGETVarsList('page');//page needs to be removed from VarsList to work!
	}
	
//-------special case for downloadfile,
// won't work as there is no Itemid in this case thus dosef=false (see above), there is also no $catid in this case [IMHO this is a bug in RSGallery2]
if ($task == 'downloadfile') {
	$title[] = $task_downloadfile;
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
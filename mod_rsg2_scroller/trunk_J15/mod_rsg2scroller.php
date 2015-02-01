<?php
/**************************************
 * File: mod_rsg2scroller.php
 * RSGallery2 Thumbnail Scroller
 **************************************
 * @ package Joomla! Open Source
 * @ Released under GNU/ GPL license
 * @ Modified by Radek Kafka to work in Joomla 1.5.x
 * @ Author Daniel Tulp
 * @ DT^2 Joomla! based webdesign http://design.danieltulp.nl
 * @ version 2.0 alpha
 * @ Module based on:
 * @ RSgallery thumbnailscroller by J van Kranenburg, which is based on:
 * @ AKO Gallery Thumbnail Scroller
 **************************************/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.(mod_rsg2scroller)' );

$database = &JFactory::getDBO();

$query = "SELECT id"
	. "\n FROM #__menu"
	. "\n WHERE published = 1"
	. "\n AND link = 'index.php?option=com_rsgallery2'"
	. "\n ORDER BY link"
	;
$database->setQuery( $query );
$RSG2Itemidobj = $database->loadObjectList();
if (count($RSG2Itemidobj) > 0)
	$RSG2Itemid = $RSG2Itemidobj[0]->id;

//initialise init file
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_rsgallery2'.DS.'init.rsgallery2.php');

// Retrieve the parameters.
$Clickornot 	= 			$params->get( 'Clickornot',		'1');
$link2gal		= trim   (	$params->get( 'link2gal',		'dis'));
$Pause			= 			$params->get( 'Pause',			'1');
$useACL 		= 			$params->get( 'useACL',			'0');
$usegalselect 	= 			$params->get( 'usegalselect',	'0');
$galselect 		= 			$params->get( 'galselect');		
$Width			= intval (	$params->get( 'Width',			'80'));
$WidthUnit	 	= trim 	(	$params->get( 'widthunit',		'%'));
$Height			= intval (	$params->get( 'Height',			'150'));
$HeightUnit	 	= trim 	(	$params->get( 'heightunit',		'px'));
$PicsNum		= intval (	$params->get( 'PicsNum',		'5'));
$PickMethod		= trim   (	$params->get( 'PickMethod',		'Rand()'));
$ScrollDirection= trim   (	$params->get( 'ScrollDirection','up'));
$ScrollAmount	= intval (	$params->get( 'ScrollAmount',	'2'));
$ScrollDelay	= intval (	$params->get( 'ScrollDelay',	'50'));
$ScrollSpace	= intval (	$params->get( 'ScrollSpace',	'2'));
$BugSpace		= intval (	$params->get( 'BugSpace',		'10'));
$usecss			=			$params->get( 'usecss',			'1');
$css			=			$params->get( 'css');

//determine which gallery id's to use
//use ACL
if($useACL){
	global $rsgAccess;
	//check if acl is activated
	if(rsgAccess::aclActivated()){
		//make list of allowed gallery_ids
		$gal_ids = $rsgAccess->actionPermitted('view');
		if($usegalselect){
			if(in_array($galselect, $gal_ids)){
				$list = "WHERE #__rsgallery2_files.gallery_id IN(".$galselect.")";
			}
			else{
				echo "One or more gallery id limits is not viewable for the current usertype";
				exit;
			}
		}
		else{
			$list = "WHERE #__rsgallery2_files.gallery_id IN(".implode(",", $gal_ids).")";
		}
	}
	else{
		echo"ACL not enabled in RSGallery2 config<br>Enable it, or also disable it for this module";
		exit;
	}
}
else{
	//determine gallery id's to use if only usegalsselect
	if($usegalselect){
		$list = "WHERE gallery_id IN(".$galselect.")";
	}
	else{
	$list = '';
	}
}

// Retrieve the selected images from the database.
$query = "SELECT * FROM #__rsgallery2_files $list ORDER BY $PickMethod DESC LIMIT $PicsNum";
$database->setQuery ($query);
$rows = $database->LoadObjectList ();
//error trapping:
	if(mysql_error()){
		echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$query\n<br>";
	}

//css, the javascript that can insert it into the head, does not work properly in ie
if($usecss){
	echo "<style type=\"text/css\">".$css."</style>";
	  }
	  
// Set the marquee parameters.
echo "<div class=\"rsg2_scroller\" style=\"overflow:hidden; position: relative;\">
<marquee behavior=\"scroll\" direction=\"$ScrollDirection\" height=\"$Height$HeightUnit\" width=\"$Width$WidthUnit\" scrollamount=\"$ScrollAmount\" scrolldelay=\"$ScrollDelay\"";
if ($Pause){
	echo "onmouseover=this.stop() onmouseout=this.start() ";
	}
echo "> ";?>
<div class='rsscroller_thumb'><?php

// Loop through the images.
foreach ($rows as $row)
	{
	$filename = $row->name;	
	$limitstart = $row->ordering - 1;
	// Insert an <a> tag if the images are clickable.
	if ($Clickornot)
		{
		//display or gallery view
		if($link2gal == 'dis'){
			?>
			<a title="<?php echo $row->title;?>" href="<?php echo JRoute::_('index.php?option=com_rsgallery2&page=inline&Itemid='.$RSG2Itemid.'&id='.$row->id.'&catid='.$row->gallery_id.'&limitstart='.$limitstart);?>">
			<?php
			}
		elseif($link2gal == 'gal'){
		?>
			<a title="<?php echo $row->title;?>" href="<?php echo JRoute::_('index.php?option=com_rsgallery2&Itemid='.$RSG2Itemid.'&id='.$row->id.'&catid='.$row->gallery_id);?>">
			<?php
			}
		}
	// The <img> tag
	echo "<img src='".imgUtils::getImgThumb($filename)."' alt='".$row->title."'>";
	// Insert a </a> tag if the images are clickable.
	if ($Clickornot){echo "</a>";}
	}?>
</div>
</marquee>
</div>
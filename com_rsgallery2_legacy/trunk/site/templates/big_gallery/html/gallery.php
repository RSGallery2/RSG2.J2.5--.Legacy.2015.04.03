<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php

global $ItemId, $mainframe;

$title = $this->gallery->get('name');
$mainframe->setPageTitle( ' '. $title );

//Show My Galleries link
if ($rsgConfig->get('show_mygalleries')) {
	echo $this->showRsgHeader();
}
//show search box
$this->showSearchBox();

//Show introduction text
?>

<div class="intro_text"><?php echo $this->gallery->description; ?></div>
<?php
//Show limitbox
if( $this->pageNav ):
?>
	<div class="rsg2-pagenav-limitbox">
		<?php echo $this->getGalleryLimitBox(); ?>
	</div>
<?php
endif;
?>
	 <h2 align="center"><?php echo $this->gallery->get('name'); ?> </h2>
<?php



//  BEGIN TEST
$count = 10;
global $database;



if (! $this->gallery->id ) {
	

	$queryb="SELECT * FROM (SELECT * FROM #__rsgallery2_files ORDER BY `hits` DESC ) AS MOSTHITS GROUP BY `gallery_id` ORDER BY `date` DESC LIMIT $count";
	
	$queryb = "SELECT LOWHITS.name as imgname, #__rsgallery2_galleries.name as galname, #__rsgallery2_galleries.id AS galid, LOWHITS.id AS imgid FROM (SELECT * FROM #__rsgallery2_files ORDER BY `hits` DESC ) AS LOWHITS  INNER JOIN #__rsgallery2_galleries ON (LOWHITS.gallery_id=#__rsgallery2_galleries.id) GROUP BY `gallery_id` ORDER BY LOWHITS.date ASC";
 
 
	$database->setQuery( $queryb );
	$rows = $database->loadObjectList();
foreach ($rows as $row) {
?>
<div class="rsg_newgalleryblock">
<?php
	$filename = $row->imgname ;
	$id= $row->imgid;
	?>

        			<a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;page=inline&amp;id=".$id); ?>">
             		<img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $filename; ?>" title="<?php echo $row->galname  ?>" border="2" /></a>
<h3><?php echo $row->galname; ?></h3>
 </div>
<?php
} // END TEST


}
?>
<div class="rsg2-clr"></div>
<?php
if($this->gallery->id == 0){

	// show random and latest only in the top gallery 
	
	//Show block with random images 
	$this->showImages("random", 3);
	//Show block with latest images
	$this->showImages("latest", 3);
}
if( $this->pageNav ):
?>

<div class="rsg2-pageNav">
	<?php echo $this->getGalleryPageLinks(); ?>
	<br/>
	<?php echo $this->getGalleryPagesCounter(); ?>
</div>
<div class='rsg2-clr'>&nbsp;</div>
<?php endif; ?>

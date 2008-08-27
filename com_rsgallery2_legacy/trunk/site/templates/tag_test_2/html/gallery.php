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

<table width="100%" border="1">
   <tr colspan="2" align="center">
 <td align="center">
 <h2 align="center"><?php echo $this->gallery->get('name'); ?> </h2>
 </td>
 </tr>
 
 
<?php
$i=0;
foreach( $this->kids as $kid ):
++$i;

if ($i % 2)  {
	echo "<tr>";
}

?>


<td width="50%">
<div class="rsg_galleryblock">
	<div class="rsg2-galleryList-status"><?php echo $kid->status;?></div>
	<div class="rsg2-galleryList-thumb">
		<?php echo $kid->thumbHTML; ?>
	</div>
	<div class="rsg2-galleryList-text">
		<?php echo $kid->galleryName;?>
		<span class='rsg2-galleryList-newImages'>
			<sup><?php if( $this->gallery->hasNewImages() ) echo _RSGALLERY_NEW; ?></sup>
		</span>
		<?php echo $this->_showGalleryDetails( $kid );?>
		<div class="rsg2-galleryList-description"><?php echo $kid->description;?>
		</div>
	</div>
	<div class="rsg_sub_url_single">
		<?php $this->_subGalleryList( $kid ); ?>
	</div>
</div>
</td>

<?php
if ( $i % 2 == 0)  {
	echo "</tr>";
}

?>

<?php
endforeach;
?>

</table>


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

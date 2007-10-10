<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
//Show limitbox
if( $this->pageNav ):
?>
	<div class="rsg2-pagenav-limitbox">
		<?php echo $pageNav->writeLimitBox("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"); ?>
	</div>
<?php
endif;

foreach( $this->kids as $kid ):
?>
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
	<div class="rsg_sub_url_single"><?php $this->_subGalleryList( $kid ); ?>
	</div>
</div>
<?php
endforeach;

if( $this->pageNav ):
?>

<div class="rsg2-pageNav">
	<?php echo $pageNav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid");echo "<br>".$pageNav->writePagesCounter(); ?>
</div>
<div class='clr'>&nbsp;</div>
<?php endif; ?>
<?php defined('_JEXEC') or die('Restricted access'); ?>

<h2 class="xspf_semantic_gallery_title"><?php echo stripslashes($this->gallery->name)?></h2>

<?php if($this->gallery->description): ?>
	<p class="xspf_semantic_gallery_desc"><?php echo stripslashes($this->gallery->description)?></p>
<?php endif; ?>
<ul class="xspf_items" >
	<?php
	foreach($this->audio_clips as $audio_clip):
		$this->audio_clip = $audio_clip;
		$this->display( 'item.php' );
	endforeach;
	?>
</ul>

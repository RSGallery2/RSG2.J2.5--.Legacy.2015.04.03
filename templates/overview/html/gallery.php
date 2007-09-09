<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$gallery = $this->gallery;
$items = $this->items;
?>

<div class="rsg2-gallery">
<h3><?php echo $gallery->name; ?></h3>

<?php foreach( $items as $item ):
	if( $item->type != 'image' ) return;
	$thumb = $item->thumb();
	?>
	<div class="rsg2-thumb" >
		<img src="<?php echo $thumb->url(); ?>" />
		<h5><?php echo $item->name; ?></h5>
		<?php echo $item->descr; ?>
	</div>
<?php endforeach; ?>

<div class="clr">&nbsp;</div>
</div>
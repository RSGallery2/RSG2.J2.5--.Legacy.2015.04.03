<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
$audio_clip = $this->audio_clip;

$player = $this->getPlayer('slim');
$player_type = $player['player'];
$width = $player['width'];
$height = $player['height'];

$link = sefRelToAbs('index.php?option=com_rsgallery2&task=downloadfile&id='.$audio_clip['id']);
?>

<li class="xspf_semantic_item">
	<h3><?php echo $audio_clip['title'];?></h3>
	<?php $this->showPlayer($audio_clip['location'], $audio_clip['title']) ?><a href="<?php echo $link; ?>" class='xspf_download_file' >
		<?php echo _RSGALLERY_DOWNLOAD?>
	</a>
	<p class="xspf_semantic_item_desc">
		<?php echo $audio_clip['descr'];?>
	</p>
</li>

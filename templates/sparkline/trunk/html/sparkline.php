<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

$popup = $this->popup;
?>

<span class='rsg2-sparkline gid<?php echo $this->gallery->id;?>'>

<?php foreach( $this->gallery->items() as $item ):
	if( $item->type != 'image' ) continue;
	$thumb = $item->thumb();
	$original = $item->$popup();
	?><a 
		href='<?php echo $original->url(); ?>' 
		rel='lightbox[gid<?php echo $this->gallery->id; ?>]' 
		title='<?php echo $item->descr; ?>'
		><img 
			src='<?php echo $thumb->url(); ?>'
			alt='<?php echo $item->name; ?>'
			/></a><?php endforeach; ?>

</span>

<?php
defined( '_VALID_MOS' ) or die( 'Restricted Access' );
?>

<h2><?php echo $this->gallery->name; ?></h2>
<p><?php echo $this->gallery->description; ?></p>

<?php foreach( $this->gallery->kids() as $g ): ?>

	<h3><?php echo $g->name; ?></h3>
	<?php echo $g->thumbHTML; ?>
	<p><?php echo $g->description; ?></p>
<?php endforeach; ?>

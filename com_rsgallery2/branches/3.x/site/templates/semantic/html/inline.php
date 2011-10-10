<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class="rsg_sem_inl">
	<?php //if (nav_both_top_and_bottom or nav_only_top){ //MK// [todo] [make config var for location navigation]?>
		<div class="rsg_sem_inl_Nav">
	    	<?php $this->showDisplayPageNav(); ?>
		</div>
	<?php //}  ?>
	<div class="rsg_sem_inl_dispImg">
    	<?php $this->showItem(); ?>
	</div>
	<?php //if (nav_both_top_and_bottom or nav_only_bottom){ //MK// [todo] [make config var for location navigation] ?>
		<div class="rsg_sem_inl_Nav">
	    	<?php $this->showDisplayPageNav(); ?>
		</div>
	<?php //}?>
	<div class="rsg_sem_inl_ImgDetails">
    	<?php $this->showDisplayImageDetails(); ?>
	</div>
	<div class="rsg_sem_inl_footer">
    	<?php $this->showRsgFooter(); ?>
	</div>
</div>
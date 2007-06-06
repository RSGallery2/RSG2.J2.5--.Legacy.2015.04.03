<?php
/**
* This file contains the main template file for RSGallery2.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @FrogJS: http://www.dynamicdrive.com/dynamicindex4/frogjs/index.htm
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

// bring in display code
require_once( JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'semantic' . DS . 'display.class.php');
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'FrogJS';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_FrogJS();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/FrogJS";

// append to Joomla's pathway
$rsgDisplay->showRSPathWay();
require_once(JPATH_ROOT.'/includes/pageNavigation.php');
//place javascripts in header
?>
<script type = "text/javascript">
	<!-- 
	  var script1 = document.createElement('script');
	  script1.setAttribute('type', 'text/javascript');
	  script1.setAttribute('src', '<?php echo $template_dir; ?>/scripts/prototype.js');
	  var head1 = document.getElementsByTagName('head').item(0);
	  head1.appendChild(script1);
	  //-->
	</script>
	<script type = "text/javascript">
	<!-- 
	  var script2 = document.createElement('script');
	  script2.setAttribute('type', 'text/javascript');
	  script2.setAttribute('src', '<?php echo $template_dir; ?>/scripts/scriptaculous.js?load=effects');
	  var head2 = document.getElementsByTagName('head').item(0);
	  head2.appendChild(script2);
	  //-->
	</script>
	<script type = "text/javascript">
	<!-- 
	  var script3 = document.createElement('script');
	  script3.setAttribute('type', 'text/javascript');
	  script3.setAttribute('src', '<?php echo $template_dir; ?>/scripts/frog.js');
	  var head3 = document.getElementsByTagName('head').item(0);
	  head3.appendChild(script3);
	//-->
	</script>

<link href="<?php echo $template_dir; ?>/css/template.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mosConfig_live_site?>/components/com_rsgallery2/templates/semantic/css/template.css" rel="stylesheet" type="text/css" />
<div id='rsg2'>
	<?php $rsgDisplay->mainPage(); ?>
</div>
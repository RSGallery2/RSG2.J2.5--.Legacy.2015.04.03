<?php
/**
* RSGallery2 Items - Random, Latest, Popular, Most Voted
* @ package Joomla! Open Source
* @ Based on the RSitems module from Errol Elumir
* @ Modified for use with RSgallery2 by Daniel Tulp
* @ Joomla! Open Source is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ version 1.4.2
**/

defined( '_VALID_MOS' ) or die( 'Access Denied' );

//initialise init file
global $mosConfig_absolute_path, $mosConfig_live_site;
require_once($mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php');

// dimensions of flash player
$width = $params->get( 'width' );
$height = $params->get( 'height' );

// build get string
$getString = '';
$paramArray = array( 'gid', 'imageSize', 'timer', 'order', 'looping', 'fadeTime',  'xpos',  'ypos' );
foreach( $paramArray as $p )
	$getString .= "%26$p%3D" . $params->get( $p );


echo <<<EOD
<object
	class='mod_rsg2_toddFlash'
	type="application/x-shockwave-flash"
	data="$mosConfig_live_site/components/com_rsgallery2/flash/todd_dominey.swf?xmlName=/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dtodd_dominey$getString"
	width="$width" height="$height"
	align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="movie" value="$mosConfig_live_site/components/com_rsgallery2/flash/todd_dominey.swf?xmlName=/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dtodd_dominey$getString" />
	<param name="quality" value="high" />
	<param name="bgcolor" value="#ffffff" />
</object>
EOD;
?>
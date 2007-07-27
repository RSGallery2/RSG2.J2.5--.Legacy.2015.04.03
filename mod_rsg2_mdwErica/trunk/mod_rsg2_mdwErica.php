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

// gallery id to show
$gid = $params->get( 'gid' );
$imgSize = $params->get( 'imgSize' );
$showTitle = $params->get( 'showTitle' );

echo <<<EOD
<object
	type="application/x-shockwave-flash"
	data="$mosConfig_live_site/components/com_rsgallery2/flash/mdwErica.swf"
	width="474" height="334"
	align="middle">
	<param name="FlashVars" value="gid=$gid" />
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="wmode" value="transparent">
	<param name="movie" value="$mosConfig_live_site/components/com_rsgallery2/flash/mdwErica.swf" />
	<param name="quality" value="high" />
	<param name="bgcolor" value="#ffffff" />
</object>
EOD;

?>

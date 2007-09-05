<?php
/**
* This file contains the main template file for RSGallery2.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

//initialise init file
global $mosConfig_absolute_path, $mosConfig_live_site;

// gallery id to show
$gid = rsgInstance::getInt( 'gid' );
//$imgSize = $params->get( 'imgSize' );
$showTitle = rsgInstance::getBool( 'showTitle' );

$flashURL = "$mosConfig_live_site/components/com_rsgallery2/templates/mdwerica/mdwErica.swf?".
	"xmlName=".
	urlencode( "$mosConfig_live_site/index.php?option=com_rsgallery2&task=xml&xmlTemplate=mdwErica&gid=$gid" );;

echo <<<EOD
<div class="object_holder">
	<object
		type="application/x-shockwave-flash"
		data="$flashURL"
		width="490" height="334"
		align="middle">

		<param name="allowScriptAccess" value="sameDomain" />
		<param name="wmode" value="transparent">
		<param name="movie" value="$flashURL" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#ffffff" />
	</object>
</div>
EOD;
?>
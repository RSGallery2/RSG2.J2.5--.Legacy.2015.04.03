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

// bunch more parameters
$p = '';
$p .= buildParamString( 'gid' );
$p .= buildParamString( 'imageSize' );
$p .= buildParamString( 'timer' );
$p .= buildParamString( 'order' );
$p .= buildParamString( 'looping' );
$p .= buildParamString( 'fadeTime' );
$p .= buildParamString( 'xpos' );
$p .= buildParamString( 'ypos' );

function buildParamString( $name ){
	if( $params->get( $name ))
		return "%26$name%3D" . $params->get( $name );
	else
		return '';
}

switch( $params->get( 'xmlTemplate' )) {
	case 'todd':
		echo <<<EOD
<object
	type="application/x-shockwave-flash"
	data="$mosConfig_live_site/components/com_rsgallery2/flash/todd_dominey.swf?xmlName=/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dtodd_dominey$p"
	width="$width" height="$height"
	align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="movie" value="$mosConfig_live_site/components/com_rsgallery2/flash/todd_dominey.swf?xmlName=/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dtodd_dominey$p" />
	<param name="quality" value="high" />
	<param name="bgcolor" value="#ffffff" />
</object>
EOD;
	break;
}
?>
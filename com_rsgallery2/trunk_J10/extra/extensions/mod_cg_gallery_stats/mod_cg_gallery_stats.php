<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//initialise init file
global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php');

$query="SELECT COUNT(*) FROM #__rsgallery2_files";
$database->setQuery( $query );
$imagecount = $database->loadResult();




$query="SELECT COUNT(*) FROM #__rsgallery2_files WHERE (date + INTERVAL 30 DAY) >= NOW()";
$database->setQuery( $query );
$last30days = $database->loadResult();

;

$query="SELECT COUNT(*) FROM #__rsgallery2_files WHERE (date + INTERVAL 7 DAY) >= NOW()";
$database->setQuery( $query );
$last7days = $database->loadResult();

$query="SELECT COUNT(*) FROM #__rsgallery2_files WHERE (date + INTERVAL 1 DAY) >= NOW()";
$database->setQuery( $query );
$addedtoday = $database->loadResult();

echo "Image Count: <b>".$imagecount."</b><br/>";
echo "Added today: <b>".$addedtoday."</b><br/>";
echo "Added last 7 days: <b>".$last7days."</b><br/>";
echo "Added last 30 days: <b>".$last30days."</b><br/>";

?>

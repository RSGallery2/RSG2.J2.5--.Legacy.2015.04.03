<?php
/**
* Newest Images for RSGallery
* @ version 0.0.3
*
* @ Based on the RSitems module from Errol Elumir
* @ Modified for use with RSgallery2 by Daniel Tulp
* @ Stripped and modified to work with Joomla 1.5 and RSgallery 1.14.x by Chef Groovy

*************************
STILL HIGHLY EXPERIMENTAL. CHANGE GOOGLE AD BLOCK IF USE ON YOUR SITE
*************************
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//Get paramaters
 $RowsToDisplay = intval($params->get('DisplayRows', 1));
 $CodeAfterRow = intval($params->get('CodeAfterRow', 1));
 $InsertedCode = $params->get('InsertedCode', "");

//initialise init file
global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php');



// HARD CODE VARIABLES FOR NOW


$dateformat = "M j, Y";
$count =  $RowsToDisplay * 2;

//get Itemid from menutable
  $query = "SELECT id"
     . "\n FROM #__menu"
      . "\n WHERE published = 1"
     . "\n AND link = 'index.php?option=com_rsgallery2'"
      . "\n ORDER BY link"
     ;
  $database->setQuery( $query );
   $RSG2Itemidobj = $database->loadObjectList();
   if (count($RSG2Itemidobj) > 0)
       $RSG2Itemid = $RSG2Itemidobj[0]->id;

// Get list of new images
	$queryb="SELECT * FROM #__rsgallery2_files  ORDER BY date DESC LIMIT $count";
	$queryb="SELECT * FROM (SELECT * FROM #__rsgallery2_files ORDER BY `date` DESC ) AS MOSTHITS GROUP BY `gallery_id` ORDER BY `date` DESC LIMIT $count";
	$database->setQuery( $queryb );
	$rows = $database->loadObjectList();

	//error trapping:
	if(mysql_error()){
		echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing b:<br>\n$queryb\n<br>";
	}

if ( sizeof($rows) == 0)
{	
		echo( "Nothing in gallery");
}
else
{
?>


<table class="rsitems_table">
	<tr>
		<td>
	
    <!--BEGIN LIST  -->  
    
<table width="180" border="0" cellspacing="0" cellpadding="0">
    <?php
	
	$i = 0;
	for ($rownumber = 1; $rownumber <= $RowsToDisplay; $rownumber = $rownumber +1 ) {
			if( $i < sizeof($rows)) {
				$filename       = $rows[$i]->name;
				$title          = $rows[$i]->title;
				$description    = $rows[$i]->descr;
				$id             = $rows[$i]->id;
				$gid          = $rows[$i]->gallery_id;
				$date			  = $rows[$i]->date;
				
				$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id=" . $gid);
				$resultb= $database->loadObjectList();
				$result=$resultb[0];
				$catname = $result->name;
				
				
				$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id=".$gid." ORDER BY hits DESC LIMIT $count");
				?>

			<tr style="margin-bottom:10px;">
   				<td align="center"  >				
        			<a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;page=inline&amp;id=".$id); ?>">
             		<img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $title; ?>" title="<?php echo $catname  ?>" border="1" /></a>	
    			<div class="rsitems_date"><?php echo date($dateformat, strtotime($date));  ?></div>
			<br/>
              <?php } // END LEFT IMAGE?>  
			</td>
			<?php
			++$i;
			if( $i < sizeof($rows)) {
    			$filename       = $rows[$i]->name;
				$title          = $rows[$i]->title;
				$description    = $rows[$i]->descr;
				$id             = $rows[$i]->id;
				$gid          = $rows[$i]->gallery_id;
				$date			= $rows[$i]->date;
				
				$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id=" . $gid);
				$resultb= $database->loadObjectList();
				$result=$resultb[0];
				$catname = $result->name;
			 ?>               
    <td align="center">
    <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;page=inline&amp;id=".$id);?>">
<img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $title; ?>" title="<?php echo $catname  ?>" border="1" /></a>	
    <div class="rsitems_date"><?php echo date($dateformat ,strtotime($date));  ?></div>
    <br/>
    		<?php } // END RIGHT IMAGE ?>  
    </td>
  </tr>

			<?php 
			// BEGIN INSERTED CODE BLOCK
			if ($rownumber == $CodeAfterRow ) {
            ?>
            <tr>
            <td colspan="2" align="center">

            <div style="width: 180px; padding-bottom:8px; border:#FF3333 1px solid; margin-bottom:10px;">
            <?php
            echo $InsertedCode;
			?>
            </div>
            </td>
            </tr>
            <?php } // end ad block?>



<?php ++$i;
} // End for Loop ?>

</table>

<!-- END LIST -->

	</td>
	
	</tr>
</table>

<?php }?>

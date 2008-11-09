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
$sortmode = $params->get('SortModeForDisplay');
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
switch ($sortmode) {
	case "uploaddate":
		$queryb="SELECT BYDATE.name as imgname, BYDATE.title as filetitle, BYDATE.id as fileid, BYDATE.gallery_id as galleryid, BYDATE.date as filedate FROM (SELECT * FROM #__rsgallery2_files where published = 1 ORDER BY `date` DESC ) AS BYDATE GROUP BY `galleryid` ORDER BY `date` DESC LIMIT $count";		
break;
	case "leasthits":
	default:
		$queryb="SELECT * FROM (SELECT * FROM #__rsgallery2_files where published = 1 ORDER BY `hits` ASC ) AS MOSTHITS GROUP BY `gallery_id` ORDER BY `hits` ASC LIMIT $count";
		
				$queryb = "SELECT LOWHITS.id as fileid, LOWHITS.name as imgname, #__rsgallery2_galleries.name as galname, #__rsgallery2_galleries.hits AS galhits, #__rsgallery2_galleries.id AS galleryid, LOWHITS.id AS imgid FROM (SELECT * FROM #__rsgallery2_files ORDER BY `hits` ASC ) AS LOWHITS  INNER JOIN #__rsgallery2_galleries ON (LOWHITS.gallery_id=#__rsgallery2_galleries.id) GROUP BY `gallery_id` ORDER BY galhits DESC";
		
		break;
	}




	//leadt hits first

		
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
				$filename	= $rows[$i]->imgname;
				$title		= $rows[$i]->filetitle;
				$id			= $rows[$i]->fileid;
				$gid		= $rows[$i]->galleryid;
				$date		= $rows[$i]->filedate;
				
			
				$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id=" . $gid);
				$resultb= $database->loadObjectList();
				$result=$resultb[0];
				$catname = $result->name;
				
				
			//	$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id=".$gid." ORDER BY hits DESC LIMIT $count");
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
				$filename	= $rows[$i]->imgname;
				$title		= $rows[$i]->filetitle;
				$id			= $rows[$i]->fileid;
				$gid		= $rows[$i]->galleryid;
				$date		= $rows[$i]->filedate;
				
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

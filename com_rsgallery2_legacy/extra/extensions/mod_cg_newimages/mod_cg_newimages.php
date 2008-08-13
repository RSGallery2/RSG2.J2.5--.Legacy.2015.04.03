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

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//initialise init file
global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php');



// How many to display
$count = 10;
$dateformat = "M j, Y";

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



	$queryb="SELECT * FROM #__rsgallery2_files  ORDER BY date DESC LIMIT $count";
	$queryb="SELECT * FROM (SELECT * FROM #__rsgallery2_files ORDER BY `date` DESC ) AS MOSTHITS GROUP BY `gallery_id` ORDER BY `date` DESC LIMIT $count";
	$database->setQuery( $queryb );
	$rows = $database->loadObjectList();
	//$rowb=$rows[0];
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
	
	
	for ($i = 0; $i <= $count-1; $i=$i+2) {
			if( $i < sizeof($rows)) {
				$filename       = $rows[$i]->name;
				$title          = $rows[$i]->title;
				$description    = $rows[$i]->descr;
				$id             = $rows[$i]->id;
				$catid          = $rows[$i]->gallery_id;
				$date			  = $rows[$i]->date;
				
				$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id=" . $catid);
				$resultb= $database->loadObjectList();
				$result=$resultb[0];
				$catname = $result->name;
				
				
				$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id=".$catid." ORDER BY hits DESC LIMIT $count");
        	//	$countb = $database->loadResult();
			//	$limit= $countb - $limitstart_b - 1;
				
				?>

 			 <tr style="margin-bottom:10px;">
   					 <td align="center"  >				
        	<a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;page=inline&amp;id=".$id."&amp;catid=".$catid."&amp;Itemid=".$RSG2Itemid);?>">
             <img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $title; ?>" title="<?php echo $catname  ?>" border="1" /></a>	
    		<div class="rsitems_date"><?php echo date($dateformat, strtotime($date));  ?></div>
			<br/>
              <?php } ?>  
			</td>
			<?php
			if( $i+1 < sizeof($rows)) {
    			$filename       = $rows[$i+1]->name;
				$title          = $rows[$i+1]->title;
				$description    = $rows[$i+1]->descr;
				$id             = $rows[$i+1]->id;
				//$limitstart_b     = $rowdb[$i+1]->ordering - 1;
				$catid          = $rows[$i+1]->gallery_id;
				$date			  = $rows[$i+1]->date;
				
				$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id=" . $catid);
				$resultb= $database->loadObjectList();
				$result=$resultb[0];
				$catname = $result->name;
			 ?>               
    <td align="center">
    <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;page=inline&amp;id=".$id."&amp;catid=".$catid."&amp;Itemid=".$RSG2Itemid);?>">
<img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $title; ?>" title="<?php echo $catname  ?>" border="1" /></a>	
    <div class="rsitems_date"><?php echo date($dateformat ,strtotime($date));  ?></div>
    <br/>
    <?php } ?>  
    </td>
  </tr>

<?php if ($i == 2 ) {
?>
<tr>
<td colspan="2" align="center">
<div style="width: 180px; height:150px; border:#FF3333 1px solid; margin-bottom:10px;">
			<script type="text/javascript"><!--
            google_ad_client = "pub-4848754589931121";
            /* 180x150, created 5/26/08 */
            google_ad_slot = "7148097726";
            google_ad_width = 180;
            google_ad_height = 150;
            //-->
            </script>
            <script type="text/javascript"
            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
</div>
</td>
</tr>

  <?php } 
  // END FOR LOOP
  ?>  
</table>

<!-- END LIST -->

	</td>
	
	</tr>
</table>

<?php }


		
}?>
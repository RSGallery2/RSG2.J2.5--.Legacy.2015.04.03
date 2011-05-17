<?php
/**
* JCE plugin to insert {rsg2_singledisplay: imageid, size, caption, format}
* for RSGallery2 Single Image Display popup.
* @version $Id $
* @package JCE_RSGallery2_SingleImageDisplay_Insert
* @copyright Copyright (C) 2011 Mirjam Kaizer - RSGallery2 Team All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$version = "1.5.0";

// Load JCE classes
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'plugin.php' );
//require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'utils.php' );
//require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'editor.php' );

// Initialise plugin stuff
$RSG2_SingleDisplay =& JContentEditorPlugin::getInstance();
$RSG2_SingleDisplay->checkPlugin() or die( 'Restricted access' );
$params	= $RSG2_SingleDisplay->getPluginParams();					// Load Plugin Parameters
$RSG2_SingleDisplay->loadLanguages();								// Load Languages
$RSG2_SingleDisplay->script( array('tiny_mce_popup',				// Set javascript file array
						), 'tiny_mce' );
$RSG2_SingleDisplay->script( array('tiny_mce_utils',
						'mootools',
						'jce',
						'plugin',
						'window'
						) );
$RSG2_SingleDisplay->script( array( 'rsg2_singledisplay' ), 'plugins' );
$RSG2_SingleDisplay->css( array( 'rsg2_singledisplay' ), 'plugins' );			// Set css file array
$RSG2_SingleDisplay->css( array( 'plugin', 'tree' ) );
$RSG2_SingleDisplay->css( array(	'window',
						'dialog'
					), 'skins' );
$RSG2_SingleDisplay->processXHR( true );							// Process any XHR requests
$RSG2_SingleDisplay->_debug = false;
$version .= $RSG2_SingleDisplay->_debug ? ' - debug' : '';

// Initialise Joomla stuff
$database = JFactory::getDBO();
jimport( 'joomla.html.pagination' );
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $RSG2_SingleDisplay->getLanguageTag();?>" lang="<?php echo $RSG2_SingleDisplay->getLanguageTag();?>" dir="<?php echo $RSG2_SingleDisplay->getLanguageDir();?>" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>
		<?php echo JText::_('RSGallery2 Single Image Display');?> - <?php echo $version;?>
	</title>
<?php
	$RSG2_SingleDisplay->printScripts();
	$RSG2_SingleDisplay->printCss();

	echo $RSG2_SingleDisplay->printHead();
?>
</head>

<body lang="<?php echo $RSG2_SingleDisplay->getLanguage();?>" id="RSG2_SingleDisplay">
	<!-- div: table with images to select (populate image id radio list -->
	<div style="height:490px; overflow:auto;">
		<fieldset>
			<legend>RSGallery2 Single Image Display - Insert</legend>

<?php		// Initialize RSG2 core functionality
			require_once( JPATH_SITE.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );
			
			// Get user state variables for filter (search), gallery select and pagination
			$option 	= 'com_rsgallery2';
			$gallery_id	= intval( $mainframe->getUserStateFromRequest( "gallery_id{$option}", 'gallery_id', 0 ) );
			$javascript = 'onchange="document.adminForm.submit();"';
			$lists['gallery_id']	= galleryUtils::galleriesSelectList( $gallery_id, 'gallery_id', false, $javascript );
			
			$search 	= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
			$search 	= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
			$search 	= $database->getEscaped( trim( strtolower( $search ) ) );

			$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');	
			$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );

			// Initialise and make sql WHERE clause (In SELECT 'a' is #__rsgallery2_files table)
			$where = array();
			if ($gallery_id > 0) {
				$where[] = "a.gallery_id = $gallery_id";	
			}
			if ($search) {
				$where[] = "LOWER(a.title) LIKE '%$search%'";
			}

			// Get the total number of records (for pagination)
			$query = "SELECT COUNT(1)"
					. " FROM #__rsgallery2_files AS a"
					. (count( $where ) ? " WHERE " . implode( ' AND ', $where ) : "")
					;
			$database->setQuery( $query );
			$total = $database->loadResult();

			// Initialise pagination
			$pageNav = new JPagination( $total, $limitstart, $limit  );

			// Get image info from database with where clause and pagination
			$query = "SELECT a.*, cc.name AS category, u.name AS editor"
					. " FROM #__rsgallery2_files AS a"
					. " LEFT JOIN #__rsgallery2_galleries AS cc ON cc.id = a.gallery_id"
					. " LEFT JOIN #__users AS u ON u.id = a.checked_out"
					. ( count( $where ) ? " WHERE " . implode( ' AND ', $where ) : "")
					. " ORDER BY a.gallery_id, a.ordering"
					;
			$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
			$rows = $database->loadObjectList();
			
			// Start of HTML output for popup window
			?>

			<!-- The pagination buttons have the javascript "submitform()" in their onclick event, which doesn't work: "document.adminForm.submit()" does. -->
			<script type="text/javascript">
				function submitform(){
					document.adminForm.submit();
				}
			</script>

			<link href="templates/khepri/css/general.css" rel="stylesheet" type="text/css" />

			<fieldset class="adminform">
				<form action="index.php?option=com_jce&task=plugin&plugin=rsg2_singledisplay&file=rsg2_singledisplay" method="post" name="adminForm">
				<!-- Table with search and gallery filter, and pagination -->
				<table class="admintable" border="0" width="100%">
					<tr>
						<td align="left">
							<?php echo JText::_('Filter:')?>
							<input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
							<button onclick="this.form.submit();">
								<?php echo JText::_("GO"); ?>
							</button>
						</td>
						<td align="right">
							<?php echo $lists['gallery_id'];?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo $pageNav->getListFooter(); ?>
						</td>
					</tr>
				</table>
				
				<!-- Table with radio button image list -->
				<table class="adminlist">
					<thead>
						<tr>
							<th width="5">		ID		</th>
							<th width="20">		&nbsp;	</th>
							<th width="20">		&nbsp;	</th>
							<th class="title">	<?php echo JText::_('Title (filename)')?><?php echo JText::_( 'Num' ); ?></th>
							<th width="5%">		<?php echo JText::_('Published')?></th>
							<th width="15%"><?php echo JText::_('Gallery')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Loop through all the available rows
						$k = 0;
						for ($i=0, $n=count( $rows ); $i < $n; $i++) {
							$row = &$rows[$i];
							// Get image and its alt text based on being (un)published
							$img 	= $row->published ? 'publish_g.png' : 'publish_x.png';
							$alt 	= $row->published ? 'Published' : 'Unpublished';
							?>
							
							<tr class="<?php echo "row$k"; ?>">
								<td>
									<?php echo $row->id; ?>
								</td>
								<td>
									<input type="radio" name="idradio" value="<?php echo $row->id; ?>">
								</td>
								<td>
									<?php
										echo '<img src="'.JURI_SITE.$rsgConfig->get('imgPath_thumb').'/'.$row->name.'.jpg" alt="'.$row->name.'" /> ';
									?>
								</td>
								<td>
									<?php
										echo $row->title.' ('.$row->name.')';
									?>
								</td>
								<td align="center">
									<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
								</td>
								<td>
									<?php echo $row->category; ?>
								</td>
							</tr>
					</tbody>
							<?php
							$k = 1 - $k;
						}
						?>
				</table>
				</form>
			</fieldset>
		</fieldset>
	</div><!-- div: table with images to select - end -->

	<!-- Bottom part of popup window -->
	<form onsubmit="insertAction();return false;" action="#">
		<!-- ID nr, image size, caption and style -->
		<div >
			<fieldset>
				<table border="0" cellpadding="0" cellspacing="4">
					<tr>
						<td>
							<label id="id" for="id" class="hastip" title="ID: Select image in list above.">ID nr</label>
						</td>
						<td>
							Select image in list above (ID number will be used).
							<!--<input id="idfield" type="text" size="30" value="">-->
						</td>
					</tr>
					<tr>
						<td>
							<label id="Size" for="Size" class="hastip" title="Image size: RSGallery2 stores the image in three sizes: display, thumb and the original size. Which one to use here?">Image size</label>
						</td>
						<td>
							<select id="sizelist">
								<option value="display" selected="selected">Display</option>
								<option value="thumb">Thumb</option>
								<option value="original">Original</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="column1">
							<label id="Caption" for="Caption" class="hastip" title="Show caption for inserted image or not?">Caption</label>
						</td>
						<td>
							<select id="captionlist" >
								<option value="true" selected="selected"><?php echo JText::_('YES');?></option>
								<option value="false"><?php echo JText::_('No');?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label id="Style" for="Style" class="hastip" title="Image will be aligned using this style (optionally, the text in the Style field can be chosen).">Style</label>
						</td>
						<td>
							<select id="stylelist">
								<option value="">- Use Style Field -</option>
								<option value="left,float:left" selected="selected">left,float:left</option>
								<option value="right,float:right">right,float:right</option>
								<option value="center">center</option>
							</select>
							Style field <input id="stylefield" type="text" size="30" value="">
						</td>
					</tr>
				</table>
			</fieldset>
		</div><!-- ID nr, image size, caption and style - end -->

		<!-- insert and cancel buttons -->
		<div class="mceActionPanel">
			<div style="float: left">
				<input type="button" id="insert" name="insert" value="<?php echo JText::_('Insert');?>" onclick="RSG2_SingleDisplayDialog.insert();" />
			</div>
			<div style="float: right">
				<input type="button" id="cancel" name="cancel" value="<?php echo JText::_('Cancel');?>" onclick="tinyMCEPopup.close();" />
			</div>
		</div><!-- insert and cancel buttons - end -->
		
		<div id="hidden_elements">
			<input type="hidden" id="onclick" value="" />
			<input type="hidden" id="onmouseover" value="" />
		</div>
    </form>
	<!-- Bottom part of popup window - end -->
</body>
</html>
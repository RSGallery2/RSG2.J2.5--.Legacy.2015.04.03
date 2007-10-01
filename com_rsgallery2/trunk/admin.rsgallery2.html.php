<?php
/**
* This file handles the HTML processing for the Admin section of RSGallery.
*
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );


/**
* The HTML_RSGALLERY class is used to encapsulate the HTML processing for RSGallery.
* @package RSGallery2
* @todo Move this class to a seperate class file and add loose functions to it
**/
class HTML_RSGALLERY{

    
    /**
     * use to prints a message between HTML_RSGallery::RSGalleryHeader(); and a normal feature.
     * use for things like deleting an image, where a success message should be displayed and viewImages() called aferwards.
     * two css classes are used: rsg-admin-msg, rsg-admin-msg-important
     * this function replaces newlines with <br> for convienence.
     *  
     * @todo implement css classes in css file
     *  
     * @param string message to print
     * @param boolean optionally display the message as important, possibly changing the text to red or bold, etc.  as a general rule, expected results should be normal, unexpected results should be marked important.
     */
    function printAdminMsg($msg, $important=false) {
        // replace newlines with html line breaks.
        str_replace('\n', '<br>', $msg);
        
        if($important)
            echo "<p class='rsg-admin-msg'>$msg</p>";
        else
            echo "<p class='rsg-admin-msg-important message'>$msg</p>";
    }
    
    /**
      * Used by showCP to generate buttons
      * @param string URL for button link
      * @param string Image name for button image
      * @param string Text to show in button
      */
    function quickiconButton( $link, $image, $text ) {
        ?>
        <div style="float:left;">
        <div class="icon">
            <a href="<?php echo $link; ?>">
                <div class="iconimage">
                    <?php echo JHTML::_('image.site', $image, '/components/com_rsgallery2/images/', NULL , NULL , $text); ?>
                </div>
                <?php echo $text; ?></a>
        </div>
        </div>
        <?php
    }
    
    /**
     * Shows the RSGallery Control Panel in backend.
     * @todo complete translation tags
     * @todo use div in stead of tables (LOW PRIORITY)
     * @todo Move CSS to stylesheet
     */
    function showCP(){
        global $mosConfig_live_site, $rows, $rows2, $rsgConfig, $rsgVersion;

        // Get the current JUser object
		$user = &JFactory::getUser();

        //Show Warningbox if some preconditions are not met
        galleryUtils::writeWarningBox();
        ?>
        <div id="rsg2-thisform">
            <div id='rsg2-infoTabs'>
                <table width="100%">
                    <tr>
                        <td>
                        <?php
                        $tabs = new mosTabs(0);
                        $tabs->startPane( 'recent' );
                        $tabs->startTab( _RSGALLERY_TAB_GALLERIES, 'Categories' );
                        ?>
                        <table class="adminlist" width="500">
                            <tr>
                                <th colspan="3"><?php echo _RSGALLERY_MOST_RECENT_GAL; ?></th>
                            </tr>
                            <tr>
                                <td><strong><?php echo _RSGALLERY_GALLERY; ?></strong></td>
                                <td><strong><?php echo _RSGALLERY_USER; ?></strong></td>
                                <td><strong><?php echo _RSGALLERY_ID; ?></strong></td>
                            </tr>
                            <?php echo galleryUtils::latestCats();?>
                            <tr>
                                <th colspan="3">&nbsp;</th>
                            </tr>
                        </table>
                        <?php
                        $tabs->endTab();
                        $tabs->startTab( _RSGALLERY_TAB_IMAGES, 'Images' );
                        ?>
                        <table class="adminlist" width="500">
                            <tr>
                                <th colspan="4"><?php echo _RSGALLERY_MOST_RECENT_IMG; ?></th>
                            </tr>
                            <tr>
                                <td><strong><?php echo _RSGALLERY_FILENAME;?></strong></td>
                                <td><strong><?php echo _RSGALLERY_GALLERY;?></strong></td>
                                <td><strong><?php echo _RSGALLERY_DATE; ?></strong></td>
                                <td><strong><?php echo _RSGALLERY_USER; ?></strong></td>
                            </tr>
                            <?php echo galleryUtils::latestImages();?>
                            <tr>
                                <th colspan="4">&nbsp;</th>
                            </tr>
                        </table>
                        <?php
                        $tabs->endTab();
                        $tabs->startTab(_RSGALLERY_CREDITS, 'Credits' );
                        ?>

                        
<div id='rsg2-credits'>
    <h3>Core Team</h3>
    <dl>
        <dt>Architect</dt>
        	<dd><b>Jonah Braun</b> <a href='http://whalehosting.ca/' target='_blank'>Whale Hosting Inc.</a></dd>
        <dt>Lead Developers</dt>
        	<dd><b>Ronald Smit</b> <a href='http://www.rsdev.nl/' target='_blank'>RSDevelopment</a></dd>
        <dt>Developers</dt>
        	<dd><b>Dani&#235;l Tulp</b> <a href='http://design.danieltulp.nl/' target='_blank'>DT^2</a></dd>
        <dt>Community Liaison</dt>
        	<dd><b>Dani&#235;l Tulp</b> <a href='http://design.danieltulp.nl/' target='_blank'>DT^2</a></dd>
    </dl>
    
    <h3>Logo</h3>
    <dl>
        <dt>Designer</dt> <dd><b>Cory "ccdog" Webb</b> <a href='http://www.corywebb.com/' target='_blank'>CoryWebb.com</a></dd>
    </dl>

    <h3>Translations</h3>
    <dl>
        <dt>Brazilian Portuguese</dt> <dd><b>Helio Wakasugui</b></dd>
        <dt>Czech</dt> 
			<dd>David Zirhut <a href='http://www.joomlaportal.cz/'>joomlaportal.za</a></dd>
			<dd><b>Felix 'eFix' Lauda</b></dd>
        <dt>Dutch</dt>
			<dd>Tomislav Ribicic</dd>
        	<dd>Dani&#235;l Tulp <a href='http://design.danieltulp.nl' target='_blank'></a></dd>
			<dd><b>Bas</b><a href='http://www.fantasea.nl' target='_blank'>http://www.fantasea.nl</a></dd>
        <dt>French</dt><dd><b>Fabien de Silvestre</b></dd>
        <dt>German</dt> <dd><b>woelzen</b><a href='http://conseil.silvestre.fr' target='_blank'>http://conseil.silvestre.fr</a></dd>
		<dt>Greek</dt><dd><b>Charis Argyropoulos</b><a href='http://www.symenox.gr' target='_blank'>http://www.symenox.gr</a></dd>
        <dt>Hungarian</dt> 
			<dd><b>SOFT-TRANS</b> <a href='http://www.soft-trans.hu' target='_blank'>SOFT-TRANS</a></dd>
        <dt>Norwegian</dt> 
			<dd>Ronny Tjelle</dd>
            <dd><b>Steinar Vikholt</b></dd>
        <dt>Polish</dt> 
			<dd><b>Zbyszek Rosiek</b></dd>
        <dt>Russian</dt>
			<dd><b>Ragnaar</b></dd>
        <dt>Spanish</dt> 
			<dd><b>Ebävs</b> <a href='http://www.ebavs.net/' target='_blank'>ebävs.net</a></dd>
        <dt>Traditional Chinese</dt>
			<dd>Sun Yu<a href='http://www.meto.com.tw' target='_blank'>Meto</a></dd>
			<dd><b>Mike Ho</b> mikeho1980 <a href='http://www.dogneighbor.com' target='_blank'>http://www.dogneighbor.com</a></dd>
		<dt>Italian</dt><dd><b>Michele Monaco</b><a href='http://www.mayavoyage.com' target='_blank'>Maya Voyages</a>
    </dl>

    <h3>Legacy</h3>
    <dl>
        <dt>Creator</dt>
        	<dd><b>Ronald Smit</b></dd>
        <dt>RSGallery 1.x</dt>
        	<dd><b>Andy "Troozers" Stewart</b></dd>
            <dd><b>Richard Foster</b></dd>
		<dt>RSGallery2 2005</dt>
			<dd><b>Dani&#235;l Tulp</b></dd>
			<dd><b>Jonah Braun</b></dd>
			<dd><b>Tomislav Ribicic</b></dd>
		<dt>RSGallery2 2006</dt>
			<dd><b>Dani&#235;l Tulp</b></dd>
			<dd><b>Jonah Braun</b></dd>
			<dd><b>Ronald Smit</b></dd>
			<dd><b>Tomislav Ribicic</b></dd>
    </dl>
</div>
                        <?php
                        $tabs->endTab();
                        $tabs->endPane();
                        ?>
                        </td>
                            </tr>
                        </table><br />
                <table border="0" width="100%" cellspacing="1" cellpadding="1" style=background-color:#CCCCCC;>
                    <tr>
                        <td bgcolor="#FFFFFF" colspan="2">
                            <img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_rsgallery2/images/rsg2-logo.png" align="middle" alt="RSGallery logo"/>
                        </td>
                    </tr>
                    <tr>
                        <td width="120" bgcolor="#FFFFFF"><strong><?php echo _RSGALLERY_INSTALLED_VERSION;?></strong></td>
                        <td bgcolor="#FFFFFF"><a href='index2.php?option=com_rsgallery2&task=viewChangelog' title='view changelog'><?php echo $rsgVersion->getVersionOnly(); echo " (".$rsgVersion->getSVNonly().")";?></a></td>
                    </tr>
                    <tr>
                        <td bgcolor="#FFFFFF"><strong><?php echo _RSGALLERY_LICENSE?>:</strong></td>
                        <td bgcolor="#FFFFFF"><a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU GPL</a></td>
                    </tr>
                </table>
            </div>

            <div id='cpanel'>
                <?php
                if ( $user->get('gid') > 23 ):
                    $link = 'index2.php?option=com_rsgallery2&task=showConfig';
                    HTML_RSGALLERY::quickiconButton( $link, 'config.png',  _RSGALLERY_C_CONFIG );
                endif;

                $link = 'index2.php?option=com_rsgallery2&rsgOption=images&task=upload';
                HTML_RSGALLERY::quickiconButton( $link, 'upload.png', _RSGALLERY_C_UPLOAD );

                $link = 'index2.php?option=com_rsgallery2&task=batchupload';
                HTML_RSGALLERY::quickiconButton( $link, 'upload_zip.png', _RSGALLERY_C_UPLOAD_ZIP );
                
                $link = 'index2.php?option=com_rsgallery2&rsgOption=images&task=view_images';
                HTML_RSGALLERY::quickiconButton( $link, 'mediamanager.png', _RSGALLERY_C_IMAGES );

                $link = 'index2.php?option=com_rsgallery2&rsgOption=galleries';
                HTML_RSGALLERY::quickiconButton( $link, 'categories.png', _RSGALLERY_C_CATEGORIES );

                if ( $user->get('gid') > 23 ):
                    $link = 'index2.php?option=com_rsgallery2&task=consolidate_db';
                    HTML_RSGALLERY::quickiconButton( $link, 'dbrestore.png', _RSGALLERY_C_DATABASE );
    
                    $link = 'index2.php?option=com_rsgallery2&task=install&opt=migration';
                    HTML_RSGALLERY::quickiconButton( $link, 'dbrestore.png', _RSGALLERY_C_MIGRATION );
                endif;
				
				$link = 'index2.php?option=com_rsgallery2&task=edit_css';
				HTML_RSGALLERY::quickiconButton( $link, 'cssedit.png', _RSGALLERY_C_CSS_EDIT);
				
				$link = 'index2.php?option=com_rsgallery2&rsgOption=templates';
				HTML_RSGALLERY::quickiconButton( $link, 'template.png', _RSGALLERY_TEMP_MANG);

                // if debug is on, display advanced options
                if( $rsgConfig->get( 'debug' )): ?>
                <div id='rsg2-cpanelDebug'><?php echo _RSGALLERY_C_DEBUG_ON;?>
                    <?php
                    if ( $user->get('gid') > 23 ):
                        $link = 'index2.php?option=com_rsgallery2&task=purgeEverything';
                        HTML_RSGALLERY::quickiconButton( $link, 'menu.png', _RSGALLERY_C_PURGE );
    
                        $link = 'index2.php?option=com_rsgallery2&task=reallyUninstall';
                        HTML_RSGALLERY::quickiconButton( $link, 'menu.png', _RSGALLERY_C_REALLY_UNINSTALL );
        
                        $link = 'index2.php?option=com_rsgallery2&task=config_rawEdit';
                        HTML_RSGALLERY::quickiconButton( $link, 'menu.png', _RSGALLERY_C_EDIT_CONFIG );
                    endif;
                    
                    $link = 'index2.php?option=com_rsgallery2&task=config_dumpVars';
                    HTML_RSGALLERY::quickiconButton( $link, 'menu.png', _RSGALLERY_C_VIEW_CONFIG );
                    ?>
                    <div class='rsg2-clr'>&nbsp;</div>
                </div>
                <?php endif; ?>
                <div class='rsg2-clr'>&nbsp;</div>
            </div>
        </div>
        <div class='rsg2-clr'>&nbsp;</div>
        <?php
    }

	function showInstallForm( $title, $option, $p_startdir = "", $backLink="" ) {

		?>
		<script language="javascript" type="text/javascript">
		function submitbutton3(pressbutton) {
			var form = document.filename;

			// do field validation
			if (form.userfile.value == ""){
				alert( "Please select a ZIP-file" );
			} else {
				form.submit();
			}
		}
		</script>
		<form enctype="multipart/form-data" action="index2.php" method="post" name="filename">
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title;?>
			</th>
			<td align="right" nowrap="nowrap">
			<?php echo $backLink;?>
			</td>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th>
			<?php echo _RSGALLERY_INST_UPL_PCK_FILE?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo _RSGALLERY_INST_PCK_FILE?>
			<input class="text_area" name="userfile" type="file" size="70"/>
			<input type="button" class="button" value="Upload File &amp; Install" onclick="submitbutton3()" />
			</td>
		</tr>
		</table>
		<table class="content">

		</table>
		<input type="hidden" name="task" value="upload_template"/>
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		</form><br /><br />
		<?php
	}

	/**
	* @param string
	* @param string
	* @param string
	* @param string
	*/
	function showInstallMessage( $message, $title, $url ) {
		global $PHP_SELF;
		?>
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title; ?>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<td align="left">
			<strong><?php echo $message; ?></strong>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			[&nbsp;<a href="<?php echo $url;?>" style="font-size: 16px; font-weight: bold"><?php echo _RSGALLERY_INST_MES_CONTINUE?></a>&nbsp;]
			</td>
		</tr>
		</table>
		<?php
	}


    /**
     * if there are no categories and a user has requested an action that
     * requires a category, this is the error message to display
     */
    function requestCatCreation(){
		?>
		<script>
		function submitbutton(pressbutton){
            if (pressbutton != 'cancel'){
                submitform( pressbutton );
                return;
            } else {
                window.history.go(-1);
                return;
            }
        }
		</script>

        <table width="100%">
        	<tr>
        		<td width="40%">&nbsp;</td>
        		<td align="center">
			        <table width=""100%">
			        	<tr>
			        		<td><h3><?php echo _RSGALLERY_C_CAT_FIRST;?></h3></td>
			        	<tr>
			        		<td>
			        		<div id='cpanel'>
			        			<?php
			        			$link = 'index2.php?option=com_rsgallery2&rsgOption=galleries';
			        			HTML_RSGALLERY::quickiconButton( $link, 'categories.png', _RSGALLERY_C_CATEGORIES );
			        			?>
			        		</td>
			        		</div>
			        	</tr>
			        </table>
			    </td>
			    <td width="40%">&nbsp;</td>
			</tr>
		</table>
        <div class='rsg2-clr'>&nbsp;</div>
        <?php
    }

    /**
     * Inserts the HTML placed at the top of all RSGallery Admin pages.
     */
    function RSGalleryHeader($type='', $text=''){
        global $mosConfig_live_site;
        ?>
        <table class="adminheading">
          <tr>
            <td><!--<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_rsgallery2/images/rsg2-logo.png" border=0 />--></td>
            <th class='<?php echo $type; ?>'>RSGallery <?php echo $text; ?></th>
          </tr>
        </table>
        <?php
    }

    /**
     * Inserts the HTML placed at the bottom of all RSGallery Admin pages.
     */
    function RSGalleryFooter()
        {
        global $rsgVersion;
        ?>
        <div class= "rsg2-footer" align="center"><br /><br /><?php echo $rsgVersion->getShortVersion();?></div>
        <div class='rsg2-clr'>&nbsp;</div>
        <?php
        }

    /**
     * RS 29-10-2005 NEW FUNCTION
     * Inserts the HTML content for the first screen of the batch upload process.
     */
    function batch_upload($option)
        {
        global $rsgConfig, $task;
        $FTP_path = $rsgConfig->get('ftp_path');
        $size = round( ini_get('upload_max_filesize') * 1.024 );
        ?>
        <script language="javascript" type="text/javascript">
        <!--
        function submitbutton(pressbutton) {
            var form = document.adminForm;
 
            for (i=0;i<document.forms[0].batchmethod.length;i++) {
                if (document.forms[0].batchmethod[i].checked) {
                    upload_method = document.forms[0].batchmethod[i].value;
                    }
            }
            
            for (i=0;i<document.forms[0].selcat.length;i++) {
                if (document.forms[0].selcat[i].checked) {
                    selcat_method = document.forms[0].selcat[i].value;
                    }
            }
        if (pressbutton == 'controlPanel') {
        	location = "index2.php?option=com_rsgallery2";
        	return;
        }
        if (pressbutton == 'batchupload')
            {
            // do field validation
            if (upload_method == 'zip')
                {
                if (form.zip_file.value == '')
                    {
                    alert( "<?php echo _RSGALLERY_BATCH_NO_ZIP;?>" );
                    }        
               else if (form.xcat.value == '0' & selcat_method == '1')
                    {
                    alert("<?php echo _RSGALLERY_BATCH_GAL_FIRST;?>");
                    }
                else
                    {
                    form.submit();
                    }
                }
            else if (upload_method == 'ftp')
            	{
            	if (form.ftppath.value == '')
            		{
            		alert( "<?php echo _RSGALLERY_BATCH_NO_FTP;?>" );	
            		}
            	else if (form.xcat.value == '0' & selcat_method == '1')
            		{
					alert("<?php echo _RSGALLERY_BATCH_GAL_FIRST;?>");
            		}
            	else
            		{
            		form.submit();
            		}
            	}
            }
        }
        //-->
        </script>
        <form name="adminForm" action="index2.php" method="post" enctype="multipart/form-data">
        <table width="100%">
        <tr>
            <td width="300">&nbsp;</td>
            <td>
                <table class="adminform">
                <tr>
                    <th colspan="3"><font size="4"><?php echo _RSGALLERY_BATCH_STEP1;?></font></th>
                </tr>
                <tr>
                    <td width="200"><strong><?php echo _RSGALLERY_BATCH_METHOD;?></strong>
                    <?php
                    echo mosToolTip( _RSGALLERY_BATCH_METHOD_TIP, _RSGALLERY_BATCH_METHOD );
                    ?>
                    </td>
                    <td width="200">
                        <input type="radio" value="zip" name="batchmethod" CHECKED/>
                        <?php echo _RSGALLERY_BATCH_ZIPFILE; ?> :</td>
                    <td>
                        <input type="file" name="zip_file" size="20" />
                        <div style=color:#FF0000;font-weight:bold;font-size:smaller;>
                        <?php echo _RSGALLERY_BATCH_UPLOAD_LIMIT . $size ._RSGALLERY_BATCH_IN_PHPINI;?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type="radio" value="ftp" name="batchmethod" />
                        <?php echo _RSGALLERY_BATCH_FTP_PATH;?> :</td>
                    <td>

                        <input type="text" name="ftppath" value="<?php echo $FTP_path; ?>" size="30" /><?php echo mosToolTip( _RSGALLERY_BATCH_FTP_PATH_OVERL, _RSGALLERY_BATCH_FTP_PATH );//echo _RSGALLERY_BATCH_DONT_FORGET_SLASH;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;<br /></td>
                </tr>
                <tr>
                <td valign="top"><strong><?php echo _RSGALLERY_BATCH_CATEGORY;?></strong></td>
                    <td valign="top">
                        <input type="radio" name="selcat" value="1" CHECKED/>&nbsp;&nbsp;<?php echo _RSGALLERY_BATCH_YES_IMAGES_IN;?>:&nbsp;
                    </td>
                    <td valign="top">
                        <?php echo galleryUtils::galleriesSelectList( null, 'xcat', false );?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="2"><input type="radio" name="selcat" value="0" />&nbsp;&nbsp;<?php echo _RSGALLERY_BATCH_NO_SPECIFY; ?></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;<br /></td>
                </tr>
                <tr class="row1">
                    <th colspan="3">
                        <div align="center" style="visibility: hidden;">
                        <input type="button" name="something" value="<?php echo _RSGALLERY_BATCH_NEXT;?>" onClick="submitbutton('batchupload');" />
                        </div>
                        </th>
                </tr>
                </table>
            </td>
            <td width="300">&nbsp;</td>
        </tr>
        </table>
        <input type="hidden" name="uploaded" value="1" />
        <input type="hidden" name="option" value="com_rsgallery2" />
        <input type="hidden" name="task" value="batchupload" />
        <input type="hidden" name="boxchecked" value="0" />
        </form>
        <?php
        }


    /**
     * Inserts the HTML content for the second screen of the batch upload process.
     */
    function batch_upload_2( $ziplist, $extractDir )
        {
        global $mosConfig_live_site, $database;
        
        //Get variables from form
        $selcat = rsgInstance::getInt('selcat'  , null);
        $ftppath = rsgInstance::getVar('ftppath'  , null);
        $xcat = rsgInstance::getInt('xcat'  , null);
        $batchmethod = rsgInstance::getVar('batchmethod'  , null);
        ?>
                <script>
                function submitbutton(pressbutton){
                    if (pressbutton != 'cancel'){
                        submitform( pressbutton );
                        return;
                    } else {
                        window.history.go(-1);
                        return;
                    }
                }
                </script>

        <table class="adminform">
        <tr>
            <th colspan="5" class="sectionname"><font size="4"><?php echo _RSGALLERY_BATCH_STEP2;?></font></th>
        </tr>
        <form name="adminForm" method="post" action="index2.php?option=com_rsgallery2&task=save_batchupload">
        <tr>
        <?php
		
        // Initialize k (the column reference) to zero.
        $k = 0;
        $i = 0;

        foreach ($ziplist as $filename) {
        	$k++;
        	//Check if filename is dir
        	if ( is_dir(JPATH_ROOT. DS . 'media' . DS . $extractDir . DS . $filename) ) {
        		continue;
        	} else {
        		//Check if file is allowed
        		$allowed_ext = array("gif","jpg","png");
        		$ext = fileHandler::getImageType( JPATH_ROOT. DS . 'media' . DS . $extractDir . DS . $filename );
        		if ( !in_array($ext, $allowed_ext) ) {
        			continue;
        		} else {
        			$i++;
        		}
        	}
            ?>
            <td align="center" valign="top" bgcolor="#CCCCCC">
                <table class="adminform" border="0" cellspacing="1" cellpadding="1">
                    <tr>
                        <th colspan="2">&nbsp;</th>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><?php echo _RSGALLERY_BATCH_DELETE;?> #<?php echo $i - 1;?>: <input type="checkbox" name="delete[<?php echo $i - 1;?>]" value="true" /></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2"><img src="<?php echo $mosConfig_live_site. DS . "media" . DS . $extractDir . DS . $filename;?>" alt="" border="1" width="100" align="center" /></td>
                    </tr>
                    <input type="hidden" value="<?php echo $filename;?>" name="filename[]" />
                    <tr>
                        <td><?php echo _RSGALLERY_BATCH_TITLE;?></td>
                        <td>
                            <input type="text" name="ptitle[]" size="15" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo _RSGALLERY_BATCH_GAL;?></td>
                        <td><?php
                            if ($selcat == 1 && $xcat !== '0')
                                {
                                ?>
                                <input type="text" name="cat_text" value="<?php echo htmlspecialchars(stripslashes(galleryUtils::getCatnameFromId($xcat)));?>" readonly />
                                <input type="hidden" name="category[]" value="<?php echo $xcat;?>" />
                                <?php
                                }
                            else
                                {
								echo galleryUtils::galleriesSelectList( null, 'category[]', false );
                                }
                                ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo _RSGALLERY_DESCR;?></td>
                        <td><textarea cols="15" rows="2" name="descr[]"></textarea></td>
                    </tr>
                </table>
            </td>
            <?php
            if ($k == 5)
                {
                echo "</tr><tr>";
                $k = 0;
                }
            }
            ?>
            <input type="hidden" name="teller" value="<?php echo $i;?>" />
            <input type="hidden" name="extractdir" value="<?php echo $extractDir;?>" />
            <tr>
                <td colspan="5" align="center"><br /><br /><div align="center"><input type="submit" name="submit" value="<?php echo _RSGALLERY_BATCH_UPLOAD?>" /></div></td>
            </tr>
            </table></form>
        <?php
        }

    /**
     * Inserts the HTML content for the image information editing screen.
     */
    function editImage($option, $e_id, &$rows)
        {
        global $database, $option, $mosConfig_live_site, $rsgConfig;
        ?>
                <script>
                function submitbutton(pressbutton){
                    if (pressbutton != 'cancel'){
                        submitform( pressbutton );
                        return;
                    } else {
                        window.history.go(-1);
                        return;
                    }
                }
                </script>
        <form name="adminForm" method="post" action="index2.php">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                <table>
                    <tr>
                        <td class="sectionname"><?php echo _RSGALLERY_PROP_TITLE;?></td>
                    </tr>
                </table>
                <?php
                foreach($rows as $row)
                    {
                    //echo "De naam is : ".$row->fname;
                    ?>
                    <table class="adminform" border="0" width="100%">
                        <tr>
                            <td colspan="2" width="200" class="sectionname">&nbsp;</td>
                            <td rowspan="3"><img src="<?php echo imgUtils::getImgThumb($row->fname);?>" alt="<?php echo htmlspecialchars(stripslashes($row->descr), ENT_QUOTES);?>" border="1" /></td>
                        </tr>
                        <tr>
                            <td><?php echo _RSGALLERY_TITLE;?>:</td>
                            <td width="100"><input type="text" name="xtitle" size="40" value="<?php echo htmlspecialchars(stripslashes($row->title), ENT_QUOTES);?>" /></td>
                        </tr>
                        <tr>
                           <td><?php echo _RSGALLERY_FILENAME;?>:</td>
                           <td><strong><?php echo $row->fname;?></strong></td>
                        </tr>
                        <!--<tr>
                           <td><?php echo _RSGALLERY_ID;?>:</td>
                           <td><strong><?php echo $e_id;?></strong></td>
                        </tr>-->
                        <tr>
                           <td><?php echo _RSGALLERY_DESCR;?>:</td>
                           <td colspan="2"><textarea cols="30" rows="5" name="descr"><?php echo htmlspecialchars(stripslashes($row->descr));?></textarea></td>
                        </tr>
                    </table>
                    <?php
                    }
                    ?>
                    <input type="hidden" name="id" value="<?php echo $e_id; ?>" />
                    <input type="hidden" name="option" value="<?php echo $option;?>" />
                    <input type="hidden" name="task" value="" />
                </form>
                </td>
            </tr>
        </table>
        <?php
        }

     /**
     * asks user to choose a category for uploaded files to go in
     */
    function showUploadStep1(){
        ?>
        <script language="javascript" type="text/javascript">
        function submitbutton( pressbutton ) {
        var form = document.form;
        if ( pressbutton == 'controlPanel' ) {
        	location = "index2.php?option=com_rsgallery2";
        	return;
        }
        
        if ( pressbutton == 'upload' ) {
        	// do field validation
        	if (form.catid.value == "0")
            	alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_CAT; ?>" );
            else
            	form.submit();
        }
        	
  
        }
        </script>
        
        <table width="100%">
        <tr>
            <td width="300">&nbsp;</td>
            <td>
                <form name="form" action="index2.php?option=com_rsgallery2&task=upload" method="post">
                <input type="hidden" name="uploadStep" value="2" />
                <table class="adminform">
                <tr>
                    <th colspan="2"><font size="4"><?php echo _RSGALLERY_BATCH_STEP1;?></font></th>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="200">

                    <?php echo _RSGALLERY_PICK; ?>
                    </td>
                    <td>
                        <?php echo galleryUtils::galleriesSelectList(NULL, 'catid', false) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr class="row1">
                    <td colspan="2"><div style=text-align:center;></div></td>
                </tr>
                </table>
                </form>
               </td>
            <td width="300">&nbsp;</td>
       </tr>
       </table>
        <?php
    }

    /**
     * asks user to choose how many files to upload
     */
    function showUploadStep2( ){
        $catid = rsgInstance::getInt('catid', null); 
        ?>
        <table width="100%">
        <tr>
            <td width="300">&nbsp;</td>
            <td>
                <form name="form" action="index2.php?option=com_rsgallery2&task=upload" method="post">
                <input type="hidden" name="uploadStep" value="3" />
                <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
                <table class="adminform">
                <tr>
                    <th colspan="2"><font size="4"><?php echo _RSGALLERY_BATCH_STEP2;?></font></th>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="200">
                    <?php echo _RSGALLERY_UPLOAD_NUMBER ;?>
                    </td>
                    <td>
                    <?php echo mosHTML::integerSelectList( 1, 25, 1, 'numberOfUploads', 'onChange="form.submit()"', 1 ); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr class="row1">
                    <td colspan="2"><div style=text-align:center;><input type="submit" value="<?php echo _RSGALLERY_TOOL_NEXT?>"/></div></td>
                </tr>
                </table>
                </form>
               </td>
            <td width="300">&nbsp;</td>
       </tr>
       </table>
        <?php
    }

    /**
     * asks user to choose what files to upload
     */
    function showUploadStep3( ){
        $catid = rsgInstance::getInt('catid', null); 
        $uploadstep = rsgInstance::getInt('uploadstep', null); 
        $numberOfUploads = rsgInstance::getInt('numberOfUploads', null); 

        ?>
        <script language="javascript" type="text/javascript">
        function submitbutton(pressbutton) {
        var form = document.form3;
            form.submit();
        }
        </script>
        <form name="form3" action="index2.php?option=com_rsgallery2&task=upload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="uploadStep" value="4" />
        <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
        <input type="hidden" name="numberOfUploads" value="<?php echo $numberOfUploads; ?>" />
        <table width="100%">
        <tr>
            <td width="300">&nbsp;</td>
            <td>
            <table class="adminform">
            <tr>
                <th colspan="2"><font size="4"><?php echo _RSGALLERY_BATCH_STEP3;?></font></td>
            </tr>
            <?php for( $t=1; $t < ($numberOfUploads+1); $t++ ): ?>
            <tr>
                <td colspan="2">
                    <table width="100%" cellpadding="1" cellspacing="1">
                    <tr>
                        <td colspan="2"><strong><?php echo _RSGALLERY_UPLOAD_FORM_IMAGE;?><?php echo "&nbsp;".$t;?></strong></td>
                    </tr>
                    <tr>
                        <td><?php echo _RSGALLERY_CATNAME;?>:</td>
                        <td><strong><?php echo galleryUtils::getCatnameFromId($catid);?></strong></td>
                    </tr>
                    <tr>
                        <td valign="top" width="100"><?php echo _RSGALLERY_UPLOAD_FORM_TITLE." ".$t; ?>:</td>
                        <td>
                        <input name="imgTitle[]" type="text" class="inputbox" size="40" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><?php echo _RSGALLERY_UPLOAD_FORM_FILE." ".$t; ?>:</td>
                        <td>
                        <input class="inputbox" name="images[]" type="file" size="30" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><?php echo _RSGALLERY_DESCR." ".$t; ?></td>
                        <td>
                        <textarea class="inputbox" cols="35" rows="3" name="descr[]"></textarea>
                        </td>
                    </tr>
                    <tr class="row1">
                    <th colspan="2">&nbsp;</th>
                    </tr>
                    </table>
                    </td>
                    </tr>
              <?php endfor; ?>
              </table>
              </td>
                <td width="300">&nbsp;</td>
            </tr>
            </table>
</form>
        <?php
    }


   /**
    * This function generates the appropriate HTML for the "View Images" operation within
    * the Admin back-end.
    * @todo Make name in JS use the naming functions from our imgLib
    */
    function viewImages( $option, &$rows, &$clist, &$clist2, &$search, &$pageNav, &$cats, &$cat_ordering_min, &$cat_ordering_max) {
        global $mosConfig_live_site, $rsgConfig ;

        mosCommonHTML::loadOverlib();
        ?>
        <script language="Javascript">
        <!--
        function showInfo(name) {
                var src = '<?php echo $mosConfig_live_site.$rsgConfig->get('imgPath_display'); ?>/'+name+'.jpg';
                var html=name;
                html = '<br /><img border="1" src="'+src+'" name="imagelib" alt="<?php echo _RSGALLERY_NO_PREVIEW?>" width="250" />';
                return overlib(html, CAPTION, name);
        }
        //-->
        </script>
        <br />
        <form action="index2.php" method="post" name="adminForm">
        <table cellpadding="4" cellspacing="0" border="0" width="100%">
        <tr>
            <td style='width:40px;'>&nbsp;</td>
            <td>
                <input type="button" name="delete" value="<?php echo _RSGALLERY_DELETE ?>" class="button"
                   onClick="if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _RSGALLERY_SEL_TO_DEL?>'); } else if (confirm('<?php echo _RSGALLERY_SURE_DEL?>')){ submitbutton('delete_image');}" />
            </td>
            <td width="10%"></td>
            <td>
                <input type="button" name="moveto" value="<?php echo _RSGALLERY_MOVETO ?>" class="button"
                   onClick="if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _RSGALLERY_SEL_TO_MOVE?>'); } else if (document.adminForm.catmoveid.value==0) { alert('<?php echo _RSGALLERY_SEL_GAL_TO_MOVE?>'); }else if (confirm('<?php echo _RSGALLERY_SURE_MOVE?>')){ submitbutton('move_image');}" />
            </td>
            <td>
                <?php echo $clist2;?>
            </td>
            <td width="100%" class="sectionname">&nbsp;
            </td>
            <td nowrap="nowrap"><?php echo _RSGALLERY_NUMDISPLAY;?></td>
            <td>
                <?php echo $pageNav->writeLimitBox(); ?>
            </td>
            <td><?php echo _RSGALLERY_SEARCH;?>:</td>
            <td>
                <input type="text" name="search" value="<?php echo htmlspecialchars(stripslashes($search), ENT_QUOTES);?>" class="inputbox" onChange="document.adminForm.submit();" />
            </td>
            <td width="right">
                <?php echo $clist;?>
            </td>
        </tr>
        </table>

        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
            <tr>
                <th width="20">#</th>
                <th width="20">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
                </th>
                <th class="title" width="25%" nowrap="nowrap"><?php echo _RSGALLERY_IMAGENAME;?></th>
                <th width="25%" align="left" nowrap="nowrap"><?php echo _RSGALLERY_IMAGEFILE;?></th>
                <th width="15%" align="left" nowrap="nowrap"><?php echo _RSGALLERY_IMAGECAT;?></th>
                <th width="10%" nowrap="nowrap"><?php echo _RSGALLERY_IMAGEHITS;?></th>
                <th width="20%" nowrap="nowrap"><?php echo _RSGALLERY_IMAGEDATE;?></th>
                <th colspan="2"><?php echo _RSGALLERY_REORDER;?></th>
            </tr>
        <?php
        $k = 0;
        for ($i=0, $n=count( $rows ); $i < $n; $i++)
            {
            //asdbg_break();
            $row = &$rows[$i];
            ?>
            <tr class="<?php echo "row$k"; ?>">
                                <td width="20"><?php echo $i + 1 + $pageNav->limitstart;?></td>
                <td width="20">
                <input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->xid; ?>" onclick="isChecked(this.checked);" />
                </td>
                <td width="25%">
                    <a href="index2.php?option=com_rsgallery2&task=edit_image&e_id=<?php echo $row->xid;?>"
                       onmouseover="showInfo('<?php echo $row->fname;?>')" 
                       onmouseout="return nd();"><?php echo $row->title;?></a>
                </td>
                <td width="25%"><?php echo $row->fname; ?></td>
                <td width="15%"><?php echo  htmlspecialchars(stripslashes($row->name), ENT_QUOTES); ?></td>
                <td width="10%" align="center"><?php echo $row->ahits;?></td>
                <td width="20%" align="center"><?php echo $row->fdate;?></td>
                                <td>
<?php
//asdbg_break();
if ($row->fordering > $cat_ordering_min[$row->gallery_id]) { ?>
                    <a href="#reorder" onclick="return listItemTask('cb<?php echo $i;?>','images_orderup')">
                                        <img src="images/uparrow.png" width="12" height="12" border="0" alt="<?php echo _RSGALLERY_ORDER_UP?>" />
                    </a>
<?php       } else { ?>
            &nbsp;
<?php       } ?>
                </td>
                <td>
<?php       if ($row->fordering < $cat_ordering_max[$row->gallery_id]) { ?>
                    <a href="#reorder" onclick="return listItemTask('cb<?php echo $i;?>','images_orderdown')">
                    <img src="images/downarrow.png" width="12" height="12" border="0" alt="<?php echo _RSGALLERY_ORDER_DOWN?>" />
                    </a>
<?php       } else { ?>
            &nbsp;
<?php       } ?>
                </td>
<?php
                $k = 1 - $k;
                }//End for ?>
            </tr>
            <tr>
                <th align="center" colspan="9">
                    <?php echo $pageNav->writePagesLinks(); ?></th>
            </tr>
            <tr>
                <td align="center" colspan="9">
                    <?php echo $pageNav->writePagesCounter(); ?></td>
            </tr>
        </table>
        <input type="hidden" name="option" value="<?php echo $option;?>" />
        <input type="hidden" name="task" value="view_images" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="catChanged" value="0" />
        </form>

        <script language="Javascript">
        // preload all the thumb images for the overlay frame
        var image = new Array();

        function setImageLoaded(){
            this.loaded=1;
            return;
        }
        <?php
        for ($i=0, $n=count( $rows ); $i < $n; $i++){
            $row = &$rows[$i];
            echo "image[".$i."] = new Image();\n";
            echo "image[".$i."].loaded = 0;\n";
            echo "image[".$i."].onLoad = setImageLoaded();\n";
            echo "image[".$i."].src = \"".imgUtils::getImgThumb($row->name)."\";\n";
            }
        ?>
        </script>
<?php
    }
    
    
    function consolidateDbGo($db_name, $file_display, $file_original, $file_thumb, $files_total) {
    global $mosConfig_live_site, $rsgConfig;
    require_once( JPATH_RSGALLERY2_ADMIN.'/config.rsgallery2.php' );
    $file_diff = array_diff($files_total, $db_name);
    ?>
    <script language="Javascript">
    function db_create() {
    	var form = document.adminForm;
			form.submit();
    }
    </script>
    <form method="post" action="index2.php?option=com_rsgallery2&task=db_create" name="adminForm">
    
    <table width="100%" border="0">
    	<tr>
    	<td width="15%">&nbsp;</td>
    	<td width="70%">
		    <table class="adminlist" border="1">
		    <tr>
		    	<td colspan="9" align="center">
			    	<div style="clear: both; margin: 3px; margin-top: 10px; padding: 5px 15px; display: block; float: left; border: 1px solid #cc0000; background: #ffffcc; text-align: left; width: 80%;">
	    				<p style="color: #CC0000;">
	    				<img src="<?php echo $mosConfig_live_site;?>/includes/js/ThemeOffice/warning.png" alt="Warning icon" />
						<?php echo _RSGALLERY_CONSDB_NOTICE?>
						</p>
					</div>
					<div class='rsg2-clr'>&nbsp;</div>
		    	</td>
		    </tr>
		    <tr>
		    	<th>#</th>
		        <th><?php echo _RSGALLERY_FILENAME;?></th>
		        <th align="center"><?php echo _RSGALLERY_CONSDB_IN_DB;?></th>
		        <th align="center"><?php echo _RSGALLERY_CONSDB_DISP;?></th>
		        <th align="center"><?php echo _RSGALLERY_CONSDB_ORIG;?></th>
		        <th align="center"><?php echo _RSGALLERY_CONSDB_THUMB;?></th>
		        <th>&nbsp;</th>
		        <th>Image</th>
		        <th align="center"><?php echo _RSGALLERY_CONSDB_ACT;?></th>
		    </tr>
		    <tr>
		        <td colspan="9">&nbsp;</td>
		    </tr>
		    <?php
		    $yes    = "<td align=\"center\"><img src=\"$mosConfig_live_site/images/tick.png\" alt=\""._RSGALLERY_CONSDB_IMG_IN_FLDR."\" border=\"0\"></td>";
		    $no     = "<td align=\"center\"><img src=\"$mosConfig_live_site/images/publish_x.png\" alt=\""._RSGALLERY_CONSDB_IMG__NOT_IN_FLDR."\" border=\"0\"></td>";
		    $z = 0;
		    $c = 0;
		    //Check database and crossreference against filesystem
		    foreach ($db_name as $name)
		        {
		        $c++;
		        $i = 0;
		        $fid = galleryUtils::getFileIdFromName($name);
		        $html = "<tr><td><input type=\"checkbox\" id=\"cb$c\" name=\"xid[]\" value=\"$name\" onclick=\"isChecked(this.checked);\" /></td><td>$name</td>".$yes;
		        if (in_array($name, $file_display )) {
		            $i++;
		            $html .= $yes;
		            $display = true;
		        } else {
		            $z++;
		            $html .= $no;
		            $display = false;
				}
				
		        if (in_array($name, $file_original )) {
		            $i++;
		            $html .= $yes;
		            $original = true; 
		        } else {
		            $z++;
		            $html .= $no;
		            $original = false;
				}
				
		        if (in_array($name, $file_thumb )) {
		            $i++;
		            $html .= $yes;
		            $thumb = true;
				} else {
		            $z++;
		            $html .= $no;
		            $thumb = false;
				}
				
		        if ($i < 3) {
		            echo $html;
		            ?>
		            <td>&nbsp;</td>
		            <td>
		            	<img src="<?php echo imgUtils::getImgThumb( $name );?>" name="image" width="<?php echo $rsgConfig->get('thumb_width')?>" alt="<?php echo $name;?>"/>
		            </td>
		            <td align="center">
		                <a href="index2.php?option=com_rsgallery2&task=c_delete&cid=<?php echo $fid;?>"><?php echo _RSGALLERY_CONSDB_DELETE_DB?></a><br />
		                <?php
		                if ($original == true OR $display == true) {
		                    ?>
		                    <a href="index2.php?option=com_rsgallery2&task=c_create&id=<?php echo $fid;?>"><?php echo _RSGALLERY_CONSDB_CREATE_IMG?></a>
		                    <?php
		                    }
		                    ?>
		            </td></tr>
		            <?php
		        } else {
		            continue;
				}
			}
		    ?>
		    </tr>
		    
		    <?php
		    $zz = 0;
		    $t = 0;
		    //Check filesystem and crossreference against database
		    foreach ($file_diff as $diff) {
		        $t++;
		        $y = 0;
		        
		        $html2 = "<tr><td><input type=\"checkbox\" id=\"cb$t\" name=\"xid[]\" value=\"$diff\" onclick=\"isChecked(this.checked);\" /></td><td><font color=\"#FF0000\">$diff</font></td>$no";
		        if (in_array($diff, $file_display ))
		            {
		            $y++;
		            $html2 .= $yes;
		            $display2 = true;
		            }
		        else
		            {
		            $zz++;
		            $html2 .= $no;
		            $display2 = false;
		            }
		        if (in_array($diff, $file_original ))
		            {
		            $y++;
		            $html2 .= $yes;
		            $original2 = true;
		            }
		        else
		            {
		            $zz++;
		            $html2 .= $no;
		            $original2 = false;
		            }
		        if (in_array($diff, $file_thumb ))
		            {
		            $y++;
		            $html2 .= $yes;
		            $thumb2 = true;
		            }
		        else
		            {
		            $zz++;
		            $html2 .= $no;
		            $thumb2 = false;
		            }
		        if ($y < 4)
		            {
		            echo $html2;
		            ?>
		            <td>
		            	<?php echo galleryUtils::galleriesSelectList(NULL,'gallery_id[]', false, false);?>
		            	<input type="hidden" name="name[]" value="<?php echo $diff;?>" />
		            </td>
		            <td>
		            	<img src="<?php echo imgUtils::getImgThumb( $diff );?>" name="image" width="<?php echo $rsgConfig->get('thumb_width')?>" />
		            </td>
		            <td align="center">
		                <a href="javascript:void();" onClick="javascript:db_create();"><?php echo _RSGALLERY_CONSDB_CREATE_DB?></a><br />
		                <a href="index2.php?option=com_rsgallery2&task=c_delete&name=<?php echo $diff;?>"><?php echo _RSGALLERY_CONSDB_DELETE_IMG?></a>&nbsp;
		                <?php
		                if ($original2 == true AND $display2 == true AND $thumb2 == true)
		                    {
		                    continue;
		                    }
		                else
		                    {
		                    ?>
		                    <br /><a href="index2.php?option=com_rsgallery2&task=c_create&name=<?php echo $diff;?>"><?php echo _RSGALLERY_CONSDB_CREATE_IMG?></a>
		                    <?php
		                    }
		                    ?>
		            </td>
		            <?php
		            }
		        else
		            {
		            continue;
		            }
		        }
		        if ($t == 0 AND $z == 0)
		        	echo "<tr><td colspan=\"8\"><font color=\"#008000\"><strong>"._RSGALLERY_CONSDB_NO_INCOS."</strong></font></td>";
		    ?>
		    </tr>
		    <tr>
		        <th colspan="9" align="center">
		        <a href="index2.php?option=com_rsgallery2&task=consolidate_db_go">
		        <input type="button" value="<?php echo _RSGALLERY_REFRESH?>" onclick="location=index2.php?option=com_rsgallery2&task=consolidate_db_go">
		        </a>
		        </th>
		    </tr>
		    <!--
		    <tr>
		    	<td colspan="2"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo (count( $db_name ) + count( $file_diff )); ?>);" /></td>
		    	<td colspan="5"> With selection:<br /> 
		    		<a href="javascript:void();" onClick="javascript:alert('<?php echo _RSGALLERY_NOT_WORKING ?>');"><?php echo _RSGALLERY_DEL_FROM_SYSTEM ?></a>&nbsp;|&nbsp; 
		    		<a href="javascript:void();" onClick="javascript:alert('<?php echo _RSGALLERY_NOT_WORKING ?>');"><?php echo _RSGALLERY_CREATE_MISSING_IMG?></a>&nbsp;|&nbsp;
		    		<a href="javascript:void();" onClick="javascript:alert('<?php echo _RSGALLERY_NOT_WORKING ?>');"><?php echo _RSGALLERY_CREATE_DB_ENTRIES?></a>
		    	</td>
		
		    </tr>
		    -->
		    </table>
    </td>
    <td width="15%">&nbsp;</td>
    </tr>
    </table>
    </form>
    <?php
    }

}//end class
?>

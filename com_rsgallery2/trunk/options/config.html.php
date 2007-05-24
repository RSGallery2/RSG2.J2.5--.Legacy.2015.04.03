<?php
/**
* Galleries option for RSGallery2 - HTML display code
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

/**
 * Explain what this class does
 * @package RSGallery2
 */
class html_rsg2_config{
    /**
     * raw configuration editor, debug only
     */
    function config_rawEdit(){
        global $rsgConfig, $option;
        $config = get_object_vars( $rsgConfig );

        ?>
        <form action="index2.php" method="post" name="adminForm" id="adminForm">
        <table id='rsg2-config_rawEdit' align='left'>
        <?php foreach( $config as $name => $value ): ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td><input type='text' name='<?php echo $name; ?>' value='<?php echo $value; ?>'></td>
            </tr>
            
        <?php endforeach; ?>
        </table>
        <input type="hidden" name="option" value="<?php echo $option;?>" />
        <input type="hidden" name="task" value="config_rawEdit_save" />
        </form>
        <?php
    }
    
    /**
     * Shows the configuration page.
     * @todo get rid of patTemplate!!!
    **/
    function showconfig( &$lists ){
        global $rsgConfig;
        $config = $rsgConfig;
        
        // import the body of the page
        $tmpl =& HTML_RSGALLERY::createTemplate();

        // i think this would be more proper:
        // $tmpl->setAttribute( 'body', 'src', 'config.html' );
        // but doing this instead:
        $tmpl->readTemplatesFromInput( 'config.html' );

        // build array containing template vars
        $templateVariables = (array)$config;

        //load language definition constants into variables
        $templateVariables['_RSGALLERY_C_TMPL_VERSION'] = _RSGALLERY_C_TMPL_VERSION;
        $templateVariables['_RSGALLERY_C_TMPL_INTRO_TEXT'] = _RSGALLERY_C_TMPL_INTRO_TEXT;
        $templateVariables['_RSGALLERY_C_TMPL_DEBUG'] = _RSGALLERY_C_TMPL_DEBUG;
        $templateVariables['_RSGALLERY_C_TMPL_IMG_MANIP'] = _RSGALLERY_C_TMPL_IMG_MANIP;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_WIDTH'] = _RSGALLERY_C_TMPL_DISP_WIDTH;
        $templateVariables['_RSGALLERY_C_TMPL_THUMB_WIDTH'] = _RSGALLERY_C_TMPL_THUMB_WIDTH;
        $templateVariables['_RSGALLERY_C_TMPL_THUMBNAIL_STYLE'] = _RSGALLERY_C_TMPL_THUMBNAIL_STYLE;
        $templateVariables['_RSGALLERY_C_TMPL_JPEG_QUALITY'] = _RSGALLERY_C_TMPL_JPEG_QUALITY;
        $templateVariables['_RSGALLERY_C_TMPL_GRAPH_LIB'] = _RSGALLERY_C_TMPL_GRAPH_LIB;
        $templateVariables['_RSGALLERY_C_TMPL_NOTE_GLIB_PATH'] = _RSGALLERY_C_TMPL_NOTE_GLIB_PATH;
        $templateVariables['_RSGALLERY_C_TMPL_IMGMAGICK_PATH'] = _RSGALLERY_C_TMPL_IMGMAGICK_PATH;
        $templateVariables['_RSGALLERY_C_TMPL_NETPBM_PATH'] = _RSGALLERY_C_TMPL_NETPBM_PATH;
        $templateVariables['_RSGALLERY_C_TMPL_FTP_PATH'] = _RSGALLERY_C_TMPL_FTP_PATH;
        $templateVariables['_RSGALLERY_C_TMPL_IMG_STORAGE'] = _RSGALLERY_C_TMPL_IMG_STORAGE;
        $templateVariables['_RSGALLERY_C_TMPL_KEEP_ORIG'] = _RSGALLERY_C_TMPL_KEEP_ORIG;
        $templateVariables['_RSGALLERY_C_TMPL_ORIG_PATH'] = _RSGALLERY_C_TMPL_ORIG_PATH;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_PATH'] = _RSGALLERY_C_TMPL_DISP_PATH;
        $templateVariables['_RSGALLERY_C_TMPL_THUMB_PATH'] = _RSGALLERY_C_TMPL_THUMB_PATH;
        $templateVariables['_RSGALLERY_C_TMPL_CREATE_DIR'] = _RSGALLERY_C_TMPL_CREATE_DIR;
        $templateVariables['_RSGALLERY_C_TMPL_FRONT_PAGE'] = _RSGALLERY_C_TMPL_FRONT_PAGE;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_RAND'] = _RSGALLERY_C_TMPL_DISP_RAND;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_LATEST'] = _RSGALLERY_C_TMPL_DISP_LATEST;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_BRAND'] = _RSGALLERY_C_TMPL_DISP_BRAND;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_DOWN'] = _RSGALLERY_C_TMPL_DISP_DOWN;
        $templateVariables['_RSGALLERY_C_TMPL_WATERMARK'] = _RSGALLERY_C_TMPL_WATERMARK;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_WTRMRK'] = _RSGALLERY_C_TMPL_DISP_WTRMRK;
        $templateVariables['_RSGALLERY_C_TMPL_WTRMRK_TEXT'] = _RSGALLERY_C_TMPL_WTRMRK_TEXT;
        $templateVariables['_RSGALLERY_C_TMPL_WTRMRK_FONTSIZE'] = _RSGALLERY_C_TMPL_WTRMRK_FONTSIZE;
        $templateVariables['_RSGALLERY_C_TMPL_WTRMRK_ANGLE'] = _RSGALLERY_C_TMPL_WTRMRK_ANGLE;
        $templateVariables['_RSGALLERY_C_TMPL_WTRMRK_POS'] = _RSGALLERY_C_TMPL_WTRMRK_POS;
        $templateVariables['_RSGALLERY_C_TMPL_GAL_VIEW'] = _RSGALLERY_C_TMPL_GAL_VIEW;
        $templateVariables['_RSGALLERY_C_TMPL_THUMB_STYLE'] = _RSGALLERY_C_TMPL_THUMB_STYLE;
        $templateVariables['_RSGALLERY_C_TMPL_FLOATDIRECTION'] = _RSGALLERY_C_TMPL_FLOATDIRECTION;
        $templateVariables['_RSGALLERY_C_TMPL_COLS_PERPAGE'] = _RSGALLERY_C_TMPL_COLS_PERPAGE;
        $templateVariables['_RSGALLERY_C_TMPL_THUMBS_PERPAGE'] = _RSGALLERY_C_TMPL_THUMBS_PERPAGE;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_SLIDE'] = _RSGALLERY_C_TMPL_DISP_SLIDE;
        $templateVariables['_RSGALLERY_C_TMPL_IMG_DISP'] = _RSGALLERY_C_TMPL_IMG_DISP;
        $templateVariables['_RSGALLERY_C_TMPL_RESIZE_OPT'] = _RSGALLERY_C_TMPL_RESIZE_OPT;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_DESCR'] = _RSGALLERY_C_TMPL_DISP_DESCR;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_HITS'] = _RSGALLERY_C_TMPL_DISP_HITS;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_VOTE'] = _RSGALLERY_C_TMPL_DISP_VOTE;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_COMM'] = _RSGALLERY_C_TMPL_DISP_COMM;
        $templateVariables['_RSGALLERY_C_TMPL_DISP_EXIF'] = _RSGALLERY_C_TMPL_DISP_EXIF;
        $templateVariables['_RSGALLERY_C_TMPL_ENABLE_U_UP'] = _RSGALLERY_C_TMPL_ENABLE_U_UP;
        $templateVariables['_RSGALLERY_C_TMPL_ONLY_REGISTERED'] = _RSGALLERY_C_TMPL_ONLY_REGISTERED;
        $templateVariables['_RSGALLERY_C_TMPL_U_CREATE_GAL'] = _RSGALLERY_C_TMPL_U_CREATE_GAL;
        $templateVariables['_RSGALLERY_C_TMPL_U_MAX_GAL'] = _RSGALLERY_C_TMPL_U_MAX_GAL;
        $templateVariables['_RSGALLERY_C_TMPL_U_MAX_IMG'] = _RSGALLERY_C_TMPL_U_MAX_IMG;
        $templateVariables['_RSGALLERY_CONF_POPUP_STYLE'] = _RSGALLERY_CONF_POPUP_STYLE;
        $templateVariables['_RSGALLERY_C_TMPL_SHOW_IMGNAME'] = _RSGALLERY_C_TMPL_SHOW_IMGNAME;
        $templateVariables['_RSGALLERY_C_TMPL_ACL_SETINGS'] = _RSGALLERY_C_TMPL_ACL_SETINGS;
        $templateVariables['_RSGALLERY_C_TMPL_ACL_ENABLE'] = _RSGALLERY_C_TMPL_ACL_ENABLE;
        $templateVariables['_RSGALLERY_C_TMPL_SHOW_MYGAL'] = _RSGALLERY_C_TMPL_SHOW_MYGAL;
        $templateVariables['_RSGALLERY_C_TMPL_USER_SET'] = _RSGALLERY_C_TMPL_USER_SET;
        $templateVariables['_RSGALLERY_C_DISP_STATUS_ICON'] = _RSGALLERY_C_DISP_STATUS_ICON;
        $templateVariables['_RSGALLERY_C_GEN_SET'] = _RSGALLERY_C_GEN_SET;
        $templateVariables['_RSGALLERY_C_HTML_ROOT'] = _RSGALLERY_C_HTML_ROOT;
        $templateVariables['_RSGALLERY_C_DISP_LIMIB'] = _RSGALLERY_C_DISP_LIMIB;
        $templateVariables['_RSGALLERY_C_NUMB_GAL_FRONT'] = _RSGALLERY_C_NUMB_GAL_FRONT;
        $templateVariables['_RSGALLERY_C_FONT'] = _RSGALLERY_C_FONT;
        $templateVariables['_RSGALLERY_C_WATER_TRANS'] = _RSGALLERY_C_WATER_TRANS;
        $templateVariables['_RSGALLERY_C_ALLOWED_FILE'] =_RSGALLERY_C_ALLOWED_FILE;
        
        //General
        $templateVariables['debug'] = mosHTML::yesnoRadioList('debug', '', $config->debug);
        $templateVariables['allowed_file_types'] = $rsgConfig->get('allowedFileTypes');

        // images
        $templateVariables['keepOriginalImage'] = mosHTML::yesnoRadioList('keepOriginalImage', '', $config->keepOriginalImage);
        $templateVariables['graphicsLib'] = $lists['graphicsLib'];
        $templateVariables['createImgDirs'] = mosHTML::yesnoRadioList('createImgDirs', '', $config->createImgDirs);
        
        $templateVariables['display_thumbs_colsPerPage'] = mosHTML::integerSelectList(1, 19, 1, 'display_thumbs_colsPerPage', '', $config->display_thumbs_colsPerPage);
        
        // front display
        $templateVariables['displayRandom'] = mosHTML::yesnoRadioList('displayRandom', '', $config->displayRandom);
        $templateVariables['displayLatest'] = mosHTML::yesnoRadioList('displayLatest', '', $config->displayLatest);
        $templateVariables['displayBranding'] = mosHTML::yesnoRadioList('displayBranding','', $config->displayBranding);
        $templateVariables['displayDownload'] = mosHTML::yesnoRadioList('displayDownload','', $config->displayDownload);
        $templateVariables['displayStatus'] = mosHTML::yesnoRadioList('displayStatus', '', $config->displayStatus);

        $display_thumbs_style[] = mosHTML::makeOption('table',_RSGALLERY_CONF_OPTION_TABLE);
        $display_thumbs_style[] = mosHTML::makeOption('float',_RSGALLERY_CONF_OPTION_FLOAT);
        $display_thumbs_style[] = mosHTML::makeOption('magic',_RSGALLERY_CONF_OPTION_MAGIC);
        $templateVariables['display_thumbs_style'] 
            = mosHTML::selectList( $display_thumbs_style, 'display_thumbs_style', '', 'value', 'text', $config->display_thumbs_style );
        $display_thumbs_floatDirection[] = mosHTML::makeOption('left',_RSGALLERY_CONF_OPTION_L2R);
        $display_thumbs_floatDirection[] = mosHTML::makeOption('right',_RSGALLERY_CONF_OPTION_R2L);
        $templateVariables['display_thumbs_floatDirection'] 
            = mosHTML::selectList( $display_thumbs_floatDirection, 'display_thumbs_floatDirection', '', 'value', 'text', $config->display_thumbs_floatDirection );
        $templateVariables['display_thumbs_showImgName'] = mosHTML::yesnoRadioList( 'display_thumbs_showImgName','', $config->display_thumbs_showImgName );
        
        $thumb_style[] = mosHTML::makeOption('0',_RSGALLERY_CONF_OPTION_PROP);
        $thumb_style[] = mosHTML::makeOption('1',_RSGALLERY_CONF_OPTION_SQUARE);
        $templateVariables['thumb_style'] 
            = mosHTML::selectList( $thumb_style, 'thumb_style', '', 'value', 'text', $config->thumb_style );
        
        $templateVariables['displayDesc'] = mosHTML::yesnoRadioList('displayDesc', '', $config->displayDesc);
        $templateVariables['displayHits'] = mosHTML::yesnoRadioList('displayHits', '', $config->displayHits);
        $templateVariables['displayVoting'] = mosHTML::yesnoRadioList('displayVoting', '', $config->displayVoting);
        $templateVariables['displayEXIF'] = mosHTML::yesnoRadioList('displayEXIF', '', $config->displayEXIF);
        $templateVariables['displaySlideshow'] = mosHTML::yesnoRadioList('displaySlideshow', '', $config->displaySlideshow);
        $templateVariables['displayComments'] = mosHTML::yesnoRadioList('displayComments', '', $config->displayComments);
        $resizeOptions[] = mosHTML::makeOption('0',_RSGALLERY_CONF_OPTION_DEFAULT_SIZE);
        $resizeOptions[] = mosHTML::makeOption('1',_RSGALLERY_CONF_OPTION_REZ_LARGE);
        $resizeOptions[] = mosHTML::makeOption('2',_RSGALLERY_CONF_OPTION_REZ_SMALL);
        $resizeOptions[] = mosHTML::makeOption('3',_RSGALLERY_CONF_OPTION_REZ_2FIT);
        $templateVariables['display_img_dynamicResize'] 
            = mosHTML::selectList( $resizeOptions, 'display_img_dynamicResize', '', 'value', 'text', $config->display_img_dynamicResize );
        $displayPopup[] = mosHTML::makeOption('0',_RSGALLERY_CONF_POPUP_NO);
        $displayPopup[] = mosHTML::makeOption('1',_RSGALLERY_CONF_POPUP_NORMAL);
        $displayPopup[] = mosHTML::makeOption('2',_RSGALLERY_CONF_POPUP_FANCY);
        $templateVariables['displayPopup'] 
            = mosHTML::selectList( $displayPopup, 'displayPopup', '', 'value', 'text', $config->displayPopup );
        
        // user uploads
        $templateVariables['show_mygalleries'] = mosHTML::yesnoRadioList('show_mygalleries', '', $config->show_mygalleries);
        $templateVariables['uu_enabled'] = mosHTML::yesnoRadioList('uu_enabled', '', $config->uu_enabled);
        //$templateVariables['uu_registeredOnly'] = mosHTML::yesnoRadioList('uu_registeredOnly', '', $config->uu_registeredOnly);
        $templateVariables['uu_createCat'] = mosHTML::yesnoRadioList('uu_createCat', '', $config->uu_createCat);
        $templateVariables['acl_enabled'] = mosHTML::yesnoRadioList('acl_enabled', '', $config->acl_enabled);
        
        //Number of galleries dropdown field
        $dispLimitbox[] = mosHTML::makeOption('0',_RSGALLERY_CONF_LIMIT_NEV);
        $dispLimitbox[] = mosHTML::makeOption('1',_RSGALLERY_CONF_LIMIT_LIMIT);
        $dispLimitbox[] = mosHTML::makeOption('2',_RSGALLERY_CONF_LIMIT_ALW);
        $templateVariables['dispLimitbox'] = mosHTML::selectList($dispLimitbox, 'dispLimitbox','','value', 'text', $config->dispLimitbox);
        $galcountNrs[] = mosHTML::makeOption('5','5');
        $galcountNrs[] = mosHTML::makeOption('10','10');
        $galcountNrs[] = mosHTML::makeOption('15','15');
        $galcountNrs[] = mosHTML::makeOption('20','20');
        $galcountNrs[] = mosHTML::makeOption('25','25');
        $galcountNrs[] = mosHTML::makeOption('30','30');
        $galcountNrs[] = mosHTML::makeOption('50','50');
        $templateVariables['galcountNrs'] = mosHTML::selectList($galcountNrs, 'galcountNrs','','value', 'text', $config->galcountNrs);
        // watermark
        $templateVariables['watermark'] = mosHTML::yesnoRadioList('watermark','', $config->watermark);
        $watermarkAngles[] = mosHTML::makeOption('0','0');
        $watermarkAngles[] = mosHTML::makeOption('45','45');
        $watermarkAngles[] = mosHTML::makeOption('90','90');
        $watermarkAngles[] = mosHTML::makeOption('135','135');
        $watermarkAngles[] = mosHTML::makeOption('180','180');
        $templateVariables['watermark_angle'] = mosHTML::selectList($watermarkAngles, 'watermark_angle','','value', 'text', $config->watermark_angle);
        $watermarkPosition[] = mosHTML::makeOption('1',_RSGALLERY_CONF_OPTION_TL);
        $watermarkPosition[] = mosHTML::makeOption('2',_RSGALLERY_CONF_OPTION_TC);
        $watermarkPosition[] = mosHTML::makeOption('3',_RSGALLERY_CONF_OPTION_TR);
        $watermarkPosition[] = mosHTML::makeOption('4',_RSGALLERY_CONF_OPTION_L);
        $watermarkPosition[] = mosHTML::makeOption('5',_RSGALLERY_CONF_OPTION_C);
        $watermarkPosition[] = mosHTML::makeOption('6',_RSGALLERY_CONF_OPTION_R);
        $watermarkPosition[] = mosHTML::makeOption('7',_RSGALLERY_CONF_OPTION_BL);
        $watermarkPosition[] = mosHTML::makeOption('8',_RSGALLERY_CONF_OPTION_BC);
        $watermarkPosition[] = mosHTML::makeOption('9',_RSGALLERY_CONF_OPTION_BR);
        $templateVariables['watermark_position'] = mosHTML::selectList($watermarkPosition, 'watermark_position','','value', 'text', $config->watermark_position);
        $watermarkFontSize[] = mosHTML::makeOption('5','5');
        $watermarkFontSize[] = mosHTML::makeOption('6','6');
        $watermarkFontSize[] = mosHTML::makeOption('7','7');
        $watermarkFontSize[] = mosHTML::makeOption('8','8');
        $watermarkFontSize[] = mosHTML::makeOption('9','9');
        $watermarkFontSize[] = mosHTML::makeOption('10','10');
        $watermarkFontSize[] = mosHTML::makeOption('11','11');
        $watermarkFontSize[] = mosHTML::makeOption('12','12');
        $watermarkFontSize[] = mosHTML::makeOption('13','13');
        $watermarkFontSize[] = mosHTML::makeOption('14','14');
        $watermarkFontSize[] = mosHTML::makeOption('15','15');
        $watermarkFontSize[] = mosHTML::makeOption('16','16');
        $watermarkFontSize[] = mosHTML::makeOption('17','17');
        $watermarkFontSize[] = mosHTML::makeOption('18','18');
        $watermarkFontSize[] = mosHTML::makeOption('19','19');
        $watermarkFontSize[] = mosHTML::makeOption('20','20');
        $watermarkFontSize[] = mosHTML::makeOption('22','22');
        $watermarkFontSize[] = mosHTML::makeOption('24','24');
        $watermarkFontSize[] = mosHTML::makeOption('26','26');
        $watermarkFontSize[] = mosHTML::makeOption('28','28');
        $watermarkFontSize[] = mosHTML::makeOption('30','30');
        $watermarkFontSize[] = mosHTML::makeOption('36','36');
        $watermarkFontSize[] = mosHTML::makeOption('40','40');
        $templateVariables['watermark_font_size'] = mosHTML::selectList($watermarkFontSize, 'watermark_font_size','','value', 'text', $config->watermark_font_size);
        $watermarkTransparency[] = mosHTML::makeOption('0','0');
        $watermarkTransparency[] = mosHTML::makeOption('10','10');
        $watermarkTransparency[] = mosHTML::makeOption('20','20');
        $watermarkTransparency[] = mosHTML::makeOption('30','30');
        $watermarkTransparency[] = mosHTML::makeOption('40','40');
        $watermarkTransparency[] = mosHTML::makeOption('50','50');
        $watermarkTransparency[] = mosHTML::makeOption('60','60');
        $watermarkTransparency[] = mosHTML::makeOption('70','70');
        $watermarkTransparency[] = mosHTML::makeOption('80','80');
        $watermarkTransparency[] = mosHTML::makeOption('90','90');
        $watermarkTransparency[] = mosHTML::makeOption('100','100');
        $templateVariables['watermark_transparency'] = mosHTML::selectList($watermarkTransparency, 'watermark_transparency','','value', 'text', $config->watermark_transparency);

        $watermarkType[] = mosHTML::makeOption('image','Image');
        $watermarkType[] = mosHTML::makeOption('text','Text');
        $templateVariables['watermark_type'] = mosHTML::selectList($watermarkType, 'watermark_type','','value', 'text', $config->watermark_type);

        $templateVariables['watermark_upload'] = "An upload option should appear here";
        $templateVariables['watermark_font'] = galleryUtils::showFontList();
        /**
         * Routine checks if Freetype library is compiled with GD2
         * @return boolean True or False
         */
        if (function_exists('gd_info'))
            {
            $gd_info = gd_info();
            $freetype = $gd_info['FreeType Support'];
            if ($freetype == 1)
                $freeTypeSupport = "<div style=\"color:#009933;\">". _RSGALLERY_FREETYPE_INSTALLED. "</div>";
            else
                $freeTypeSupport = "<div style=\"color:#FF0000;\">". _RSGALLERY_FREETYPE_NOTINSTALLED."</div>";
            }
        $templateVariables['freetype_support'] = $freeTypeSupport;
        $templateVariables['root'] = $_SERVER['DOCUMENT_ROOT'];
        // binding vars to template(s)
        $tmpl->addVars('configTableGeneral', $templateVariables);
        $tmpl->addVars('configTableImages', $templateVariables);
        $tmpl->addVars('configTableFrontDisplay', $templateVariables);
        $tmpl->addVars('configTableUsers', $templateVariables);

        // in page.html joomla has a template for $tabs->func*()
        // couldn't figure out how to use it effectively however.
        // this is why the templates were broken up, so i could call $tabs->func*() between them
        $tabs = new mosTabs(1);
        $tmpl->displayParsedTemplate( 'configStart' );
        $tabs->startPane( 'rsgConfig' );

        $tabs->startTab( _RSGALLERY_CONF_GENERALTAB, 'rsgConfig' );
        $tmpl->displayParsedTemplate( 'configTableGeneral' );
        $tabs->endTab();

        $tabs->startTab( _RSGALLERY_CONF_IMAGESTAB, 'rsgConfig' );
        $tmpl->displayParsedTemplate( 'configTableImages' );
        $tabs->endTab();

        $tabs->startTab( _RSGALLERY_CONF_DISPLAY, 'rsgConfig' );
        $tmpl->displayParsedTemplate( 'configTableFrontDisplay' );
        $tabs->endTab();

        $tabs->startTab( _RSGALLERY_CONF_USERS, 'rsgConfig' );
        $tmpl->displayParsedTemplate( 'configTableUsers' );
        $tabs->endTab();

        $tabs->endPane();
        $tmpl->displayParsedTemplate( 'configFinish' );

    }
}
<?php
/**
* RSGallery Toolbar Menu HTML
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
**/

// ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class menu_rsg2_maintenance{
	
	function regenerateThumbs() {

		JToolBarHelper::custom('executeRegenerateImages','next_f2.png','next_f2.png','** Regenerate **', false);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2' );
		
	}
	
}

class menu_rsg2_templateManager
{
	function _DEFAULT()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom('showInstall', '../components/com_rsgallery2/images/rsg2-install.png', '../components/com_rsgallery2/images/rsg2-install.png','Install', false, false);
		mosMenuBar::spacer();
		mosMenuBar::deleteList( '', 'remove', 'Uninstall' );
		mosMenuBar::spacer();
		mosMenuBar::makeDefault();
		mosMenuBar::spacer();
		mosMenuBar::editListX( 'edit', 'Edit' );
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'closeManager', 'Close' );
		mosMenuBar::endTable();
	}
	function _VIEW(){
		
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}
	
	function _EDIT_SOURCE(){
		
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_file' );
		mosMenuBar::apply( 'apply_file' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('edit');
		mosMenuBar::endTable();
	}
	
	function _EDIT(){
		mosMenuBar::startTable();
		mosMenuBar::custom('preview', 'preview.png', 'preview_f2.png', 'Preview', false, false);
		mosMenuBar::spacer();
		mosMenuBar::custom( 'edit_source', 'html.png', 'html_f2.png', 'Edit HTML', false, false );
		mosMenuBar::custom( 'edit_display', 'html.png', 'html_f2.png', 'Edit Display', false, false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'choose_override', 'html.png', 'html.png', 'Edit Override', false, false );
		mosMenuBar::custom( 'choose_css', 'css.png', 'css_f2.png', 'Edit CSS', false, false );
		mosMenuBar::spacer();
		mosMenuBar::save( 'save' );
		mosMenuBar::apply();
		mosMenuBar::cancel( 'cancel', 'Close' );
		mosMenuBar::endTable();
	}
	
	function _CHOOSE_CSS(){
		mosMenuBar::startTable();
		mosMenuBar::custom( 'edit_css', 'edit.png', 'edit_f2.png', 'Edit', true );
		mosMenuBar::cancel('edit');
		mosMenuBar::endTable();
	}
	function _EDIT_CSS(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_file' );
		mosMenuBar::apply( 'apply_file');
		mosMenuBar::spacer();
		mosMenuBar::cancel('choose_css');
		mosMenuBar::endTable();
	}
	
	function _CHOOSE_OVERRIDE(){
		mosMenuBar::startTable();
		mosMenuBar::custom( 'edit_override', 'edit.png', 'edit_f2.png', 'Edit', true );
		mosMenuBar::spacer();
		mosMenuBar::cancel('edit');
		mosMenuBar::endTable();
	}
	
	function _EDIT_OVERRIDE(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_file' );
		mosMenuBar::apply( 'apply_file');
		mosMenuBar::spacer();
		mosMenuBar::cancel('choose_override');
		mosMenuBar::endTable();
	}
	function _INSTALL(){
		mosMenuBar::startTable();
		mosMenuBar::cancel( 'cancel', 'Close' );
		mosMenuBar::endTable();
	}
}

class menu_rsg2_images{
    function upload() {
    	JToolBarHelper::title( '** Upload **', 'generic.png' );
        JToolBarHelper::spacer();
        JToolBarHelper::custom('save_upload','upload.png','upload.png',_RSGALLERY_TOOL_UP, false);
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2' );
        
    }
    function show(){
        JToolBarHelper::title( '** Manage Items **', 'generic.png' );
        JToolBarHelper::custom('move_images','forward.png','forward.png',_RSGALLERY_MOVETO, true);
        JToolBarHelper::spacer();
        JToolBarHelper::custom('copy_images','copy.png','copy.png',_RSGALLERY_COPYIMAGE, true);
        JToolBarHelper::spacer();
        JToolBarHelper::publishList();
        JToolBarHelper::spacer();
        JToolBarHelper::unpublishList();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('upload','upload.png','upload.png',_RSGALLERY_TOOL_UP, false);
        JToolBarHelper::spacer();
        JToolBarHelper::editListX();
        JToolBarHelper::spacer();
        JToolBarHelper::deleteList();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('reset_hits','default.png','default.png',_RSGALLERY_TOOL_RES_HITS, true);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2' );
        //menuRSGallery::adminTasksMenu();
    }
    function edit() {
        global $id;

        
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        if ( $id ) {
            // for existing content items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', _RSGALLERY_TOOL_CLOSE );
        } else {
            JToolBarHelper::cancel();
        }
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2.edit' );
        
    }
    function remove() {
        global $id;

        
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('removeReal','delete_f2.png','',_RSGALLERY_TOOL_CONFIRM_DEL, false);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2.edit' );
        
    }
}

class menu_rsg2_galleries{
    function show(){
        JToolBarHelper::title( '** Manage Galleries **', 'generic.png' );
        JToolBarHelper::spacer();
        JToolBarHelper::publishList();
        JToolBarHelper::spacer();
        JToolBarHelper::unpublishList();
        JToolBarHelper::spacer();
        JToolBarHelper::editListX();
        JToolBarHelper::spacer();
        JToolBarHelper::deleteList();
        JToolBarHelper::spacer();
        JToolBarHelper::addNewX();
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2' );
        //menuRSGallery::adminTasksMenu();
    }
    function edit() {
        global $id;

        
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        if ( $id ) {
            // for existing content items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', _RSGALLERY_TOOL_CLOSE );
        } else {
            JToolBarHelper::cancel();
        }
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2.edit' );
        
    }
    function remove() {
        global $id;

        
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('removeReal','delete_f2.png','',_RSGALLERY_TOOL_CONFIRM_DEL, false);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2.edit' );
        
    }
}

class menuRSGallery {

    function adminTasksMenuX(){
        

        // do we want an admin tasks menu for navigation?
        /*
        JToolBarHelper::spacer();
        JToolBarHelper::spacer();
        JToolBarHelper::divider();
        JToolBarHelper::spacer();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('controlPanel', '../components/com_rsgallery2/images/rsg2-cpanel.png', '../components/com_rsgallery2/images/rsg2-cpanel.png', _RSGALLERY_TOOL_PANEL, false);
        JToolBarHelper::custom('view_categories', '../components/com_rsgallery2/images/rsg2-categories.png', '../components/com_rsgallery2/images/rsg2-categories.png', _RSGALLERY_TOOL_GAL, false);
        JToolBarHelper::custom('view_images', '../components/com_rsgallery2/images/rsg2-mediamanager.png', '../components/com_rsgallery2/images/rsg2-mediamanager.png', _RSGALLERY_TOOL_IMG, false);
        JToolBarHelper::custom('upload', 'upload_f2.png', 'upload_f2.png', _RSGALLERY_TOOL_UP, false);
        
        */
    }
    
    function image_new()
        {
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        
        }

    function image_edit()
        {
        
        JToolBarHelper::save('save_image');
        JToolBarHelper::cancel('view_images');
        JToolBarHelper::spacer();
        
        }
    
    function image_batchUpload()
        {
        JToolBarHelper::title( '** Batch Upload **', 'generic.png' );
        if( rsgInstance::getVar('uploaded'  , null) )
        	JToolBarHelper::custom('save_batchupload','upload.png','upload.png',_RSGALLERY_TOOL_UP, false);
		else
        	JToolBarHelper::custom('batchupload','forward.png','forward.png',_RSGALLERY_TOOL_NEXT, false);
        //JToolBarHelper::save('save_image');
        //JToolBarHelper::cancel();
        //JToolBarHelper::back();
        JToolBarHelper::spacer();
        JToolBarHelper::help('screen.rsgallery2');
        
        }
    
    function image_upload()
        {
        JToolBarHelper::title( '** Upload **', 'generic.png' );
        JToolBarHelper::custom('upload','upload_f2.png','upload_f2.png',_RSGALLERY_TOOL_UP, false);
        //JToolBarHelper::save('upload');
		JToolBarHelper::custom('upload','forward.png','forward.png',_RSGALLERY_TOOL_NEXT, false);
        
        }
    
    function images_show()
        {
        
        JToolBarHelper::addNew('forward');
        JToolBarHelper::editList('edit_image');
        JToolBarHelper::deleteList( '', 'delete_image', _RSGALLERY_TOOL_DELETE );
        //menuRSGallery::adminTasksMenu();
        }
        
    function config_rawEdit(){
        JToolBarHelper::title( _RSGALLERY_HEAD_CONF_RAW_EDIT, 'generic.png' );
        JToolBarHelper::apply('config_rawEdit_apply');
        JToolBarHelper::save('config_rawEdit_save');
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        
    }
    
    function config_dumpVars(){
        JToolBarHelper::title( _RSGALLERY_HEAD_CONF_VARIA, 'generic.png' );
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        
    }
    
    function config_show()
        {
        JToolBarHelper::title( _RSGALLERY_HEAD_CONFIG, 'generic.png' );
        JToolBarHelper::apply('applyConfig');
        JToolBarHelper::save('saveConfig');
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.rsgallery2');
        //menuRSGallery::adminTasksMenu();
        }
	function edit_main(){
		
		JToolBarHelper::save( 'save_main' );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
		
	}
	function edit_thumbs(){
		
		JToolBarHelper::save( 'save_thumbs' );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
		
	}
	function edit_display(){
		
		JToolBarHelper::save( 'save_display' );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
		
	}
    function simple(){
        JToolBarHelper::title( _RSGALLERY_HEAD_CPANEL, 'generic.png' );
        JToolBarHelper::help('screen.rsgallery2', true);
        //menuRSGallery::adminTasksMenu();
    }
} 
    class menu_rsg2_jumploader {
    	function show() {
    	JToolBarHelper::title( '** Java Uploader **', 'generic.png' );
        JToolBarHelper::apply('');
        JToolBarHelper::save('');
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.rsgallery2');
    	}
    	
    	function simple() {
    		JToolBarHelper::title( '** Java Uploader **', 'generic.png' );
    		JToolBarHelper::help('screen.rsgallery2');
    	}
    }
?>
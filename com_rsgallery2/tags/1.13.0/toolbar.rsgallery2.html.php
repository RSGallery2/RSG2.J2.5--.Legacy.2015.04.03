<?php
/**
* RSGallery Toolbar Menu HTML
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
**/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class menu_rsg2_images{
    function upload() {
    	mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::custom('save_upload','save_f2.png','save_f2.png',_RSGALLERY_TOOL_UP, false);
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::help( 'screen.rsgallery2' );
        mosMenuBar::endTable();
    }
    function show(){
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::publishList();
        mosMenuBar::spacer();
        mosMenuBar::unpublishList();
        mosMenuBar::spacer();
        mosMenuBar::custom('upload','upload_f2.png','upload_f2.png',_RSGALLERY_TOOL_UP, false);
        mosMenuBar::spacer();
        mosMenuBar::editListX();
        mosMenuBar::spacer();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::custom('reset_hits','reload_f2.png','reload_f2.png','*Reset hits*', true);
        mosMenuBar::spacer();
        mosMenuBar::help( 'screen.rsgallery2' );
        menuRSGallery::adminTasksMenu();
    }
    function edit() {
        global $id;

        mosMenuBar::startTable();
        mosMenuBar::save();
        mosMenuBar::spacer();
        if ( $id ) {
            // for existing content items the button is renamed `close`
            mosMenuBar::cancel( 'cancel', _RSGALLERY_TOOL_CLOSE );
        } else {
            mosMenuBar::cancel();
        }
        mosMenuBar::spacer();
        mosMenuBar::help( 'screen.rsgallery2.edit' );
        mosMenuBar::endTable();
    }
    function remove() {
        global $id;

        mosMenuBar::startTable();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::custom('removeReal','delete_f2.png','',_RSGALLERY_TOOL_CONFIRM_DEL, false);
        mosMenuBar::spacer();
        mosMenuBar::help( 'screen.rsgallery2.edit' );
        mosMenuBar::endTable();
    }
}

class menu_rsg2_galleries{
    function show(){
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::publishList();
        mosMenuBar::spacer();
        mosMenuBar::unpublishList();
        mosMenuBar::spacer();
        mosMenuBar::editListX();
        mosMenuBar::spacer();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::addNewX();
        mosMenuBar::spacer();
        mosMenuBar::help( 'screen.rsgallery2' );
        menuRSGallery::adminTasksMenu();
    }
    function edit() {
        global $id;

        mosMenuBar::startTable();
        mosMenuBar::save();
        mosMenuBar::spacer();
        if ( $id ) {
            // for existing content items the button is renamed `close`
            mosMenuBar::cancel( 'cancel', _RSGALLERY_TOOL_CLOSE );
        } else {
            mosMenuBar::cancel();
        }
        mosMenuBar::spacer();
        mosMenuBar::help( 'screen.rsgallery2.edit' );
        mosMenuBar::endTable();
    }
    function remove() {
        global $id;

        mosMenuBar::startTable();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::custom('removeReal','delete_f2.png','',_RSGALLERY_TOOL_CONFIRM_DEL, false);
        mosMenuBar::spacer();
        mosMenuBar::help( 'screen.rsgallery2.edit' );
        mosMenuBar::endTable();
    }
}

class menuRSGallery {

    function adminTasksMenu(){
        mosMenuBar::endTable();

        // do we want an admin tasks menu for navigation?
        /*
        mosMenuBar::spacer();
        mosMenuBar::spacer();
        mosMenuBar::divider();
        mosMenuBar::spacer();
        mosMenuBar::spacer();
        mosMenuBar::custom('controlPanel', '../components/com_rsgallery2/images/rsg2-cpanel.png', '../components/com_rsgallery2/images/rsg2-cpanel.png', _RSGALLERY_TOOL_PANEL, false);
        mosMenuBar::custom('view_categories', '../components/com_rsgallery2/images/rsg2-categories.png', '../components/com_rsgallery2/images/rsg2-categories.png', _RSGALLERY_TOOL_GAL, false);
        mosMenuBar::custom('view_images', '../components/com_rsgallery2/images/rsg2-mediamanager.png', '../components/com_rsgallery2/images/rsg2-mediamanager.png', _RSGALLERY_TOOL_IMG, false);
        mosMenuBar::custom('upload', 'upload_f2.png', 'upload_f2.png', _RSGALLERY_TOOL_UP, false);
        mosMenuBar::endTable();
        */
    }
    
    function image_new()
        {
        mosMenuBar::startTable();
        mosMenuBar::save();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
        }

    function image_edit()
        {
        mosMenuBar::startTable();
        mosMenuBar::save('save_image');
        mosMenuBar::cancel('view_images');
        mosMenuBar::spacer();
        mosMenuBar::endTable();
        }
    
    function image_batchUpload()
        {
        mosMenuBar::startTable();
        mosMenuBar::custom('batchupload','next_f2.png','next_f2.png',_RSGALLERY_TOOL_NEXT, false);
        //mosMenuBar::save('save_image');
        //mosMenuBar::cancel();
        //mosMenuBar::back();
        mosMenuBar::spacer();
        mosMenuBar::help('screen.rsgallery2', true);
        mosMenuBar::endTable();
        }
    
    function image_upload()
        {
        mosMenuBar::startTable();
        mosMenuBar::custom('upload','upload_f2.png','upload_f2.png',_RSGALLERY_TOOL_UP, false);
        //mosMenuBar::save('upload');
		mosMenuBar::custom('upload','next_f2.png','next_f2.png',_RSGALLERY_TOOL_NEXT, false);
        mosMenuBar::endTable();
        }
    
    function templates()
        {
        mosMenuBar::startTable();
        mosMenuBar::makeDefault();
		mosMenuBar::spacer();
		//mosMenuBar::assign();
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();/*
		mosMenuBar::editHtmlX( 'edit_main', 'Main' );
		mosMenuBar::spacer();
		mosMenuBar::editHtmlX( 'edit_display', 'Display' );
		mosMenuBar::spacer();
		mosMenuBar::editCssX( 'edit_css', 'CSS' );*/
		mosMenuBar::spacer();
		//mosMenuBar::addNew();
		//mosMenuBar::spacer();
		mosMenuBar::help( 'screen.rsgallery2', true );
		mosMenuBar::endTable();
		/*
        mosMenuBar::custom('save', 'publish_f2.png', 'publish_f2.png', 'Default', false);
        mosMenuBar::spacer();
        mosMenuBar::help('screen.rsgallery2', true);
        mosMenuBar::endTable();
        */
        }
    
    function images_show()
        {
        mosMenuBar::startTable();
        mosMenuBar::addNew('upload');
        mosMenuBar::editList('edit_image');
        mosMenuBar::deleteList( '', 'delete_image', _RSGALLERY_TOOL_DELETE );
        menuRSGallery::adminTasksMenu();
        }
        
    function config_rawEdit(){
        mosMenuBar::startTable();
        mosMenuBar::apply('config_rawEdit_apply');
        mosMenuBar::save('config_rawEdit_save');
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }
    function config_show()
        {
        mosMenuBar::startTable();
        mosMenuBar::apply('applyConfig');
        mosMenuBar::save('saveConfig');
        mosMenuBar::cancel();
        mosMenuBar::help('screen.rsgallery2', true);
        menuRSGallery::adminTasksMenu();
        }
	function edit_css(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_css' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('templates');
		mosMenuBar::endTable();
	}
	function edit_main(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_main' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('templates');
		mosMenuBar::endTable();
	}
	function edit_thumbs(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_thumbs' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('templates');
		mosMenuBar::endTable();
	}
	function edit_display(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_display' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('templates');
		mosMenuBar::endTable();
	}
    function simple(){
        mosMenuBar::startTable();
        mosMenuBar::help('screen.rsgallery2', true);
        menuRSGallery::adminTasksMenu();
    }
}
?>
<?php
/**
* RSGallery2 Toolbar Menu HTML
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2011 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
**/

// ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class menu_rsg2_submenu{
	function addRSG2Submenu($rsgOption = '', $task = '') {
		
		$canDo	= Rsgallery2Helper::getActions();
		
		//The template manager (still) has its own submenu
		if (!($rsgOption == 'installer')){		
			//Control Panel
			JSubMenuHelper::addEntry(
				JText::_('COM_RSGALLERY2_SUBMENU_CONTROL-PANEL'),
				'index.php?option=com_rsgallery2',
		        (($rsgOption=='' AND $task == '' ) OR ($rsgOption == 'config')));
			//Upload
			JSubMenuHelper::addEntry(
				JText::_('COM_RSGALLERY2_SUBMENU_UPLOAD'),
				'index.php?option=com_rsgallery2&rsgOption=images&task=upload',
		        $rsgOption=='images' AND $task == 'upload');
		    //Batch Upload
			JSubMenuHelper::addEntry(
				JText::_('COM_RSGALLERY2_SUBMENU_BATCH-UPLOAD'),
				'index.php?option=com_rsgallery2&rsgOption=images&task=batchupload',
		        $rsgOption=='images' AND $task == 'batchupload');
			//Items
			JSubMenuHelper::addEntry(
				JText::_('COM_RSGALLERY2_SUBMENU_ITEMS'),
				'index.php?option=com_rsgallery2&rsgOption=images',
		        $rsgOption=='images' AND ($task == '' OR $task == 'view_images'));
		    //Galleries
			JSubMenuHelper::addEntry(
				JText::_('COM_RSGALLERY2_SUBMENU_GALLERIES'),
				'index.php?option=com_rsgallery2&rsgOption=galleries',
		        $rsgOption=='galleries' AND $task == '');

		}
	}
}

class menu_rsg2_maintenance{
	function regenerateThumbs() {
		JToolBarHelper::custom('executeRegenerateImages','forward.png','forward.png','COM_RSGALLERY2_MAINT_REGEN_BUTTON', false);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2',true);
	}
}

class menu_rsg2_images{
    function upload() {
		JToolBarHelper::title( JText::_('COM_RSGALLERY2_UPLOAD'), 'generic.png' );
        JToolBarHelper::spacer();
        JToolBarHelper::custom('save_upload','upload.png','upload.png','COM_RSGALLERY2_UPLOAD', false);
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2',true );
    }

    function show() {
        JToolBarHelper::title( JText::_('COM_RSGALLERY2_MANAGE_ITEMS'), 'generic.png' );
        JToolBarHelper::custom('move_images','forward.png','forward.png','COM_RSGALLERY2_MOVE_TO', true);
        JToolBarHelper::spacer();
        JToolBarHelper::custom('copy_images','copy.png','copy.png','COM_RSGALLERY2_COPY', true);
        JToolBarHelper::spacer();
        JToolBarHelper::publishList();
        JToolBarHelper::spacer();
        JToolBarHelper::unpublishList();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('upload','upload.png','upload.png','COM_RSGALLERY2_UPLOAD', false);
        JToolBarHelper::spacer();
        JToolBarHelper::editListX();
        JToolBarHelper::spacer();
        JToolBarHelper::deleteList();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('reset_hits','default.png','default.png','COM_RSGALLERY2_RESET_HITS', true);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2',true );
        //menuRSGallery::adminTasksMenu();
    }
    
    function edit() {
        global $id;

        JToolBarHelper::apply();
		JToolBarHelper::save();
        JToolBarHelper::spacer();
        if ( $id ) {
            // for existing content items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', JText::_('COM_RSGALLERY2_CLOSE') );
        } else {
            JToolBarHelper::cancel();
        }
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2',true );
        
    }
    
    function remove() {
        global $id;

        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('removeReal','delete_f2.png','','COM_RSGALLERY2_CONFIRM_REMOVAL', false);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2',true );
        
    }
}

class menu_rsg2_galleries{
    function show() {
		JToolBarHelper::title( JText::_('COM_RSGALLERY2_MANAGE_GALLERIES'), 'generic.png' );
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
        JToolBarHelper::help( 'screen.rsgallery2' ,true);
        //menuRSGallery::adminTasksMenu();
    }

    function edit() {
        global $id;

        JToolBarHelper::apply();
		JToolBarHelper::save();
        JToolBarHelper::spacer();
        if ( $id ) {
            // for existing content items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', JText::_('COM_RSGALLERY2_CLOSE') );
        } else {
            JToolBarHelper::cancel();
        }
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2',true );
        
    }
    
    function remove() {
        global $id;

        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        JToolBarHelper::trash('removeReal', JText::_('COM_RSGALLERY2_CONFIRM_REMOVAL'), false);
        JToolBarHelper::spacer();
        JToolBarHelper::help( 'screen.rsgallery2',true );
    }
}

class menuRSGallery {

    function adminTasksMenuX() {
        // do we want an admin tasks menu for navigation?
        /*
        JToolBarHelper::spacer();
        JToolBarHelper::spacer();
        JToolBarHelper::divider();
        JToolBarHelper::spacer();
        JToolBarHelper::spacer();
        JToolBarHelper::custom('controlPanel', '../components/com_rsgallery2/images/rsg2-cpanel.png', '../components/com_rsgallery2/images/rsg2-cpanel.png', JText::_('COM_RSGALLERY2_CPANEL'), false);
        JToolBarHelper::custom('view_categories', '../components/com_rsgallery2/images/rsg2-categories.png', '../components/com_rsgallery2/images/rsg2-categories.png', JText::_('COM_RSGALLERY2_GALLERIES'), false);
        JToolBarHelper::custom('view_images', '../components/com_rsgallery2/images/rsg2-mediamanager.png', '../components/com_rsgallery2/images/rsg2-mediamanager.png', JText::_('COM_RSGALLERY2_IMAGES'), false);
        JToolBarHelper::custom('upload', 'upload_f2.png', 'upload_f2.png', JText::_('COM_RSGALLERY2_UPLOAD'), false);
        */
    }
    
    function image_new() {
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
        }

    function image_edit() {
        JToolBarHelper::save('save_image');
        JToolBarHelper::cancel('view_images');
        JToolBarHelper::spacer();
        
        }
    
    function image_batchUpload() {
		JToolBarHelper::title( JText::_('COM_RSGALLERY2_BATCH_UPLOAD'), 'generic.png' );
        if( JRequest::getVar('uploaded'  , null) )
        	JToolBarHelper::custom('save_batchupload','upload.png','upload.png','COM_RSGALLERY2_UPLOAD', false);
		else
        	JToolBarHelper::custom('batchupload','forward.png','forward.png','COM_RSGALLERY2_NEXT', false);
        //JToolBarHelper::save('save_image');
        //JToolBarHelper::cancel();
        //JToolBarHelper::back();
        JToolBarHelper::spacer();
        JToolBarHelper::help('screen.rsgallery2',true);
        }
    
    function image_upload() {
		JToolBarHelper::title( JText::_('COM_RSGALLERY2_UPLOAD'), 'generic.png' );
        JToolBarHelper::custom('upload','upload_f2.png','upload_f2.png','COM_RSGALLERY2_UPLOAD', false);
        //JToolBarHelper::save('upload');
		JToolBarHelper::custom('upload','forward.png','forward.png','COM_RSGALLERY2_NEXT', false);
        }
    
    function images_show() {
        JToolBarHelper::addNew('forward');
        JToolBarHelper::editList('edit_image');
        JToolBarHelper::deleteList( '', 'delete_image', JText::_('COM_RSGALLERY2_DELETE') );
        //menuRSGallery::adminTasksMenu();
        }
        
    function config_rawEdit() {
        JToolBarHelper::title( JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'generic.png' );
        JToolBarHelper::apply('config_rawEdit_apply');
        JToolBarHelper::save('config_rawEdit_save');
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
    }
    
    function config_dumpVars() {
        JToolBarHelper::title( JText::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'), 'generic.png' );
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
    }
    
    function config_show() {
        JToolBarHelper::title( JText::_('COM_RSGALLERY2_CONFIGURATION'), 'generic.png' );
        JToolBarHelper::apply('applyConfig');
        JToolBarHelper::save('saveConfig');
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.rsgallery2',true);
        //menuRSGallery::adminTasksMenu();
        }
        
	function edit_main() {
		JToolBarHelper::save( 'save_main' );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
	}

	function edit_thumbs() {
		JToolBarHelper::save( 'save_thumbs' );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
	}

	function edit_display() {
		JToolBarHelper::save( 'save_display' );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
		
	}

	function simple(){
		$user = JFactory::getUser();
		$canConfigure = $user->authorise('core.admin', 	'com_rsgallery2');
		
        JToolBarHelper::title( JText::_('COM_RSGALLERY2_CONTROL_PANEL'), 'generic.png' );
		//options button, only for uses who are allowed to see/use this
		if ($canConfigure){
			JToolBarHelper::preferences('com_rsgallery2');
		}
        JToolBarHelper::help('screen.rsgallery2', true);
        //menuRSGallery::adminTasksMenu();
    }
} 

class menu_rsg2_jumploader {
	function show() {
		JToolBarHelper::title( JText::_('COM_RSGALLERY2_JAVA_UPLOADER'), 'generic.png' );
		JToolBarHelper::apply('');
		JToolBarHelper::save('');
		JToolBarHelper::cancel();
		JToolBarHelper::help('screen.rsgallery2',true);
	}
	
	function simple() {
		JToolBarHelper::title( JText::_('COM_RSGALLERY2_JAVA_UPLOADER'), 'generic.png' );
		JToolBarHelper::help('screen.rsgallery2',true);
	}
}
?>
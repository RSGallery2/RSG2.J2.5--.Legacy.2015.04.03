<?php
/**
 * RSGallery2 template manager toolbar class
 * Derived from Joomla 1.5 mod_templates
 * @author John Caprez <john@swizzysoft.com>
 * @package RSGallery2
 */

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Direct Access to this location is not allowed.');



class TOOLBAR_templates
{
	function _DEFAULT()
	{
		JToolBarHelper::title( JText::_( 'RSGallery 2 Template Manager' ), 'thememanager' );
		JToolBarHelper::makeDefault();
		JToolBarHelper::deleteList( '', 'remove', 'Uninstall' );
		JToolBarHelper::editListX( 'edit', 'Edit' );
	}
 	function _VIEW(){

		JToolBarHelper::title( JText::_( 'RSGallery 2 Template Manager' ), 'thememanager' );
		JToolBarHelper::back();
	}

	function _EDIT_SOURCE(){

		JToolBarHelper::title( JText::_( 'RSGallery 2 Template Editor' ), 'thememanager' );
		JToolBarHelper::save( 'save_file' );
		JToolBarHelper::apply( 'apply_file' );
		JToolBarHelper::cancel('edit');
	}

	function _EDIT(){
		JToolBarHelper::title( JText::_( 'RSGallery 2 Template Manager' ) . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'thememanager' );
		JToolBarHelper::custom('preview', 'preview.png', 'preview_f2.png', 'Preview', false, false);
		JToolBarHelper::custom( 'edit_source', 'html.png', 'html_f2.png', 'Edit HTML', false, false );
		JToolBarHelper::custom( 'edit_display', 'html.png', 'html_f2.png', 'Edit Display', false, false );
		JToolBarHelper::custom( 'choose_override', 'html.png', 'html.png', 'Edit Override', false, false );
		JToolBarHelper::custom( 'choose_css', 'css.png', 'css_f2.png', 'Edit CSS', false, false );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply();
		JToolBarHelper::cancel( 'cancel', 'Close' );
	}

	function _CHOOSE_CSS(){
		JToolBarHelper::title( JText::_( 'RSGallery 2 Template CSS Manager' ), 'thememanager' );
		JToolBarHelper::custom( 'edit_css', 'edit.png', 'edit_f2.png', 'Edit', true );
		JToolBarHelper::cancel('edit');
	}
	function _EDIT_CSS(){
		JToolBarHelper::title( JText::_( 'RSGallery 2 Template CSS Editor' ), 'thememanager' );
		JToolBarHelper::save( 'save_file' );
		JToolBarHelper::apply( 'apply_file');
		JToolBarHelper::cancel('choose_css');
	}

	function _CHOOSE_OVERRIDE(){
		JToolBarHelper::title( JText::_( 'RSGallery 2 Template HTML Override Manager' ), 'thememanager' );
		JToolBarHelper::custom( 'edit_override', 'edit.png', 'edit_f2.png', 'Edit', true );
		JToolBarHelper::cancel('edit');
	}

	function _EDIT_OVERRIDE(){
		JToolBarHelper::title( JText::_( 'RSGallery 2 Template HTML Override Editor' ), 'thememanager' );
		JToolBarHelper::save( 'save_file' );
		JToolBarHelper::apply( 'apply_file');
		JToolBarHelper::cancel('choose_override');
	}
	function _INSTALL(){
		JToolBarHelper::title( JText::_( 'RSGallery 2 Template Installer' ), 'thememanager' );
	}
}
?>
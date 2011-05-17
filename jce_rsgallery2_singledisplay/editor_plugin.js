/**
* JCE plugin to insert {rsg2_singledisplay: imageid, size, caption, format}
* for RSGallery2 Single Image Display popup.
* @version $Id $
* @package JCE_RSGallery2_SingleImageDisplay_Insert
* @copyright Copyright (C) 2011 Mirjam Kaizer - RSGallery2 Team All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

(function() {
	tinymce.create('tinymce.plugins.RSG2_SingleDisplayPlugin', {
		init : function(ed, url) {
			var t = this;
			
			// Register buttons (on JCE bar)
			ed.addButton('rsg2_singledisplay', {
				title 	: 'rsg2_singledisplay.desc',
				cmd 	: 'mceRSG2_SingleDisplay',
				image 	: url + '/img/rsg2_singledisplay.gif'
			});
			 
			// Register commands
			ed.addCommand('mceRSG2_SingleDisplay', function() {	
				// If you just want to insert some text, use these 3 lines:
			//	var html = '<p>Some text!</p>'; 
			//	ed.execCommand("mceInsertContent", false, html);
			//	return true;

				//This opens a window
				//var se = ed.selection;
				ed.windowManager.open({
					// file link needs plugin name and filename (file FILENAME.php is found in JOOMLAROOT\plugins\editors\jce\tiny_mce\plugins\PLUGINNAME\
					file : ed.getParam('site_url') + 'index.php?option=com_jce&task=plugin&plugin=rsg2_singledisplay&file=rsg2_singledisplay',
					width : 580 + ed.getLang('rsg2_singledisplay.delta_width', 0),
					height : 640 + ed.getLang('rsg2_singledisplay.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url
				});

			});
			
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setActive('rsg2_singledisplay', (n.nodeName == 'A' || n.nodeName == 'IMG') && !n.name);
			});
			
		}
	});
	// Register plugin
	tinymce.PluginManager.add('rsg2_singledisplay', tinymce.plugins.RSG2_SingleDisplayPlugin);
})();
/**
* JCE plugin to insert {rsg2_singledisplay: imageid, size, caption, format}
* for RSGallery2 Single Image Display popup.
* @version $Id $
* @package JCE_RSGallery2_SingleImageDisplay_Insert
* @copyright Copyright (C)2011 Mirjam Kaizer - RSGallery2 Team All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

var RSG2_SingleDisplayDialog = {
	
	init : function() {
		// Nothing to initialise.
	},

	insert : function() {
		// Get imageid, size, caption and style and form them 
		// into the RSGallery2 Single Image Display string to insert.

		//variable imageid will hold the value of the selected radio button 	
		var radioButtons = document.getElementsByName("idradio");
		for (var x = 0; x < radioButtons.length; x ++) {
			if (radioButtons[x].checked) {
				//alert("You checked: " +radioButtons[x].value);
				imageid = radioButtons[x].value;
			}
		}
//		for (i=0;i<document.adminForm.idradio.length;i++){
//			if (document.adminForm.idradio[i].checked==true){
//				imageid = document.adminForm.idradio[i];	//doesn't work in IE8, works in FF 3.6
//				}
//		}

		// Get ID and check if not empty
		//id		= dom.value('idfield');	//textfield not needed since the radio button is used
		if(imageid === '') {
			alert('id needed!');
			return false;
		}
			
		// If user chooses '- Use Style Field -' then get size from stylefield, else from sizelist.
		if(dom.getSelect('stylelist') === ''){
			style		= dom.value('stylefield');
		} else {
			style		= dom.getSelect('stylelist');	
		}
		// Get size and caption
		size		= dom.getSelect('sizelist');
		caption		= dom.getSelect('captionlist');
		
		// Make string to return
		html = '{rsg2_singledisplay:' + imageid + ',' + size + ',' + caption + ' ,' + style + '}';
		tinyMCEPopup.execCommand("mceInsertContent", false, html);
		
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(RSG2_SingleDisplayDialog.init, RSG2_SingleDisplayDialog);
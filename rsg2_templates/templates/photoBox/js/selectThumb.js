/*******************************************************************************
* selectThumb.js
* Author: Tobby Hagler
* Email: tobby.hagler@morris.com
* Copyright: Open Source, GPL
*
* Sponsered by: Morris DigitalWorks 
*
* select_thumb()
* This function shows the desired photo and caption box, markes the selected 
* thumb with a different class, and hides the other large photo box.
*******************************************************************************/
function select_thumb(large, thumb) {

	// Collect a list of all large photo containers
	var all_divs 	= document.getElementsByTagName('div');
	var photo_divs 	= new Array();
	for (i = 0; i < all_divs.length; i ++) {
		if (all_divs[i].id.indexOf("photo-id-") == 0) {
			photo_divs.push(all_divs[i].id);
		}
	}

	// Collect a list of all thumbnail images
	var all_divs 	= document.getElementsByTagName('img');
	var thumb_divs	= new Array();
	for (i = 0; i < all_divs.length; i ++) {
		if (all_divs[i].id.indexOf("thumb-id-") == 0) {
			thumb_divs.push(all_divs[i].id);
		}
	}

	// Loop through every photo, as determined by the large photo container
	for (i = 0; i < photo_divs.length; i ++) {

		// Hide all large photo boxes, or show the desired large photo box
		if (document.getElementById(photo_divs[i])) {
			document.getElementById(photo_divs[i]).style.display = 
				(photo_divs[i] == "photo-id-" + large)
				? "block"
				: "none";
		}

		// Unset the class names for all thumbnail images, or set the desired
		// thumbnail image's class name to "selected"
		if (document.getElementById(thumb_divs[i])) {
			document.getElementById(thumb_divs[i]).className = 
				(thumb_divs[i] == "thumb-id-" + thumb)
				? "selected"
				: "";
		}
	}
}
<?php

/**
* xml file to create podcasting feeds via rsgallery2
* @package RSGallery2
* @author Josiah Claassen <josiah@WhaleHosting.ca>
*/

class rsgXmlGalleryTemplate_podcasting extends rsgXmlGalleryTemplate_generic{
	function getName(){
		return 'Podcasting Template';
	}

	function prepare(){
	global $rsgConfig, $mosConfig_live_site, $mosConfig_mailfrom;

        foreach( $this->gallery->kids() as $kid ){
		$version = "2.0";
		$title = htmlentities($kid->get("name"));
		$descr = htmlentities($kid->get("description"));
		$year = date('Y');
		$date = $kid->get("date");
		$email = $mosConfig_mailfrom;

		$this->output = "\n";
		$this->output .= "<rss version='$version'><br />";
		$this->output .= "<channel><br />";

		$this->output .= "<title>$title</title><br />";
		$this->output .= "<description>$descr</description><br />";
		$this->output .= "<link>$mosConfig_live_site</link><br />";
		$this->output .= "<language>en-us</language><br />";
		$this->output .= "<copyright>$year</copyright><br />";
		$this->output .= "<lastBuildDate>$date</lastBuildDate><br />";
		$this->output .= "<webMaster>$email</webMaster><br />";
		$this->output .= "<ttl>1</ttl><br />";

		foreach( $kid->items() as $img ){
			$path = $img->display();
			$path = $display->display();
			$path = $display->name;
			//$path = imgUtils::getImgNameDisplay($img['name']);
			print_r($path); die();
			
			$title = $img->title;
			$descr = $img->descr;
			
			$pubDate = $kid->get("date");
			$podPath = $rsgConfig->get("imgPath_display");
			
			
			$location = $img->display()
			$location = $img->url();
			$size = filesize($location);

			$this->output .= "<item><br />";
			$this->output .= "<title>$title</title><br />";
			$this->output .= "<description>$descr</description><br />";
			$this->output .= "<pubDate>$pubDate</pubDate><br />";
			$this->output .= '<enclosure url="';
			$this->output .= "$mosConfig_live_site$podPath/$path";
			$this->output .= '" length="';
			$this->output .= "$size";
			$this->output .= '" type="';
			$this->output .= 'audio/mpeg" />\n<br />"';
			$this->output .= '</item><br />';
		}
	}
        $this->output .= "</channel><br />";
        $this->output .= "</rss><br />";
	}
}
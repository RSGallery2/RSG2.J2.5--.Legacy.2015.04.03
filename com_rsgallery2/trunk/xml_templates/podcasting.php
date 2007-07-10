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
		$this->output = "\n";
		$this->output .= "<rss version='$version'>\n";
		
		$this->processGallery( $this->gallery );
		
		$this->output .= "</rss>\n";
	}
	
	function processGallery( $gallery ){
		global $rsgConfig, $mosConfig_live_site, $mosConfig_mailfrom;
	
		$version = "2.0";
		$title = htmlentities($gallery->get("name"));
		$descr = htmlentities($gallery->get("description"));
		$year = date('Y');
		$date = $gallery->get("date");
		$email = $mosConfig_mailfrom;

		$this->output .= "<channel>\n";

		$this->output .= "<title>$title</title>\n";
		$this->output .= "<description>$descr</description>\n";
		$this->output .= "<link>$mosConfig_live_site</link>\n";
		$this->output .= "<language>en-us</language>\n";
		$this->output .= "<copyright>$year</copyright>\n";
		$this->output .= "<lastBuildDate>$date</lastBuildDate>\n";
		$this->output .= "<webMaster>$email</webMaster>\n";
		$this->output .= "<ttl>1</ttl>\n";
		
		foreach( $gallery->items() as $item ){
			// if not an audio file, don't use it
			if(!is_a($item, "rsgItem_audio")) continue;
			
			$mp3 = $item->original();
			$url = $mp3->url();
			
			$title = $item->title;
			$descr = $item->descr;
			
			$pubDate = $item->date;
			
			
			$size = filesize($mp3->filePath());

			$this->output .= "<item>\n";
			$this->output .= "<title>$title</title>\n";
			$this->output .= "<description>$descr</description>\n";
			$this->output .= "<pubDate>$pubDate</pubDate>\n";
			$this->output .= '<enclosure url="';
			$this->output .= "$url";
			$this->output .= '" length="';
			$this->output .= "$size";
			$this->output .= '" type="';
			$this->output .= 'audio/mpeg" />"';
			$this->output .= '</item>';
		}
		
		$this->output .= "</channel>\n";

		foreach( $this->gallery->kids() as $kid ){
			$this->processGallery( $gallery );
		}
	}
}
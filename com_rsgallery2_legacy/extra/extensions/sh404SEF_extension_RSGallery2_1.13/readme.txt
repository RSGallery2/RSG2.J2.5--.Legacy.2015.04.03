INFO (on v0.4)
This is a plugin to have sh404SEF working with RSGallery2 1.13.1 (not 1.14!). It gives 
- the menutitle; 
- the (final) category/galleryname or multiple categorynames (default; new in v0.2) based on the option set (see "options");
- the id+title (default) or filename (based on the option set) of an image (new in v0.3);
- when JoomFish is installed the menuname, galleryname and image title will be translated (new in v0.4).

INSTALLATION
The file com_rsgallery2.php in this zip file can be either copied to \joomlaroot\components\com_sef\sef_ext\ or Joomla's component installer can be used with this zip file. The second options creates a \joomlaroot\components\com_rsgallery2\sef_ext\ directory and places com_rsgallery2.php there.

OPTIONS 
At the top of the com_rsgallery2.php file there are two variables:
  $rsInsertMultipleCategories = 1; 	// 1 = true = multiple categories in URL; 0 = false = single category in URL
  $rsImageTitleNotFilename = 1; 	// 1 = true = use image id and its title; 0 = false = use filename
  $RSdatabaseConsolidated = 1;
these can be used to toggle the behaviour. After changing an option you need to "purge SEF URLs" in the back-end and start clicking your links at your homepage. 
- $rsInsertMultipleCategories switches between having multiple categories in the URL e.g. www.mysite.com/pictures/holiday/2007/france/ or www.mysite.com/pictures/france/, where the categories are: holiday, 2007, france.
- $rsImageTitleNotFilename switches between having filenames in the URL like /23-me_at_the_swimmingpool.html (where the image title is "me at the swimmingpool" and "23" is the unique image id in the database) or /23_34235.jpg (where "23_34235.jpg" is the filename).
- $RSdatabaseConsolidated gives a choice on how the image name is fetched from the database. When true it is important to have the database "consolidated" (see "IMPORTANT" below), when false this is not necessary. (The second option "false" is not tested as good as the option "true".)

IMPORTANT (and remember) 
If $RSdatabaseConsolidated = 1:
After adding/removing images in RSGallery2 please "consolidate database" in RSGallery2 and "purge SEF URLs" in sh404SEF (both in back-end). "Consolidate database" is needed because is renews/refreshes the 'order' in MySQL's #__rsgallery2_files table. A correct order is needed to get the (file)names of the images right.
If $RSdatabaseConsolidated = 0:
After adding/removing images in RSGallery2 please "purge SEF URLs" in sh404SEF's back-end.

TESTED
This file was tested with RSGallery2 1.13.1 Alpha - SVN: 289 and sh404SEF Version_1.3_RC - build_150 on my localhost. I had 5 galleries of which 3 were nested (sub- and subsubgalleries) and three images per gallery. Translations with JoomFish were tested with version 1.8.2 (2007-12-16).

FIX: RANDOM IMAGES & LATEST IMAGES MODULES
In case of the Random Images and Latest Images modules, the URL gets no Itemid from RSGallery2. I chose not to do SEF-URLs in that case. 
Not having the Itemid in the URL is a bug in these RSGallery2 modules. I prefer not to do SEF in these cases as I think that this should be handled in RSGallery2s modules, not in a sef extension file (although it is possible, and then it will generate many more redirect/SEF-URLs).
The bug can be fixed in the file \joomlaroot\components\com_rsgallery2\templates\tables\display.class.php by taking the following steps
* on line 1967, just below "function showRandom($rows, $style = "hor") {", add ", $Itemid" to
global $mosConfig_live_site;
to get
global $mosConfig_live_site, $Itemid;
* on line 2021 a few lines below the second occurance of "_RSGALLERY_RANDOM_TITLE" change:
<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2"&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">
where "&Itemid=".$Itemid."" (excluding the outer "") must be put between "com_rsgallery2" and "&page" to get:
<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">
* on line 2041, just below "function showLatest($rows, $style = "hor") {" add ", $Itemid" to
global $mosConfig_live_site;
to get
global $mosConfig_live_site, $Itemid;
* on line 2092 just below "_RSGALLERY_LATEST_TITLE" change:
<a href="<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">
where "&Itemid=".$Itemid."" (excluding the outer "") must be put between "com_rsgallery2" and "&page" to get:
<a href="<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">

FIX: remove URL part that results in $id=0
In the file \joomlaroot\components\com_rsgallery2\templates\tables\display.class.php 
find line 903 right above "<?php echo _RSGALLERY_SLIDESHOW_EXIT; ?>":
<a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&catid=".$catid."&id=". 
mosGetParam ( $_REQUEST, 'id'  , ''));?>">
Change this line to:
<a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&catid=".$catid);?>">
The parts "&page=inline" and "."&id=". mosGetParam ( $_REQUEST, 'id'  , '')" were removed. 

MISC
- There is a special case for $page==downloadfile (to have the text 'downloadfile' in the URL), but it won't work as there is no Itemid in this case thus not SEF-URLs are made. There is also no $catid in this case (IMHO this is also a bug in RSGallery2, but I love to be corrected on this).
- That this v0.4 will work with JoomFish and v0.3 only partly is due to the way information is extracted from the database: with "loadRow" things are not translated, with "loadObjectList" they are.

WEBSITES
- sh404SEF's website: http://extensions.siliana.net
- RSGallery2's website: http://rsgallery2.net
- Joom!Fish's website: http://www.joomfish.net

January 28, 2008
Kaizer, M (Mirjam)
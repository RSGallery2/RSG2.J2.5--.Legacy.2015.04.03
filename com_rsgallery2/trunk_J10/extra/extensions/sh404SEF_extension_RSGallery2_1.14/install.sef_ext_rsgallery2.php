<?php
defined('_VALID_MOS') or die('Restricted access');

function com_install ()
{
    global $mosConfig_absolute_path;
    
    $component = 'rsgallery2';
    $component_name = 'RSGallery2';
    $install_array = array('sef' => true, 'meta' => false);
    
    $msgs = array();
    $error = false;
    
    define('_THIS_PATH', dirname(__FILE__));
    define('_COMPONENT_PATH', "$mosConfig_absolute_path/components/com_$component");
    
    if ( ! file_exists(_COMPONENT_PATH."/$component.php") ) {
        $msgs[] = "<span style='color:red'>Couldn't find component $component_name.
            <br />Aborting installation.</span>";
        $error = true;
    }
    foreach ( $install_array as $type => $install ) {
        if ( !$install || $error ) {
        	if ( !$install ) {
                $msgs[] = $type . "_ext is set to false so won't be installed";
        	}
        } else {
        	$folder_name = $type . "_ext";
            $msgs[] = "Installing ".$folder_name;
            
            $component_path = _COMPONENT_PATH."/".$folder_name;
            $component_file = "com_$component.php";
            $patch_files = array(_THIS_PATH."/".$folder_name."/".$component_file 
                => $component_path."/$component_file");
            
            if ( ! is_dir($component_path) ) {
                mkdir($component_path);
            }
            foreach ( $patch_files as $src => $dest ) {
                if ( file_exists($dest) ) {
                    if ( rename($dest, $dest.'.'.date(ymdHi).'.~bak') ) {
                        $msgs[] = "Successfully backed up $dest to $dest.".date(ymdHi).'.~bak';
                    } else {
                        $msgs[] = "<span style='color:red'>Failed backing up $dest</span>";
                        $error = true;
                    }
                }
                if ( is_writable($component_path) ) {
                    if ( rename($src, $dest) ) {
                        $msgs[] = "Successfully copied $src to $dest";
                    } else {
                        $msgs[] = "<span style='color:red'>Failed copying $src <br />
                            to $dest</span>";
                        $error = true;
                    }
                } else {
                    $msgs[] = "<span style='color:red'>$dest is not writable, patch failed!</span>";
                    $error = true;
                }
            }
        }
    }
    if ( $error ) {
        $message = "<b>WARNING: There was a problem with the installation. 
            Please check these messages, then uninstall the component and 
            reinstall after making any necessary changes.</b>";
    } else {
        $message = "<b>The plugin was installed successfully to $component_name.<br /> 
        <span style='color:blue;'>You can now uninstall this component as it is no longer needed.</span><p>
		<span style='color:red;'>Please read the notes in readme.txt, for your convenience they are printed below as well.</span><p> 
		

		<strong>INFO (on v0.1 for RSGallery2 1.14)</strong><br>
This is a plugin to (try to) have sh404SEF working with RSGallery2 1.14.1 (not 1.13!). It gives 
<br>- the menutitle; 
<br>- the (final) galleryname or multiple gallerynames (default) based on the option set (see \"options\");
<br>- the id+title (default) or filename (based on the option set) of an image.
<br>- Menuname, galleryname(s) and image descriptons are translated in URL when using JoomFish 
(tested with v1.8; option to turn this off in sh404SEF's back-end does not work because it's not in 
this code). BUT THIS GIVES SOME STRANGE BEHAVIOUR (in that URLs in the seconds language are non-SEF and that when switching galleries the default language is used)!!! [cause unknown to me at this point]
<br>- Remarkebly some links show in the statusbar as non-SEF links and when clicked they are SEF links 
in the address-bar [cause unknown to me at this point]. 

<p>
<strong>INSTALLATION</strong><br>
The file com_rsgallery2.php in this zip file can be either copied to 
\joomlaroot\components\com_sef\sef_ext\ or Joomla's component installer can be used with this zip 
file. The second options creates a \joomlaroot\components\com_rsgallery2\sef_ext\ directory and 
places com_rsgallery2.php there.
<p>
<strong>OPTIONS</strong><br>
At the top of the com_rsgallery2.php file there are three variables:
  <br>\$rsInsertMultipleCategories = 1; 	// 1 = true = multiple categories in URL; 0 = false = 
single category in URL
  <br>\$rsImageTitleNotFilename = 1; 	// 1 = true = use image id and its title; 0 = false = use 
filename
  <br>\$RSdatabaseConsolidated = 0;		// 0 = false: default option of database being not 
consolidated
<br>these can be used to toggle the behaviour. After changing an option you need to \"purge SEF URLs\" in 
the back-end and start clicking your links at your homepage. <br>
- \$rsInsertMultipleCategories switches between having multiple categories in the URL e.g. 
www.mysite.com/pictures/holiday/2007/france/ or www.mysite.com/pictures/france/, where the 
categories are: holiday, 2007, france.<br>
- \$rsImageTitleNotFilename switches between having filenames in the URL like 
/23-me_at_the_swimmingpool.html (where the image title is \"me at the swimmingpool\" and \"23\" is the 
unique image id in the database) or /23_34235.jpg (where \"23_34235.jpg\" is the filename).<br>
- \$RSdatabaseConsolidated gives a choice on how the image name is fetched from the database. When 
true it is important to have the database \"consolidated\" (not implemented in RSGallery2 1.14 at 
this point; see \"IMPORTANT\" below), when false this is not necessary. 
<p>
<strong>IMPORTANT</strong><br>
If \$RSdatabaseConsolidated = 1:
<br>For the filenames/titles to be displayed correctly in the URL the 'order' in MySQL's 
#__rsgallery2_files table must be correct. By this I mean that if the order values for a particular 
gallery are \"2, 3, 4 ,6 ,7\" (e.g. missing 1 and 5) you will not get the right name. If the order 
values for a particular gallery are \"1, 2, 3, 4, 5, etc.\" (e.g. consecutively starting at 1) you 
will get the right name.
In RSGallery2 v1.13.1 there is an option \"consolidate database\" which does this automatically for 
you. Unfortunately \"Consolidate database\" (in \"Maintenance\") is not implemented yet in RSGallery 
1.14.1 SVN573. When it is implemented you should use this feature after adding/removing images in 
RSGallery2 and then \"purge SEF URLs\" in sh404SEF (both in back-end). 
<br>If \$RSdatabaseConsolidated = 0:<br>
After adding/removing images in RSGallery2 please \"purge SEF URLs\" in sh404SEF's back-end.</p>
<p>
<strong>TESTED</strong><br>
This file was tested with RSGallery2 1.14.1 Alpha - SVN: 573 (which is not production site ready) 
and sh404SEF Version_1.3_RC - build_150 on my localhost. I had 5 galleries of which 3 were nested 
(sub- and subsubgalleries) and three images per gallery.
<p>
<strong>MISC</strong><br>
There is a special case for \$page==slideshow: making SEF URLs with sh404SEF breaks the ability of 
showing the slideshow correctly which is why \$dosef in this case is set false so that no SEF URLs 
are made. Note: even without a plugin file for RSGallery2 1.14 and with sh404SEF installed the 
slideshow did not work on my localhost.
<p>
<strong>WEBSITES</strong><br>
- sh404SEF's website: http://extensions.siliana.net
<br>- RSGallery2's website: http://rsgallery2.net/
<br>- Joom!Fish's website: http://www.joomfish.net
<p>
February 3, 2008<br>
Kaizer, M (Mirjam)	

		 
		";
    }
    return '<hr /><ul><li>' . implode('</li><li>', $msgs) . '</li></ul><hr /><p>' . $message . '</p>';
}
?>
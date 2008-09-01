<?php
/**
 * admin.sef_ext_rsgallery2.php
 * @version $Id: 
 * @copyright (C) 2008 Bob Janes
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://ciamos.com Official website
 **/
defined('_VALID_MOS') or die('Restricted access');
?><table class="adminform">
<tr><td>
NOTE: You can safely uninstall this component, it is no longer needed.
</td></tr>
<tr><td>
<p><strong>INFO (on v0.4 for RSGallery 1.13)</strong><br>
  This is a plugin to have sh404SEF working with RSGallery2 1.13.1 (not 1.14!). It gives <br>
  - the menutitle; <br>
  - the (final) category/galleryname or multiple categorynames (default; new in v0.2) based on the option set (see &quot;options&quot;);<br>
  - the id+title (default) or filename (based on the option set) of an image (new in v0.3);<br>
  - when JoomFish is installed the menuname, galleryname and image title will be translated (new in v0.4).</p>
<p><strong>INSTALLATION</strong><br>
The file com_rsgallery2.php in this zip file can be either copied to \joomlaroot\components\com_sef\sef_ext\ or Joomla's component installer can be used with this zip file. The second options creates a \joomlaroot\components\com_rsgallery2\sef_ext\ directory and places com_rsgallery2.php there.</p>
<p><strong>OPTIONS </strong><br>
  At the top of the com_rsgallery2.php file there are two variables:<br>
  $rsInsertMultipleCategories = 1; // 1 = true = multiple categories in URL; 0 = false = single category in URL<br>
  $rsImageTitleNotFilename = 1; // 1 = true = use image id and its title; 0 = false = use filename<br>
  $RSdatabaseConsolidated = 1;<br>
  these can be used to toggle the behaviour. After changing an option you need to &quot;purge SEF URLs&quot; in the back-end and start clicking your links at your homepage. <br>
  - $rsInsertMultipleCategories switches between having multiple categories in the URL e.g. www.mysite.com/pictures/holiday/2007/france/ or www.mysite.com/pictures/france/, where the categories are: holiday, 2007, france.<br>
  - $rsImageTitleNotFilename switches between having filenames in the URL like /23-me_at_the_swimmingpool.html (where the image title is &quot;me at the swimmingpool&quot; and &quot;23&quot; is the unique image id in the database) or /23_34235.jpg (where &quot;23_34235.jpg&quot; is the filename).<br>
- $RSdatabaseConsolidated gives a choice on how the image name is fetched from the database. When true it is important to have the database &quot;consolidated&quot; (see &quot;IMPORTANT&quot; below), when false this is not necessary. (The second option &quot;false&quot; is not tested as much as the option &quot;true&quot;.)</p>
<p><strong>IMPORTANT (and remember)</strong> <br>
  If $RSdatabaseConsolidated = 1:<br>
  After adding/removing images in RSGallery2 please &quot;consolidate database&quot; in RSGallery2 and &quot;purge SEF URLs&quot; in sh404SEF (both in back-end). &quot;Consolidate database&quot; is needed because is renews/refreshes the 'order' in MySQL's #__rsgallery2_files table. A correct order is needed to get the (file)names of the images right.<br>
  If $RSdatabaseConsolidated = 0:<br>
  After adding/removing images in RSGallery2 please &quot;purge SEF URLs&quot; in sh404SEF's back-end.</p>
<p><strong>TESTED</strong><br>
  This file was tested with RSGallery2 1.13.1 Alpha - SVN: 289 and sh404SEF Version_1.3_RC - build_150 on my localhost. I had 5 galleries of which 3 were nested (sub- and subsubgalleries) and three images per gallery. Translations with JoomFish were tested with version 1.8.2 (2007-12-16).</p>
<p><strong>FIX: Random Images &amp; Latest Images Modules</strong><br>
  In case of the Random Images and Latest Images modules, the URL gets no Itemid from RSGallery2. I chose not to do SEF-URLs in that case. <br>
  Not having the Itemid in the URL is a bug in these RSGallery2 modules. I prefer not to do SEF in these cases as I think that this should be handled in RSGallery2s modules, not in a sef extension file (although it is possible, and then it will generate many more redirect/SEF-URLs).<br>
  The bug can be fixed in the file \joomlaroot\components\com_rsgallery2\templates\tables\display.class.php by taking the following steps<br>
  * on line 1967, just below &quot;function showRandom($rows, $style = &quot;hor&quot;) {&quot;, add &quot;, $Itemid&quot; to<br>
  global $mosConfig_live_site;<br>
  to get<br>
  global $mosConfig_live_site, $Itemid;<br>
  * on line 2021 a few lines below the second occurance of &quot;_RSGALLERY_RANDOM_TITLE&quot; change:<br>
&lt;?php echo sefRelToAbs($mosConfig_live_site.&quot;/index.php?option=com_rsgallery2&quot;&amp;page=inline&amp;id=&quot;.$row-&gt;id.&quot;&amp;catid=&quot;.$row-&gt;gallery_id.&quot;&amp;limitstart=&quot;.$l_start);?&gt;&quot;&gt;<br>
  where &quot;&amp;Itemid=&quot;.$Itemid.&quot;&quot; (excluding the outer &quot;&quot;) must be put between &quot;com_rsgallery2&quot; and &quot;&amp;page&quot; to get:<br>
&lt;?php echo sefRelToAbs($mosConfig_live_site.&quot;/index.php?option=com_rsgallery2&amp;Itemid=&quot;.$Itemid.&quot;&amp;page=inline&amp;id=&quot;.$row-&gt;id.&quot;&amp;catid=&quot;.$row-&gt;gallery_id.&quot;&amp;limitstart=&quot;.$l_start);?&gt;&quot;&gt;<br>
  * on line 2041, just below &quot;function showLatest($rows, $style = &quot;hor&quot;) {&quot; add &quot;, $Itemid&quot; to<br>
  global $mosConfig_live_site;<br>
  to get<br>
  global $mosConfig_live_site, $Itemid;<br>
  * on line 2092 just below &quot;_RSGALLERY_LATEST_TITLE&quot; change:<br>
&lt;a href=&quot;&lt;?php echo sefRelToAbs($mosConfig_live_site.&quot;/index.php?option=com_rsgallery2&amp;page=inline&amp;id=&quot;.$row-&gt;id.&quot;&amp;catid=&quot;.$row-&gt;gallery_id.&quot;&amp;limitstart=&quot;.$l_start);?&gt;&quot;&gt;<br>
  where &quot;&amp;Itemid=&quot;.$Itemid.&quot;&quot; (excluding the outer &quot;&quot;) must be put between &quot;com_rsgallery2&quot; and &quot;&amp;page&quot; to get:<br>
&lt;a href=&quot;&lt;?php echo sefRelToAbs($mosConfig_live_site.&quot;/index.php?option=com_rsgallery2&amp;Itemid=&quot;.$Itemid.&quot;&amp;page=inline&amp;id=&quot;.$row-&gt;id.&quot;&amp;catid=&quot;.$row-&gt;gallery_id.&quot;&amp;limitstart=&quot;.$l_start);?&gt;&quot;&gt;</p>
<p><strong>FIX: remove URL part that results in $id=0</strong><br>
  In the file \joomlaroot\components\com_rsgallery2\templates\tables\display.class.php <br>
  find line 903 right above &quot;&lt;?php echo _RSGALLERY_SLIDESHOW_EXIT; ?&gt;&quot;:<br>
&lt;a href=&quot;&lt;?php echo sefRelToAbs(&quot;index.php?option=com_rsgallery2&amp;Itemid=&quot;.$Itemid.&quot;&amp;page=inline&amp;catid=&quot;.$catid.&quot;&amp;id=&quot;. <br>
  mosGetParam ( $_REQUEST, 'id' , ''));?&gt;&quot;&gt;<br>
  Change this line to:<br>
&lt;a href=&quot;&lt;?php echo sefRelToAbs(&quot;index.php?option=com_rsgallery2&amp;Itemid=&quot;.$Itemid.&quot;&amp;catid=&quot;.$catid);?&gt;&quot;&gt;<br>
  The parts &quot;&amp;page=inline&quot; and &quot;.&quot;&amp;id=&quot;. mosGetParam ( $_REQUEST, 'id' , '')&quot; were removed. </p>
<p><strong>MISC</strong><br>
  - There is a special case for $page==downloadfile (to have the text 'downloadfile' in the URL), but it won't work as there is no Itemid in this case thus not SEF-URLs are made. There is also no $catid in this case (IMHO this is also a bug in RSGallery2, but I love to be corrected on this).<br>
  - That this v0.4 will work with JoomFish and v0.3 only partly is due to the way information is extracted from the database: with &quot;loadRow&quot; things are not translated, with &quot;loadObjectList&quot; they are.</p>
<p><strong>WEBSITES</strong><br>
  - sh404SEF's website: http://extensions.siliana.net<br>
  - RSGallery2's website: http://rsgallery2.net<br>
  - Joom!Fish's website: http://www.joomfish.net</p>
<p>January 28, 2008<br>
  Kaizer, M (Mirjam)</p>
</tr></td>
</table>
<?php
/**
 * SEF module for Joomla!
 *
 * This is an example file demonstrating how to write own extensions for Artio JoomSEF.
 *
 * @author      Daniel Tulp
 * @copyright   DT^2 Daniel Tulp design technology
 * @license	GNU/ GPL
 * @package     JoomSEF
 * @version     0.1
 */

// Security check to ensure this file is being included by a parent file.
if (!defined('_VALID_MOS')) die('Direct Access to this location is not allowed.');


/**
 * Use this to get variables from the original Joomla! URL, such as $task, $page, $id, $catID, ...
 */
extract($vars);

/**
 * Now compose your SEF path.
 * Store the path parts in an array. Further named $fields in this example.
 *
 * To compose the path, you will probably need to call your module funcitons (methods) or connect
 * to the tables used by your module.
 */

//load main gallery page name
if ($option = com_rsgallery2){
    $option = "gallery";
}
//load gallery name
if (isset($catid)) {
    $query_gal = "
		SELECT `name`
		FROM `#__rsgallery2_galleries`
		WHERE `id` = $catid
		";
    $database->setQuery($query_gal);
    $gallery = $database->loadResult();
}
//load imagename
if (isset($limitstart)){
$order = $limitstart +1;
}
if (isset($order) && isset($catid)) {
    $query_name = "
		SELECT `title`
		FROM `#__rsgallery2_files`
		WHERE `ordering` = $order AND `gallery_id` = $catid
		";
    $database->setQuery($query_name);
    $name = $database->loadResult();
}
//apply to array title[]
if (!empty($option)) {
    $title[] = $option;
    // Unset the original URL variable not to interfere anymore.
    unset($vars['option']);
}
if (!empty($gallery)) {
    $title[] = $gallery;
    // Unset the original URL variable not to interfere anymore.
    unset($vars['catid']);
}

// Now message title read from DB is added as the next part of the SEF path.
if (!empty($name)) {
    $title[] = $name;
    // Unset the original URL variable not to interfere anymore.
    unset($vars['id']);
}


/**
 * Finally, at the end of this file, call JoomSEF::sefGetLocation method to generate and store resulting URL.
 *
 * $string  - original URL which comes automatically (do not change this)
 * $title   - your SEF path (array of single parts)
 * $task    - if not empty (null), the task string will be appended to resulting SEF URL,
 *            e.g. if task=read, the result URL will be your/parts/read(suffix)
 */
if (count($title) > 0) {
    $string = sef_404::sefGetLocation($string, $title, (isset($lang) ? @$lang : null));
}

/**
 * And that is all folks!
 */
?>

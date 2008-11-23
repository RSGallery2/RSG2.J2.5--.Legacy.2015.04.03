<?php
/**
 * @version		$Id: router.php 7380 2007-05-06 21:26:03Z eddieajau $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

function Rsgallery2BuildRoute(&$query)
{
	static $items;
	
	$currentMenu = null;
	$segments	= array();
	$itemid		= isset($query['Itemid']) ? $query['Itemid'] : null;
	
	// Get the menu items for this component.
	if (!$items) {
		$component	= &JComponentHelper::getComponent('com_rsgallery2');
		$menu		= &JSite::getMenu();
		$items		= $menu->getItems('componentid', $component->id);
	}
	
	if($itemid != null){
		// get the right menu item if multiple galliers exist
		foreach($items as $key => $item)
		{
			if($item->id == $itemid){
				$currentMenu = $item;
				break;
			}				
		}
	}
	
	// rename catId to gId
	if(isset($query['catid'])){
		$query['gid'] = $query['catid'];
		unset($query['catid']);
	}
	
	// direct category link
	if(isset($query['gid'])){
		// add the gallery id only if it is not part of the menu link
		if(empty($item) ||
		   preg_match( "/gid=([0-9]*)/", $currentMenu->link) == 0){
			$segments[] = 'category';
			
			// instead of the numerical id the gallery's (unique) title could be added here
			
			$segments[] = $query['gid'];
		}
		unset($query['gid']);
	}
	
	// gallery paging	
	if(isset($query['limitstartg'])){
		$segments[] = 'categoryPage';
		$segments[] = $query['limitstartg'];
		unset($query['limitstartg']);
	}

	// direct item link
	if(isset($query['id'])){
		$segments[] = 'item';
		
		// instead of the numerical id the item's (unique) title could be added here
		
		$segments[] = $query['id'];
		unset($query['id']);
	}
	
	// item paging
	if(isset($query['start'])){
		$segments[] = 'itemPage';
		$segments[] = $query['start'];
		unset($query['start']);
	}
	
	// how to show the item
	if(isset($query['page'])){
		$segments[] = 'as' . ucfirst($query['page']);
		unset($query['page']);
	}
		
	return $segments;
}

function Rsgallery2ParseRoute($segments)
{
	$vars	= array();
	
	// Get the active menu item.
	$menu	= &JSite::getMenu();
	$item	= &$menu->getActive();
	
	if(!empty($item)){
		// add gallery id if it exists in the menu link
		if(preg_match( "/gid=([0-9]*)/", $item->link, $matches) != 0){
			$vars['gid'] = $matches[1];
		}
		
	}
	
	for ($index = 0 ; $index < count($segments) ; $index++){
		
		switch ($segments[$index]){
			// gallery link
			case 'category':
			{
				$vars['gid'] = $segments[++$index];
				// check if the gallery id is non numerial
				if(!ctype_digit($vars['gid']))
				{
					// after sanitising the id value the numerial id could be fetched from the 
					// database.
				}

				break;
			}
			// item link
			case 'item':
			{
				$vars['id'] = $segments[++$index];
				
				// check if the item id is non numerial
				if(!ctype_digit($vars['id']))
				{
					// after sanitising the id value the numerial id could be fetched from the 
					// database.
				}
				
				break;
			}
			// gallery paging
			case 'categoryPage':
			{
				$vars['limitstartg'] = 	$segments[++$index];
				$vars['limitstart'] = 1;
				break;
			}
			// item paging
			case 'itemPage':
			{
				$vars['limitstart'] = 	$segments[++$index];
				break;
			}
			
		}
		// how to show the item
		$pos = strpos($segments[$index],'as'); 
		if($pos !== false && $pos == 0)
		{
			$vars['page'] = strtolower(substr($segments[$index],2));
		}
		
		
	}
	
	if(isset($vars["id"]) && !isset($vars['page']))
	{
		$vars['page'] = "inline";
	}
	return $vars;
}
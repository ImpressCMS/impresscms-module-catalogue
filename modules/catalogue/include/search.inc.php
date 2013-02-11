<?php
/**
* Catalogue version infomation
*
* This file holds the configuration information of this module
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function catalogue_search($queryarray, $andor, $offset = 0, $userid)
{
	global $icmsConfigSearch;
	
	$itemsArray = $ret = array();
	$count = $number_to_process = $items_left = '';
	
	$catalogue_item_handler = icms_getModuleHandler('item', basename(dirname(dirname(__FILE__))),
		'catalogue');
	$itemsArray = $catalogue_item_handler->getItemsForSearch($queryarray, $andor, $limit, $offset,
		$userid);
	
	// Count the number of records
	$count = count($itemsArray);
	
	// The number of records actually containing publication objects is <= $limit, the rest are padding
	$items_left = ($count - ($offset + $icmsConfigSearch['search_per_page']));
	if ($items_left < 0) {
		$number_to_process = $icmsConfigSearch['search_per_page'] + $items_left; // $items_left is negative
	} else {
		$number_to_process = $icmsConfigSearch['search_per_page'];
	}
	
	// Process the actual articles (not the padding)
	foreach ($itemsArray as $itemArray) {
		$item['image'] = "images/icon_small.png";
		$item['link'] = $itemArray['itemUrl'];
		$item['title'] = $itemArray['title'];
		$item['time'] = strtotime($itemArray['date']);
		$item['uid'] = $itemArray['submitter'];
		$ret[] = $item;
		unset($item);
	}
	
	// Restore the padding (required for 'hits' information and pagination controls). The offset
	// must be padded to the left of the results, and the remainder to the right or else the search
	// pagination controls will display the wrong results (which will all be empty).
	// Left padding = -($limit + $offset)
	$ret = array_pad($ret, -($offset + $number_to_process), 1);
	
	// Right padding = $count
	$ret = array_pad($ret, $count, 1);
	
	return $ret;
}
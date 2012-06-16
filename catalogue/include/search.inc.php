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

function catalogue_search($queryarray, $andor, $limit, $offset, $userid)
{
	$catalogue_item_handler = icms_getModuleHandler('item', basename(dirname(dirname(__FILE__))),
		'catalogue');
	$itemsArray = $catalogue_item_handler->getItemsForSearch($queryarray, $andor, $limit, $offset,
		$userid);

	$ret = array();

	foreach ($itemsArray as $itemArray) {
		$item['image'] = "images/icon_small.png";
		$item['link'] = $itemArray['itemUrl'];
		$item['title'] = $itemArray['title'];
		$item['time'] = strtotime($itemArray['date']);
		$item['uid'] = $itemArray['submitter'];
		$ret[] = $item;
		unset($item);
	}
	return $ret;
}
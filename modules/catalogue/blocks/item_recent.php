<?php
/**
 * New products (recent items) block file
 *
 * This file holds the functions needed for the new products block
 *
 * @copyright	http://smartfactory.ca The SmartFactory
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * Modified for use in the Catalogue module by Madfish
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function catalogue_item_recent_show($options) {
	include_once(ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
		. '/include/common.php');
	$catalogue_item_handler = icms_getModuleHandler('item',
		basename(dirname(dirname(__FILE__))), 'catalogue');
	$criteria = new icms_db_criteria_Compo();
	$criteria->add(new icms_db_criteria_Item('online_status', TRUE));
	$criteria->setStart(0);
	$criteria->setLimit($options[0]);
	$criteria->setSort('date');
	$criteria->setOrder('DESC');
	$block['catalogue_items'] = $catalogue_item_handler->getObjects($criteria, TRUE, TRUE);
	foreach ($block['catalogue_items'] as $key => &$value) {
		$date = $value->getVar('date', 'e');
		$value = $value->toArray();
		$value['date'] = date('j/n/Y', $date);
	}
	return $block;
}

function catalogue_item_recent_edit($options) {
	include_once(ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
		. '/include/common.php');
	$catalogue_item_handler = icms_getModuleHandler('item',
		basename(dirname(dirname(__FILE__))), 'catalogue');

	// select number of recent soundtracks to display in the block
	$form = '<table><tr>';
	$form .= '<tr><td>' . _MB_CATALOGUE_ITEM_RECENT_LIMIT . '</td>';
	$form .= '<td>' . '<input type="text" name="options[]" value="' . $options[0] . '"/></td>';
	$form .= '</tr></table>';
	return $form;
}
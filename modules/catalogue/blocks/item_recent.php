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
	
	$block['catalogue_items'] = $catalogue_item_array = array();
	$catalogueModule = icms_getModuleInfo('catalogue');
	$sprocketsModule = icms_getModuleInfo('sprockets');
	$catalogue_item_handler = icms_getModuleHandler('item', basename(dirname(dirname(__FILE__))),
			'catalogue');
	
	// Check for dynamic tag filtering, including untagged content
	$untagged_content = FALSE;
	if ($options[2] == 1 && isset($_GET['tag_id'])) {
		$untagged_content = ($_GET['tag_id'] == 'untagged') ? TRUE : FALSE;
		$options[1] = (int)trim($_GET['tag_id']);
	}
	
	// Retrieve the last XX items
	// Filter by tag
	if (icms_get_module_status("sprockets") && ($options[1] || $untagged_content)) {
		$query = $rows = $tag_article_count = '';
		$article_object_array = array();
		$sprockets_taglink_handler = icms_getModuleHandler('taglink', $sprocketsModule->getVar('dirname'),
				'sprockets');
		$query = "SELECT * FROM " . $catalogue_item_handler->table . ", "
					. $sprockets_taglink_handler->table
					. " WHERE `item_id` = `iid`"
					. " AND `online_status` = '1'";
		if ($untagged_content) {
			$options[1] = 0;
		}
		$query .= " AND `tid` = '" . $options[1] . "'"
					. " AND `mid` = '" . $catalogueModule->getVar('mid') . "'"
					. " AND `item` = 'item'"
					. " ORDER BY `date` DESC"
					. " LIMIT " . '0, ' . $options[0]++;

		$result = icms::$xoopsDB->query($query);

		if (!$result) {
			echo 'Error: Recent items block';
			exit;

		} else {
			$rows = $catalogue_item_handler->convertResultSet($result, TRUE, TRUE);
			foreach ($rows as $key => $row) {
				$catalogue_item_array[$row->getVar('item_id')] = $row;
			}
		}
	} else { // do not filter by tag
		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('online_status', TRUE));
		$criteria->setStart(0);
		$criteria->setLimit($options[0]);
		$criteria->setSort('date');
		$criteria->setOrder('DESC');
		$catalogue_item_array = $catalogue_item_handler->getObjects($criteria, TRUE, TRUE);
	}
	if (count($catalogue_item_array)) {
		foreach ($catalogue_item_array as $key => $value) {
			$item = array();
			$item['date'] = date('j/n/Y', $value->getVar('date', 'e'));
			$item['itemLink'] = $value->getItemLinkWithSEOString();
			$block['catalogue_items'][] = $item;
			unset($item);
		}
	} else {
		$block = array();
	}
	return $block;
}

function catalogue_item_recent_edit($options) {
	include_once(ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
		. '/include/common.php');
	$catalogueModule = icms_getModuleInfo('catalogue');
	$catalogue_item_handler = icms_getModuleHandler('item',
		basename(dirname(dirname(__FILE__))), 'catalogue');

	// Select number of recent items to display in the block
	$form = '<table><tr>';
	$form .= '<tr><td>' . _MB_CATALOGUE_ITEM_RECENT_LIMIT . '</td>';
	$form .= '<td>' . '<input type="text" name="options[]" value="' . $options[0] . '"/></td></tr>';
	
	// Optionally display results from a single tag - but only if sprockets module is installed
	$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");

	if (icms_get_module_status("sprockets"))
	{
		$sprockets_tag_handler = icms_getModuleHandler('tag', $sprocketsModule->getVar('dirname'), 'sprockets');
		$sprockets_taglink_handler = icms_getModuleHandler('taglink', $sprocketsModule->getVar('dirname'), 'sprockets');
		
		// Get only those tags that contain content from this module
		$criteria = '';
		$relevant_tag_ids = array();
		$criteria = icms_buildCriteria(array('mid' => $catalogueModule->getVar('mid')));
		$catalogue_module_taglinks = $sprockets_taglink_handler->getObjects($criteria, TRUE, TRUE);
		foreach ($catalogue_module_taglinks as $key => $value)
		{
			$relevant_tag_ids[] = $value->getVar('tid');
		}
		$relevant_tag_ids = array_unique($relevant_tag_ids);
		$relevant_tag_ids = '(' . implode(',', $relevant_tag_ids) . ')';
		unset($criteria);

		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('tag_id', $relevant_tag_ids, 'IN'));
		$criteria->add(new icms_db_criteria_Item('label_type', '0'));
		$tagList = $sprockets_tag_handler->getList($criteria);

		$tagList = array(0 => _MB_CATALOGUE_ALL) + $tagList;
		$form .= '<tr><td>' . _MB_CATALOGUE_TAG . '</td>';
		// Parameters icms_form_elements_Select: ($caption, $name, $value = null, $size = 1, $multiple = TRUE)
		$form_select = new icms_form_elements_Select('', 'options[1]', $options[1], '1', FALSE);
		$form_select->addOptionArray($tagList);
		$form .= '<td>' . $form_select->render() . '</td></tr>';
		
		// Dynamic tagging (overrides static tag filter)
		$form .= '<tr><td>' . _MB_CATALOGUE_DYNAMIC_TAG . '</td>';			
		$form .= '<td><input type="radio" name="options[2]" value="1"';
		if ($options[2] == 1) {
			$form .= ' checked="checked"';
		}
		$form .= '/>' . _MB_CATALOGUE_PROJECT_YES;
		$form .= '<input type="radio" name="options[2]" value="0"';
		if ($options[2] == 0) {
			$form .= 'checked="checked"';
		}
		$form .= '/>' . _MB_CATALOGUE_PROJECT_NO . '</td></tr>';
	}
	$form .= '</table>';
	return $form;
}
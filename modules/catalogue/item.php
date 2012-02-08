<?php
/**
* Item page
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

include_once 'header.php';

$xoopsOption['template_main'] = 'catalogue_item.html';
include_once ICMS_ROOT_PATH . '/header.php';

global $icmsConfig;

$catalogue_item_handler = icms_getModuleHandler('item');

$sprocketsModule = icms_getModuleInfo('sprockets');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0 ;
$itemObj = $catalogue_item_handler->get($clean_item_id);

// check if single item is set online, if not, torch it
if ($itemObj->getVar('online_status', 'e') == FALSE) {
	unset($itemObj);
}

// get relative path to document root for this ICMS install
// this is required to call the image correctly if ICMS is installed in a subdirectory
$directory_name = basename(dirname(__FILE__));
$script_name = getenv("SCRIPT_NAME");
$document_root = str_replace('modules/' . $directory_name . '/item.php', '', $script_name);

if($itemObj && !$itemObj->isNew()) {
	
	////////////////////////////////////////////////////////////////
	//////////////////// DISPLAY SINGLE ITEM // ////////////////////
	////////////////////////////////////////////////////////////////
	
	// Update hit counter
	if (!icms_userIsAdmin(icms::$module->getVar('dirname')))
	{
		$catalogue_item_handler->updateCounter($itemObj);
	}
	
	// display single item
	$catalogue_item = $itemObj->toArray();
	$catalogue_item['image'] = $document_root . 'uploads/' . $directory_name . '/item/'
				. $itemObj->getVar('image', 'e');
	$catalogue_item['image_width'] = icms::$module->config['image_width'];
	$catalogue_item['image_height'] = icms::$module->config['image_height'];
	if (icms::$module->config['show_prices'] == 0) {
		unset($catalogue_item['price']);
	}
	$icmsTpl->assign('catalogue_base_currency', icms::$module->config['base_currency']);
	$icmsTpl->assign('catalogue_single_item', $catalogue_item);
	if (icms::$module->config['view_cart']) {
		$icmsTpl->assign('catalogue_view_cart', icms::$module->config['view_cart']);
	}
	$icmsTpl->assign('catalogue_single_view', TRUE);
	
	// comments
	if (icms::$module->config['com_rule']) {
		$icmsTpl->assign('catalogue_item_comment', TRUE);
		include_once ICMS_ROOT_PATH . '/include/comment_view.php';
	}

	/**
	 * Generating meta information for this page
	 */
	$icms_metagen = new icms_ipf_Metagen($itemObj->getVar('title'),
	$itemObj->getVar('meta_keywords','n'),
	$itemObj->getVar('meta_description', 'n'));
	$icms_metagen->createMetaTags();
	
} else {
	
	////////////////////////////////////////////////////////////////
	///////////////// DISPLAY ITEM INDEX PAGE //////////////////////
	////////////////////////////////////////////////////////////////
	
	$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$clean_tag_id = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : 0;
	
	$item_count = $style = $number_items_per_row = '';
	$itemObjects = $catalogue_items = array();
	
	// Prepare a tag select box if sprockets module is installed & set in module preferences
	if (icms_get_module_status("sprockets")) {

		// initialise
		$form = '';
		$tag_buffer = $tagList = array();
		$sprockets_tag_handler = icms_getModuleHandler('tag',
				$sprocketsModule->getVar('dirname'), 'sprockets');
		$sprockets_taglink_handler = icms_getModuleHandler('taglink', 
			$sprocketsModule->getVar('dirname'), 'sprockets');

		// prepare buffers to reduce queries
		$tag_buffer = $sprockets_tag_handler->getObjects(null, TRUE, TRUE);

		// append the tag to the News title and link RSS to tag-specific feed
		if (array_key_exists($clean_tag_id, $tag_buffer) && ($clean_tag_id !== 0)) {
			$icmsTpl->assign('catalogue_tag_name', $tag_buffer[$clean_tag_id]->title());
			$icmsTpl->assign('catalogue_category_path', $tag_buffer[$clean_tag_id]->title());
		} else {
			$icmsTpl->assign('catalogue_tag_name', _CO_CATALOGUE_ITEM_ALL_ITEMS);
		}
		if (icms::$module->config['show_tag_select_box'] == TRUE) {
			// prepare a tag navigation select box
			$tag_select_box = $sprockets_tag_handler->getTagSelectBox('item.php', $clean_tag_id,
				_CO_CATALOGUE_ITEM_SELECT_ITEMS, TRUE, icms::$module->getVar('mid'));
			$icmsTpl->assign('catalogue_tag_select_box', $tag_select_box);
			$icmsTpl->assign('catalogue_show_tag_select_box', TRUE);
		}
	}

	// RSS feed including autodiscovery link, which is inserted in the module header
	global $xoTheme;
	if (icms_get_module_status("sprockets") && $clean_tag_id) {
		$icmsTpl->assign('catalogue_rss_link', 'rss.php?tag_id=' . $clean_tag_id);
		$icmsTpl->assign('catalogue_rss_title', _CO_CATALOGUE_SUBSCRIBE_RSS_ON
				. $tag_buffer[$clean_tag_id]->title());
		$rss_attributes = array('type' => 'application/rss+xml',
			'title' => $icmsConfig['sitename'] . ' - ' . $tag_buffer[$clean_tag_id]->title());
		$rss_link = CATALOGUE_URL . 'rss.php?tag_id=' . $clean_tag_id;
	} else {
			$icmsTpl->assign('catalogue_rss_link', 'rss.php');
			$icmsTpl->assign('catalogue_rss_title', _CO_CATALOGUE_SUBSCRIBE_RSS);
			$rss_attributes = array('type' => 'application/rss+xml',
				'title' => $icmsConfig['sitename'] . ' - ' .  _CO_CATALOGUE_NEW);
			$rss_link = CATALOGUE_URL . 'rss.php';
	}
	$xoTheme->addLink('alternate', $rss_link, $rss_attributes);
	
	// list of articles, filtered by tags (if any), pagination and preferences
	$itemObjects = array();

	if ($clean_tag_id && icms_get_module_status("sprockets")) {

		/**
		 * Retrieve a list of items JOINED to taglinks by item_id/tag_id/module_id/item
		 */

		global $xoopsDB;
		$query = $rows = $tag_item_count = '';
		$linked_item_ids = array();
		$catalogueModule = icms_getModuleInfo(basename(dirname(__FILE__)));

		// first, count the number of items for the pagination control
		$group_query = "SELECT count(*) FROM " . $catalogue_item_handler->table . ", "
				. $sprockets_taglink_handler->table
				. " WHERE `item_id` = `iid`"
				. " AND `online_status` = '1'"
				. " AND `tid` = '" . $clean_tag_id . "'"
				. " AND `mid` = '" . $catalogueModule->getVar('mid') . "'"
				. " AND `item` = 'item'";

		$result = $xoopsDB->query($group_query);

		if (!$result) {
			echo 'Error';
			exit;

		} else {
			while ($row = $xoopsDB->fetchArray($result)) {
				foreach ($row as $key => $count) {
					$item_count = $count;
				}

			}
			unset($result);
		}

		// second, get the items
		$query = "SELECT * FROM " . $catalogue_item_handler->table . ", "
				. $sprockets_taglink_handler->table
				. " WHERE `item_id` = `iid`"
				. " AND `online_status` = '1'"
				. " AND `tid` = '" . $clean_tag_id . "'"
				. " AND `mid` = '" . $catalogueModule->getVar('mid') . "'"
				. " AND `item` = 'item'"
				. " ORDER BY `weight` ASC"
				. " LIMIT " . $clean_start . ", " . icms::$module->config['number_items_per_page'];

		$result = $xoopsDB->query($query);

		if (!$result) {
			echo 'Error';
			exit;

		} else {

			$rows = $catalogue_item_handler->convertResultSet($result);
			foreach ($rows as $key => $row) {
				$itemObjects[$row->getVar('item_id')] = $row;
			}
		}

	} else {

		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('online_status', TRUE));

		// grab the total item count first
		$item_count = $catalogue_item_handler->getCount($criteria);

		$criteria->setStart($clean_start);
		$criteria->setLimit(icms::$module->config['number_items_per_page']);
		$criteria->setSort('weight');
		$criteria->setOrder('ASC');
		$number_items_per_row = icms::$module->config['number_items_per_row'];
		$itemObjects = $catalogue_item_handler->getObjects($criteria, TRUE, TRUE);
	}

	unset($criteria);
	
	foreach ($itemObjects as $itemObj) {
		$item = $itemObj->toArray();
		$item['image'] = $document_root . 'uploads/' . $directory_name . '/item/'
				. $itemObj->getVar('image', 'e');
		$item['number_items_per_row'] = icms::$module->config['number_items_per_row'];
		$item['thumbnail_width'] = icms::$module->config['thumbnail_width'];
		$item['thumbnail_height'] = icms::$module->config['thumbnail_height'];
		if (icms::$module->config['show_prices'] == 0) {
			unset($item['price']);
		}
		$catalogue_items[] = $item;
	}

	// split the catalogue items into a multi-dimensional array with each element representing a row
	$catalogue_item_rows = array_chunk($catalogue_items, icms::$module->config['number_items_per_row']);

	// prepare margins according to module preferences
	$catalogue_row_margins = 'style="margin:' . icms::$module->config['thumbnail_margin_top'] . 'px '
		. '0px ' . icms::$module->config['thumbnail_margin_bottom'] . 'px 0px;"';
	$catalogue_item_margins = 'align="center" style="display:inline-block; margin: 0px ' . icms::$module->config['thumbnail_margin_right']
		. 'px 0px ' . icms::$module->config['thumbnail_margin_left'] . 'px;"';

	// pagination
	if (!empty($clean_tag_id)) {
		$extra_arg = 'tag_id=' . $clean_tag_id;
	} else {
		$extra_arg = FALSE;
	}
	$pagenav = new icms_view_PageNav($item_count, icms::$module->config['number_items_per_page'],
			$clean_start, 'start', $extra_arg);

	// assign to template
	$icmsTpl->assign('catalogue_title', _MD_CATALOGUE_ALL_ITEMS);
	$icmsTpl->assign('catalogue_item_rows', $catalogue_item_rows);
	$icmsTpl->assign('catalogue_row_margins', $catalogue_row_margins);
	$icmsTpl->assign('catalogue_item_margins', $catalogue_item_margins);
	$icmsTpl->assign('catalogue_base_currency', icms::$module->config['base_currency']);
	$icmsTpl->assign('catalogue_navbar', $pagenav->renderNav());
	$icmsTpl->assign('catalogue_index_view', TRUE);
}

$icmsTpl->assign('catalogue_show_breadcrumb', icms::$module->config['show_breadcrumb']);
$icmsTpl->assign('catalogue_page_title', icms::$module->getVar('name'));
$icmsTpl->assign('catalogue_module_home', catalogue_getModuleName(TRUE, TRUE));

include_once 'footer.php';
<?php
/**
 * Generates RSS feeds of products
 *
 * Also allows the possibility (not in use here) of adding media enclosures to item, thereby allowing
 * podcast clients to automatically retrieve files from the feeds. It uses a modified
 * icmsfeed.php and rss template - these have been built into the module in the interests of
 * a zero post-installation config. Per-category feed functionality will be added when categories
 * are implemented in a future version.
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		catalogue
 * @version		$Id$
 */

/** Include the module's header for all pages */
include_once 'header.php';
include_once ICMS_ROOT_PATH.'/header.php';

function encode_entities($field) {
	$field = htmlspecialchars(html_entity_decode($field, ENT_QUOTES, 'UTF-8'), 
		ENT_NOQUOTES, 'UTF-8');
	return $field;
}

global $catalogueConfig;
$sort_order = '';

$clean_tag_id = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : FALSE;

include_once ICMS_ROOT_PATH . '/modules/' . basename(dirname(__FILE__))
	. '/class/icmsfeed.php';
$catalogue_feed = new IcmsFeed();
$catalogue_item_handler = icms_getModuleHandler('item', basename(dirname(__FILE__)), 'catalogue');
$catalogueModule = icms_getModuleInfo(basename(dirname(__FILE__)));
$sprocketsModule = icms_getModuleInfo('sprockets');

if (icms_get_module_status("sprockets")) {
	$sprockets_taglink_handler = icms_getModuleHandler('taglink',
			$sprocketsModule->getVar('dirname'), 'sprockets');
	$sprockets_tag_handler = icms_getModuleHandler('tag',
			$sprocketsModule->getVar('dirname'), 'sprockets');
}

// generates a feed of recent items across all tags
if (empty($clean_tag_id) || !icms_get_module_status("sprockets")) {
	$feed_title = _CO_CATALOGUE_NEW;
	$site_name = encode_entities($icmsConfig['sitename']);

	$catalogue_feed->title = $site_name . ' - ' . $feed_title;
	$catalogue_feed->url = CATALOGUE_URL;
	$catalogue_feed->description = _CO_CATALOGUE_NEW_DSC . $site_name . '.';
	$catalogue_feed->language = _LANGCODE;
	$catalogue_feed->charset = _CHARSET;
	$catalogue_feed->category = $catalogueModule->getVar('name');

	$url = ICMS_URL . 'images/logo.gif';
	$catalogue_feed->image = array('title' => $catalogue_feed->title, 'url' => $url,
			'link' => CATALOGUE_URL . 'item.php');
	$width = 144;
	$catalogue_feed->width = $width;
	$catalogue_feed->atom_link = '"' . CATALOGUE_URL . 'rss.php"';

	// use criteria to retrieve the most recent (online) items as per module preferences
	$criteria = new icms_db_criteria_Compo();
	$criteria->add(new icms_db_criteria_Item('online_status', TRUE));
	$criteria->setStart(0);
	$criteria->setLimit($catalogueConfig['number_rss_items']);
	$criteria->setSort('date');
	$criteria->setOrder('DESC');

	$itemArray = $catalogue_item_handler->getObjects($criteria);

} else {
	
	// need to remove html tags and problematic characters to meet RSS spec
	$tagObj = $sprockets_tag_handler->get($clean_tag_id);
	$site_name = encode_entities($icmsConfig['sitename']);
	$tag_title = encode_entities($tagObj->getVar('title'));
	$tag_description = strip_tags($tagObj->getVar('description'));
	$tag_description = encode_entities($tag_description);

	$catalogue_feed->title = $site_name . ' - ' . $tag_title;
	$catalogue_feed->url = ICMS_URL;
	$catalogue_feed->description = $tag_description;
	$catalogue_feed->language = _LANGCODE;
	$catalogue_feed->charset = _CHARSET;
	$catalogue_feed->category = $catalogueModule->getVar('name');

	$url = ICMS_URL . 'images/logo.gif';
	$catalogue_feed->image = array('title' => $catalogue_feed->title, 'url' => $url,
			'link' => CATALOGUE_URL . 'rss.php?tag_id=' . $tagObj->id());
	$catalogue_feed->width = 144;
	$catalogue_feed->atom_link = '"' . CATALOGUE_URL . 'rss.php?tag_id=' . $tagObj->id() . '"';
	
	// retrieve items relevant to this tag using a JOIN to the taglinks table

	global $xoopsDB;

	$query = $rows = $tag_item_count = '';

	$query = "SELECT * FROM " . $catalogue_item_handler->table . ", "
			. $sprockets_taglink_handler->table
			. " WHERE `item_id` = `iid`"
			. " AND `online_status` = '1'"
			. " AND `tid` = '" . $clean_tag_id . "'"
			. " AND `mid` = '" . $catalogueModule->getVar('mid') . "'"
			. " AND `item` = 'item'"
			. " ORDER BY `date` DESC"
			. " LIMIT " . $catalogueConfig['number_rss_items'];

	$result = $xoopsDB->query($query);

	if (!$result) {
		echo 'Error';
		exit;

	} else {

		$rows = $catalogue_item_handler->convertResultSet($result);
		foreach ($rows as $key => $row) {
			$itemArray[$row->getVar('item_id')] = $row;
		}
	}
}

// prepare an array of items
$member_handler = icms::handler("icms_member");

foreach($itemArray as $item) {
	$flattened_item = $item->toArray();
	$user = & $member_handler->getUser($item->getVar('submitter', 'e'));
	$creator = $user->getVar('uname');
	
	$creator = encode_entities($creator);
	$description = encode_entities($flattened_item['description']);
	$title = encode_entities($flattened_item['title']);
	$link = encode_entities($flattened_item['itemUrl']);

	$catalogue_feed->feeds[] = array (
		'title' => $title,
		'link' => $link,
		'description' => $description,
		'author' => $creator,
		// pubdate must be a RFC822-date-time EXCEPT with 4-digit year or the feed won't validate
		'pubdate' => date(DATE_RSS, $item->getVar('date')),
		'guid' => $link,
		'category' => $tag_title);
}

$catalogue_feed->render();
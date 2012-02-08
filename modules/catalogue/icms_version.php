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

/**  General Information  */
$modversion = array(
  'name'=> _MI_CATALOGUE_MD_NAME,
  'version'=> 1.1,
  'description'=> _MI_CATALOGUE_MD_DESC,
  'author'=> "Madfish (Simon Wilkinson)",
  'credits'=> "",
  'help'=> "",
  'license'=> "GNU General Public License (GPL)",
  'official'=> 0,
  'dirname'=> basename( dirname( __FILE__ ) ),

/**  Images information  */
  'iconsmall'=> "images/icon_small.png",
  'iconbig'=> "images/icon_big.png",
  'image'=> "images/icon_big.png", /* for backward compatibility */

/**  Development information */
  'status_version'=> "1.1",
  'status'=> "Beta",
  'date'=> "8/2/2012",
  'author_word'=> "Live long and prosper.",

/** Contributors */
  'developer_website_url' => "https://www.isengard.biz",
  'developer_website_name' => "Isengard.biz",
  'developer_email' => "simon@isengard.biz");

$modversion['people']['developers'][] = "Madfish (Simon Wilkinson)";

/** Manual */
$modversion['manual']['wiki'][] = "<a href='http://wiki.impresscms.org/index.php?title=Catalogue' target='_blank'>English</a>";

/** Administrative information */
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

/** Database information */
$modversion['object_items'][1] = 'item';
$modversion['object_items'][] = 'order';

$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/** Install and update informations */
$modversion['onInstall'] = "include/onupdate.inc.php";
$modversion['onUpdate'] = "include/onupdate.inc.php";

/** Search information */
$modversion['hasSearch'] = 1;
$modversion['search'] = array (
  'file' => "include/search.inc.php",
  'func' => "catalogue_search");

/** Menu information */
$modversion['hasMain'] = 1;

/** Blocks information */
$modversion['blocks'][1] = array(
  'file' => 'item_recent.php',
  'name' => _MI_CATALOGUE_ITEMRECENT,
  'description' => _MI_CATALOGUE_ITEMRECENTDSC,
  'show_func' => 'catalogue_item_recent_show',
  'edit_func' => 'catalogue_item_recent_edit',
  'options' => '5',
  'template' => 'catalogue_item_recent.html');

/** Templates information */
$modversion['templates'][1] = array(
  'file' => 'catalogue_header.html',
  'description' => 'Module Header');

$modversion['templates'][] = array(
  'file' => 'catalogue_footer.html',
  'description' => 'Module Footer');

$modversion['templates'][] = array(
  'file' => 'catalogue_rss.html',
  'description' => 'RSS feed');

$modversion['templates'][] = array(
  'file' => 'catalogue_requirements.html',
  'description' => 'Module Requirements');

$modversion['templates'][]= array(
  'file' => 'catalogue_admin_item.html',
  'description' => 'Item Admin Index');

$modversion['templates'][]= array(
  'file' => 'catalogue_item.html',
  'description' => 'Item Index, this is the main template for displaying the catalogue.');

$modversion['templates'][]= array(
  'file' => 'catalogue_admin_order.html',
  'description' => 'Order Admin Index');

$modversion['templates'][]= array(
  'file' => 'catalogue_order.html',
  'description' => 'Order Index');

/** Preferences information */

$modversion['config'][] = array(
	'name' => 'base_currency',
	'title' => '_MI_CATALOGUE_BASE_CURRENCY',
	'description' => '_MI_CATALOGUE_BASE_CURRENCYDSC',
	'formtype' => 'textbox',
	'valuetype' => 'text',
	'default' =>  'US$');

$modversion['config'][] = array(
	'name' => 'show_prices',
	'title' => '_MI_CATALOGUE_SHOW_PRICES',
	'description' => '_MI_CATALOGUE_SHOW_PRICESDSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' =>  '1');

$modversion['config'][] = array(
	'name' => 'show_breadcrumb',
	'title' => '_MI_CATALOGUE_SHOW_BREADCRUMB',
	'description' => '_MI_CATALOGUE_SHOW_BREADCRUMB_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
	'name' => 'show_tag_select_box',
	'title' => '_MI_CATALOGUE_SHOW_TAG_SELECT_BOX',
	'description' => '_MI_CATALOGUE_SHOW_TAG_SELECT_BOXDSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' =>  '1');

$modversion['config'][] = array(
	'name' => 'number_items_per_row',
	'title' => '_MI_CATALOGUE_NUMBER_ITEMS_PER_ROW',
	'description' => '_MI_CATALOGUE_NUMBER_ITEMS_PER_ROWDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '4');

$modversion['config'][] = array(
	'name' => 'number_items_per_page',
	'title' => '_MI_CATALOGUE_NUMBER_ITEMS_PER_PAGE',
	'description' => '_MI_CATALOGUE_NUMBER_ITEMS_PER_PAGEDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '20');

$modversion['config'][] = array(
	'name' => 'thumbnail_width',
	'title' => '_MI_CATALOGUE_THUMBNAIL_WIDTH',
	'description' => '_MI_CATALOGUE_THUMBNAIL_WIDTHDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '110');

$modversion['config'][] = array(
	'name' => 'thumbnail_height',
	'title' => '_MI_CATALOGUE_THUMBNAIL_HEIGHT',
	'description' => '_MI_CATALOGUE_THUMBNAIL_HEIGHTDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '150');

$modversion['config'][] = array(
	'name' => 'thumbnail_margin_top',
	'title' => '_MI_CATALOGUE_THUMBNAIL_MARGIN_TOP',
	'description' => '_MI_CATALOGUE_THUMBNAIL_MARGINDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '10');

$modversion['config'][] = array(
	'name' => 'thumbnail_margin_right',
	'title' => '_MI_CATALOGUE_THUMBNAIL_MARGIN_RIGHT',
	'description' => '_MI_CATALOGUE_THUMBNAIL_MARGINDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '15');

$modversion['config'][] = array(
	'name' => 'thumbnail_margin_bottom',
	'title' => '_MI_CATALOGUE_THUMBNAIL_MARGIN_BOTTOM',
	'description' => '_MI_CATALOGUE_THUMBNAIL_MARGINDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '10');

$modversion['config'][] = array(
	'name' => 'thumbnail_margin_left',
	'title' => '_MI_CATALOGUE_THUMBNAIL_MARGIN_LEFT',
	'description' => '_MI_CATALOGUE_THUMBNAIL_MARGINDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '15');

$modversion['config'][] = array(
	'name' => 'image_width',
	'title' => '_MI_CATALOGUE_IMAGE_WIDTH',
	'description' => '_MI_CATALOGUE_IMAGE_WIDTHDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '1024');

$modversion['config'][] = array(
	'name' => 'image_height',
	'title' => '_MI_CATALOGUE_IMAGE_HEIGHT',
	'description' => '_MI_CATALOGUE_IMAGE_HEIGHTDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '768');

$modversion['config'][] = array(
	'name' => 'image_upload_width',
	'title' => '_MI_CATALOGUE_IMAGE_UPLOAD_WIDTH',
	'description' => '_MI_CATALOGUE_IMAGE_UPLOAD_WIDTHDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '1024');

$modversion['config'][] = array(
	'name' => 'image_upload_height',
	'title' => '_MI_CATALOGUE_IMAGE_UPLOAD_HEIGHT',
	'description' => '_MI_CATALOGUE_IMAGE_UPLOAD_HEIGHTDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '768');

$modversion['config'][] = array(
	'name' => 'image_file_size',
	'title' => '_MI_CATALOGUE_IMAGE_FILE_SIZE',
	'description' => '_MI_CATALOGUE_IMAGE_FILE_SIZEDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '2097152'); // 2MB default max upload size

$modversion['config'][] = array(
	'name' => 'number_rss_items',
	'title' => '_MI_CATALOGUE_RSS_ITEMS',
	'description' => '_MI_CATALOGUE_RSS_ITEMSDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '10');

$modversion['config'][] = array(
	'name' => 'view_cart',
	'title' => '_MI_CATALOGUE_VIEW_CART',
	'description' => '_MI_CATALOGUE_VIEW_CARTDSC',
	'formtype' => 'textarea',
	'valuetype' => 'text',
	'default' =>  '');

/** Comments information */
$modversion['hasComments'] = 1;

$modversion['comments'] = array(
  'itemName' => 'item_id',
  'pageName' => 'item.php',
  /* Comment callback functions */
  'callbackFile' => 'include/comment.inc.php',
  'callback' => array(
    'approve' => 'catalogue_com_approve',
    'update' => 'catalogue_com_update')
    );

/** Notification information */

$modversion['hasNotification'] = 1;

$modversion['notification'] = array (
  'lookup_file' => 'include/notification.inc.php',
  'lookup_func' => 'catalogue_notify_iteminfo');

// Notification categories

$modversion['notification']['category'][1] = array (
  'name' => 'global',
  'title' => _MI_CATALOGUE_GLOBAL_NOTIFY,
  'description' => _MI_CATALOGUE_GLOBAL_NOTIFY_DSC,
  'subscribe_from' => array('item.php'));

// Notification events - global

$modversion['notification']['event'][1] = array(
  'name' => 'item_published',
  'category'=> 'global',
  'title'=> _MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY,
  'caption'=> _MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY_CAP,
  'description'=> _MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY_DSC,
  'mail_template'=> 'global_item_published',
  'mail_subject'=> _MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY_SBJ);
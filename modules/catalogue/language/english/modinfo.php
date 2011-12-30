<?php
/**
* English language constants related to module information
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

global $icmsModule;
define("_MI_CATALOGUE_MD_NAME", "Catalogue");
define("_MI_CATALOGUE_MD_DESC", "Catalogue module for ImpressCMS");

define("_MI_CATALOGUE_ITEMS", "Items");
define("_MI_CATALOGUE_ORDERS", "Orders");
define("_MI_CATALOGUE_TEMPLATES", "Templates");

define("_MI_CATALOGUE_NUMBER_ITEMS_PER_ROW", "Number of items displayed per row");
define("_MI_CATALOGUE_NUMBER_ITEMS_PER_ROWDSC", "This is the number of products that will be
	displayed per row in catalogue view. You need to consider the width of your site, and the
	width	of the thumbnails and margins you assign to the items. If you put more than can fit on
	the page it will mangle your layout.");
define("_MI_CATALOGUE_NUMBER_ITEMS_PER_PAGE", "Number of items per page");
define("_MI_CATALOGUE_NUMBER_ITEMS_PER_PAGEDSC", "The number of products that will be displayed in 
	group view on the catalogue index page. If there are more than this, pagination links will be
	automatically generated.");
define("_MI_CATALOGUE_BASE_CURRENCY", "Base currency symbol");
define("_MI_CATALOGUE_BASE_CURRENCYDSC", "Enter the currency prefix you want do display for your
	default currency, for example US$.");
define("_MI_CATALOGUE_SHOW_PRICES", "Display prices?");
define("_MI_CATALOGUE_SHOW_PRICESDSC", "If you want to sell things, set this to 'yes' otherwise
	pricing information will not be shown.");

define("_MI_CATALOGUE_THUMBNAIL_HEIGHT", "Thumbnail height (in pixels)");
define("_MI_CATALOGUE_THUMBNAIL_HEIGHTDSC", "Thumbnails are the product images displayed when an 
	item is viewed in group mode (ie. in a collection or listing). Aspect ratio will be preserved,
	so it will be the largest of the width and height preferences that is the constraint.");
define("_MI_CATALOGUE_THUMBNAIL_WIDTH", "Thumbnail width (in pixels)");
define("_MI_CATALOGUE_THUMBNAIL_WIDTHDSC", "Thumbnails are the product images displayed when an 
	item is viewed in group mode (ie. in a collection or listing). Aspect ratio will be preserved,
	so it will be the largest of the width and height preferences that is the constraint.");
define("_MI_CATALOGUE_THUMBNAIL_MARGIN_TOP", "Thumbnail TOP margin");
define("_MI_CATALOGUE_THUMBNAIL_MARGIN_RIGHT", "Thumbnail RIGHT margin");
define("_MI_CATALOGUE_THUMBNAIL_MARGIN_BOTTOM", "Thumbnail BOTTOM margin");
define("_MI_CATALOGUE_THUMBNAIL_MARGIN_LEFT", "Thumbnail LEFT margin");
define("_MI_CATALOGUE_THUMBNAIL_MARGINDSC", "Sets the margin around each product thumbnail image,
	use this to tweak your catalogue layout to your satisfaction.");

define("_MI_CATALOGUE_IMAGE_HEIGHT", "Item maximum display height in single view (pixels)");
define("_MI_CATALOGUE_IMAGE_HEIGHTDSC", "The maximum height that item photos will be displayed at
	in single view mode. Images will be scaled with aspect ratio preserved, according to the
	largest dimension specified (width or height). So in reality, image scaling will be constrained
	by either the width or height you have specified, but not both.");
define("_MI_CATALOGUE_IMAGE_WIDTH", "Item maximum display width in single view (pixels)");
define("_MI_CATALOGUE_IMAGE_WIDTHDSC", "The maximum width that items photos will be displayed at in
	single view mode. Images will be scaled with aspect ratio preserved, according to the largest
	dimension specified (width or height). So in reality, image scaling will be constrained by
	either the width or height you have specified, but not both.");
define("_MI_CATALOGUE_IMAGE_UPLOAD_HEIGHT", "Maximum HEIGHT of uploaded images (pixels)");
define("_MI_CATALOGUE_IMAGE_UPLOAD_HEIGHTDSC", "This is the maximum height allowed for uploaded
	images. Don't forget that images will be automatically scaled for display, so its ok to allow
	bigger images than you plan to actually use. In fact, it gives your site a bit of flexibility
	should you decide to change the display settings later.");
define("_MI_CATALOGUE_IMAGE_UPLOAD_WIDTH", "Maximum WIDTH of uploaded images (pixels)");
define("_MI_CATALOGUE_IMAGE_UPLOAD_WIDTHDSC", "This is the maximum width allowed for uploaded
	images. Don't forget that images will be automatically scaled for display, so its ok to allow
	bigger images than you plan to actually use. In fact it gives your site a bit of flexibility
	should you decide to change the display settings later.");
define("_MI_CATALOGUE_IMAGE_FILE_SIZE", "Maximum image FILE SIZE of uploaded images (bytes)");
define("_MI_CATALOGUE_IMAGE_FILE_SIZEDSC", "This is the maximum size (in bytes) allowed for image
	uploads.");
define("_MI_CATALOGUE_VIEW_CART", "Checkout link");
define("_MI_CATALOGUE_VIEW_CARTDSC", "If you are using an external ecommerce provider, enter the 
	html snippet they gave you to view the shopping cart contents / proceed to checkout. You can make
	links look like buttons using the CSS class 'buttonstyle', which you can modify in module.css.");

// blocks
define("_MI_CATALOGUE_ITEMRECENT", "New products");
define("_MI_CATALOGUE_ITEMRECENTDSC", "Displays the latest products");

// RSS preference
define("_MI_CATALOGUE_RSS_ITEMS", "RSS feed length");
define("_MI_CATALOGUE_RSS_ITEMSDSC", "Number of items you want to include in your RSS feed");
define("_MI_CATALOGUE_SHOW_TAG_SELECT_BOX", "Show tag select box");
define("_MI_CATALOGUE_SHOW_TAG_SELECT_BOXDSC", "Only available if the Sprockets module is installed");
define("_MI_CATALOGUE_SHOW_BREADCRUMB", "Show breadcrumb?");
define("_MI_CATALOGUE_SHOW_BREADCRUMBDSC", "Toggles the breadcrumb navigation on and off");

// notifications
define("_MI_CATALOGUE_GLOBAL_NOTIFY","All content");
define("_MI_CATALOGUE_GLOBAL_NOTIFY_DSC", "Notifications related to all products in this module.");
define("_MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY", "New product released");
define("_MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY_CAP", "Notify me when a new product is released.");
define("_MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY_DSC", "Receive notification when a new product
	is released");
define("_MI_CATALOGUE_GLOBAL_ITEM_PUBLISHED_NOTIFY_SBJ", "New product released at {X_SITENAME}");

define("_MI_CATALOGUE_RELEASE_CANDIDATE", "Release candidate 1");
define("_MI_CATALOGUE_MANUAL", "Manual");
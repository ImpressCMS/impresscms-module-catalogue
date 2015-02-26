<?php
/**
* English language constants commonly used in the module
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// items
define("_CO_CATALOGUE_ITEM_TITLE", "Title");
define("_CO_CATALOGUE_ITEM_TITLE_DSC", " Name of the item.");
define("_CO_CATALOGUE_ITEM_TAG", "Tags");
define("_CO_CATALOGUE_ITEM_TAG_DSC", " Specify the sections of your catalogue you would like this 
	item to display in.");
define("_CO_CATALOGUE_ITEM_DESCRIPTION", "Description");
define("_CO_CATALOGUE_ITEM_DESCRIPTION_DSC", " Optional description of the item.");
define("_CO_CATALOGUE_ITEM_IDENTIFIER", "Model");
define("_CO_CATALOGUE_ITEM_IDENTIFIER_DSC", " Interal reference for your own usage.");
define("_CO_CATALOGUE_ITEM_WEIGHT", "Order (weight)");
define("_CO_CATALOGUE_ITEM_WEIGHT_DSC", " The order in which this item will appear on the catalogue
	page. Lower numbers appear first.");
define("_CO_CATALOGUE_ITEM_PRICE", "Price");
define("_CO_CATALOGUE_ITEM_PRICE_DSC", " Enter the price in your base currency.");
define("_CO_CATALOGUE_ITEM_SHIPPING", "Shipping cost");
define("_CO_CATALOGUE_ITEM_SHIPPING_DSC", " Shipping cost in your base currency.");
define("_CO_CATALOGUE_ITEM_IMAGE", "Photo");
define("_CO_CATALOGUE_ITEM_IMAGE_DSC", " You <strong>MUST</strong> upload a picture or screenshot
	of the item as Catalogue is a visually-oriented module. Upload an original image that is a bit
	larger than you intend to use for full-sized display. Catalogue will automatically build
	thumbnails and full-size images according to the sizes you specify in the module preferences.");
define("_CO_CATALOGUE_ITEM_ECOMMERCE_LINK", "eCommerce link");
define("_CO_CATALOGUE_ITEM_ECOMMERCE_LINK_DSC", " If you are using an external eCommerce provider, 
	enter the html snippet they gave you to add this product to their shopping cart (ie. 'buy me'). 
	You can make links look like buttons using the CSS class 'buttonstyle', which you can modify in 
	module.css.");
define("_CO_CATALOGUE_ITEM_SUBMITTER", "Submitter");
define("_CO_CATALOGUE_ITEM_SUBMITTER_DSC", "Person responsible for managing this item.");
define("_CO_CATALOGUE_ITEM_DATE", "Date");
define("_CO_CATALOGUE_ITEM_DATE_DSC", "Date item was added to catalogue.");

define("_CO_CATALOGUE_ITEM_ONLINE_STATUS", "Online");
define("_CO_CATALOGUE_ITEM_ONLINE_STATUS_DSC", " Mark the item as online or offline. Items that are
	online are not displayed and do not appear in search results.");
define("_CO_CATALOGUE_BUY_ME", "Buy me!");

// orders
define("_CO_CATALOGUE_ORDER_ITEM_ID", "Item id");
define("_CO_CATALOGUE_ORDER_ITEM_ID_DSC", " Id of the item ordered by the customer.");
define("_CO_CATALOGUE_ORDER_NUMBER_ORDERED", "Number ordered");
define("_CO_CATALOGUE_ORDER_NUMBER_ORDERED_DSC", " Number of this item ordered");

// RSS
define("_CO_CATALOGUE_SUBSCRIBE_RSS", "Subscribe to our newsfeed");
define("_CO_CATALOGUE_SUBSCRIBE_RSS_ON", "Subscribe to our newsfeed on: ");

// tags
define("_CO_CATALOGUE_ITEM_ALL_ITEMS", "All items");
define("_CO_CATALOGUE_ITEM_SELECT_ITEMS", "-- Select items --");

// other stuff
define("_CO_CATALOGUE_NEW", "New products");
define("_CO_CATALOGUE_NEW_DSC", "A selection of the most recent products from ");

// New in V1.15
define("_CO_CATALOGUE_ITEM_CREATOR", "Creator");
define("_CO_CATALOGUE_ITEM_CREATOR_DSC", "The artist or manufacturer of this item.");
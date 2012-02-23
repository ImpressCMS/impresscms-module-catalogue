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

if (!defined("ICMS_ROOT_PATH")) die("ImpressCMS Basispfad nicht definiert");

// items
define("_CO_CATALOGUE_ITEM_TITLE", "Titel");
define("_CO_CATALOGUE_ITEM_TITLE_DSC", " Name des Gegenstandes");
define("_CO_CATALOGUE_ITEM_DESCRIPTION", "Beschreibung");
define("_CO_CATALOGUE_ITEM_DESCRIPTION_DSC", " Optionale Beschreibung des Gegenstandes");
define("_CO_CATALOGUE_ITEM_IDENTIFIER", "Model");
define("_CO_CATALOGUE_ITEM_IDENTIFIER_DSC", " Interne Referenz (Nummer) für die eigene Benutzung.");
define("_CO_CATALOGUE_ITEM_WEIGHT", "Reihenfolgen (nach Gewichtung)");
define("_CO_CATALOGUE_ITEM_WEIGHT_DSC", " Die Reihenfolge der Gegenstände wie sie in der Katalogseite dargestellt werden soll. Die kleinste Zahl wird zuerst angezeigt.");
define("_CO_CATALOGUE_ITEM_PRICE", "Preis");
define("_CO_CATALOGUE_ITEM_PRICE_DSC", " Geben Sie den Preis in Ihrer Basiswährung ein.");
define("_CO_CATALOGUE_ITEM_SHIPPING", "Versandkosten");
define("_CO_CATALOGUE_ITEM_SHIPPING_DSC", " Versandkosten in Ihrer Basiswährung.");
define("_CO_CATALOGUE_ITEM_IMAGE", "Foto");
define("_CO_CATALOGUE_ITEM_IMAGE_DSC", " Es <strong>MUSS</strong> ein Bild hochgeladen werden oder ein Bildschirmfoto des Gegenstandes, da dies zur visuellen Darstellung benötigt wird. Dabei wird das Bild in die Größe wie in den Einstellungen vorgenommen, das Bild in der Größe automatisch anpassen.");
define("_CO_CATALOGUE_ITEM_ECOMMERCE_LINK", "eCommerce Link");
define("_CO_CATALOGUE_ITEM_ECOMMERCE_DSC", " If you are using an external eCommerce provider, enter the html snippet/link they gave you for this product here to link to the order page. Otherwise leave it blank.");
define("_CO_CATALOGUE_ITEM_SUBMITTER", "Antragsteller");
define("_CO_CATALOGUE_ITEM_SUBMITTER_DSC", "Verantwortlicher für die Verwaltung diesen Artikels.");
define("_CO_CATALOGUE_ITEM_DATE", "Datum");
define("_CO_CATALOGUE_ITEM_DATE_DSC", "Datum des Gegenstandes, wann es in den Katalog aufgenommen wurde.");

define("_CO_CATALOGUE_ITEM_ONLINE_STATUS", "Online");
define("_CO_CATALOGUE_ITEM_ONLINE_STATUS_DSC", " Mark the item as online or offline. Items that are online are not displayed and do not appear in search results.");
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
<?php
/**
* Common file of the module included on all pages of the module
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

if(!defined("CATALOGUE_DIRNAME")) define("CATALOGUE_DIRNAME",
	$modversion['dirname'] = basename(dirname(dirname(__FILE__))));
if(!defined("CATALOGUE_URL")) define("CATALOGUE_URL", ICMS_URL . '/modules/'
	. CATALOGUE_DIRNAME . '/');
if(!defined("CATALOGUE_ROOT_PATH")) define("CATALOGUE_ROOT_PATH", ICMS_ROOT_PATH.'/modules/'
	. CATALOGUE_DIRNAME . '/');
if(!defined("CATALOGUE_IMAGES_URL")) define("CATALOGUE_IMAGES_URL", CATALOGUE_URL . 'images/');
if(!defined("CATALOGUE_ADMIN_URL")) define("CATALOGUE_ADMIN_URL", CATALOGUE_URL . 'admin/');

// Include the common language file of the module
icms_loadLanguageFile('catalogue', 'common');

include_once(CATALOGUE_ROOT_PATH . "include/functions.php");

// Creating the module object to make it available throughout the module
$catalogueModule = icms_getModuleInfo(CATALOGUE_DIRNAME);
if (is_object($catalogueModule)){
	$catalogue_moduleName = $catalogueModule->getVar('name');
}

// Find if the user is admin of the module and make this info available throughout the module
$catalogue_isAdmin = icms_userIsAdmin(CATALOGUE_DIRNAME);

// Creating the module config array to make it available throughout the module
$catalogueConfig = icms_getModuleConfig(CATALOGUE_DIRNAME);
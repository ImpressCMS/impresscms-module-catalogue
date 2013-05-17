<?php
/**
* Configuring the amdin side menu for the module
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

global $icmsConfig;

$i = -1;

$i++;
$adminmenu[$i]['title'] = _MI_CATALOGUE_ITEMS;
$adminmenu[$i]['link'] = 'admin/item.php';

// Orders are not implemented yet
//$i++;
//$adminmenu[$i]['title'] = _MI_CATALOGUE_ORDERS;
//$adminmenu[$i]['link'] = 'admin/order.php';

$catalogueModule = icms_getModuleInfo('catalogue');

if (isset($catalogueModule)) {

	$i = -1;

	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_GOTOMODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/catalogue/';
	
	$i++;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' .
		$catalogueModule->getVar('mid');
	
	$i++;
	$headermenu[$i]['title'] = _MI_CATALOGUE_TEMPLATES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=tplsets&op=listtpl&tplset='
		. $icmsConfig['template_set'] . '&moddir=' . $catalogueModule->getVar('dirname');

	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_UPDATE_MODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module='
		. $catalogueModule->getVar('dirname');

	$i++;
	$headermenu[$i]['title'] = _MI_CATALOGUE_MANUAL;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/' . $catalogueModule->getVar('dirname') . '/manual/catalogue_manual.pdf';

	$i++;
	$headermenu[$i]['title'] = _MODABOUT_ABOUT;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/' . $catalogueModule->getVar('dirname') . '/admin/about.php';
}
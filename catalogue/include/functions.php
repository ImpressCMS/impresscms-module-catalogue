<?php
/**
* Common functions used by the module
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**
 * Get module admion link
 *
 * @todo to be move in icms core
 *
 * @param string $moduleName dirname of the moodule
 * @return string URL of the admin side of the module
 */

function catalogue_getModuleAdminLink($moduleName='catalogue') {
	$moduleName = icms::$module->getVar('dirname');
	$ret = '';
	if ($moduleName) {
		$ret = "<a href='" . ICMS_URL . "/modules/$moduleName/admin/index.php'>" ._MD_CATALOGUE_ADMIN_PAGE . "</a>";
	}
	return $ret;
}

/**
 * @todo to be move in icms core
 */
function catalogue_getModuleName($withLink = TRUE, $forBreadCrumb = FALSE, $moduleName = FALSE) {
	
	if (!icms_get_module_status("catalogue")) {
		return '';
	}

	if (!$withLink) {
		return icms::$module->getVar('name');
	} else {
		$ret = ICMS_URL . '/modules/' . icms::$module->getVar('dirname') . '/';
		return '<a href="' . $ret . '">' . icms::$module->getVar('name') . '</a>';
	}
}

/**
 * Get URL of previous page
 *
 * @todo to be moved in ImpressCMS 1.2 core
 *
 * @param string $default default page if previous page is not found
 * @return string previous page URL
 */
function catalogue_getPreviousPage($default=FALSE) {
	global $impresscms;
	if (isset($impresscms->urls['previouspage'])) {
		return $impresscms->urls['previouspage'];
	} elseif($default) {
		return $default;
	} else {
		return ICMS_URL;
	}
}

/**
 * Get month name by its ID
 *
 * @todo to be moved in ImpressCMS 1.2 core
 *
 * @param int $month_id ID of the month
 * @return string month name
 */
function catalogue_getMonthNameById($month_id) {
	return Icms_getMonthNameById($month_id);
}
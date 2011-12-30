<?php
/**
* Footer page included at the end of each page on user side of the mdoule
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$icmsTpl->assign("catalogue_adminpage", catalogue_getModuleAdminLink());
$icmsTpl->assign("catalogue_is_admin", $catalogue_isAdmin);
$icmsTpl->assign('catalogue_url', CATALOGUE_URL);
$icmsTpl->assign('catalogue_images_url', CATALOGUE_IMAGES_URL);

$xoTheme->addStylesheet(CATALOGUE_URL . 'module'.(( defined("_ADM_USE_RTL")
	&& _ADM_USE_RTL )?'_rtl':'').'.css');

include_once(ICMS_ROOT_PATH . '/footer.php');
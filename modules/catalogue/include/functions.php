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
 * Formats items for user-side display, prepares them for insertion to templates
 * 
 * @param object $itemObj
 * @return array 
 */
function prepareItemForDisplay($itemObj, $with_overrides = TRUE) {

	$itemArray = array();
	
	if ($with_overrides) {
		$itemArray = $itemObj->toArray();
	} else {
		$itemArray = $itemObj->toArrayWithoutOverrides();
	}
	
	return $itemArray;
}

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

/**
* Return a linked username or full name for a specific $userid
*
* @todo this function is fixing a ucwords bug in icms_getLinkedUnameFromId so we will update this in icms 1.2
*
* @param integer $userid uid of the related user
* @param bool $name TRUE to return the fullname, FALSE to use the username; if TRUE and the user does not have fullname, username will be used instead
* @param array $users array already containing XoopsUser objects in which case we will save a query
* @param bool $withContact TRUE if we want contact details to be added in the value returned (PM and email links)
* @return string name of user with a link on his profile
*/
function catalogue_getLinkedUnameFromId($userid, $name = FALSE, $users = array (), $withContact = FALSE)
{
	if(!is_numeric($userid)) {return $userid;}
	$userid = intval($userid);
	if($userid > 0)
	{
		if($users == array())
		{
			//fetching users
			$user = icms::handler('member')->getUser($userid);
		}
		else
		{
			if(!isset($users[$userid])) {return $GLOBALS['icmsConfig']['anonymous'];}
			$user = & $users[$userid];
		}
		if(is_object($user))
		{
			$ts = & MyTextSanitizer::getInstance();
			$username = $user->getVar('uname');
			$fullname = '';
			$fullname2 = $user->getVar('name');
			if(($name) && !empty($fullname2)) {$fullname = $user->getVar('name');}
			if(!empty ($fullname)) {$linkeduser = "$fullname [<a href='".ICMS_URL."/userinfo.php?uid=".$userid."'>".$ts->htmlSpecialChars($username)."</a>]";}
			else {$linkeduser = "<a href='".ICMS_URL."/userinfo.php?uid=".$userid."'>".$ts->htmlSpecialChars($username)."</a>";}
			// add contact info : email + PM
			if($withContact)
			{
				$linkeduser .= '<a href="mailto:'.$user->getVar('email').'"><img style="vertical-align: middle;" src="'.ICMS_URL.'/images/icons/email.gif'.'" alt="'._US_SEND_MAIL.'" title="'._US_SEND_MAIL.'"/></a>';
				$js = "javascript:openWithSelfMain('".ICMS_URL.'/pmlite.php?send2=1&to_userid='.$userid."', 'pmlite',450,370);";
				$linkeduser .= '<a href="'.$js.'"><img style="vertical-align: middle;" src="'.ICMS_URL.'/images/icons/pm.gif'.'" alt="'._US_SEND_PM.'" title="'._US_SEND_PM.'"/></a>';
			}
			return $linkeduser;
		}
	}
	return $GLOBALS['icmsConfig']['anonymous'];
}
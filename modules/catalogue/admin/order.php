<?php
/**
* Admin page to manage orders
*
* List, add, edit and delete order objects
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

/**
 * Edit a Order
 *
 * @param int $order_id Orderid to be edited
*/
function editorder($order_id = 0)
{
	global $catalogue_order_handler, $icmsAdminTpl;
	
	$catalogueModule = icms_getModuleInfo(basename(dirname(dirname(__FILE__))));

	$orderObj = $catalogue_order_handler->get($order_id);

	if (!$orderObj->isNew()){
		$catalogueModule->displayAdminMenu(0, _AM_CATALOGUE_ORDERS . " > " . _CO_ICMS_EDITING);
		$sform = $orderObj->getForm(_AM_CATALOGUE_ORDER_EDIT, 'addorder');
		$sform->assign($icmsAdminTpl);

	} else {
		$catalogueModule->displayAdminMenu(0, _AM_CATALOGUE_ORDERS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $orderObj->getForm(_AM_CATALOGUE_ORDER_CREATE, 'addorder');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:catalogue_admin_order.html');
}

include_once("admin_header.php");

$catalogue_order_handler = icms_getModuleHandler('order');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addorder','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op,$valid_op,true)) {
  switch ($clean_op) {
  	case "mod":
  	case "changedField":

  		icms_cp_header();

  		editorder($clean_order_id);
  		break;
  	case "addorder":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($catalogue_order_handler);
  		$controller->storeFromDefaultForm(_AM_CATALOGUE_ORDER_CREATED, _AM_CATALOGUE_ORDER_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($catalogue_order_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$orderObj = $catalogue_order_handler->get($clean_order_id);

  		icms_cp_header();
  		smart_adminMenu(1, _AM_CATALOGUE_ORDER_VIEW . ' > ' . $orderObj->getVar('order_name'));

  		smart_collapsableBar('orderview', $orderObj->getVar('order_name') .
			$orderObj->getEditOrderLink(), _AM_CATALOGUE_ORDER_VIEW_DSC);

  		$orderObj->displaySingleObject();

  		smart_close_collapsable('orderview');

  		break;

  	default:

  		icms_cp_header();

  		$catalogueModule->displayAdminMenu(0, _AM_CATALOGUE_ORDERS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new icms_ipf_view_Table($catalogue_order_handler);
  		$objectTable->addColumn(new icms_ipf_view_Column('item_id'));

  		$objectTable->addIntroButton('addorder', 'order.php?op=mod', _AM_CATALOGUE_ORDER_CREATE);
  		$icmsAdminTpl->assign('catalogue_order_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:catalogue_admin_order.html');
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
<?php

/**
* Classes responsible for managing Catalogue item objects
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/catalogue/include/functions.php');

class CatalogueItem extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler CataloguePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('item_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);
		$this->initNonPersistableVar('tag', XOBJ_DTYPE_INT, 'tag', false, false, false, true);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('image', XOBJ_DTYPE_IMAGE, true);
		$this->quickInitVar('identifier', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('weight', XOBJ_DTYPE_INT, true, false, false, 0);
		$this->quickInitVar('price', XOBJ_DTYPE_CURRENCY, true, false, false, 0);
		$this->quickInitVar('shipping', XOBJ_DTYPE_CURRENCY, true, false, false, 0);
		$this->quickInitVar('ecommerce_link', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('submitter', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('date', XOBJ_DTYPE_LTIME, true);
		$this->quickInitVar('online_status', XOBJ_DTYPE_INT, true, false, false, 1);
		$this->initCommonVar('counter');
		$this->initCommonVar('dohtml', false, 1);
		$this->initCommonVar('dobr', true, 1);
		$this->initCommonVar('doxcode', true, 1);
		$this->quickInitVar ('item_notification_sent', XOBJ_DTYPE_INT, false, false, false, 0);
		
		// Only display the tag field if Sprockets is installed
		$sprocketsModule = icms_getModuleInfo('sprockets');
		if ($sprocketsModule) {
			$this->setControl('tag', array(
			'name' => 'select_multi',
			'itemHandler' => 'tag',
			'method' => 'getTags',
			'module' => 'sprockets'));
		} else {
			$this->hideFieldFromForm('tag');
			$this->hideFieldFromSingleView ('tag');
		}
		
		// Add WYSIWYG editor to description field
		$this->setControl('description', 'dhtmltextarea');

		// product image
		$this->setControl('image', array('name' => 'image'));
		$url = ICMS_URL . '/uploads/' . basename(dirname(dirname(__FILE__))) . '/';
		$path = ICMS_ROOT_PATH . '/uploads/' . basename(dirname(dirname(__FILE__))) . '/';
		$this->setImageDir($url, $path);
		
		$this->setControl('online_status', 'yesno');
		
		// set user control
		$this->setControl('submitter', 'user');

		// hide the notification status field, its for internal use only
		$this->hideFieldFromForm ('item_notification_sent');
		$this->hideFieldFromSingleView ('item_notification_sent');

		$this->IcmsPersistableSeoObject();
	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ('online_status'))) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}
	
		/**
	 * Duplicates the functionality of toArray() but does not execute getVar() overrides that require DB calls
	 * 
	 * Use this function when parsing multiple articles for display. If a getVar() override executes 
	 * a DB query (for example, to lookup a value in another table) then parsing multiple articles 
	 * will trigger that query multiple times. If you are doing this for a multiple fields and a 
	 * large number of articles, this can result in a huge number of queries. It is more efficient
	 * then to build a reference buffer for each such field and then do the lookups in memory 
	 * instead. However, you need to create a reference buffer for each value where you want to 
	 * avoid a DB lookup and manually assign the value in your code
	 *
	 * @return array
	 */
	public function toArrayWithoutOverrides() {
		
		$vars = $this->getVars();
		$do_not_override = array(0 => 'tag');
		$ret = array();
		
		foreach ($vars as $key => $var) {
			if (in_array($key, $do_not_override)) {
				$value = $this->getVar($key, 'e');
			} else {
				$value = $this->getVar($key);
			}
			$ret[$key] = $value;
		}

		if ($this->handler->identifierName != "") {
			$controller = new IcmsPersistableController($this->handler);
			$ret['itemLink'] = $controller->getItemLink($this);
			$ret['itemUrl'] = $controller->getItemLink($this, true);
			$ret['editItemLink'] = $controller->getEditItemLink($this, false, true);
			$ret['deleteItemLink'] = $controller->getDeleteItemLink($this, false, true);
			$ret['printAndMailLink'] = $controller->getPrintAndMailLink($this);
		}
		
		return $ret;
	}

	/**
	 * Converts online_status to human readable
	 * 
	 * @return string
	 */
	public function online_status() {
		$online_status = $this->getVar('online_status', 'e');
		if ($online_status == false) {
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/item.php?item_id=' . $this->getVar('item_id') . '&amp;op=visible">
				<img src="../images/button_cancel.png" alt="Offline" /></a>';
		} else {
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/item.php?item_id=' . $this->getVar('item_id') . '&amp;op=visible">
				<img src="../images/button_ok.png" alt="Online" /></a>';
		}
	}

	public function getWeightControl(){
		include_once ICMS_ROOT_PATH.'/class/xoopsformloader.php';
		$control = new XoopsFormText('','weight[]',5,7,$this->getVar( 'weight', 'e'));
		$control->setExtra('style="text-align:center;"');
		return $control->render();
	}
	
	/**
	 * Load tags linked to this item
	 *
	 * @return void
	 */
	public function loadTags() {
		
		$ret = '';
		
		$sprocketsModule = icms_getModuleInfo('sprockets');
		if ($sprocketsModule) {
			$sprockets_taglink_handler = icms_getModuleHandler('taglink',
					$sprocketsModule->dirname(), 'sprockets');
			$ret = $sprockets_taglink_handler->getTagsForObject($this->id(), $this->handler);
			$this->setVar('tag', $ret);
		}
	}

	/*
     * Sends notifications to subscribers when a new soundtrack is published, called by afterSave()
	*/
	public function sendNotifItemPublished() {
		
		$item_id = $module_handler = $module = $notification_handler = '';
		$tags = array();
		
		$item_id = $this->id();
		$module_handler = xoops_getHandler('module');
		$module = $module_handler->getByDirname(basename(dirname(dirname(__FILE__))));
		$module_id = $module->getVar('mid');
		$notification_handler = xoops_getHandler ('notification');

		$tags = array();
		$tags['ITEM_TITLE'] = $this->title();
		$tags['ITEM_URL'] = $this->getItemLink(true);
		
		// global notification
		$notification_handler->triggerEvent('global', 0, 'item_published', $tags,
			array(), $module_id, 0);
	}
}

class CatalogueItemHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'item', 'item_id', 'title', 'description',
			'catalogue');

		// enable upload of product photos, this should be handled by core mimetype manager later
		global $catalogueConfig;
		$mimetypes = array('image/jpeg', 'image/png', 'image/gif');
		$this->enableUpload($mimetypes,
			$catalogueConfig['image_file_size'],
			$catalogueConfig['image_upload_width'],
			$catalogueConfig['image_upload_height']);
	}
	
	/** Provides global search functionality for Catalogue module
     *
	 */
	public function getItemsForSearch($queryarray, $andor, $limit, $offset, $userid) {
		$criteria = new CriteriaCompo();
		$criteria->setStart($offset);
		$criteria->setLimit($limit);
		$criteria->setSort('date');
		$criteria->setOrder('DESC');

		if ($userid != 0) {
			$criteria->add(new Criteria('submitter', $userid));
		}
		if ($queryarray) {
			$criteriaKeywords = new CriteriaCompo();
			for ($i = 0; $i < count($queryarray); $i++) {
				$criteriaKeyword = new CriteriaCompo();
				$criteriaKeyword->add(new Criteria('title', '%' . $queryarray[$i] . '%',
					'LIKE'), 'OR');
				$criteriaKeyword->add(new Criteria('description', '%' . $queryarray[$i]
					. '%', 'LIKE'), 'OR');
				$criteriaKeywords->add($criteriaKeyword, $andor);
				unset ($criteriaKeyword);
			}
			$criteria->add($criteriaKeywords);
		}
		$criteria->add(new Criteria('online_status', true));
		return $this->getObjects($criteria, true, false);
	}
	
	/**
	 * Switches an items status from online to offline or vice versa
	 *
	 * @return null
	 */
	public function changeVisible($item_id) {
		$visibility = '';
		$itemObj = $this->get($item_id);
		if ($itemObj->getVar('online_status', 'e') == true) {
			$itemObj->setVar('online_status', 0);
			$visibility = 0;
		} else {
			$itemObj->setVar('online_status', 1);
			$visibility = 1;
		}
		$this->insert($itemObj, true);
		return $visibility;

	}

	/**
	 * Converts status value to human readable text
	 *
	 * @return array
	 */
	public function online_status_filter() {
		return array(0 => 'Offline', 1 => 'Online');
	}
	
	public function updateComments($item_id, $total_num) {
		$itemObj = $this->get($item_id);
		if ($itemObj && !$itemObj->isNew()) {
			$itemObj->setVar('item_comments', $total_num);
			$this->insert($itemObj, true);
		}
	}

	/**
	 * Triggers notifications, called when an item is inserted or updated
	 *
	 * @param object $obj CatalogueItem object
	 * @return bool
	 */
	protected function afterSave(& $obj) {
		
		$sprockets_taglink_handler = '';
		
		
		// triggers notification event for subscribers
		if (!$obj->getVar('item_notification_sent') && $obj->getVar('online_status', 'e') == 1) {
			$obj->sendNotifItemPublished();
			$obj->setVar('item_notification_sent', true);
			$this->insert ($obj);
		}
		
		// storing tags
		$sprocketsModule = icms_getModuleInfo('sprockets');
		
		if ($sprocketsModule) {
			$sprockets_taglink_handler = icms_getModuleHandler('taglink', 
					$sprocketsModule->dirname(), 'sprockets');
			$sprockets_taglink_handler->storeTagsForObject($obj);
		}
		
		return true;
	}

	/**
	 * Deletes notification subscriptions, called when an item is deleted
	 *
	 * @global object $icmsModule
	 * @param object $obj CatalogueItem object
	 * @return bool
	 */
	protected function afterDelete(& $obj) {
		global $icmsModule;
		$notification_handler =& xoops_gethandler('notification');
		$module_handler = xoops_getHandler('module');
		$module = $module_handler->getByDirname(basename(dirname(dirname(__FILE__))));
		$module_id = $module->getVar('mid');
		$category = 'global';
		$item_id = $obj->id();

		// delete global notifications
		$notification_handler->unsubscribeByItem($module_id, $category, $item_id);
		
		// delete taglinks
		$sprocketsModule = icms_getModuleInfo('sprockets');
		if ($sprocketsModule) {
			$sprockets_taglink_handler = icms_getModuleHandler('taglink',
					$sprocketsModule->dirname(), 'sprockets');
			$sprockets_taglink_handler->deleteAllForObject(&$obj);
		}

		return true;
	}
}
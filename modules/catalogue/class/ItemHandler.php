<?php

/**
* Class representing Catalogue item handler objects
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

class CatalogueItemHandler extends icms_ipf_Handler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		
		parent::__construct($db, 'item', 'item_id', 'title', 'description',
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
		$criteria = new icms_db_criteria_Compo();
		$criteria->setStart($offset);
		$criteria->setLimit($limit);
		$criteria->setSort('date');
		$criteria->setOrder('DESC');

		if ($userid != 0) {
			$criteria->add(new icms_db_criteria_Item('submitter', $userid));
		}
		if ($queryarray) {
			$criteriaKeywords = new icms_db_criteria_Compo();
			for ($i = 0; $i < count($queryarray); $i++) {
				$criteriaKeyword = new icms_db_criteria_Compo();
				$criteriaKeyword->add(new icms_db_criteria_Item('title', '%' . $queryarray[$i] . '%',
					'LIKE'), 'OR');
				$criteriaKeyword->add(new icms_db_criteria_Item('description', '%' . $queryarray[$i]
					. '%', 'LIKE'), 'OR');
				$criteriaKeywords->add($criteriaKeyword, $andor);
				unset ($criteriaKeyword);
			}
			$criteria->add($criteriaKeywords);
		}
		$criteria->add(new icms_db_criteria_Item('online_status', true));
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
					$sprocketsModule->getVar('dirname'), 'sprockets');
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
					$sprocketsModule->getVar('dirname'), 'sprockets');
			$sprockets_taglink_handler->deleteAllForObject(&$obj);
		}

		return true;
	}
}
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
		
		$count = $results = '';
		$criteria = new icms_db_criteria_Compo();

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
		$criteria->add(new icms_db_criteria_Item('online_status', TRUE));
		
		/*
		 * Improving the efficiency of search
		 * 
		 * The general search function is not efficient, because it retrieves all matching records
		 * even when only a small subset is required, which is usually the case. The full records 
		 * are retrieved so that they can be counted, which is used to display the number of 
		 * search results and also to set up the pagination controls. The problem with this approach 
		 * is that a search generating a very large number of results (eg. > 650) will crash out. 
		 * Maybe its a memory allocation issue, I don't know.
		 * 
		 * A better approach is to run two queries: The first a getCount() to find out how many 
		 * records there are in total (without actually wasting resources to retrieve them), 
		 * followed by a getObjects() to retrieve the small subset that are actually needed. 
		 * Due to the way search works, the object array needs to be padded out 
		 * with the number of elements counted in order to preserve 'hits' information and to construct
		 * the pagination controls. So to minimise resources, we can just set their values to '1'.
		 * 
		 * In the long term it would be better to (say) pass the count back as element[0] of the 
		 * results array, but that will require modification to the core and will affect all modules.
		 * So for the moment, this hack is convenient.
		 */
		
		// Count the number of search results WITHOUT actually retrieving the objects
		$count = $this->getCount($criteria);
		
		$criteria->setStart($offset);
		$criteria->setSort('date');
		$criteria->setOrder('DESC');
		
		// Retrieve the subset of results that are actually required.
		// Problem: If show all results # < shallow search #, then the all results preference is 
		// used as a limit. This indicates that shallow search is not setting a limit! The largest 
		// of these two values should always be used
		if (!$limit) {
			global $icmsConfigSearch;
			$limit = $icmsConfigSearch['search_per_page'];
		}
		
		$criteria->setLimit($limit);
		$results = $this->getObjects($criteria, FALSE, TRUE);
		
		// Pad the results array out to the counted length to preserve 'hits' and pagination controls.
		// This approach is not ideal, but it greatly reduces the load for queries with large result sets
		$results = array_pad($results, $count, 1);
		
		return $this->getObjects($criteria, TRUE, FALSE);
	}
	
	/**
	 * Switches an items status from online to offline or vice versa
	 *
	 * @return null
	 */
	public function changeVisible($item_id) {
		$visibility = '';
		$itemObj = $this->get($item_id);
		if ($itemObj->getVar('online_status', 'e') == TRUE) {
			$itemObj->setVar('online_status', 0);
			$visibility = 0;
		} else {
			$itemObj->setVar('online_status', 1);
			$visibility = 1;
		}
		$this->insert($itemObj, TRUE);
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
			$this->insert($itemObj, TRUE);
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
			$obj->setVar('item_notification_sent', TRUE);
			$this->insert ($obj);
		}
		
		// storing tags
		$sprocketsModule = icms_getModuleInfo('sprockets');
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && icms_get_module_status("sprockets")) {
			$sprockets_taglink_handler = icms_getModuleHandler('taglink', 
				$sprocketsModule->getVar('dirname'), 'sprockets');
			$sprockets_taglink_handler->storeTagsForObject($obj, 'tag', 0);
		}
		
		return TRUE;
	}

	/**
	 * Deletes notification subscriptions, called when an item is deleted
	 *
	 * @global object $icmsModule
	 * @param object $obj CatalogueItem object
	 * @return bool
	 */
	protected function afterDelete(& $obj) {
		$notification_handler = icms::handler("icms_data_notification");
		$module = icms::handler("icms_module")->getByDirname(basename(dirname(dirname(__FILE__))));
		$module_id = $module->getVar('mid');
		$category = 'global';
		$item_id = $obj->id();

		// delete global notifications
		$notification_handler->unsubscribeByItem($module_id, $category, $item_id);
		
		// delete taglinks
		$sprocketsModule = icms_getModuleInfo('sprockets');
		if (icms_get_module_status("sprockets")) {
			$sprockets_taglink_handler = icms_getModuleHandler('taglink',
					$sprocketsModule->getVar('dirname'), 'sprockets');
			$sprockets_taglink_handler->deleteAllForObject(&$obj);
		}

		return TRUE;
	}
}
<?php

/**
* Class representing Catalogue item objects
*
* @copyright			Copyright Madfish (Simon Wilkinson)
* @license              http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since                1.0
* @author               Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package              catalogue
* @version              $Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

class CatalogueItem extends icms_ipf_seo_Object 
{
	/**
	 * Constructor
	 *
	 * @param object $handler CataloguePostHandler object
	 */
	public function __construct(& $handler)
	{
		parent::__construct($handler);

		$this->quickInitVar('item_id', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, TRUE);
		$this->initNonPersistableVar('tag', XOBJ_DTYPE_INT, 'tag', FALSE, FALSE, FALSE, TRUE);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar('creator', XOBJ_DTYPE_TXTBOX, FALSE);
		$this->quickInitVar('image', XOBJ_DTYPE_IMAGE, TRUE);
		$this->quickInitVar('identifier', XOBJ_DTYPE_TXTBOX, FALSE);
		$this->quickInitVar('weight', XOBJ_DTYPE_INT, TRUE, FALSE, FALSE, 0);
		$this->quickInitVar('price', XOBJ_DTYPE_CURRENCY, TRUE, FALSE, FALSE, 0);
		$this->quickInitVar('shipping', XOBJ_DTYPE_CURRENCY, TRUE, FALSE, FALSE, 0);
		$this->quickInitVar('ecommerce_link', XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar('type', XOBJ_DTYPE_TXTBOX, TRUE, FALSE, FALSE, 'Image');
		$this->quickInitVar('submitter', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('date', XOBJ_DTYPE_LTIME, TRUE);
		$this->quickInitVar('online_status', XOBJ_DTYPE_INT, TRUE, FALSE, FALSE, 1);
		$this->initCommonVar('counter');
		$this->initCommonVar('dohtml', FALSE, 1);
		$this->initCommonVar('dobr', TRUE, 1);
		$this->initCommonVar('doxcode', TRUE, 1);
		$this->quickInitVar ('item_notification_sent', XOBJ_DTYPE_INT, FALSE, FALSE, FALSE, 0);

		// Only display the tag field if Sprockets is installed
		$sprocketsModule = icms_getModuleInfo('sprockets');
		if (icms_get_module_status("sprockets")) {
			$this->setControl('tag', array(
			'name' => 'selectmulti',
			'itemHandler' => 'tag',
			'method' => 'getTags',
			'module' => 'sprockets'));
		} else {
			$this->hideFieldFromForm('tag');
			$this->hideFieldFromSingleView('tag');
		}
		$this->hideFieldFromForm('type');
		$this->hideFieldFromSingleView('type');

		// Add WYSIWYG editor to description field
		$this->setControl('description', 'dhtmltextarea');

		// product image
		$this->setControl('image', array('name' => 'imageupload'));
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
				return call_user_func(array ($this, $key));
		}
		return parent :: getVar($key, $format);
	}

	/**
	 * Converts online_status to human readable
	 * 
	 * @return string
	 */
	public function online_status() {
		$online_status = $this->getVar('online_status', 'e');
		if ($online_status == FALSE) {
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
					. '/admin/item.php?item_id=' . $this->getVar('item_id') . '&amp;op=visible">
					<img src="../images/button_cancel.png" alt="Offline" /></a>';
		} else {
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
					. '/admin/item.php?item_id=' . $this->getVar('item_id') . '&amp;op=visible">
					<img src="../images/button_ok.png" alt="Online" /></a>';
		}
	}
	
		/**
	 * Customise object itemLink to append the SEO-friendly string.
	 */
	public function getItemLinkWithSEOString()
	{
		$short_url = $this->short_url();
		if (!empty($short_url))
		{
			$seo_url = '<a href="' . $this->getItemLink(TRUE) . '&amp;title=' . $this->short_url() 
					. '">' . $this->getVar('title', 'e') . '</a>';
		}
		else
		{
			$seo_url = $this->getItemLink(FALSE);
		}
		
		return $seo_url;
	}

	public function getWeightControl(){
		$control = new icms_form_elements_Text('','weight[]',5,7,$this->getVar( 'weight', 'e'));
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
		if (icms_get_module_status("sprockets")) {
			$sprockets_taglink_handler = icms_getModuleHandler('taglink',
					$sprocketsModule->getVar('dirname'), 'sprockets');
			$ret = $sprockets_taglink_handler->getTagsForObject($this->id(), $this->handler, 0);
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
		$module = icms::handler("icms_module")->getByDirname(basename(dirname(dirname(__FILE__))));
		$module_id = $module->getVar('mid');
		$notification_handler = icms::handler("icms_data_notification");

		$tags = array();
		$tags['ITEM_TITLE'] = $this->title();
		$tags['ITEM_URL'] = $this->getItemLink(TRUE);

		// global notification
		$notification_handler->triggerEvent('global', 0, 'item_published', $tags,array(), $module_id, 0);
	}
}
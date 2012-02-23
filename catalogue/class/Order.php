<?php

/**
* Class representing Catalogue order objects
*
* @copyright			Copyright Madfish (Simon Wilkinson)
* @license              http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since                1.0
* @author               Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package              catalogue
* @version              $Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

class CatalogueOrder extends icms_ipf_seo_Object {

	/**
	 * Constructor
	 *
	 * @param object $handler CataloguePostHandler object
	 */
	public function __construct(& $handler) 
	{
		parent::__construct($handler);

		$this->quickInitVar('order_id', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('item_id', XOBJ_DTYPE_INT, FALSE);
		$this->quickInitVar('unit_price', XOBJ_DTYPE_TXTBOX, FALSE);
		$this->quickInitVar('number_ordered', XOBJ_DTYPE_TXTBOX, FALSE);
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
		if ($format == 's' && in_array($key, array())) {
			return call_user_func(array ($this, $key));
		}
		return parent :: getVar($key, $format);
	}
}
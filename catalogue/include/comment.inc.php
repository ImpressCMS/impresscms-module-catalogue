<?php
/**
* Comment include file
*
* File holding functions used by the module to hook with the comment system of ImpressCMS
*
* @copyright	Copyright Madfish (Simon Wilkinson)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		catalogue
* @version		$Id$
*/

function catalogue_com_update($item_id, $total_num) {
	$catalogue_item_handler = icms_getModuleHandler('item', basename(dirname(dirname(__FILE__))),
		'catalogue');
	$catalogue_item_handler->updateComments($item_id, $total_num);

}

function catalogue_com_approve(&$comment) {
    // notification mail here
}
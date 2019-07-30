<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Testimonial\Block\Adminhtml;

class Testimo extends \Magento\Backend\Block\Widget\Grid\Container {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function _construct() {

		$this->_controller = 'adminhtml_testimo';
		$this->_blockGroup = 'Saffron_Testimonial';
		$this->_headerText = __('Testimonials');
		$this->_addButtonLabel = __('Add New Testimonial');
		parent::_construct();
	}
}

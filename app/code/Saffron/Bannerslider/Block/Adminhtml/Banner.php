<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Bannerslider\Block\Adminhtml;

class Banner extends \Magento\Backend\Block\Widget\Grid\Container {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function _construct() {

		$this->_controller = 'adminhtml_banner';
		$this->_blockGroup = 'Saffron_Bannerslider';
		$this->_headerText = __('Banners');
		$this->_addButtonLabel = __('Add New Banner');
		parent::_construct();
	}
}

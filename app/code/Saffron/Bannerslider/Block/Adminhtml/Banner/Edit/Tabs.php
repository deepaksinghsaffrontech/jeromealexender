<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Bannerslider\Block\Adminhtml\Banner\Edit;

/**
 * Admin Locator left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs {
	protected function _construct() {
		parent::_construct();
		$this->setId('banner_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(__('Banner Information'));
	}

	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
}

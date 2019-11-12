<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Bannerslider\Controller\Adminhtml\Banner;

class Delete extends \Saffron\Bannerslider\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $bannerId = $this->getRequest()->getParam('banner_id');
        try {
            $locator = $this->_objectManager->create('Saffron\Bannerslider\Model\Banner')->load($bannerId);
            $locator->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}

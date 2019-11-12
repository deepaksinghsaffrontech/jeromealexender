<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Testimonial\Controller\Adminhtml\Testimo;

class Delete extends \Saffron\Testimonial\Controller\Adminhtml\Testimo
{
    public function execute()
    {
        $testimoId = $this->getRequest()->getParam('testimo_id');
        try {
            $locator = $this->_objectManager->create('Saffron\Testimonial\Model\Testimo')->load($testimoId);
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

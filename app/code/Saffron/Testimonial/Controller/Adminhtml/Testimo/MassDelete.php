<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Testimonial\Controller\Adminhtml\Testimo;

class MassDelete extends \Saffron\Testimonial\Controller\Adminhtml\Testimo
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $testimoIds = $this->getRequest()->getParam('testimo');
        if (!is_array($testimoIds) || empty($testimoIds)) {
            $this->messageManager->addError(__('Please select testimonial(s).'));
        } else {
            try {
                foreach ($testimoIds as $testimoId) {
                    $testimo =$this->_objectManager->create('Saffron\Testimonial\Model\Testimo')->load($testimoId);
                    $testimo->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($testimoIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}

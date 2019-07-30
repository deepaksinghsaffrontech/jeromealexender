<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Testimonial\Controller\Adminhtml\Testimo;

class MassStatus extends \Saffron\Testimonial\Controller\Adminhtml\Testimo
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $testimoIds = $this->getRequest()->getParam('testimo');
        $status = $this->getRequest()->getParam('status');
        $storeViewId = $this->getRequest()->getParam('store');
        var_dump($status);
        // die;
        if (!is_array($testimoIds) || empty($testimoIds)) {
            $this->messageManager->addError(__('Please select testimonial(s).'));
        } else {
            try {
                foreach ($testimoIds as $testimoId) {
                    // $testimo = $this->_testimoFactory->create()->setStoreViewId($storeViewId)->load($testimoId);
                    $testimo = $this->_objectManager->create('Saffron\Testimonial\Model\Testimo')->load($testimoId);
                    $testimo->setStatus($status)
                           ->setIsMassupdate(true)
                           ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($testimoIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/', ['store' => $this->getRequest()->getParam("store")]);
    }
}

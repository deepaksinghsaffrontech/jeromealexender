<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Bannerslider\Controller\Adminhtml\Banner;

class MassDelete extends \Saffron\Bannerslider\Controller\Adminhtml\Banner
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $bannerIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannerIds) || empty($bannerIds)) {
            $this->messageManager->addError(__('Please select banner(s).'));
        } else {
            try {
                foreach ($bannerIds as $bannerId) {
                    $banner =$this->_objectManager->create('Saffron\Bannerslider\Model\Banner')->load($bannerId);
                    $banner->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($bannerIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}

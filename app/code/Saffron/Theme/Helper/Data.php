<?php

namespace Saffron\Theme\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    protected $_customerSession;
    protected $_groupRepository;
    protected $_context;
    /**
     * @param Context $context
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
    )
    {
        parent::__construct($context);
        $this->_context = $context;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_groupRepository = $groupRepository;
    }

   /* public function getWebsites() {
        $websites = $this->_storeManager->getWebsites();
        foreach($websites as $key => $website)
        {
            if($website->getName() == 'USA')
            {
                $website->setName('United States');
            }
        }
        return $this->_storeManager->getWebsites();
    }
    public function getCurrentWebsiteId()
    {
        return $this->_storeManager->getWebsite()->getId();
    }

    public function getCustomerGroup()
    {
    	return $this->_customerSession->getCustomer()->getGroupId();
    }

    public function getCurrentWebsiteName()
    {
    	$name = $this->_storeManager->getWebsite()->getName();

    	if($name == 'USA')
    	{
    		$name = 'US';
    	}

    	return $name;
    }

    public function getSwitchLabel()
    {
    	$currentWebsiteName = $this->getCurrentWebsiteName();

    	if(strtolower($currentWebsiteName) == 'canada')
    	{
    		$label = 'Switch to US site';
    	}else{
    		$label = 'Switch to Canadian site';
    	}
    	return $label;
    }

    public function getCustomerGroupData($group)
    {
    	$groupData = $this->_groupRepository->getById($group);
    	if(strpos($groupData->getCode(),'US') !== false){
    		$groupData->setCode(str_replace('US ','',$groupData->getCode()));
    	}
    	return $groupData;
    }*/
    public function isLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    }
}
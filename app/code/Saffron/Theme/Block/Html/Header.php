<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Saffron\Theme\Block\Html;

/**
 * Html page header block
 *
 * @api
 * @since 100.0.2
 */
use Magento\Framework\App\Http\Context as AppContext;
use Magento\Wishlist\Model\WishlistFactory;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;

class Header extends \Magento\Framework\View\Element\Template
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'html/header.phtml';
    protected $_appContext;
    protected $_wishlistFactory;
    protected $_customerSession;
    protected $_currentUserWishlistCollectionFactory;
    /**
     * Retrieve welcome text
     *
     * @return string
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,AppContext $appContext,WishlistFactory $wishlistFactory,\Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory $currentUserWishlistCollectionFactory, CustomerSession $customerSession, array $data = [])
    {
        parent::__construct($context,
                            $data,
                            $context->getValidator(),
                            $context->getResolver(),
                            $context->getFilesystem(),
                            $context->getEnginePool(),
                            $context->getStoreManager(),
                            $context->getAppState(),
                            $this,
                            $context->getPageConfig(),
                            $context, $data);
        $this->_appContext = $appContext;
        $this->_wishlistFactory = $wishlistFactory;
        $this->_customerSession = $customerSession;
        $this->_currentUserWishlistCollectionFactory = $currentUserWishlistCollectionFactory;
    }
    public function getWelcome()
    {
        if (empty($this->_data['welcome'])) {
            $this->_data['welcome'] = $this->_scopeConfig->getValue(
                'design/header/welcome',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }
        return __($this->_data['welcome']);
    }

    public function isLoggedIn()
    {
        return $this->_appContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getWishlistCount()
    {
        $collection = $this->_currentUserWishlistCollectionFactory->create();
        $collection->addCustomerIdFilter($this->_customerSession->getCustomerId());
        if($collection)
        {
            $count = $collection->count();
        }
        return $count;
    }
	
	
		public function getMediaurl(){
		$_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
		$storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
		$currentStore = $storeManager->getStore();
		$mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
				
		return $mediaUrl;
		}
	
}

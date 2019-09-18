<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Controller\Index;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class Index extends \Magento\Checkout\Controller\Index\Index
{
    /**
     * @var \Amasty\Checkout\Helper\Onepage
     */
    protected $onepageHelper;

    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $amCheckoutConfig;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Amasty\Checkout\Helper\Onepage $onepageHelper,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Amasty\Checkout\Model\Config $amCheckoutConfig
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $customerRepository,
            $accountManagement,
            $coreRegistry,
            $translateInline,
            $formKeyValidator,
            $scopeConfig,
            $layoutFactory,
            $quoteRepository,
            $resultPageFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $resultJsonFactory
        );

        $this->onepageHelper = $onepageHelper;
        $this->checkoutHelper = $checkoutHelper;
        $this->amCheckoutConfig = $amCheckoutConfig;
    }

    /**
     * Checkout page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->amCheckoutConfig->isEnabled()) {
            return parent::execute();
        }

        if (!$this->checkoutHelper->canOnepageCheckout()) {
            $this->messageManager->addErrorMessage(__('One-page checkout is turned off.'));

            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError() || !$quote->validateMinimumAmount()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        if (!$this->_customerSession->isLoggedIn() && !$this->checkoutHelper->isAllowedGuestCheckout($quote)) {
            $this->messageManager->addErrorMessage(__('Guest checkout is disabled.'));

            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $this->_customerSession->regenerateId();
        $this->_objectManager->get('Magento\Checkout\Model\Session')->setCartWasUpdated(false);
        $this->getOnepage()->initCheckout();
        $resultPage = $this->resultPageFactory->create();

        if ($font = $this->scopeConfig->getValue('amasty_checkout/design/font', ScopeInterface::SCOPE_STORE)) {
            $resultPage->getConfig()->addRemotePageAsset(
                'https://fonts.googleapis.com/css?family=' . urlencode($font),
                'css'
            );
        }

        $resultPage->getLayout()->getUpdate()->addHandle('amasty_checkout');

        if ($this->scopeConfig->getValue('amasty_checkout/design/header_footer', ScopeInterface::SCOPE_STORE)) {
            $resultPage->getLayout()->getUpdate()->addHandle('amasty_checkout_headerfooter');
        }

        /** @var \Magento\Checkout\Block\Onepage $checkoutBlock */
        $checkoutBlock = $resultPage->getLayout()->getBlock('checkout.root');

        $checkoutBlock
            ->setTemplate('Amasty_Checkout::onepage.phtml')
            ->setData('amcheckout_helper', $this->onepageHelper);

        $resultPage->getConfig()->getTitle()->set(__('Checkout'));

        return $resultPage;
    }
}

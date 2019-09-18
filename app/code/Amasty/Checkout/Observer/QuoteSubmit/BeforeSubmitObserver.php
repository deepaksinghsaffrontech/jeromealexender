<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Observer\QuoteSubmit;

use Magento\Framework\Event\ObserverInterface;

class BeforeSubmitObserver implements ObserverInterface
{
    /**
     * @var \Amasty\Checkout\Api\AdditionalFieldsManagementInterface
     */
    private $fieldsManagement;

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $config;

    public function __construct(
        \Amasty\Checkout\Api\AdditionalFieldsManagementInterface $fieldsManagement,
        \Amasty\Checkout\Model\Config $config
    ) {
        $this->fieldsManagement = $fieldsManagement;
        $this->config = $config;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->config->isEnabled()) {
            return;
        }
        /** @var \Magento\Quote\Model\Quote $order */
        $quote = $observer->getEvent()->getQuote();
        $fields = $this->fieldsManagement->getByQuoteId($quote->getId());
        if ($fields->getComment()) {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $observer->getEvent()->getOrder();
            $history = $order->addStatusHistoryComment($fields->getComment());
            $history->setIsVisibleOnFront(true);
            $history->setIsCustomerNotified(true);
        }
    }
}

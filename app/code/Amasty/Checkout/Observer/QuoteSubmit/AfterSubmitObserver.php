<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Observer\QuoteSubmit;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class AfterSubmitObserver implements ObserverInterface
{
    /**
     * @var \Amasty\Checkout\Api\AdditionalFieldsManagementInterface
     */
    private $fieldsManagement;

    /**
     * @var \Amasty\Checkout\Model\Subscription
     */
    private $subscription;

    /**
     * @var \Amasty\Checkout\Model\FeeRepository
     */
    private $feeRepository;

    /**
     * @var \Amasty\Checkout\Model\Delivery
     */
    private $delivery;

    /**
     * @var \Amasty\Checkout\Model\ResourceModel\Delivery
     */
    private $deliveryResource;

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $config;

    /**
     * @var \Amasty\Checkout\Model\ResourceModel\QuoteCustomFields\CollectionFactory
     */
    private $quoteCollectionFactory;

    /**
     * @var \Amasty\Checkout\Model\OrderCustomFieldsFactory
     */
    private $orderCustomFieldsFactory;

    /**
     * @var \Amasty\Checkout\Model\ResourceModel\OrderCustomFields
     */
    private $orderCustomFieldsResource;

    public function __construct(
        \Amasty\Checkout\Api\AdditionalFieldsManagementInterface $fieldsManagement,
        \Amasty\Checkout\Model\Subscription $subscription,
        \Amasty\Checkout\Model\FeeRepository $feeRepository,
        \Amasty\Checkout\Model\Delivery $delivery,
        \Amasty\Checkout\Model\ResourceModel\Delivery $deliveryResource,
        \Amasty\Checkout\Model\Config $config,
        \Amasty\Checkout\Model\ResourceModel\QuoteCustomFields\CollectionFactory $quoteCollectionFactory,
        \Amasty\Checkout\Model\OrderCustomFieldsFactory $orderCustomFieldsFactory,
        \Amasty\Checkout\Model\ResourceModel\OrderCustomFields $orderCustomFieldsResource
    ) {
        $this->fieldsManagement = $fieldsManagement;
        $this->subscription = $subscription;
        $this->feeRepository = $feeRepository;
        $this->delivery = $delivery;
        $this->deliveryResource = $deliveryResource;
        $this->config = $config;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->orderCustomFieldsFactory = $orderCustomFieldsFactory;
        $this->orderCustomFieldsResource = $orderCustomFieldsResource;
    }

    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }
        /** @var  \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        if (!$order) {
            return $this;
        }

        $orderId = $order->getId();
        $quoteId = $quote->getId();

        $fee = $this->feeRepository->getByQuoteId($quoteId);
        if ($fee->getId()) {
            $fee->setOrderId($orderId);
            $this->feeRepository->save($fee);
        }

        $delivery = $this->delivery->findByQuoteId($quoteId);

        if ($delivery->getId()) {
            $delivery->setData('order_id', $orderId);
            $this->deliveryResource->save($delivery);
        }

        $fields = $this->fieldsManagement->getByQuoteId($quoteId);

        if (!$fields->getId()) {
            return $this;
        }

        if ($fields->getSubscribe()) {
            $this->subscription->subscribe($order->getCustomerEmail());
        }

        $this->convertCustomFields($quoteId, $orderId);

        return $this;
    }

    /**
     * Convert Custom Fields from Quote to Order
     *
     * @param int $quoteId
     * @param int $orderId
     */
    private function convertCustomFields($quoteId, $orderId)
    {
        /** @var \Amasty\Checkout\Model\ResourceModel\QuoteCustomFields\Collection $quoteCustomFiledsCollection */
        $quoteCustomFiledsCollection = $this->quoteCollectionFactory->create();

        $quoteCustomFiledsCollection->addFieldByQuoteId($quoteId);

        /** @var \Amasty\Checkout\Model\QuoteCustomFields $quoteCustomFiled */
        foreach ($quoteCustomFiledsCollection->getItems() as $quoteCustomFiled) {
            /** @var \Amasty\Checkout\Model\OrderCustomFields $orderCustomField */
            $orderCustomField = $this->orderCustomFieldsFactory->create();

            $orderCustomField->setBillingValue($quoteCustomFiled->getBillingValue());
            $orderCustomField->setShippingValue($quoteCustomFiled->getShippingValue());
            $orderCustomField->setName($quoteCustomFiled->getName());
            $orderCustomField->setOrderId($orderId);

            $this->orderCustomFieldsResource->save($orderCustomField);
        }
    }
}

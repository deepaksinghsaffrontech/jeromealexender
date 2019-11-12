<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Amasty\Checkout\Api\AdditionalFieldsManagementInterface;
use Amasty\Checkout\Api\QuotePasswordsRepositoryInterface;
use Amasty\Checkout\Model\Config;
use Amasty\Checkout\Model\CustomerValidator;
use Amasty\Checkout\Model\QuotePasswordsFactory;
use Amasty\Checkout\Model\Sales\OrderCustomerExtractor;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Data\Customer;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Stdlib\DateTime;

class AccountManagement implements \Amasty\Checkout\Api\AccountManagementInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var AccountManagementInterface
     */
    private $accountManagement;

    /**
     * @var OrderCustomerExtractor
     */
    private $customerExtractor;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var QuotePasswordsRepositoryInterface
     */
    private $quotePasswordsRepository;

    /**
     * @var \Amasty\Checkout\Model\QuotePasswordsFactory
     */
    private $quotePasswordsFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var AdditionalFieldsManagementInterface
     */
    private $fieldsManagement;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var CustomerValidator
     */
    private $customerValidator;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AccountManagementInterface $accountManagement,
        OrderCustomerExtractor $customerExtractor,
        Config $config,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        QuotePasswordsRepositoryInterface $quotePasswordsRepository,
        QuotePasswordsFactory $quotePasswordsFactory,
        LoggerInterface $logger,
        EncryptorInterface $encryptor,
        AdditionalFieldsManagementInterface $fieldsManagement,
        TimezoneInterface $timezone,
        CustomerFactory $customerFactory,
        CustomerValidator $customerValidator,
        StoreManagerInterface $storeManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->accountManagement = $accountManagement;
        $this->customerExtractor = $customerExtractor;
        $this->config = $config;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->quotePasswordsRepository = $quotePasswordsRepository;
        $this->quotePasswordsFactory = $quotePasswordsFactory;
        $this->logger = $logger;
        $this->encryptor = $encryptor;
        $this->fieldsManagement = $fieldsManagement;
        $this->timezone = $timezone;
        $this->customerFactory = $customerFactory;
        $this->customerValidator = $customerValidator;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function createAccount($order)
    {
        if ($this->config->getAdditionalOptions('create_account') === '2') {
            $this->setCustomerDob($order);

            /** @var Customer $customer */
            $customer = $this->customerExtractor->extract($order);

            if (!$customer->getId()) {
                $this->setCustomerInformation($customer, $order);

                /** @var \Magento\Customer\Model\Customer $customerModel */
                $customerModel = $this->customerFactory->create();
                $customerModel->setData($customer->__toArray());
                $customerModel->getGroupId();

                if ($this->accountManagement->isEmailAvailable($customer->getEmail())) {
                    /** @var QuotePasswords $passwordQuote */
                    $passwordQuote = $this->quotePasswordsRepository->getByQuoteId($order->getQuoteId());

                    if (!$this->customerValidator->validate($customerModel)) {
                        return false;
                    }

                    /** @var Customer $account */
                    $account = $this->accountManagement->createAccountWithPasswordHash(
                        $customer,
                        $passwordQuote->getPasswordHash()
                    );

                    $order->setCustomerId($account->getId());
                    $order->setCustomerIsGuest(0);
                    $this->orderRepository->save($order);
                    $this->deletePassword($order);

                    return $account;
                }
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function savePassword($cartId, $password)
    {
        if ($this->config->getAdditionalOptions('create_account') === '2' && $password) {
            try {
                /** @var QuoteIdMask $quoteIdMask */
                $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
                /** @var QuotePasswords $quotePassword */
                $quotePassword = $this->getPasswordQuote($quoteIdMask->getQuoteId());

                $passwordHash = $this->createPasswordHash($password);
                $quotePassword->setPasswordHash($passwordHash);
                $quotePassword->setQuoteId($quoteIdMask->getQuoteId());

                $this->quotePasswordsRepository->save($quotePassword);
            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deletePassword($order)
    {
        if ($order) {
            try {
                $passwordQuote = $this->quotePasswordsRepository->getByQuoteId($order->getQuoteId());
                $this->quotePasswordsRepository->delete($passwordQuote);
            } catch (\Exception $exception) {
                return true;
            }
        }

        return true;
    }

    /**
     * @param int $quoteId
     *
     * @return QuotePasswords
     */
    private function getPasswordQuote($quoteId)
    {
        try {
            $quotePassword = $this->quotePasswordsRepository->getByQuoteId($quoteId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
            $quotePassword = $this->quotePasswordsFactory->create();
        }

        /** @var QuotePasswords $quotePassword */
        return $quotePassword;
    }

    /**
     * Create a hash for the given password
     *
     * @param string $password
     * @return string
     */
    private function createPasswordHash($password)
    {
        return $this->encryptor->getHash($password, true);
    }

    /**
     * @param OrderInterface $order
     */
    private function setCustomerDob(OrderInterface $order)
    {
        /** @var \Amasty\Checkout\Model\AdditionalFields $fields */
        $fields = $this->fieldsManagement->getByQuoteId($order->getQuoteId());

        if ($fields->getDateOfBirth()) {
            $customerDob = $this->timezone->date($fields->getDateOfBirth())
                ->format(DateTime::DATETIME_PHP_FORMAT);
            /** @var \Magento\Sales\Model\Order\Address $billingAddress */
            $billingAddress = $order->getBillingAddress();
            $billingAddress->setCustomerDob($customerDob);
        }
    }

    /**
     * @param Customer $customer
     * @param OrderInterface $order
     */
    private function setCustomerInformation(Customer $customer, OrderInterface $order)
    {
        // Make sure we have a storeId to associate this customer with.
        if (!$customer->getStoreId()) {
            if ($customer->getWebsiteId()) {
                $storeId = $this->storeManager->getWebsite($customer->getWebsiteId())->getDefaultStore()->getId();
            } else {
                $this->storeManager->setCurrentStore(null);
                $storeId = $this->storeManager->getStore()->getId();
            }
            $customer->setStoreId($storeId);
        }

        // Associate website_id with customer
        if (!$customer->getWebsiteId()) {
            $websiteId = $this->storeManager->getStore($customer->getStoreId())->getWebsiteId();
            $customer->setWebsiteId($websiteId);
        }

        // Associate tax_vat with customer
        if (!$customer->getTaxvat()) {
            $customer->setTaxvat($order->getShippingAddress()->getVatId());
        }
    }
}

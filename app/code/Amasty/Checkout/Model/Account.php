<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Magento\Framework\Stdlib\DateTime;

class Account
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Sales\Api\OrderCustomerManagementInterface
     */
    protected $orderCustomerService;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        $this->messageManager = $messageManager;
        $this->orderCustomerService = $orderCustomerService;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->timezone = $timezone;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param int $orderId
     * @param \Amasty\Checkout\Model\AdditionalFields $fields
     */
    public function create($orderId, $fields)
    {
        if ($this->customerSession->isLoggedIn()) {
            $this->messageManager->addErrorMessage(__('Customer is already registered'));
        }
        $order = $this->orderRepository->get($orderId);
        $orderId = $order->getId();
        if (!$orderId) {
            $this->messageManager->addErrorMessage(__('Your session has expired'));
        }
        try {
            if ($fields->getDateOfBirth()) {
                $customerDob = $this->timezone->date($fields->getDateOfBirth())
                    ->format(DateTime::DATETIME_PHP_FORMAT);
                $billingAddress = $order->getBillingAddress();
                $billingAddress->setCustomerDob($customerDob);
            }
            $this->orderCustomerService->create($orderId);
            $this->messageManager->addSuccessMessage(
                __('Registration: A letter with further instructions will be sent to your email.')
            );
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong with the registration.'));
        }
    }
}

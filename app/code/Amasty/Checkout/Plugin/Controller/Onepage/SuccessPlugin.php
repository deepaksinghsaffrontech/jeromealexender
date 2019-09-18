<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\Controller\Onepage;

use Amasty\Checkout\Model\CustomerValidator;
use Magento\Framework\Exception\LocalizedException;

class SuccessPlugin
{

    /**
     * @var \Amasty\Checkout\Model\Account
     */
    private $account;

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $config;

    /**
     * @var \Amasty\Checkout\Api\AdditionalFieldsManagementInterface
     */
    private $fieldsManagement;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    public function __construct(
        \Amasty\Checkout\Model\Account $account,
        \Amasty\Checkout\Model\Config $config,
        \Amasty\Checkout\Api\AdditionalFieldsManagementInterface $fieldsManagement,
        \Magento\Checkout\Model\Session $session,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->account = $account;
        $this->config = $config;
        $this->fieldsManagement = $fieldsManagement;
        $this->session = $session;
        $this->dataPersistor = $dataPersistor;
        $this->messageManager = $messageManager;
    }

    /**
     * @param \Magento\Checkout\Controller\Onepage\Success $subject
     * @return null
     */
    public function beforeExecute(\Magento\Checkout\Controller\Onepage\Success $subject)
    {
        if ($errors = $this->dataPersistor->get(CustomerValidator::ERROR_SESSION_INDEX)) {
            $this->messageManager->addExceptionMessage(
                new LocalizedException(__($errors)),
                __('Something went wrong while creating an account. Please contact us so we can assist you.')
            );

            $this->dataPersistor->clear(CustomerValidator::ERROR_SESSION_INDEX);
        }

        if (!$this->config->isEnabled()) {
            return null;
        }

        $order = $this->session->getLastRealOrder();

        if (!$order || $order->getCustomerId()) {
            return null;
        }

        $fields = $this->fieldsManagement->getByQuoteId($order->getQuoteId());

        if ($fields->getRegister()) {
            $this->account->create($order->getId(), $fields);
        }

        return null;
    }
}

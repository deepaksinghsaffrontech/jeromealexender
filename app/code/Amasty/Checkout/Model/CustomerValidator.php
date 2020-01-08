<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Magento\Eav\Model\Validator\Attribute\Data;
use Magento\Framework\App\Request\DataPersistorInterface;

class CustomerValidator
{
    const ERROR_SESSION_INDEX = 'amasty_checkout_account_create_error';

    /**
     * @var array
     */
    private $arrayErrors = [];

    /**
     * @var Data
     */
    private $eavData;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    public function __construct(
        Data $eavData,
        DataPersistorInterface $dataPersistor
    ) {
        $this->eavData = $eavData;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @param \Magento\Customer\Model\Customer $customerModel
     *
     * @return bool
     */
    public function validate(\Magento\Customer\Model\Customer $customerModel)
    {
        if (!$this->eavData->isValid($customerModel)) {
            $this->setErrorsMessage();

            return false;
        }

        return true;
    }

    public function setErrorsMessage()
    {
        $errors = $this->eavData->getMessages();

        array_map([$this, 'convertErrorsToString'], $errors);

        if ($this->arrayErrors) {
            $this->dataPersistor->set(self::ERROR_SESSION_INDEX, implode(', ', $this->arrayErrors));
        }
    }

    /**
     * @param array $error
     */
    public function convertErrorsToString($error)
    {
        if (isset($error[0]) && $error[0] instanceof \Magento\Framework\Phrase) {
            $this->arrayErrors[] = $error[0]->__toString();
        }
    }
}

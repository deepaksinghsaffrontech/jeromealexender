<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\Sales\Admin\Order\Address;

use Amasty\Checkout\Api\Data\CustomFieldsConfigInterface;
use Amasty\Checkout\Api\Data\OrderCustomFieldsInterface;

class Form
{
    /**
     * @var \Amasty\Checkout\Model\ResourceModel\OrderCustomFields\CollectionFactory
     */
    private $orderCustomFieldsCollection;

    public function __construct(
        \Amasty\Checkout\Model\ResourceModel\OrderCustomFields\CollectionFactory $orderCustomFieldsCollection
    ) {
        $this->orderCustomFieldsCollection = $orderCustomFieldsCollection;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\Address\Form $subject
     * @param array $formValues
     *
     * @return array
     */
    public function afterGetFormValues(\Magento\Sales\Block\Adminhtml\Order\Address\Form $subject, $formValues)
    {
        $countOfCustomFields = CustomFieldsConfigInterface::COUNT_OF_CUSTOM_FIELDS;
        $index = CustomFieldsConfigInterface::CUSTOM_FIELD_INDEX;

        for ($index; $index <= $countOfCustomFields; $index++) {
            /** @var \Amasty\Checkout\Model\ResourceModel\OrderCustomFields\Collection $orderCustomFieldsCollection */
            $orderCustomFieldsCollection = $this->orderCustomFieldsCollection->create();
            $orderCustomFieldsCollection->addFieldByOrderIdAndCustomField($formValues['parent_id'], 'custom_field_' . $index);
            $orderCustomFieldsData = $orderCustomFieldsCollection->getFirstItem()->getData();

            if ($orderCustomFieldsData) {
                $formValues[$orderCustomFieldsData[OrderCustomFieldsInterface::NAME]] = $orderCustomFieldsData[$formValues['address_type'] . '_value'];
            }
        }

        return $formValues;
    }
}

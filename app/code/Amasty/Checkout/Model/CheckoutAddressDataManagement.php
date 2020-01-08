<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Magento\Customer\Api\Data\AttributeMetadataInterface;

class CheckoutAddressDataManagement
{
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    public function __construct(
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->productMetadata = $productMetadata;
    }

    /**
     * @param array $inputArray
     *
     * @return array
     */
    public function prepareAddressData($inputArray)
    {
        if (version_compare($this->productMetadata->getVersion(), '2.3', '<')) {
            return $inputArray;
        } else {
            return $this->getCustomAttributesValues($inputArray);
        }
    }

    /**
     * @param array $customAttributes
     *
     * @return array
     */
    private function getCustomAttributesValues($customAttributes)
    {
        $data = [];

        foreach ($customAttributes as $customAttribute) {
            $data[$customAttribute[AttributeMetadataInterface::ATTRIBUTE_CODE]] = $customAttribute['value'];
        }

        return $data;
    }
}

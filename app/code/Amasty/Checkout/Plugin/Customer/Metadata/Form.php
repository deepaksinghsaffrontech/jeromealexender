<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\Customer\Metadata;

use Amasty\Checkout\Api\Data\CustomFieldsConfigInterface;
use Magento\Customer\Model\Indexer\Address\AttributeProvider;

class Form
{
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    private $eavAttributeRepository;

    /**
     * @var \Magento\Customer\Model\AttributeMetadataConverter
     */
    private $attributeMetadataConverter;

    public function __construct(
        \Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
        \Magento\Customer\Model\AttributeMetadataConverter $attributeMetadataConverter
    ) {
        $this->eavAttributeRepository = $eavAttributeRepository;
        $this->attributeMetadataConverter = $attributeMetadataConverter;
    }

    /**
     * @param \Magento\Customer\Model\Metadata\Form $subject
     * @param array $attributes
     *
     * @return array
     */
    public function afterGetAttributes(\Magento\Customer\Model\Metadata\Form $subject, $attributes)
    {
        $countOfCustomFields = CustomFieldsConfigInterface::COUNT_OF_CUSTOM_FIELDS;
        $index = CustomFieldsConfigInterface::CUSTOM_FIELD_INDEX;

        if (!isset($attributes['email'])) {
            for ($index; $index <= $countOfCustomFields; $index++) {
                try {
                    $customAttribute = $this->eavAttributeRepository->get(AttributeProvider::ENTITY, 'custom_field_' . $index);
                } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                    continue;
                }

                if ($customAttribute->getData()) {
                    $attributes['custom_field_' . $index] = $this->attributeMetadataConverter
                        ->createMetadataAttribute($customAttribute);
                }
            }
        }

        return $attributes;
    }
}

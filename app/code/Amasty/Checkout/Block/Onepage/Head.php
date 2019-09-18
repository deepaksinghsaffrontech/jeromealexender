<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Onepage;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Element\Template;
use Amasty\Checkout\Api\Data\CustomFieldsConfigInterface;

class Head extends Template
{
    /**
     * @var \Amasty\Checkout\Model\Field
     */
    protected $fieldSingleton;

    /**
     * Head constructor.
     *
     * @param Template\Context             $context
     * @param \Amasty\Checkout\Model\Field $fieldSingleton
     * @param array                        $data
     */
    public function __construct(
        Template\Context $context,
        \Amasty\Checkout\Model\Field $fieldSingleton,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->fieldSingleton = $fieldSingleton;
    }

    public function getFields()
    {
        $result = [];

        /** @var \Amasty\Checkout\Model\Field $field */
        foreach ($this->fieldSingleton->getConfig($this->_storeManager->getStore()->getId()) as $field) {
            $result[$field->getData('attribute_code')] = $field->getData('width');
            if ($field->getData('attribute_code') === 'region') {
                $result['region_id'] = $field->getData('width');
            }
        }

        $result = $this->addCustomFieldWidth($result);

        return $result;
    }

    public function getCustomFont()
    {
        $font = $this->_scopeConfig->getValue(
            'amasty_checkout/design/font',
            ScopeInterface::SCOPE_STORE
        );

        return $this->escapeHtml(strtok(trim($font), ':'));
    }

    public function getHeadingTextColor()
    {
        return $this->getRgbSetting('amasty_checkout/design/heading_color');
    }

    public function getSummaryBackgroundColor()
    {
        return $this->getRgbSetting('amasty_checkout/design/summary_color');
    }

    public function getBackgroundColor()
    {
        return $this->getRgbSetting('amasty_checkout/design/bg_color');
    }

    public function getButtonColor()
    {
        $colorCode = $this->getRgbSetting('amasty_checkout/design/button_color');

        if ($colorCode) {
            $less = new \Less_Functions(null);
            $darken = new \Less_Tree_Dimension(10, '%');
            $color = new \Less_Tree_Color(ltrim($colorCode, '#'));

            $hoverColor = $less->darken($color, $darken);

            return [
                'normal' => $colorCode,
                'hover' => $hoverColor->toRGB()
            ];
        }

        return false;
    }

    protected function getRgbSetting($setting)
    {
        $code = $this->_scopeConfig->getValue($setting, ScopeInterface::SCOPE_STORE);

        $code = trim($code);

        if (!preg_match('|#[0-9a-fA-F]{3,6}|', $code)) {
            return false;
        }

        return $code;
    }

    /**
     * @param array $result
     *
     * @return array
     */
    private function addCustomFieldWidth($result)
    {
        $countOfCustomFields = CustomFieldsConfigInterface::COUNT_OF_CUSTOM_FIELDS;
        $index = CustomFieldsConfigInterface::CUSTOM_FIELD_INDEX;

        for ($index; $index <= $countOfCustomFields; $index++) {
            if (isset($result['custom_field_' . $index])) {
                $customFieldIndex = 'custom_field_' . $index;
                $result['custom_attributes.' . $customFieldIndex . '_shippingAddress'] = $result[$customFieldIndex];
                $result['custom_attributes.' . $customFieldIndex . '_billingAddresscheckmo'] = $result[$customFieldIndex];
            }
        }

        return $result;
    }
}

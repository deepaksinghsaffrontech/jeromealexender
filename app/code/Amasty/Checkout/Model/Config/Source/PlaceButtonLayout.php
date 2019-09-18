<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model\Config\Source;

class PlaceButtonLayout implements \Magento\Framework\Option\ArrayInterface
{
    const PAYMENT = 'payment';
    const SUMMARY = 'summary';
    const FIXED_TOP = 'top';
    const FIXED_BOTTOM = 'bottom';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PAYMENT, 'label' => __('Below the selected payment method')],
            ['value' => self::SUMMARY, 'label' => __('Below the Order Total')]
//            ['value' => self::FIXED_TOP, 'label' => __('Fixed on top of the checkout page')],
//            ['value' => self::FIXED_BOTTOM, 'label' => __('Fixed on bottom of the checkout page')]
        ];
    }
}

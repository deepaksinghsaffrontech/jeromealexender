<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin;

class LayoutProcessor
{
    /**
     * @var array
     */
    protected $orderFixes = [];

    /**
     * @var \Amasty\Checkout\Helper\Onepage
     */
    private $onepageHelper;

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $checkoutConfig;

    public function __construct(
        \Amasty\Checkout\Helper\Onepage\Proxy $onepageHelper,
        \Amasty\Checkout\Model\Config $checkoutConfig
    ) {
        $this->onepageHelper = $onepageHelper;
        $this->checkoutConfig = $checkoutConfig;
    }

    /**
     * @param $field
     * @param $order
     */
    public function setOrder($field, $order)
    {
        $this->orderFixes[$field] = $order;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $result
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        $result
    ) {
        if ($this->checkoutConfig->isEnabled()) {
            $layoutRoot = &$result['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children'];

            foreach ($this->orderFixes as $code => $order) {
                $layoutRoot[$code]['sortOrder'] = $order;
            }
        }

        return $result;
    }
}

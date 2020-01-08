<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin;

use Magento\Checkout\Model\Session as CheckoutSession;

class DefaultConfigProvider
{
    const AMASTY_STOCKSTATUS_MODULE_NAMESPACE = 'Amasty_Stockstatus';

    const BLOCK_NAMES = [
        'block_shipping_address' => 'Shipping Address',
        'block_shipping_method' => 'Shipping Method',
        'block_delivery' => 'Delivery',
        'block_payment_method' => 'Payment Method',
        'block_order_summary' => 'Order Summary',
    ];

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var \Amasty\Checkout\Helper\Item
     */
    private $itemHelper;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    private $layout;

    /**
     * @var \Amasty\Checkout\Model\ModuleEnable
     */
    private $moduleEnable;

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $config;

    /**
     * @var \Amasty\Checkout\Model\FieldsDefaultProvider
     */
    private $fieldsDefaultProvider;

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    public function __construct(
        CheckoutSession $checkoutSession,
        \Amasty\Checkout\Helper\Item $itemHelper,
        \Magento\Framework\View\LayoutInterface $layout,
        \Amasty\Checkout\Model\ModuleEnable $moduleEnable,
        \Amasty\Checkout\Model\Config $config,
        \Amasty\Checkout\Model\FieldsDefaultProvider $fieldsDefaultProvider,
        \Amasty\Base\Model\Serializer $serializer
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->layout = $layout;
        $this->itemHelper = $itemHelper;
        $this->moduleEnable = $moduleEnable;
        $this->config = $config;
        $this->fieldsDefaultProvider = $fieldsDefaultProvider;
        $this->serializer = $serializer;
    }

    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, $config)
    {
        if (!in_array('amasty_checkout', $this->layout->getUpdate()->getHandles())) {
            return $config;
        }

        $quote = $this->checkoutSession->getQuote();

        $defaultData = $this->fieldsDefaultProvider->getDefaultData();
        if ($defaultData) {
            foreach ($defaultData as $field => $value) {
                $config['amdefault'][$field] = $value;
            }
        }

        foreach ($config['quoteItemData'] as &$item) {
            $additionalConfig = $this->itemHelper->getItemOptionsConfig($quote, $item['item_id']);

            if ($this->moduleEnable->isStockStatusEnable()) {
                $item['amstockstatus'] = $this->itemHelper->getItemCustomStockStatus($quote, $item['item_id']);
            }

            if (!empty($additionalConfig)) {
                $item['amcheckout'] = $additionalConfig;
            }
        }

        $this->getBlockNames($config);

        if ($this->moduleEnable->isPostNlEnable()) {
            $config['quoteData']['posnt_nl_enable'] = true;
        }

        $config['quoteData']['additional_options']['create_account'] =
            $this->config->getAdditionalOptions('create_account');

        return $config;
    }

    /**
     * @param $config
     */
    private function getBlockNames(&$config)
    {
        foreach (self::BLOCK_NAMES as $blockCode => $defaultName) {
            $blockInfo = $this->serializer->unserialize($this->config->getBlockInfo($blockCode));

            if ($blockInfo && empty($blockInfo['value'])) {
                $blockInfo['value'] = $defaultName;
            }

            $config['quoteData']['block_info'][$blockCode] = $blockInfo ?: ['value' => __($defaultName)];
        }
    }
}

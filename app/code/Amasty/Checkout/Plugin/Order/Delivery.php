<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\Order;

class Delivery
{
    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $checkoutConfig;

    public function __construct(
        \Amasty\Checkout\Model\Config $checkoutConfig
    ) {
        $this->checkoutConfig = $checkoutConfig;
    }

    /**
     * @param \Magento\Sales\Block\Items\AbstractItems $subject
     * @param string $result
     *
     * @return string
     */
    public function afterToHtml(
        \Magento\Sales\Block\Items\AbstractItems $subject,
        $result
    ) {
        if (!$this->checkoutConfig->isEnabled()) {
            return $result;
        }
        foreach ($subject->getLayout()->getUpdate()->getHandles() as $handle) {
            if (substr($handle, 0, 12) !== 'sales_email_') {
                return $result;
            }
            /** @var  \Magento\Sales\Model\Order $order */
            $order = $subject->getOrder();
            if (!$order || !$order->getId()) {
                return $result;
            }

            $deliveryBlock = $subject->getLayout()
                ->createBlock(
                    'Amasty\Checkout\Block\Sales\Order\Email\Delivery',
                    'amcheckout.delivery',
                    [
                        'data' => [
                            'order_id' => $order->getId()
                        ]
                    ]
                );

            $result = $deliveryBlock->toHtml() . $result;

            if ($this->checkoutConfig->getAdditionalOptions('comment')) {
                $commentsBlock = $subject->getLayout()
                    ->createBlock(
                        'Amasty\Checkout\Block\Sales\Order\Email\Comments',
                        'amcheckout.comments',
                        [
                            'data' => [
                                'order_entity' => $order
                            ]
                        ]
                    );

                $result = $commentsBlock->toHtml() . $result;
            }
        }

        return $result;
    }
}

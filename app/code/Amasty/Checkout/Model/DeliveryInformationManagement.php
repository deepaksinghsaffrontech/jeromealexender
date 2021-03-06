<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Model;

use Amasty\Checkout\Api\DeliveryInformationManagementInterface;

class DeliveryInformationManagement implements DeliveryInformationManagementInterface
{
    /**
     * @var ResourceModel\Delivery
     */
    protected $deliveryResource;

    /**
     * @var Delivery
     */
    protected $delivery;

    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    public function __construct(
        \Amasty\Checkout\Model\ResourceModel\Delivery $deliveryResource,
        \Amasty\Checkout\Model\Delivery $delivery,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->deliveryResource = $deliveryResource;
        $this->delivery = $delivery;
        $this->escaper = $escaper;
    }

    /**
     * @inheritdoc
     */
    public function update($cartId, $date, $time = -1, $comment = '')
    {
        $delivery = $this->delivery->findByQuoteId($cartId);

        $delivery->addData([
            'date' => strtotime($date) ?: null,
            'time' => $time >= 0 ? $time : null,
            'comment' => ($comment) ?: $this->escaper->escapeHtml($comment)
        ]);

        if ($delivery->getData('date') === null && $delivery->getData('time') === null && $delivery->getData('comment') === null) {
            if ($delivery->getId()) {
                $this->deliveryResource->delete($delivery);
            }
        }
        else {
            $this->deliveryResource->save($delivery);
        }

        return true;
    }
}

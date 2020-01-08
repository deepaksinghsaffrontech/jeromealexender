<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Onepage\Success;

use Magento\Store\Model\ScopeInterface;

class Cms extends \Magento\Cms\Block\Block
{
    private $blockId;

    public function getBlockId()
    {
        if ($this->blockId === null) {
            $this->blockId = $this->_scopeConfig->getValue(
                'amasty_checkout/success_page/block_id',
                ScopeInterface::SCOPE_STORE
            );
        }

        return $this->blockId;
    }

    /**
     * @inheritdoc
     */
    public function getCacheKeyInfo()
    {
        return array_merge(parent::getCacheKeyInfo(), ['store' . $this->_storeManager->getStore()->getId()]);
    }
}

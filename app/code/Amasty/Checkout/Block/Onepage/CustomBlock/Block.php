<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Onepage\CustomBlock;

class Block extends \Magento\Cms\Block\Block
{
    private $blockId;
    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $config;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Amasty\Checkout\Model\Config $config,
        array $data = []
    ) {
        parent::__construct($context, $filterProvider, $storeManager, $blockFactory, $data);
        $this->config = $config;
        $this->setData('cache_lifetime', 86400);
    }

    /**
     * @return string|int
     */
    public function getBlockId()
    {
        if ($this->blockId === null) {
            $this->blockId = $this->config->getCustomBlockIdByPosition($this->getPosition());
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

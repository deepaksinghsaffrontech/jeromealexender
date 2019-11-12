<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model\Config\Backend;

class OrderBlockSave extends \Magento\Framework\App\Config\Value implements
    \Magento\Framework\App\Config\Data\ProcessorInterface
{
    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Amasty\Base\Model\Serializer $serializer,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->serializer = $serializer;
    }

    /**
     * @inheritdoc
     */
    public function processValue($value)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        $groups = $this->getData('groups');
        if (is_array($groups) && isset($groups['block_names'])) {
            $blockField = $groups['block_names']['fields'][$this->getField()];
            $this->setValue($this->serializer->serialize($blockField));
        }

        return parent::beforeSave();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $data = $this->getData('groups');

        if (isset($data['block_names']['fields']['block_management']['inherit'])) {
            $inherit = $data['block_names']['fields']['block_management']['inherit'];

            if ($inherit) {
                $this->_resourceCollection->addFieldToFilter('scope_id', $this->getScopeId())
                    ->addFieldToFilter('scope', $this->getScope())
                    ->walk('delete');
            }
        }

        return parent::afterDelete();
    }
}

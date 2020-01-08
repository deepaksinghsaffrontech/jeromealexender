<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model\Config;

class DragAndDrop extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var array
     */
    private $blockInfo = [];

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $config;

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Checkout\Model\Config $config,
        \Amasty\Base\Model\Serializer $serializer,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_template = 'Amasty_Checkout::system/config/form/field/array.phtml';
        $this->config = $config;
        $this->serializer = $serializer;
    }

    /**
     * @return void
     */
    public function _prepareToRender()
    {
        $this->addColumn(
            'attribute',
            [
                'label' => __('Attribute'),
                'style' => 'width:120px',
            ]
        );
    }

    /**
     * @return array
     */
    public function getSortedBlockNames()
    {
        if (empty($this->blockInfo)) {
            $blockData = $this->getData('config_data');

            foreach (\Amasty\Checkout\Plugin\DefaultConfigProvider::BLOCK_NAMES as $blockCode => $blockName) {
                $blockInfo = $this->getBlockInfo($blockData, $blockCode);
                $sortOrder = 0;
                $blockValue = "";

                if ($blockInfo) {
                    $blockInfo = $this->serializer->unserialize($blockInfo);
                    $sortOrder = isset($blockInfo['sort_order']) ? $blockInfo['sort_order'] : 0;
                    $blockValue = isset($blockInfo['value']) ? $blockInfo['value'] : "";
                }

                while (isset($this->blockInfo[$sortOrder])) {
                    $sortOrder++;
                }

                $this->blockInfo[$sortOrder] = [
                    'block_code' => $blockCode,
                    'block_value' => $blockValue,
                    'block_name' => $blockName
                ];
            }

            ksort($this->blockInfo);
        }

        return $this->blockInfo;
    }

    /**
     * @return array
     */
    private function getScopeInfo()
    {
        /** @var \Magento\Config\Block\System\Config\Form $form */
        if ($form = $this->getData('form')) {
            $scope = $form->getScope();
            $scopeId = $form->getScopeId();

            return ['scope' => $scope, 'scope_id' => $scopeId];
        }

        return [];
    }

    /**
     * @param array $blockData
     * @param string $blockCode
     *
     * @return string
     */
    private function getBlockInfo($blockData, $blockCode)
    {
        $groupName = $this->config->getBlocksGroupName();
        $blockInfo = '';

        if (isset($blockData[$groupName . $blockCode])) {
            $blockInfo = $blockData[$groupName . $blockCode];
        } else {
            $scopeInfo = $this->getScopeInfo();

            if ($scopeInfo) {
                $blockInfo = $this->config->getBlockInfo($blockCode, $scopeInfo['scope_id'], $scopeInfo['scope']);
            }
        }

        return $blockInfo;
    }
}

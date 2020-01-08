<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Adminhtml\Field\Edit\Tabs;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class AdditionalOptions extends Generic implements TabInterface
{
    /**
     * @inheritdoc
     */
    public function getTabLabel()
    {
        return __('Additional Options');
    }

    /**
     * @inheritdoc
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @inheritdoc
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_');

        $fieldset = $form->addFieldset('additional_fieldset', ['legend' => __('Additional Options')]);

        $fieldset->addField(
            'additional_field',
            'text',
            [
                'label' => __('Additional Options'),
                'title' => __('Additional Options'),
                'name' => 'additional_field',
            ]
        );

        $layout = $this->getLayout();

        $form->getElement(
            'additional_field'
        )->setRenderer(
            $layout->createBlock(\Amasty\Checkout\Block\Adminhtml\Field\Edit\AdditionalOptions::class)
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }
}

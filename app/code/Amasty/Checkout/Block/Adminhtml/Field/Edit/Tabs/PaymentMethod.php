<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Adminhtml\Field\Edit\Tabs;

use Amasty\Checkout\Block\Adminhtml\Field\Edit\Group;
use Amasty\Checkout\Block\Adminhtml\Field\Edit\Group\Renderer as GroupRenderer;
use Amasty\Checkout\Block\Adminhtml\Field\Edit\Group\Row\Renderer as RowRenderer;
use Amasty\Checkout\Model\Field;
use Amasty\Checkout\Model\FormManagement;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Amasty\Checkout\Api\Data\ManageCheckoutTabsInterface;

class PaymentMethod extends Generic implements TabInterface
{
    /**
     * @var FormManagement
     */
    private $formManagement;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        FormManagement $formManagement,
        array $data = []
    ) {
        $this->formManagement = $formManagement;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @inheritdoc
     */
    public function getTabLabel()
    {
        return __('Payment Method');
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
        $storeId = $this->_request->getParam(ScopeInterface::SCOPE_STORE, Field::DEFAULT_STORE_ID);
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->formManagement->prepareForm(ManageCheckoutTabsInterface::PAYMENT_METHOD_TAB, $storeId);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        $layout = $this->getLayout();
        $nameInLayout = $this->getNameInLayout();

        Group::setRowRenderer(
            $layout->createBlock(
                RowRenderer::class,
                $nameInLayout . '_row_element'
            )
        );

        Group::setGroupRenderer(
            $layout->createBlock(
                GroupRenderer::class,
                $nameInLayout . '_group_element'
            )
        );

        return parent::_prepareLayout();
    }
}

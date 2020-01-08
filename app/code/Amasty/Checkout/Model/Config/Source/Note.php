<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model\Config\Source;

class Note extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Render element value
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _renderValue(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '';
        if ($element->getComment()) {
            $html = '<td class="value" style="padding-top: 0">';
            $html .= '<p class="note"><span>' . $element->getComment() . '</span></p>';
            $html .= '</td>';
        }

        return $html;
    }

    /**
     * @inheritdoc
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '<td class="label"></td>';
        $html .= $this->_renderValue($element);
        $html .= $this->_renderHint($element);

        return $this->_decorateRowHtml($element, $html);
    }
}

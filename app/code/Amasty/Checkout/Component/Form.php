<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Component;

use Magento\Ui\Component\Form as UiFrom;

class Form extends UiFrom
{
    /**
     * {@inheritdoc}
     */
    public function getDataSourceData()
    {
        return $this->getContext()->getDataProvider()->getData();
    }
}

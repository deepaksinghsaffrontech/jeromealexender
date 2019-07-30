<?php
/**
 * Copyright © 2015 Saffron.com. All rights reserved.

 * @author Saffron Team <contact@Saffron.com>
 */

namespace Saffron\Blog\Block\Adminhtml;

/**
 * Admin blog post
 */
class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml';
        $this->_blockGroup = 'Saffron_Blog';
        $this->_headerText = __('Post');
        $this->_addButtonLabel = __('Add New Post');
        parent::_construct();
    }
}

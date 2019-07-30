<?php
/**
 * Copyright © 2015 Saffron.com. All rights reserved.

 * @author Saffron Team <contact@Saffron.com>
 */

namespace Saffron\Blog\Controller\Adminhtml\Post;

/**
 * Blog post related products controller
 */
class RelatedProducts extends \Saffron\Blog\Controller\Adminhtml\Post
{
    /**
     * View related products action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
	public function execute()
    {
        $model = $this->_getModel();
        $this->_getRegistry()->register('current_model', $model);

        $this->_view->loadLayout()
            ->getLayout()
            ->getBlock('blog.post.edit.tab.relatedproducts')
            ->setProductsRelated($this->getRequest()->getPost('products_related', null));
 
        $this->_view->renderLayout();
    }
}

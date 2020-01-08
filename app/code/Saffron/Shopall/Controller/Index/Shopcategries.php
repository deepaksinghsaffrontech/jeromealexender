<?php

namespace Saffron\Shopall\Controller\Index;

class Shopcategries extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
    $this->_view->loadLayout();
	

    echo $this->_view->getLayout()
                 ->createBlock("Saffron\Shopall\Block\Index\Shopcategries")
                 ->setTemplate("Saffron_Shopall::shopall_index_shopcategries.phtml")
                 ->toHtml();
   die;  
     
    }
}
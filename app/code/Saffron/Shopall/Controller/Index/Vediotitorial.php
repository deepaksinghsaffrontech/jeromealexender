<?php

namespace Saffron\Shopall\Controller\Index;

class Vediotitorial extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
    $this->_view->loadLayout();
	

    echo $this->_view->getLayout()
                 ->createBlock("Saffron\Shopall\Block\Index\Vediotitorial")
                 ->setTemplate("Saffron_Shopall::shopall_index_vediotitorial.phtml")
                 ->toHtml();
   die;  
     
    }
}
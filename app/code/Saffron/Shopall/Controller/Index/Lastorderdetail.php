<?php

namespace Saffron\Shopall\Controller\Index;

class Lastorderdetail extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
    $this->_view->loadLayout();
	

    echo $this->_view->getLayout()
                 ->createBlock("Saffron\Shopall\Block\Index\Lastorderdetail")
                 ->setTemplate("Saffron_Shopall::shopall_index_lastorderdetail.phtml")
                 ->toHtml();
   die;  
     
    }
}
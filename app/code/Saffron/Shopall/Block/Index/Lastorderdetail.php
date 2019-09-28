<?php

namespace Saffron\Shopall\Block\Index;

class Lastorderdetail extends \Magento\Catalog\Block\Product\AbstractProduct {

   
  
public function getAllItems()
{
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	$lastorderId =$this->LastorderId();
    $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($lastorderId);
    $orderItems = $order->getAllItems();
    $itemQty = array();
	$itemQty['order_date']= $order->getCreatedAt() ;
    foreach ($orderItems as $item) {
        $itemQty[]=array('quantity'=>$item->getQtyOrdered(),'description'=>$item->getDescription(),'name'=>$item->getName(),'price'=>$item->getPrice());

    }
    return $itemQty;
}

public function LastorderId(){
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$checkout_session = $objectManager->get('Magento\Checkout\Model\Session');
$order = $checkout_session->getLastRealOrder();
$orderId= $order->getEntityId();

return $order->getIncrementId();



}

}
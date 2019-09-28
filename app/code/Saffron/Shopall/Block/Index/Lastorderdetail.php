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
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getId());
    $itemQty[]=array('quantity'=>$item->getQtyOrdered(),'description'=>$item->getDescription(),'name'=>$item->getName(),'productImage'=>$product->getImage(),'producturl'=>$product->getUrl(),'price'=>$item->getPrice(),'product_id'=>$item->getId());

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

public function getMedia(){
	$_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	$storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
	$currentStore = $storeManager->getStore();
	$mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
     
	  return $mediaUrl ;
} 




}
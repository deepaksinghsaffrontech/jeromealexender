<?php

namespace Saffron\Shopall\Block\Index;

class Lastorderdetail extends \Magento\Catalog\Block\Product\AbstractProduct {

   
  
public function getAllproducts($lastorderId)
{
	
	if(!empty($lastorderId)){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	
    $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($lastorderId);
    $orderItems = $order->getAllItems();
    $itemQty = array();
	$billingAddress = $order->getBillingAddress();
    $customerName = $billingAddress->getFirstname() . ' ' . 
    $billingAddress->getLastname();
	$billingstate=$order->getBillingAddress()->getRegion();
	$itemQty['order_date']= $order->getCreatedAt() ;
	$itemQty['order_name']= $customerName ;
	$itemQty['region']= $billingstate ;
    foreach ($orderItems as $item) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getId());
    $itemQty['items'][]=array('quantity'=>$item->getQtyOrdered(),'description'=>$product->getShortDescription(),'name'=>$item->getName(),'productImage'=>$product->getImage(),'producturl'=>$product->getProductUrl(),'price'=>$item->getPrice(),'product_id'=>$item->getId());

    }
	}else{
	$itemQty['No_order'];		
	}
    return $itemQty;
}



public function getMedia(){
	$_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	$storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
	$currentStore = $storeManager->getStore();
	$mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
     
	  return $mediaUrl ;
} 

public function getRandid(){

$orders =array();
$now = new \DateTime();

$date = date('m/d/Y h:i:s', time());
$prev_date = date('Y-m-d h:i:s', strtotime($date .' -3 day'));


$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$OrderFactory = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\CollectionFactory');
$orderCollection = $OrderFactory->create()->addFieldToSelect(array('*'));
 $orderCollection->addFieldToFilter('created_at', ['lteq' => $now->format('Y-m-d H:i:s')])->addFieldToFilter('created_at', ['gteq' => $now->format($prev_date)]);
$alloder = $orderCollection->getData();
$i=0;
 foreach($alloder as $items){
  $orders[$i]['increment_id']	= $items['increment_id']; 
 $i++; } 
$arrX = $orders ;
$randIndex = array_rand($arrX); 

return $arrX[$randIndex];

	
}


}
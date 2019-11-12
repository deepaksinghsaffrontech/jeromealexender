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
	$i=0;
    foreach ($orderItems as $item) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $product1 = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($item->getId());
     $product_id = $item->getId();
	if(!empty($product1)){
	if(isset($product1[0])){
            
	  $product_id =  $product1[0];
	}
    }

   $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
    $itemQty['items'][$i]['quantity']=$item->getQtyOrdered() ;
	$itemQty['items'][$i]['description']= $product->getShortDescription() ;
	$itemQty['items'][$i]['name']= $product->getName() ;
	$itemQty['items'][$i]['productImage']= $product->getImage() ;
	$itemQty['items'][$i]['producturl']= $product->getProductUrl() ;
	$itemQty['items'][$i]['product_id']= $item->getId() ;
	
	//$itemQty['items'][]=array('quantity'=>$item->getQtyOrdered(),'description'=>$product->getShortDescription(),'name'=>$item->getName(),'productImage'=>$product->getImage(),'producturl'=>$product->getProductUrl(),'price'=>$item->getPrice(),'product_id'=>$item->getId());
$i++;
    }
	}else{
	$itemQty['No_order'];		
	}
    return $itemQty;
}


public function getAlldetailpage(){
	
	
}


public function getMedia(){
	$_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	$storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
	$currentStore = $storeManager->getStore();
	$mediaUrl = $currentStore->getBaseUrl();
     
	  return $mediaUrl ;
} 

public function getRandid(){

$orders =array();
$now = new \DateTime();

$date = date('m/d/Y h:i:s', time());
$prev_date = date('Y-m-d h:i:s', strtotime($date .' -2 day'));


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
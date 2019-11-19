<?php
namespace Saffron\Newshipping\Observer;


use Magento\Quote\Model\Quote;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;

/**
 * Class TotalsBeforeEvent
 */
class TotalsBeforeEvent implements ObserverInterface
{
 
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
    }
 
 
 
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** Fetch Address related data */
        $shippingAssignment = $observer->getEvent()->getShippingAssignment();
        $address = $shippingAssignment->getShipping()->getAddress();
        // fetch quote data
        /** @var Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        // fetch totals data
        $total = $observer->getEvent()->getTotal();
        $region = $address->getData();
       
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        // retrieve quote items collection
        $itemsCollection = $cart->getQuote()->getItemsCollection();
         // get array of all items what can be display directly
        $itemsVisible = $cart->getQuote()->getAllVisibleItems();
        
        // retrieve quote items array
    $items = $cart->getQuote()->getAllItems();
    foreach ($items as $item) {
    $productId = $item->getProductId();
    $product1 = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($productId);
    $product_id = $productId;
    
	if(!empty($product1)){
	if(isset($product1[0])){
     $product_id =  $product1[0];
	}
    }
    }
        
    if($product_id =='12'){
      
     if($region['country_id'] =='CA'){
          
     }else if($region['region_id'] =='21'){
          
           $name ='deepak';   
     }else{
           $name ='ram';   
          
      }
        
        
    }    
        
        
        
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('data' . json_encode($product_id));
        $logger->info( $region['region_id'].'OrderPlacebefore:'. $region['country_id']);
        $logger->info('data' . json_encode($quote));
    }
}



 /*class OrderPlacebefore implements ObserverInterface
{
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
    }

   public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderObserverData = $observer->getOrder()->getData();
     
	if(!empty($lastorderId)){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	
    $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderObserverData['increment_id']);
    $orderItems = $order->getAllItems();
    $itemQty = array();
	$billingAddress = $order->getBillingAddress();
    $billingstate=$order->getBillingAddress()->getRegion();
	$i=0;
    foreach ($orderItems as $item) {
		
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
   
    $productId = $objectManager->get('Magento\Catalog\Model\Product')->getIdBySku($item->getSku());
	$product1 = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($productId);
    $product_id = $productId;
	if(!empty($product1)){
	if(isset($product1[0])){
     $product_id =  $product1[0];
	}
    }
	

  $i++;
    }
	}

 
 
 
       $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('OrderPlacebefore:');
        $logger->info('data' . json_encode($orderObserverData));

        echo"deepak";die;
        

          //echo"<pre>";print_r($orderObserverData);die;

        
    }
}*/
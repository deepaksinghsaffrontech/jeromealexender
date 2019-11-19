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
       //$ggg =  '\/rest\/default\/V1\/carts\/mine\/totals-information';
        
    $url     = (explode("/",$_SERVER['REQUEST_URI']) );
   
    
    if(($url[4] == 'guest-carts')||($url[4] =='carts') ){
    
    $shippingAssignment = $observer->getEvent()->getShippingAssignment();
    $address = $shippingAssignment->getShipping()->getAddress();
    $quote = $observer->getEvent()->getQuote();
    // fetch totals data
    $total = $observer->getEvent()->getTotal();
    $region = $address->getData();
    
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    
    $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
     $itemsCollection = $cart->getQuote()->getItemsCollection();
    $itemsVisible = $cart->getQuote()->getAllVisibleItems();
    $items = $cart->getQuote()->getAllItems();
	$Product_id = array()
    foreach ($items as $item) {
    $productId = $item->getProductId();
    $product1 = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($productId);
    $product_id = $productId;
    if($product_id  == '12'){
		$Product_id  =  $productId;
	}
	
	$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
	
	if(!empty($product1)){
	if(isset($product1[0])){
   if($product1[0] == '12'){
		$Product_id  =  $productId;
	}
	 
	}
    }
    }
        
    if($Product_id[0] =='12'){
      
     if($region['country_id'] =='CA'){
        $name ='shipping not allowed  ';  
       $logger->info('data' . json_encode($name)); die;
     }else if($region['region_id'] =='21'){
            $name ='shipping not allowed ';  
         $logger->info('data' . json_encode($name)); die;
     }else{
           $name ='shipping allowe';   
          
      }
        
        
    }     
        
  
    //$logger->info('id' . json_encode($product_id));
    //$logger->info( $region['region_id'].'OrderPlacebefore:'. $region['country_id']);
    //$logger->info('data' . json_encode($name));
    
    }
        
   }

}

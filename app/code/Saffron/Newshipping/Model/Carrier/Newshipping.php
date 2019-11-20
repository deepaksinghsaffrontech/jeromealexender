<?php

namespace Saffron\Newshipping\Model\Carrier;
 
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
 
class Newshipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'newshipping';
 
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }
 
    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['newshipping' => $this->getConfigData('name')];
    }
 
    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
 
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();
 
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
		
		
	//$address = $shippingAssignment->getShipping()->getAddress();
	  //$region = $address->getData();
	 $method = $this->_rateMethodFactory->create();   
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
    $quote =  $objectManager->get('Magento\Checkout\Model\Session')->getQuote();
    $address = $quote->getShippingAddress();
    $address->collectShippingRates();
    $region = $address->getData();


$region['country_id'];
$region['region_id'] ;


	
	$amount = $this->Shippingrangeprice();
 
     if($amount =='0.00'){
		 
		 $method->setCarrier('newshipping');
        $method->setCarrierTitle('Free Shipping');
 
        $method->setMethod('newshipping');
        $method->setMethodTitle('Free');
	 }else{
		 
		 $method->setCarrier('newshipping');
        $method->setCarrierTitle($this->getConfigData('title'));
 
        $method->setMethod('newshipping');
        $method->setMethodTitle($this->getConfigData('name'));
	 }
      
 
        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        //$amount = $this->getConfigData('price');
       
        $method->setPrice($amount);
        $method->setPrice($amount);
        $method->setCost($amount);
 
        $result->append($method);
 
        return $result;
    }
	
	
	public function Shippingrangeprice(){
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
	$quoteId = $objectManager->create('Magento\Checkout\Model\Session')->getQuoteId(); 
	$cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
	$itemsCollection = $cart->getQuote()->getItemsCollection();
	$itemsVisible = $cart->getQuote()->getAllVisibleItems();
	$items = $cart->getQuote()->getAllItems();
	$qty_item = $totalQuantity = $cart->getQuote()->getItemsQty();
	$subTotal = $cart->getQuote()->getSubtotal();
	$i=0;
	foreach($items  as  $item){
		   $productId = $item->getProductId(); 
		   $product1 = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($productId);
            //if($productId  == '12'){
		    //$Product_id[$i]  =  $productId;
	       //}
		   
   if(!empty($product1)){
	if(isset($product1[0] =='12')){
    $Product_id[$i] =  $product1[0];
	}
    }
		 
	$i++ ;}
	
	
	
	
	
	
	if(($qty_item=='1' )&&($subTotal<='48.99')){
		$pricerang = '5.95';
     //$pricerang = $qty_item ;

	}else if(($qty_item >= '2' )&&($subTotal<='48.99')){
		$pricerang = '7.95';	
	}else{
		$pricerang = $Product_id[1];
		//$pricerang = '0.00';	
	}
	
	
	
	/*if(('0.00' < $subTotal)&&($subTotal <= '20.00') ){
		$pricerang = '4.95';
	}else if(('21.00' <= $subTotal)&& ($subTotal <= '39.99')){
		$pricerang ='7.95';
	}else if(('40.00' <= $subTotal)&& ($subTotal <= '48.99')){
		$pricerang = '9.95';
	}else{
	$pricerang = '0.00';	
	}
	
	
	
	$reposnse = array();
	$count =  0 ;
	foreach($items as $item) {
		$productid = $item->getProductId();
		$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productid);
		$categoriesIds = $product->getCategoryIds(); 
		foreach($categoriesIds as $categoryId){
		$cat = $objectManager->create('Magento\Catalog\Model\Category')->load($categoryId);
		$pricepresent = $objectManager->create('Saffron\Shippingprice\Model\ResourceModel\Shippingprice\Collection')
		->addFieldToFilter('status', array('eq' => '0'))
		->addFieldToFilter('categries_id', array('eq' => $cat->getId()));
		foreach($pricepresent as $item){
			$reposnse['priceprent'][$count]	= $item[$pricerang];
		
		$count++ ;
		}
		}
		}
	 $allrypriceprent = max($reposnse['priceprent']);

	 $shippingrangeprice = (($subTotal/100)*$allrypriceprent) ;*/
	 
	 return  $pricerang;
			
		
	}
}
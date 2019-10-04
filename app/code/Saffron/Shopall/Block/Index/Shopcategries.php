<?php

namespace Saffron\Shopall\Block\Index;
use Magento\Catalog\Model\Resource\Product\Collection;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Customer\Model\Context;
use Magento\Framework\App\ResourceConnection;

class Shopcategries extends \Magento\Catalog\Block\Product\AbstractProduct {

    	/**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 10;

    /**
     * Products count
     *
     * @var int
     */
    protected $_productsCount;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;
	 protected $_collectionFactory;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\Resource\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
	
	protected $productFactory;
	protected $connection;
	protected $resource;
	


   public function __construct(
			\Magento\Catalog\Block\Product\Context $context,
			\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
			\Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
			\Magento\Framework\App\Http\Context $httpContext,
			  \Magento\Catalog\Model\ProductFactory $productFactory,
				\Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $collectionFactory,
				 ResourceConnection $resource,
			array $data = []
		) {
			$this->_productCollectionFactory = $productCollectionFactory;
			$this->_catalogProductVisibility = $catalogProductVisibility;
			$this->httpContext = $httpContext;
			$this->productFactory = $productFactory;
			 $this->_collectionFactory = $collectionFactory;
			 $this->resource = $resource;
			$this->connection = $resource->getConnection();
			parent::__construct(
				$context,
				$data
			);
			$this->_isScopePrivate = true;
		}
		public function _prepareLayout()
		{ 

			return parent::_prepareLayout();
		}
	
	 protected function getCustomerGroupId()
			{
				$customerGroupId =   (int) $this->getRequest()->getParam('cid');
				if ($customerGroupId == null) {
					$customerGroupId = $this->httpContext->getValue(Context::CONTEXT_GROUP);
				}
				return $customerGroupId;
			}

	/*public function getShopall(){
    $category_id= 2;
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $subcategory = $objectManager->create('Magento\Catalog\Model\Category')->load($category_id);           
    $childCategories = $subcategory->getChildrenCategories();

	$categries = array();
	$count = 1;
	foreach($childCategories as $childCategory )
        {
	    $childCategorys = $objectManager->create('Magento\Catalog\Model\Category')->load($childCategory->getId());           
   
		$categries['categries_id'][$count] =$childCategorys;
		
	    
		$count++; 
		}		
	
	
	return $categries ;
	
	}*/
	
	public function getShopaName(){
    $categoryId  = $_GET['id'];
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $subcategory = $objectManager->create('Magento\Catalog\Model\Category')->load($categoryId);           
    //$childCategories = $subcategory->getChildrenCategories();

	
	return $subcategory->getName() ;
	
	}

public function getShopallproduct(){

$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$categoryId  = $_GET['id'];
$category = $categoryFactory->create()->load($categoryId);

$categoryProducts = $category->getProductCollection()
                               //->setPageSize(3)
							   ->addAttributeToSort('entity_id', 'desc')
                             ->addAttributeToSelect('*');
							 
	

return 	$categoryProducts ;

		
}

  

}
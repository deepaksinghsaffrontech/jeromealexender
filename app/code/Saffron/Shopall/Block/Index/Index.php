<?php

namespace Saffron\Shopall\Block\Index;


class Index extends \Magento\Framework\View\Element\Template {

    public function __construct(\Magento\Catalog\Block\Product\Context $context, array $data = []) {

        parent::__construct($context, $data);

    }
	
	
	public function getShopall(){
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
	
	}

public function getShopallproduct($categoryId){

$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();

$category = $categoryFactory->create()->load($categoryId);

$categoryProducts = $category->getProductCollection()
                             ->addAttributeToSelect('*');
							 
	

return 	$categoryProducts ;

		
}

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

}
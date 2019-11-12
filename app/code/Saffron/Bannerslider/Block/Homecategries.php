<?php

namespace Saffron\Bannerslider\Block;

class Homecategries extends \Magento\Framework\View\Element\Template {

    public function __construct(\Magento\Catalog\Block\Product\Context $context, array $data = []) {

        parent::__construct($context, $data);

    }


    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
	
	
	public function getHomecategries(){
		$catId =2;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $subcategory = $objectManager->create('Magento\Catalog\Model\Category')->load($catId);           
        $subcats = $subcategory->getChildrenCategories();
		
		return $subcats  ;
		
		
	}
	
public function getMediaFolder() {
		$media_folder = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return $media_folder;
	}
}
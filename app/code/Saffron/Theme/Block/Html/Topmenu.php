<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Saffron\Theme\Block\Html;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{
    
	
	
	  /**
     * Cache identities
     *
     * @var array
     */
    //protected $identities = [];

  

	
	
	
    /**
     * Get top menu html
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_before',
            ['menu' => $this->getMenu(), 'block' => $this, 'request' => $this->getRequest()]
        );

        $this->getMenu()->setOutermostClass($outermostClass);
        $this->getMenu()->setChildrenWrapClass($childrenWrapClass);

        $html = $this->_getHtml($this->getMenu(), $childrenWrapClass, $limit);

        $transportObject = new \Magento\Framework\DataObject(['html' => $html]);
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_after',
            ['menu' => $this->getMenu(), 'transportObject' => $transportObject]
        );
        $html = $transportObject->getHtml();
        return $html;
    }

    /**
     * Count All Subnavigation Items
     *
     * @param \Magento\Backend\Model\Menu $items
     * @return int
     */
    protected function _countItems($items)
    {
        $total = $items->count();
        foreach ($items as $item) {
            /** @var $item \Magento\Backend\Model\Menu\Item */
            if ($item->hasChildren()) {
                $total += $this->_countItems($item->getChildren());
            }
        }
        return $total;
    }

    /**
     * Building Array with Column Brake Stops
     *
     * @param \Magento\Backend\Model\Menu $items
     * @param int $limit
     * @return array|void
     *
     * @todo: Add Depth Level limit, and better logic for columns
     */
    protected function _columnBrake($items, $limit)
    {
        $total = $this->_countItems($items);
        if ($total <= $limit) {
            return;
        }

        $result[] = ['total' => $total, 'max' => (int)ceil($total / ceil($total / $limit))];

        $count = 0;
        $firstCol = true;

        foreach ($items as $item) {
            $place = $this->_countItems($item->getChildren()) + 1;
            $count += $place;

            if ($place >= $limit) {
                $colbrake = !$firstCol;
                $count = 0;
            } elseif ($count >= $limit) {
                $colbrake = !$firstCol;
                $count = $place;
            } else {
                $colbrake = false;
            }

            $result[] = ['place' => $place, 'colbrake' => $colbrake];

            $firstCol = false;
        }

        return $result;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
		
        //if (!$child->hasChildren()) {
            //return $html;
        //}
		$checkmenu = $this->Checkmenu($child->getId())  ;
		if($checkmenu == '0'){
			return $html;
		}
          
		$menuproduct = $this->menuProduct($child->getId())  ;
		$menuShowmenu = $this->menuShowmenu($child->getId())  ;
        $colStops = null;
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }
          
        $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass .'">';
        
		$html .= '<div class="menu-container"><div class="menu-product-lists">'. $menuShowmenu .'</div>';
		$html .= '<div class="menu-best-seller">'. $menuproduct .'</div></div>';
		//$html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
       
	   
	   
	    $html .= '</ul>';

        return $html;
    }


public function Checkmenu($cid){
$id =(explode("-",$cid));
$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$reponse = '';
$categoryId =  $id[2]; // YOUR CATEGORY ID
$category = $categoryFactory->create()->load($categoryId);

 return $category->getData('showsubmenu');
}

public function menuShowmenu($cid){
$id =(explode("-",$cid));
$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$reponse = '';
$categoryId =  $id[2]; // YOUR CATEGORY ID
$category = $categoryFactory->create()->load($categoryId);


$category->getData();
$categoryProducts = $category->getProductCollection()
                             ->addAttributeToSelect('*')
							  ->addAttributeToFilter('show_in_menu',['eq'=>'1']);


$i = 0;
$reponse .='<div class="nemu-list">';
$reponse .='<li class="menu-title"><a href="'.$category->getUrl().'"><span>Shop All </span>'.$category->getName().'</a></li> ';

foreach ($categoryProducts as $product) {
//if(($i%5==0)OR($i==0)){
	//$reponse .='<div class="menu-product-list">';
//}
$reponse .='<li>
             <a href="'.$product->getProductUrl().'">
                    <span class="menu-name">'.$product->getName().'</span>
					 
             </a>
		    </li>';
			
//if(($i%5==0)OR($i==0)){
	//$reponse .='</div>';	
//}

$i++;			
}	
$reponse .='</div>';	
 return $reponse ;
}

public function menuProduct($cid){
$id =(explode("-",$cid));
$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$reponse = '';
$categoryId =  $id[2]; // YOUR CATEGORY ID
$category = $categoryFactory->create()->load($categoryId);
$categoryProducts = $category->getProductCollection()
                             ->addAttributeToSelect('*')
							  ->addAttributeToFilter('best_seller',['eq'=>'1'])
							  ->setPageSize(4);


$reponse .='<div class="menu-title-product">'.$category->getName().'<span> Best Sellers</span></div>'; 
$reponse .='<div class="menu-product-list">'; 
foreach ($categoryProducts as $product) 
{
    $imageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
   

              $reponse .='<div class="product-container">
                  <a href="'.$product->getProductUrl().'">

                     <div class="new-arrivals-image">
					 <img src="'.$imageUrl.'">
					 </div>
                     <div class="product-name">
					 <span class="name">'.$product->getName().'</span>
					 </div>
                  </a>
                  <!--<div class="price"><span class="pt">'.$product->getPrice().'</span></div>-->
               </div>';


}

	 $reponse .='</div>';  
	  
	   return $reponse ;
   }

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var \Magento\Framework\Data\Tree\Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                continue;
            }
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $currentClass = $child->getClass();

                if (empty($currentClass)) {
                    $child->setClass($outermostClass);
                } else {
                    $child->setClass($currentClass . ' ' . $outermostClass);
                }
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            $html .= '<a href="#" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                $child->getName()
            ) . '</span></a>' . $this->_addSubMenu(
                $child,
                $childLevel,
                $childrenWrapClass,
                $limit
            ) . '</li>';
            $itemPosition++;
            $counter++;
        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    /**
     * Generates string with all attributes that should be present in menu item element
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return string
     */
    protected function _getRenderedMenuItemAttributes(\Magento\Framework\Data\Tree\Node $item)
    {
        $html = '';
        $attributes = $this->_getMenuItemAttributes($item);
        foreach ($attributes as $attributeName => $attributeValue) {
            $html .= ' ' . $attributeName . '="' . str_replace('"', '\"', $attributeValue) . '"';
        }
        return $html;
    }

    /**
     * Returns array of menu item's attributes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemAttributes(\Magento\Framework\Data\Tree\Node $item)
    {
        $menuItemClasses = $this->_getMenuItemClasses($item);
        return ['class' => implode(' ', $menuItemClasses)];
    }

    /**
     * Returns array of menu item's classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item)
    {
        $classes = [];

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();
        $checkmenu = $this->Checkmenu($item->getId());
        if ($item->getIsCategory()) {
            $classes[] = 'category-item';
        }

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }
        if($checkmenu == '1'){
			$classes[] = 'parent';
		}
        return $classes;
    }

    /**
     * Add identity
     *
     * @param string|array $identity
     * @return void
     */
 

    /**
     * Get identities
     *
     * @return array
     */
 

    /**
     * Get cache key informative items
     *
     * @return array
     * @since 100.1.0
     */
    public function getCacheKeyInfo()
    {
        $keyInfo = parent::getCacheKeyInfo();
        $keyInfo[] = $this->getUrl('*/*/*', ['_current' => true, '_query' => '']);
        return $keyInfo;
    }

    /**
     * Get tags array for saving cache
     *
     * @return array
     * @since 100.1.0
     */
    protected function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), $this->getIdentities());
    }

   
   
}

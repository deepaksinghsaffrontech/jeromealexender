<?php
/**
* Copyright © 2015 Saffron.com. All rights reserved.

* @author Saffron Team <contact@Saffron.com>
*/

namespace Saffron\Bannerslider\Block;

class Bannerslider extends \Magento\Framework\View\Element\Template {

	protected $_template = 'Saffron_Bannerslider::bannerslider.phtml';

	/**
	 * Banner Factory
	 * @var \Saffron\Bannerslider\Model\BannerFactory
	 */
	protected $_bannerFactory;

	protected $_scopeConfig;

	/**
	 * [__construct description]
	 * @param \Magento\Framework\View\Element\Template\Context                $context                 [description]
	 * @param \Saffron\Bannerslider\Model\BannerFactory                     $bannerFactory           [description]
	 * @param \Magento\Framework\Registry                                     $coreRegistry            [description]
	 * @param \Saffron\Bannerslider\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory [description]
	 * @param array                                                           $data                    [description]
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Saffron\Bannerslider\Model\BannerFactory $bannerFactory,
		\Saffron\Bannerslider\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory,
		array $data = []
	) {
		parent::__construct($context, $data);
		$this->_bannerFactory = $bannerFactory;
		$this->_bannerCollectionFactory = $bannerCollectionFactory;
		$this->_scopeConfig = $context->getScopeConfig();
	}
	
	/**
	 * @return
	 */
	public function getBannerSlider() {
		$CurentstoreId = $this->_storeManager->getStore()->getId();
		$sliderCollection = $this->_bannerFactory
			->create()
			->getCollection()
			->addFieldToFilter('status', 1)
			->addFieldToFilter('store_id', array('or'=> array(
				0 => array('eq', '0'),
				1 => array('like' => '%'.$CurentstoreId.'%')
				)));
		$sliderCollection->setOrderByBanner();
		return $sliderCollection;
	}
	
	public function getEnable()
	{
		return $this->_scopeConfig->getValue('bannerslider/general/enable_frontend', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	
	public function getIdStore()
	{
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @return
	 */
	public function getMediaFolder() {
		$media_folder = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return $media_folder;
	}

	/**
	 * @return
	 */
	protected function _toHtml() {
		$store = $this->_storeManager->getStore()->getId();

		if ($this->_scopeConfig->getValue('bannerslider/general/enable_frontend', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store)) {
			return parent::_toHtml();
		}

		return '';
	}

	/**
	 * Add elements in layout
	 *
	 * @return
	 */
	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
}

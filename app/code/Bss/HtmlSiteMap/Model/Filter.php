<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    BSS_HtmlSiteMap
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\HtmlSiteMap\Model;

class Filter extends \Magento\Cms\Model\Template\Filter
{
    /**
     * @var \Magento\Widget\Model\ResourceModel\Widget
     */
    public $widgetResource;

    /**
     * @var \Magento\Widget\Model\Widget
     */
    public $widget;

    /**
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Variable\Model\VariableFactory $coreVariableFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\UrlInterface $urlModel
     * @param \Pelago\Emogrifier $emogrifier
     * @param \Magento\Email\Model\Source\Variables $configVariables
     * @param \Magento\Widget\Model\ResourceModel\Widget $widgetResource
     * @param \Magento\Widget\Model\Widget $widget
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Stdlib\StringUtils $string,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Variable\Model\VariableFactory $coreVariableFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\UrlInterface $urlModel,
        \Pelago\Emogrifier $emogrifier,
        \Magento\Email\Model\Source\Variables $configVariables,
        \Magento\Widget\Model\ResourceModel\Widget $widgetResource,
        \Magento\Widget\Model\Widget $widget
    ) {
        $this->widgetResource = $widgetResource;
        $this->widget = $widget;
        parent::__construct(
            $string,
            $logger,
            $escaper,
            $assetRepo,
            $scopeConfig,
            $coreVariableFactory,
            $storeManager,
            $layout,
            $layoutFactory,
            $appState,
            $urlModel,
            $emogrifier,
            $configVariables
        );
    }

    /**
     * General method for generate widget
     *
     * @param string[] $construction
     * @return string
     */
    public function generateWidget($construction)
    {
        $params = $this->getParameters($construction[2]);

        // Determine what name block should have in layout
        $name = null;
        if (isset($params['name'])) {
            $name = $params['name'];
        }

        // validate required parameter type or id
        if (!empty($params['type'])) {
            $type = $params['type'];
        } elseif (!empty($params['id'])) {
            $preConfigured = $this->widgetResource->loadPreconfiguredWidget($params['id']);
            $type = $preConfigured['widget_type'];
            $params = $preConfigured['parameters'];
        } else {
            return '';
        }

        // we have no other way to avoid fatal errors for type like 'cms/widget__link', '_cms/widget_link' etc.
        $xml = $this->widget->getWidgetByClassType($type);
        if ($xml === null) {
            return '';
        }

        // define widget block and check the type is instance of Widget Interface
        $widget = $this->_layout->createBlock($type, $name, ['data' => $params]);
        if (!$widget instanceof \Magento\Widget\Block\BlockInterface) {
            return '';
        }

        return $widget->toHtml();
    }

    /**
     * Generate widget
     *
     * @param string[] $construction
     * @return string
     */
    public function widgetDirective($construction)
    {
        return $this->generateWidget($construction);
    }

    /**
     * @param string[] $construction
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function mediaDirective($construction)
    {
        $params = $this->getParameters($construction[2]);
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $params['url'];
    }
}

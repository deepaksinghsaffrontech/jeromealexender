<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Magento\CheckoutAgreements\Model\AgreementsProvider;
use Magento\Store\Model\ScopeInterface;
use Amasty\Checkout\Model\Field;

class Config extends \Amasty\Base\Model\ConfigProviderAbstract
{
    /**
     * xpath prefix of module (section)
     */
    protected $pathPrefix = 'amasty_checkout/';

    /**#@+
     * xpath group parts
     */
    const GENERAL_BLOCK = 'general/';
    const GEOLOCATION_BLOCK = 'geolocation/';
    const DEFAULT_VALUES = 'default_values/';
    const GROUP_BLOCK = 'block_names/';
    const DESIGN_BLOCK = 'design/';
    const PLACE_BUTTON_DESIGN_BLOCK = self::DESIGN_BLOCK . 'place_button/';
    const ADDITIONAL_OPTIONS = 'additional_options/';
    const GIFTS = 'gifts/';
    const DELIVERY_DATE_BLOCK = 'delivery_date/';
    const CUSTOM_BLOCK = 'custom_blocks/';
    /**#@-*/

    /**#@+
     * xpath field parts
     */
    const FIELD_ENABLED = 'enabled';
    const FIELD_EDIT_OPTIONS = 'allow_edit_options';
    const FIELD_RELOAD_SHIPPING = 'reload_shipping';
    const GIFT_WRAP = 'gift_wrap';
    const GIFT_WRAP_FEE = 'gift_wrap_fee';
    /**#@-*/

    const VALUE_ORDER_TOTALS = 'order_totals';

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Amasty\Base\Model\Serializer $serializer
    ) {
        parent::__construct($scopeConfig);
        $this->serializer = $serializer;
    }


    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isSetFlag(self::GENERAL_BLOCK . self::FIELD_ENABLED);
    }

    /**
     * @param string $position
     *
     * @return string|int
     */
    public function getCustomBlockIdByPosition($position)
    {
        return $this->getValue(self::CUSTOM_BLOCK . $position . '_block_id');
    }

    /**
     * @return string
     */
    public function getLayoutTemplate()
    {
        return $this->getValue(self::DESIGN_BLOCK . 'layout');
    }

    /**
     * @return string
     */
    public function isGeolocationEnabled()
    {
        return $this->getValue(self::GEOLOCATION_BLOCK . 'ip_detection');
    }

    /**
     * @return string
     */
    public function getPlaceOrderPosition()
    {
        return $this->getValue(self::PLACE_BUTTON_DESIGN_BLOCK . 'layout');
    }

    /**
     * @return mixed
     */
    public function getPlaceDisplayTermsAndConditions()
    {
        return $this->getAdditionalOptions('display_agreements');
    }

    /**
     * @param string $field
     * @param null $storeId
     * @param string $scope
     *
     * @return string
     */
    public function getBlockInfo($field, $storeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->getValue(self::GROUP_BLOCK . $field, $storeId, $scope);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getDeliveryDateConfig($field)
    {
        return $this->getValue(self::DELIVERY_DATE_BLOCK . $field);
    }

    /**
     * @param string $field
     *
     * @return mixed
     */
    public function getAdditionalOptions($field)
    {
        return $this->getValue(self::ADDITIONAL_OPTIONS . $field);
    }

    /**
     * @return bool
     */
    public function isSetAgreements()
    {
        return $this->scopeConfig->isSetFlag(AgreementsProvider::PATH_ENABLED);
    }

    /**
     * @return bool
     */
    public function isReloadCheckoutShipping()
    {
        return (bool)$this->isSetFlag(self::GENERAL_BLOCK . self::FIELD_RELOAD_SHIPPING);
    }

    /**
     * @return bool
     */
    public function isCheckoutItemsEditable()
    {
        return (bool)$this->isSetFlag(self::GENERAL_BLOCK . self::FIELD_EDIT_OPTIONS);
    }

    /**
     * @return bool
     */
    public function isGiftWrapEnabled()
    {
        return $this->isSetFlag(self::GIFTS . self::GIFT_WRAP);
    }

    /**
     * @return int|float
     */
    public function getGiftWrapFee()
    {
        return $this->getValue(self::GIFTS . self::GIFT_WRAP_FEE);
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        return $this->getValue('default_values');
    }

    /**
     * @return string
     */
    public function getDefaultShippingMethod()
    {
        return $this->getValue(self::DEFAULT_VALUES .'shipping_method');
    }

    /**
     * @return string
     */
    public function getDefaultPaymentMethod()
    {
        return $this->getValue(self::DEFAULT_VALUES .'payment_method');
    }

    /**
     * @return bool
     */
    public function canShowDob()
    {
        return $this->scopeConfig->getValue(Field::XML_PATH_CONFIG . 'dob_show', ScopeInterface::SCOPE_STORE)
            === Field::MAGENTO_REQUIRE_CONFIG_VALUE;
    }

    /**
     * @return bool
     */
    public function isAddressSuggestionEnabled()
    {
        return $this->isSetFlag(self::GEOLOCATION_BLOCK . 'google_address_suggestion');
    }

    /**
     * @return string
     */
    public function getBlocksGroupName()
    {
        return $this->pathPrefix . self::GROUP_BLOCK;
    }
}

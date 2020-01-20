<?php

namespace Saffron\Book\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {

	
	const CONFIG_CUSTOM_IS_ENABLED_BOOKDISCOUNT = 'bookdiscount_customfee/bookdiscount_customfee/bookdiscount_status';
	const CONFIG_CUSTOM_FEE_BOOKDISCOUNT = 'bookdiscount_customfee/bookdiscount_customfee/bookdiscount_customfeeamount';
	const CONFIG_FEE_LABEL_BOOKDISCOUNT = 'bookdiscount_customfee/bookdiscount_customfee/bookdiscount_name';
	const CONFIG_MINIMUM_ORDER_AMOUNT_BOOKDISCOUNT = 'bookdiscount_customfee/bookdiscount_customfee/bookdiscount_minimumorderamount';

	public function isModuleEnabledBookdiscount() {
		$storeScope = \Magento\Store\Model\ScopeInterface :: SCOPE_STORE;
		$isEnabled = $this -> scopeConfig -> getValue(self :: CONFIG_CUSTOM_IS_ENABLED_BOOKDISCOUNT, $storeScope);
		return $isEnabled;
	}

	public function getCustomFeeBookdiscount() {
		$storeScope = \Magento\Store\Model\ScopeInterface :: SCOPE_STORE;
		$fee = $this -> scopeConfig -> getValue(self :: CONFIG_CUSTOM_FEE_BOOKDISCOUNT, $storeScope);
		return $fee;
	}

	public function getFeeLabelBookdiscount() {
		$storeScope = \Magento\Store\Model\ScopeInterface :: SCOPE_STORE;
		$feeLabel = $this -> scopeConfig -> getValue(self :: CONFIG_FEE_LABEL_BOOKDISCOUNT, $storeScope);
		return $feeLabel;
	}

	public function getMinimumOrderAmountBookdiscount() {
		$storeScope = \Magento\Store\Model\ScopeInterface :: SCOPE_STORE;
		$MinimumOrderAmount = $this -> scopeConfig -> getValue(self :: CONFIG_MINIMUM_ORDER_AMOUNT_BOOKDISCOUNT, $storeScope);
		return $MinimumOrderAmount;
	}
	

}

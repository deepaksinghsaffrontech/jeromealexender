<?php

namespace Saffron\Book\Model\Quote\Total;

use Magento\Store\Model\ScopeInterface;

class Bookdiscount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal {

	protected $helperData;

	protected $quoteValidator = null;

	public function __construct(\Magento\Quote\Model\QuoteValidator $quoteValidator,
		\Saffron\Book\Helper\Data $helperData) {
		$this -> quoteValidator = $quoteValidator;
		$this -> helperData = $helperData;
	}

	public function collect(\Magento\Quote\Model\Quote $quote,
		\Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
		\Magento\Quote\Model\Quote\Address\Total $total
		) {
		parent :: collect($quote, $shippingAssignment, $total);
		if (!count($shippingAssignment -> getItems())) {
			return $this;
		}

		$enabled = $this -> helperData -> isModuleEnabledBookdiscount();
		$minimumOrderAmount = $this -> helperData -> getMinimumOrderAmountBookdiscount();
		$subtotal = $total -> getTotalAmount('subtotal');
		if ($enabled && $minimumOrderAmount <= $subtotal) {
			// $bookdiscount = $quote->getbookdiscount();
		    $bookdiscount=  -12 ; //$this->helperData->getCustomFeeBookdiscount();
			// Try to test with sample value
			//$bookdiscount = 12;
			$total -> setTotalAmount('bookdiscount', $bookdiscount);
			$total -> setBaseTotalAmount('bookdiscount', $bookdiscount);
			$total -> setBookdiscount($bookdiscount);
			$total -> setBaseBookdiscount($bookdiscount);
			$quote -> setBookdiscount($bookdiscount);
			$quote -> setBaseBookdiscount($bookdiscount);
			$total -> setGrandTotal($total -> getGrandTotal() + $bookdiscount);
			$total -> setBaseGrandTotal($total -> getBaseGrandTotal() + $bookdiscount);
		}
		return $this;
	}

	public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total) {
		$enabled = $this -> helperData -> isModuleEnabledBookdiscount();
		$minimumOrderAmount = $this -> helperData -> getMinimumOrderAmountBookdiscount();
		$subtotal = $quote -> getSubtotal();
		$bookdiscount = $quote -> getBookdiscount();

		$result = [];
		if ($enabled && ($minimumOrderAmount <= $subtotal) && $bookdiscount) {
			$result = [
			'code' => 'bookdiscount',
			'title' => $this -> helperData -> getFeeLabelBookdiscount(),
			'value' => $bookdiscount
			];
		}
		return $result;
	}

	public function getLabel() {
		return __('Book Discount');
	}

	protected function clearValues(\Magento\Quote\Model\Quote\Address\Total $total) {
		$total -> setTotalAmount('subtotal', 0);
		$total -> setBaseTotalAmount('subtotal', 0);
		$total -> setTotalAmount('tax', 0);
		$total -> setBaseTotalAmount('tax', 0);
		$total -> setTotalAmount('discount_tax_compensation', 0);
		$total -> setBaseTotalAmount('discount_tax_compensation', 0);
		$total -> setTotalAmount('shipping_discount_tax_compensation', 0);
		$total -> setBaseTotalAmount('shipping_discount_tax_compensation', 0);
		$total -> setSubtotalInclTax(0);
		$total -> setBaseSubtotalInclTax(0);
	}
}  
<?php

namespace Saffron\Book\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Bookdiscount extends AbstractTotal {

	public function collect(\Magento\Sales\Model\Order\Invoice $invoice) {

		$order = $invoice -> getOrder();

		$percent = $invoice -> getSubtotal() / $order -> getSubtotal();

		$invoice -> setBookdiscount(0);
		$invoice -> setBaseBookdiscount(0);

		$amount = $invoice -> getOrder() -> getBookdiscount() * $percent;

		$baseAmount = $invoice -> getOrder() -> getBaseBookdiscount() * $percent;

		$invoice -> setBookdiscount($amount);

		$invoice -> setBaseBookdiscount($baseAmount);

		$invoice -> setGrandTotal($invoice -> getGrandTotal() + $amount);
		$invoice -> setBaseGrandTotal($invoice -> getBaseGrandTotal() + $invoice -> getBaseBookdiscount() * $baseAmount);

		return $this;
	}
} 
<?php

namespace Saffron\Book\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

class Bookdiscount extends AbstractTotal {

	public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo) {

		$order = $creditmemo -> getOrder();

		$percent = $creditmemo -> getSubtotal() / $order -> getSubtotal();

		$creditmemo -> setBookdiscount(0);
		$creditmemo -> setBaseBookdiscount(0);

		$amount = $creditmemo -> getOrder() -> getBookdiscount() * $percent;
		$baseAmount = $creditmemo -> getOrder() -> getBaseBookdiscount() * $percent;

		$creditmemo -> setBookdiscount($amount);

		$creditmemo -> setBaseBookdiscount($baseAmount);

		$creditmemo -> setGrandTotal($creditmemo -> getGrandTotal() + $amount);
		$creditmemo -> setBaseGrandTotal($creditmemo -> getBaseGrandTotal() + $baseAmount);

		return $this;
	}
} 
<?php

namespace Saffron\Book\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddFeeToOrderObserver implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $quote = $observer->getQuote();
		$order = $observer->getOrder();

		
        $CustomFeeBookdiscount = $quote->getBookdiscount();
        $CustomFeeBaseBookdiscount = $quote->getBaseBookdiscount();

        if ($CustomFeeBookdiscount&&$CustomFeeBaseBookdiscount) {

			$order->setData('bookdiscount', $CustomFeeBookdiscount);
			$order->setData('base_bookdiscount', $CustomFeeBaseBookdiscount);

        }


        return $this;

    }
}

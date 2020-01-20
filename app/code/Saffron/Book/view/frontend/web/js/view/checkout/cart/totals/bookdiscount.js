define(
    [
		'ko',
        'Saffron_Book/js/view/checkout/summary/bookdiscount',
		'Magento_Checkout/js/model/quote',
		'Magento_Catalog/js/price-utils',
		'Magento_Checkout/js/model/totals'
    ],
    function (ko, Component,quote,priceUtils, totals) {
        'use strict';

		var custom_fee_amount = 0;

		if (totals.getSegment('bookdiscount'))
		{
			custom_fee_amount=totals.getSegment('bookdiscount').value
		}

		var bookdiscount_label = window.checkoutConfig.bookdiscount_label;

        return Component.extend({

			getFormattedPrice: ko.observable(priceUtils.formatPrice(custom_fee_amount, quote.getPriceFormat())),
			getFeeLabelBookdiscount:ko.observable(bookdiscount_label),
            isDisplayed: function () {
                return this.isFullMode() && this.getPureValue() != 0;
            }
        });
    }
);
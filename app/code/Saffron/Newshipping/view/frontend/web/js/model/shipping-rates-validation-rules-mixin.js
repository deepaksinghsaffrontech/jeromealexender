define(['jquery'], function ($) {
    'use strict';

    return function (targetFunction) {
        targetFunction.getObservableFields = function () {
            var self = this,
                observableFields = [];

            $.each(self.getRules(), function (carrier, fields) {
                $.each(fields, function (field) {
                    if (observableFields.indexOf(field) === -1) {
                        observableFields.push(field);
                    }
                });
            });

            observableFields.push('country_id'); 
            observableFields.push('region_id'); 
         


            return observableFields;
        }

        return targetFunction;
    };
});
jQuery(function($) {
    var initialized = false;
    var customVariableActions = [
        'wcpbc_variable_regular_price',
        'wcpbc_variable_regular_price_increase',
        'wcpbc_variable_regular_price_decrease',
        'wcpbc_variable_sale_price',
        'wcpbc_variable_sale_price_increase',
        'wcpbc_variable_sale_price_decrease'
    ];

    $('.wc-metaboxes-wrapper').on(customVariableActions.join(' '), 'select.variation_actions', init);
    
    function init() {
        if (initialized) {
            return;
        }

        var ajaxDataEventSuffix = '_ajax_data';
        var customVariableActionEvents = customVariableActions.map(function(a) { return a + ajaxDataEventSuffix; }).join(' ');
        $('select.variation_actions').on(customVariableActionEvents,
        function(e, data) {
            var customVariableAction = e.type.substring(0, e.type.length - ajaxDataEventSuffix.length)
            switch (customVariableAction) {
                case 'wcpbc_variable_regular_price_increase':
                case 'wcpbc_variable_regular_price_decrease':
                case 'wcpbc_variable_sale_price_increase':
                case 'wcpbc_variable_sale_price_decrease':
                    value = window.prompt(woocommerce_admin_meta_boxes_variations.i18n_enter_a_value_fixed_or_percent);
                    if ( value != null ) {
                        if ( value.indexOf( '%' ) >= 0 ) {
                            data.value = accounting.unformat( value.replace( /\%/, '' ), woocommerce_admin.mon_decimal_point ) + '%';
                        } else {
                            data.value = accounting.unformat( value, woocommerce_admin.mon_decimal_point );
                        }
                    }

                    break;
                case 'wcpbc_variable_regular_price':
                case 'wcpbc_variable_sale_price':
                    value = window.prompt(woocommerce_admin_meta_boxes_variations.i18n_enter_a_value);
                    if ( value != null ) {
                        data.value = value;
                    }

                    break;
            }

            data['zone_id'] = $(this.options[this.selectedIndex]).data('zoneId');

            return data;
        });

        initialized = true;
    }
});
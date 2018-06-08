=== Currency Converter For WooCommerce PayPal Standard ===
Contributors: darioursulin
Donate link: 
Tags: paypal, custom, currency, convert
Requires at least: 4.4
Tested up to: 4.9.1
Requires PHP: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin allows you to convert the currency used in your store into any of the ones supported by PayPal (on checkout). You can also add your own custom currency.

== Description ==

The plugin allows you to convert the currency used in your store into any of the ones supported by PayPal (on checkout). You can also add your own custom currency.

You will have multiple options to choose from. You can:

* Set which currency is passed to PayPal. All currencies supported by PayPal are available.

* Choose which currency exchange rate service you want to use from multiple available options.

* Manually add your own exchange rate.

* Add your own custom currency to WooCommerce

WARNING: This plugin makes API calls to external sources. That means it sends requests to outside services which then provide data to be used to convert the prices. Services are as follows: 

* api.fixer.io - [Website](http://fixer.io/) (this is also the default option)

* free.currencyconverterapi.com - [Website](https://free.currencyconverterapi.com/)

* www.apilayer.net - [Website](https://currencylayer.com/terms)

* globalcurrencies.xignite.com - [Website](https://www.xignite.com/Policies/SiteUseTerms.aspx)

== Installation ==

1. Extract `currency-converter-for-woocommerce-paypal-standard` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

...and you're done.

BASIC USAGE:

The plugin uses external services in order to get the exchange rates in real-time. You have multiple options to choose from. Additional services may be added in the future.

After installation some exchange rate services may require an access key in order to function. Those services are marked with a '*' in the options page. After obtaining the key from the service, paste it inside the plugin options.

ADDITIONAL OPTIONS:

You have the option of adding your own exchange rate instead of using external services. If set, that manual currency exchange rate will be used.

You can add your own currency to be used in the store. In that case you will need to set to that currency in WooCommerce general settings under 'Currency Options'.

== Frequently asked questions ==

Installation instructions

1. Extract `currency-converter-for-woocommerce-paypal-standard` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

...and you're done.

BASIC USAGE:

The plugin uses external services in order to get the exchange rates in real-time. You have multiple options to choose from. Additional services may be added in the future.

After installation some exchange rate services may require an access key in order to function. Those services are marked with a '*' in the options page. After obtaining the key from the service, paste it inside the plugin options.

ADDITIONAL OPTIONS:

You have the option of adding your own exchange rate instead of using external services. If set, that manual currency exchange rate will be used.

You can add your own currency to be used in the store. In that case you will need to set to that currency in WooCommerce general settings under 'Currency Options'.
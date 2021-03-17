# Siigo Plugin integration for wordpress and Woocommerce

This plugin connects with the Envioclick REST API to automate the shipping of the products of your woocommerce store.

* Automatic creation of quote after order creation
* Once the order is mark as completed the system creates a shippment request on Envioclick automatically
* The user receives an automatic email with the link to track the shipping of his order on Envioclick
* You can select which type of shipping quote seleciton to use if the fastest or the cheapest

## Todo - Future features

* Hability to select if there's free shipping or not
* Quote the shipping on checkout if free shipping option is disabled
* Conditional free shipping using shipping zones of wordpress
* List of orders and tracking info from the user account in woocommerce
* Avoid Envioclick quotation on digital products
* Make order status trigger customizable

## Development

If you would like to contribute the only requisite is to have composer installed, we added vender folder to the github repo so it is not necessary that you set up composer on your end, but you need to install it in your machine, also if you want to update autoload you just need to run

`composer dumpautoload -o`

Follow [Envioclick API docs](http://api.envioclickpro.com.co) for more information about the API endpoints and calls.
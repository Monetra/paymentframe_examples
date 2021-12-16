# PaymentFrame PHP Examples

This is a miniature PHP web app that demonstrates usage of the Monetra PaymentFrame for accepting ecommerce payments. It contains three example "storefronts" that utilize different PaymentFrame configurations for different checkout experiences.

This repo contains an `.htaccess` file that routes all requests (other than static file requests) through `app.php`, which then handles the request based on the request method and URI. This will only work if you are using an Apache web server with an AllowOverride directive that allows the use of `.htaccess` files. Otherwise, you will need to configure your server to perform this rewrite functionality.

The request handling and helper functions in `app.php` demonstrate how to specify options for the PaymentFrame, generate an HMAC containing those options, render a web page containing a payment form within a secure iframe, and process a payment using the ticket generated from the payment form. These steps are described in detail in our [documentation](https://www.monetra.com/docs/developer/monetra_paymentframe-v1.7.pdf).
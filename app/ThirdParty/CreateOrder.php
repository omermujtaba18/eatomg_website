<?php

namespace App\ThirdParty;

//1. Import the PayPal SDK client that was created in `Set up Server-Side SDK`.
use App\ThirdParty\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class CreateOrder
{

    // 2. Set up your server to receive a call from the client
    /**
     *This is the sample function to create an order. It uses the
     *JSON body returned by buildRequestBody() to create an order.
     */
    public static function createOrder($debug = false)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = self::buildRequestBody();
        // 3. Call PayPal to set up a transaction
        $client = PayPalClient::client();
        $response = $client->execute($request);

        // 4. Return a successful response to the client.
        return $response;
    }

    /**
     * Setting up the JSON request body for creating the order with minimum request body. The intent in the
     * request body should be "AUTHORIZE" for authorize intent flow.
     *
     */
    private static function buildRequestBody()
    {
        return array(
            'intent' => 'CAPTURE',
            'application_context' =>
            array(
                'return_url' => 'http://localhost:8080/checkout/return',
                'cancel_url' => 'http://localhost:8080/checkout/return'
            ),
            'purchase_units' =>
            array(
                0 =>
                array(
                    'amount' =>
                    array(
                        'currency_code' => 'USD',
                        'value' => '220.00'
                    )
                )
            )
        );
    }
}


/**
 *This is the driver function that invokes the createOrder function to create
 *a sample order.
 */
if (!count(debug_backtrace())) {
    CreateOrder::createOrder(true);
}

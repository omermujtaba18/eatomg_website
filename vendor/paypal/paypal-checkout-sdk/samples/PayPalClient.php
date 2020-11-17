<?php

namespace Sample;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public static function environment()
    {
        $clientId = getenv("CLIENT_ID") ?: "ATE4yA5JUr6WkY0Q84F_naz0Q5JK0YOZgsO_B6cchIvoXmHNW3MKjc8TXeJ2kjVzmtdeZDJP1E391oAS";
        $clientSecret = getenv("CLIENT_SECRET") ?: "EEL5i4d2tA9MS9qi0Xz6Xo8F3YyeKnzTW6qDjFgxSp9QbJduqjwfH6WBkOesHMEzCt0JO4sCwJ7HOYoz";
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}

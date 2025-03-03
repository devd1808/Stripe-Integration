<?php
require_once 'vendor/autoload.php';

$stripe = new \Stripe\StripeClient('sk_test_51QxXWG2Ln4kaPfjVuqIWvNlY0Zq3HeDNl0yYz83f1tVQcoTWt7CXrBQGiPCVVyQNajP0JLi0NWWVIXsGHWX3dJHz00Lulfb36g');
// charge Customer
$charge = $stripe->charges->create([
    'amount' => 100000,
    'currency' => 'usd',
    'customer' => $customerid,
    'description' => "This is the test charge"
]);

?>



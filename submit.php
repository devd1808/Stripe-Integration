<?php
require_once 'vendor/autoload.php';

// Database connection
$servername = "103.174.102.89";
$username = "software_read_write";
$password = "software_read_write@2025";
$database = "software";


$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve query parameters
$userid = $_GET['user_id'] ?? null;
$token = $_POST['stripeToken'] ?? null;
$email = $_POST['email'] ?? "test@gmail.com";
$amount = $_GET['amount'] ?? 50; // Default amount is 100000 (modify as needed)

// Validate inputs
// if (!$userid || !$token ) {
//     die("Missing required parameters.");
// }
// Create Stripe client
$stripe = new \Stripe\StripeClient('sk_test_51QxXWG2Ln4kaPfjVuqIWvNlY0Zq3HeDNl0yYz83f1tVQcoTWt7CXrBQGiPCVVyQNajP0JLi0NWWVIXsGHWX3dJHz00Lulfb36g');

try {
    // Check if user exists in database
    $stmt = $conn->prepare("SELECT stripe_customer_id FROM customers WHERE user_id = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->bind_result($customerid);
    $stmt->fetch();
    $stmt->close();

    // If customer does not exist, redirect to the payment form
    if (!$customerid) {
        header("Location: index.php?user_id=$userid&amount=$amount");
        exit();
    }

    // Charge the customer
    $charge = $stripe->charges->create([
        'amount' => $amount,
        'currency' => 'usd',
        'customer' => $customerid,
        'description' => "Charge for User ID " . $userid
    ]);

    echo json_encode(["status" => "success", "message" => "Charge successful", "charge_id" => $charge->id]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

// Close database connection
$conn->close();
?>


<!-- http://yourdomain.com/charge.php?userid=123&amount=50000 -->

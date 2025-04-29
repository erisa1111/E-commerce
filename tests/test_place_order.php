<?php
namespace OrderTest;
// Set the TEST_ENV to simulate a test environment
$_SERVER['TEST_ENV'] = 'true'; // This will bypass session_start()



// Start output buffering
ob_start();
session_start(); // Start session for testing

// Simulate session data (user logged in)
$_SESSION['user_id'] = 1;

// Simulate POST data
$_POST['product_ids'] = [1]; // Ensure this product ID exists in DB
$_POST['quantities'] = [2];
$_POST['delivery_address'] = 'Test Address';

// Simulate request method
$_SERVER['REQUEST_METHOD'] = 'POST';

// === Custom header() function in the "OrderTest" namespace ===
function header($string, $replace = true, $http_response_code = null) {
    echo "Header intercepted: $string\n";
    file_put_contents(__DIR__ . '/header_log.txt', $string);
}

// Include your place_order.php script here
require_once __DIR__ . '/../place_order.php';

// Clean output buffer
ob_end_clean();
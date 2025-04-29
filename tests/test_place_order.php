<?php
// Start output buffering
ob_start();

// Ensure session_start() is at the top of the file, no output before it
session_start();

// Simulate session data
$_SESSION['user_id'] = 1; // Simulating a logged-in user

// Simulate POST data (product IDs, quantities, and delivery address)
$_POST['product_ids'] = [1];
$_POST['quantities'] = [2];
$_POST['delivery_address'] = 'Test Address';

// Simulate POST request method
$_SERVER['REQUEST_METHOD'] = 'POST';

// First verify the file exists
$filePath = __DIR__ . '/../place_order.php';
if (!file_exists($filePath)) {
    die("❌ File not found: place_order.php\n");
}

// Include the place_order.php script
require_once $filePath;

// Get the output from the script
$output = ob_get_clean();

// Check for success confirmation (based on redirection to order_success.php)
if (strpos($output, 'order_success.php') !== false) {
    echo "✅ Test passed: Order placed successfully.\n";
} else {
    echo "❌ Test failed: No success confirmation for order.\n";
    echo "First 300 chars of output:\n";
    echo substr($output, 0, 300) . (strlen($output) > 300 ? "..." : "") . "\n";
}

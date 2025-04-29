<?php
// Start output buffering to prevent headers sent issues
ob_start();

// Start session FIRST before any output
session_start();

// Then include your test message
echo "🔍 Running test: cart.php...\n";

// Simulate cart contents
$_SESSION['cart'] = [
    1 => [
        'id' => 1,
        'name' => 'Product 1',
        'price' => 24.99,
        'quantity' => 2,
        'image_path' => 'images/product1.jpg'
    ]
];

// Include the cart.php file
require_once __DIR__ . '/../cart.php';

// Get the output
$output = ob_get_clean();

// Check for product in output
if (strpos($output, 'Product 1') !== false) {
    echo "✅ Test passed: Product found in cart\n";
} else {
    echo "❌ Test failed: Product not found in cart\n";
    echo "First 200 chars of output:\n";
    echo substr($output, 0, 200) . "...\n";
}
?>
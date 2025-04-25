<?php
session_start();

// Simulate a request to the cart page after adding products
echo "🔍 Running test: cart.php...\n";

// Simulate adding products to the cart (this should match your add_to_cart.php functionality)
$productId = 1; // Example product ID
$quantity = 2;  // Example quantity

// Start a session to simulate the cart


// Add a product to the cart (this simulates a real POST request)
$_SESSION['cart'][$productId] = [
    'id' => $productId,
    'name' => 'Product 1',
    'price' => 24.99,
    'quantity' => $quantity,
    'image_path' => 'get_image.php?id=' . $productId
];

// Simulate another product in the cart
$productId2 = 2;
$_SESSION['cart'][$productId2] = [
    'id' => $productId2,
    'name' => 'Product 2',
    'price' => 14.99,
    'quantity' => 1,
    'image_path' => 'get_image.php?id=' . $productId2
];

// Simulate a request to the cart page
$url = "http://localhost/E-commerce/cart.php";
$response = @file_get_contents($url);

// Check if the page loads
if ($response === false) {
    echo "❌ Failed to load the page. Make sure your server is running.\n";
    exit;
}

// Check for expected content (product name, price, and cart elements)
if (strpos($response, 'Product 1') !== false && strpos($response, 'Product 2') !== false) {
    echo "✅ Test passed: Products found in the cart.\n";
} else {
    echo "❌ Test failed: Product(s) not found in the cart.\n";
}

if (strpos($response, 'Subtotal') !== false) {
    echo "✅ Test passed: Subtotal section found.\n";
} else {
    echo "❌ Test failed: Subtotal section not found.\n";
}

if (strpos($response, 'Proceed to Order') !== false) {
    echo "✅ Test passed: 'Proceed to Order' button found.\n";
} else {
    echo "❌ Test failed: 'Proceed to Order' button not found.\n";
}

// Check if "Your cart is empty" message is displayed when the cart is empty
$_SESSION['cart'] = []; // Empty the cart for this test

$response_empty = @file_get_contents($url);
if (strpos($response_empty, 'Your cart is empty.') !== false) {
    echo "✅ Test passed: 'Your cart is empty.' message displayed.\n";
} else {
    echo "❌ Test failed: 'Your cart is empty.' message not displayed.\n";
}

?>
<?php
session_start();

echo "🔍 Running test: update_order_status.php...\n";

// Simulate admin login
$_SESSION['user_role'] = 'admin';

// Prepare POST data
$postData = http_build_query([
    'order_id' => 1,             // Assume order with ID 1 exists
    'order_status' => 'Shipped'   // New status to update
]);

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, "http://localhost/E-commerce/update_order_status.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

// Execute cURL request and get response
$response = curl_exec($ch);

// Check for cURL errors
if(curl_errno($ch)) {
    echo "❌ cURL error: " . curl_error($ch) . "\n";
} else {
    echo "✅ Test passed: Order status updated successfully.\n";
}

// Close cURL resource
curl_close($ch);
?>

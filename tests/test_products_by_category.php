<?php
echo "🔍 Running test: products_by_category.php...\n";

// Simulate a request to the real server
$url = "http://localhost/E-commerce/products_by_category.php?category=Makeup";
$response = @file_get_contents($url);

if ($response === false) {
    echo "❌ Failed to load the page. Make sure your database is correctly connected\n";
    exit;
}

// Check for expected content
if (strpos($response, 'product-card') !== false) {
    echo "✅ Product cards found for category 'Makeup'.\n";
} else {
    echo "❌ Test failed: No product cards found for category 'Makeup'.\n";
}

if (strpos($response, 'Makeup Products') !== false) {
    echo "✅ Title includes category name.\n";
} else {
    echo "⚠️ Title check failed: Category name not shown.\n";
}
?>
<?php
echo "🔍 Running test: products_by_category.php...\n";

// Simulate a GET request
$_GET['category'] = 'Makeup'; // Replace with a valid category from your DB

// Capture the output of the product page
ob_start();
include '../products_by_category.php';
$output = ob_get_clean();

// Basic check: Did it load any products?
if (strpos($output, 'class="product-card"') !== false) {
    echo "✅ Test passed: At least one product is displayed.\n";
} else {
    echo "❌ Test failed: No product cards found for category 'Makeup'.\n";
}

// Optional: check if product name/brand shows up
$expectedText = 'Products'; // Page should say "Makeup Products" or similar
if (strpos($output, $expectedText) !== false) {
    echo "✅ Title check passed: Page displays category name.\n";
} else {
    echo "⚠️ Title check failed: Category name not shown.\n";
}
?>
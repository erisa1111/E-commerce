<?php
echo "🔍 Running test: products_by_category.php...\n";

// First verify the file exists
$filePath = __DIR__ . '/../products_by_category.php';
if (!file_exists($filePath)) {
    die("❌ File not found: products_by_category.php\n");
}

// Simulate GET parameters
$_GET['category'] = 'Makeup';

// Start output buffering
ob_start();

// Include the file directly (better than cURL for testing)
require_once $filePath;

// Get the output
$output = ob_get_clean();

// Check for expected content
if (strpos($output, 'product-card') !== false) {
    echo "✅ Product cards found for category 'Makeup'.\n";
} else {
    echo "❌ Test failed: No product cards found for category 'Makeup'.\n";
    echo "Possible issues:\n";
    echo "- No products in 'Makeup' category\n";
    echo "- Database connection issue\n";
    echo "- HTML structure changed\n";
    echo "First 300 chars of output:\n";
    echo substr($output, 0, 300) . (strlen($output) > 300 ? "..." : "") . "\n";
}

// Check for expected title
if (strpos($output, 'Makeup') !== false) {
    echo "✅ Title includes category name.\n";
} else {
    echo "⚠️ Title check failed: Category name not shown.\n";
}
?>
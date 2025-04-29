<?php
// MUST be the very first thing in the file - no output before this!
session_start();

// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize output buffering
ob_start();

echo "=== Starting Product Tests ===\n";

// Initialize test environment
$_SERVER['REQUEST_METHOD'] = 'GET'; // Default to GET
$_SERVER['HTTP_HOST'] = 'localhost';
define('TESTING', true);

// Database connection
echo "Loading database connection...\n";
try {
    require __DIR__ . '/../db_connect.php';
    $pdo->query("SELECT 1")->fetch();
    echo "Database connection successful!\n";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Verify paths
$productsPath = __DIR__ . '/../products.php';
if (!file_exists($productsPath)) {
    die("Error: Cannot find products.php at $productsPath\n");
}

// Simulate admin login
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';

echo "Session initialized. User ID: {$_SESSION['user_id']}, Role: {$_SESSION['user_role']}\n";

// Test 1: Verify admin access control
function test_admin_access() {
    global $pdo;
    
    echo "\n--- Running Test 1: Admin Access Control ---\n";
    
    // Backup session
    $backup = $_SESSION;
    
    // Test non-admin access
    $_SESSION['user_role'] = 'user';
    echo "Testing as non-admin user...\n";
    
    ob_start();
    try {
        require __DIR__ . '/../products.php';
    } catch (Exception $e) {
        echo "Error requiring products.php: " . $e->getMessage() . "\n";
    }
    $output = ob_get_clean();
    echo "Products.php executed. Output length: " . strlen($output) . " bytes\n";
    
    // Restore session
    $_SESSION = $backup;
    
    if (strpos($output, 'login.php') !== false) {
        echo "Test 1 Passed: Admin access control working\n";
    } else {
        echo "Test 1 Failed: Admin access control not working\n";
        echo "Output received: " . ($output ?: "[No output]") . "\n";
        echo "Session after test: ";
        print_r($_SESSION);
    }
}

// Test 2: Test product addition with valid data
function test_valid_product_addition() {
    global $pdo;
    
    echo "\n--- Running Test 2: Valid Product Addition ---\n";
    
    // Create test image
    $testImagePath = __DIR__ . '/test_image.jpg';
    file_put_contents($testImagePath, file_get_contents('https://via.placeholder.com/150'));

    // Simulate POST request
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = [
        'add_product' => true,
        'name' => 'Test Product ' . uniqid(),
        'description' => 'Test Description',
        'price' => 19.99,
        'stock' => 100,
        'brand' => 'Test Brand',
        'category' => 1,
        'subcategory' => 1
    ];
    
    $_FILES = [
        'image' => [
            'name' => 'test_image.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => $testImagePath,
            'error' => UPLOAD_ERR_OK,
            'size' => filesize($testImagePath)
        ]
    ];
    
    ob_start();
    require __DIR__ . '/../products.php';
    $output = ob_get_clean();
    
    // Verify product was added
    $productName = $_POST['name'];
    $stmt = $pdo->prepare("SELECT * FROM product WHERE name = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$productName]);
    $product = $stmt->fetch();
    
    if ($product) {
        echo "Test 2 Passed: Product added successfully (ID: {$product['id']})\n";
        // Clean up
        $pdo->exec("DELETE FROM product WHERE id = " . $product['id']);
        if (file_exists($product['image'])) {
            unlink($product['image']);
        }
    } else {
        echo "Test 2 Failed: Product not added to database\n";
        echo "Output received: " . ($output ?: "[No output]") . "\n";
    }
    
    unlink($testImagePath);
}

// Run tests
test_admin_access();
test_valid_product_addition();

// Final output
$output = ob_get_clean();
echo $output;
echo "\n=== Tests Completed ===\n";
?>
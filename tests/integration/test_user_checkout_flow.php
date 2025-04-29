<?php
// test_user_checkout_flow.php

session_start();

// --- Simulate Database Connection (use your real db_connect.php) ---
require_once 'db_connect.php';

// --- Cart Functions (inside same file) ---
function add_to_cart($productId, $quantity, $pdo) {
    // Fetch product
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM product WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception("Product not found");
    }

    // Initialize cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or update product in cart
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image_path' => 'get_image.php?id=' . $productId
        ];
    }

    // Update cookie
    setcookie(
        'persistent_cart', 
        json_encode($_SESSION['cart']), 
        time() + (30 * 24 * 60 * 60), 
        '/', '', false, true
    );

    return "Added to cart";
}

// --- Checkout Function (inside same file) ---
function checkout($paymentInfo, $pdo) {
    if (empty($_SESSION['cart'])) {
        throw new Exception("Cart is empty, cannot checkout.");
    }

    // Here you could add real payment validation. We'll simulate success.
    $orderTotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $orderTotal += $item['price'] * $item['quantity'];
    }

    // Simulate saving the order (example only)
    /*
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->execute([$userId, $orderTotal]);
    */

    // Clear cart
    $_SESSION['cart'] = [];
    setcookie('persistent_cart', '', time() - 3600, '/');

    return "Checkout successful. Total: $" . number_format($orderTotal, 2);
}

// --- Helper to reset session/cart ---
function reset_session_and_cart() {
    $_SESSION = [];
    setcookie('persistent_cart', '', time() - 3600, '/');
}

// --- TEST START ---
reset_session_and_cart();

// 1. Simulate adding to cart
try {
    $productId = 1;   // Test Product ID
    $quantity = 2;    // Test Quantity
    $response = add_to_cart($productId, $quantity, $pdo);
    echo "[Add to Cart] Response: $response\n";

    if (!isset($_SESSION['cart'][$productId])) {
        throw new Exception("Product not found in session cart after add_to_cart.");
    }
} catch (Exception $e) {
    die("[Add to Cart] Test failed: " . $e->getMessage());
}

// 2. Simulate checkout
try {
    $paymentInfo = [
        'card_number' => '4111111111111111',
        'expiry_date' => '12/25',
        'cvv' => '123'
    ];
    
    $checkoutResponse = checkout($paymentInfo, $pdo);
    echo "[Checkout] Response: $checkoutResponse\n";

    if (!empty($_SESSION['cart'])) {
        throw new Exception("Cart was not cleared after checkout.");
    }
} catch (Exception $e) {
    die("[Checkout] Test failed: " . $e->getMessage());
}

// 3. Test completed
echo "[Test Completed Successfully]\n";

?>

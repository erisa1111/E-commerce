<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    require_once 'db_connect.php';
    
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM product WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
//nese nuk gjehet produkti
    if (!$product) {
        http_response_code(404);
        echo "Product not found";
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

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

    // ==============================================
    // NEW COOKIE CODE (add this part)
    // ==============================================
    setcookie(
        'persistent_cart', 
        json_encode($_SESSION['cart']), 
        time() + (30 * 24 * 60 * 60), // 30 days expiration
        '/',                          // Available across entire site
        '',                           // Domain (current domain)
        false,                        // Secure flag
        true                          // HttpOnly flag
    );
    // ==============================================

    echo "Added to cart";
}
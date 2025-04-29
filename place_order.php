<?php
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';



// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method. Please submit the order form.");
}

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("User not logged in. Please log in to place an order.");
}

// Validate required fields
if (empty($_POST['product_ids']) || empty($_POST['quantities']) || empty($_POST['delivery_address'])) {
    die("Missing required order information. Please try again.");
}

$productIds = $_POST['product_ids'];
$quantities = $_POST['quantities'];
$deliveryAddress = $_POST['delivery_address'];

try {
    $pdo->beginTransaction();

    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_date, total_amount, delivery_address, order_status) VALUES (?, NOW(), ?, ?, 'Pending')");
    $total = 0;

    // Calculate total price
    $products = [];
    foreach (array_combine($productIds, $quantities) as $productId => $quantity) {
        $stmtP = $pdo->prepare("SELECT price FROM product WHERE id = ?");
        $stmtP->execute([$productId]);
        $price = $stmtP->fetchColumn();
        $total += $price * $quantity;
        $products[] = ['id' => $productId, 'price' => $price, 'quantity' => $quantity];
    }

    $stmt->execute([$userId, $total, $deliveryAddress]);
    $orderId = $pdo->lastInsertId();

    // Insert order items
    $stmtItem = $pdo->prepare("INSERT INTO order_item (order_id, product_id, quantity, price_at_time_of_purchase) VALUES (?, ?, ?, ?)");
    foreach ($products as $item) {
        $stmtItem->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
    }

    $pdo->commit();
    unset($_SESSION['cart']); // Clear cart after placing order

    // Redirect to success page
    header("Location: order_success.php?order_id=$orderId");
    exit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Error placing order: " . $e->getMessage());
}

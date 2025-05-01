<?php
session_start();
require 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's order history
try {
    $stmt = $pdo->prepare("
    SELECT o.id AS order_id, o.order_date, o.total_amount, o.order_status,
           oi.id AS item_id, oi.product_id, oi.quantity, oi.price_at_time_of_purchase,
           p.name, p.image
    FROM orders o
    JOIN order_item oi ON o.id = oi.order_id
    JOIN product p ON oi.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
");

    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group items by order
    $groupedOrders = [];
    foreach ($orders as $item) {
        $orderId = $item['order_id'];
        if (!isset($groupedOrders[$orderId])) {
            $groupedOrders[$orderId] = [
                'order_id' => $orderId,
                'order_date' => $item['order_date'],
                'total_amount' => $item['total_amount'],
                'status' => $item['order_status'],
                'items' => []
            ];
        }
        $groupedOrders[$orderId]['items'][] = $item;
    }
} catch (PDOException $e) {
    $error = "Error fetching order history: " . $e->getMessage();
}
?>
 <!--historia se qka ke bo order-->
<!DOCTYPE html>
<html>
<head>
    <title>Your Order History</title>
    <link rel="stylesheet" href="css/order_history.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
</head>
<body>
    <?php include 'navbar/navbar.php'; ?>

    <div class="order-history-container">
        <h1>Your Order History</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif (empty($groupedOrders)): ?>
            <p>You haven't placed any orders yet.</p>
        <?php else: ?>
            <?php foreach ($groupedOrders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h2>Order</h2>
                            <p class="order-date">
                                <?= date('F j, Y \a\t g:i a', strtotime($order['order_date'])) ?>
                            </p>
                        </div>
                        <div class="order-total">
                            <span>Total: $<?= number_format($order['total_amount'], 2) ?></span>
                            <span class="order-status <?= strtolower($order['status']) ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="get_image.php?id=<?= $item['product_id'] ?>" 
                                         alt="<?= htmlspecialchars($item['name']) ?>">
                                </div>
                                <div class="item-details">
                                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                                    <p>Quantity: <?= $item['quantity'] ?></p>
                                    <p>Price: $<?= number_format($item['price_at_time_of_purchase'], 2) ?></p>

                                    <p>Subtotal: $<?= number_format($item['price_at_time_of_purchase'] * $item['quantity'], 2) ?></p>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include 'footer/footer.php'; ?>
</body>
</html>
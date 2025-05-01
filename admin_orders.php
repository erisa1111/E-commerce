<?php
session_start();
require 'db_connect.php';



if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


try {
    $stmt = $pdo->prepare("
        SELECT o.id AS order_id, o.user_id, o.order_date, o.total_amount, o.order_status,
               oi.id AS item_id, oi.product_id, oi.quantity, oi.price_at_time_of_purchase,
               p.name AS product_name, p.image,
               u.name AS user_name, u.email
        FROM orders o
        JOIN order_item oi ON o.id = oi.order_id
        JOIN product p ON oi.product_id = p.id
        JOIN users u ON o.user_id = u.id
        ORDER BY o.order_date DESC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group by order ID
    $groupedOrders = [];
    foreach ($orders as $item) {
        $id = $item['order_id'];
        if (!isset($groupedOrders[$id])) {
            $groupedOrders[$id] = [
                'order_id' => $id,
                'user_name' => $item['user_name'],
                'user_email' => $item['email'],
                'order_date' => $item['order_date'],
                'total_amount' => $item['total_amount'],
                'status' => $item['order_status'],
                'items' => []
            ];
        }
        $groupedOrders[$id]['items'][] = $item;
    }
} catch (PDOException $e) {
    $error = "Error loading orders: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Orders</title>
    <link rel="stylesheet" href="css/admin_orders.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
</head>
<body>
<?php include 'navbar/navbar.php'; ?>
<div id="admin-orders-container">
<!--kjo eshte pjesa e admin qe i menaxhon orders dmth i bon delivered, shipping waiting.. status -->
    <h1>All Orders</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php else: ?>
        <?php foreach ($groupedOrders as $order): ?>
            <div class="admin-order-card">
                <h2>Order #<?= $order['order_id'] ?> - <?= htmlspecialchars($order['user_name']) ?> (<?= $order['user_email'] ?>)</h2>
                <p>Placed on: <?= $order['order_date'] ?></p>
                <p>Total: $<?= number_format($order['total_amount'], 2) ?></p>

                <form method="POST" action="update_order_status.php" style="margin-top:10px;">
                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                    <label for="status_<?= $order['order_id'] ?>">Status:</label>
                    <select name="order_status" id="status_<?= $order['order_id'] ?>">
                        <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Shipped" <?= $order['status'] === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="Delivered" <?= $order['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                    </select>
                    <button type="submit">Update</button>
                </form>

                <div class="admin-order-items">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="admin-order-item">
                            <img src="get_image.php?id=<?= $item['product_id'] ?>" width="60" />
                            <div>
                                <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                                Quantity: <?= $item['quantity'] ?> @ $<?= number_format($item['price_at_time_of_purchase'], 2) ?>
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

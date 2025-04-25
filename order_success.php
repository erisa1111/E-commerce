<?php
session_start();
require_once 'db_connect.php';

// Check if order_id is provided
$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    header("Location: index.php");
    exit();
}

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $_SESSION['user_id'] ?? 0]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found or you don't have permission to view it.");
}

// Fetch order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name 
    FROM order_item oi 
    JOIN product p ON oi.product_id = p.id 
    WHERE order_id = ?
");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="css/order_success.css">
  <link rel="stylesheet" href="navbar/navbar.css">
  <link rel="stylesheet" href="footer/footer.css">
</head>
<body>
  <?php include 'navbar/navbar.php'; ?>

  <div class="success-container">
    <div class="success-card">
      <div class="success-icon">✓</div>
      <h1 class="konfirmimi">Order Confirmed!</h1>
      
      <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="summary-row">
          <span>Date:</span>
          <span><?= date('F j, Y', strtotime($order['order_date'])) ?></span>
        </div>
        <div class="summary-row">
          <span>Total:</span>
          <span>$<?= number_format($order['total_amount'], 2) ?></span>
        </div>
        <div class="summary-row">
          <span>Status:</span>
          <span><?= htmlspecialchars($order['order_status']) ?></span>
        </div>
      </div>

      <div class="order-items">
        <h3>Items Ordered</h3>
        <ul>
          <?php foreach ($items as $item): ?>
            <li>
              <?= htmlspecialchars($item['name']) ?> - 
              <?= $item['quantity'] ?> x 
              $<?= number_format($item['price_at_time_of_purchase'], 2) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="delivery-info">
        <h3>Delivery Address</h3>
        <p><?= nl2br(htmlspecialchars($order['delivery_address'])) ?></p>
      </div>

      <a href="home.php" class="continue-shopping">Continue Shopping</a>
    </div>
  </div>

  <?php include 'footer/footer.php'; ?>
</body>
</html>
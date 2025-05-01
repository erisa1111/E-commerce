<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch current user address
$stmt = $pdo->prepare("SELECT address, city, country FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$fromCart = isset($_GET['from']) && $_GET['from'] === 'cart';
$productId = $_GET['id'] ?? null;
$quantity = $_GET['quantity'] ?? 1;
$products = [];

if ($fromCart) {
    $products = $_SESSION['cart'] ?? [];
} else if ($productId) {
    // Fetch product for direct order
    $stmt = $pdo->prepare("SELECT id, name, price FROM product WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        $product['quantity'] = $quantity;
        $products[] = $product;
    }
}
?>
<!--pjesa ku na shfaqet porosia -->
<!DOCTYPE html>
<html>
<head>
  <title>Order Review</title>
  <link rel="stylesheet" href="css/order_page.css">
  <link rel="stylesheet" href="navbar/navbar.css">
  <link rel="stylesheet" href="footer/footer.css">
</head>
<body>
  <?php include 'navbar/navbar.php'; ?>

  <div class="order-container">
    <?php if (empty($products)): ?>
      <div class="empty-cart">
        <h2>Your order is empty</h2>
        <p>Please add some products to your cart first.</p>
        <a href="products.php" class="btn">Browse Products</a>
      </div>
    <?php else: ?>
      <h2>Review Your Order</h2>
      <form method="POST" action="place_order.php">
        <div class="address-section">
          <h3>Delivery Address</h3>
          <textarea name="delivery_address" rows="4" cols="50" required><?= 
              htmlspecialchars($user['address'] ?? '') . ', ' . 
              htmlspecialchars(($user['city'] ?? '') . ', ' . 
              ($user['country'] ?? '')) 
          ?></textarea>
        </div>

        <div class="products-section">
          <h3>Products</h3>
          <ul class="product-list">
          <?php foreach ($products as $product): ?>
              <li class="product-item">
                  <?= htmlspecialchars($product['name']) ?> - 
                  $<?= number_format($product['price'], 2) ?> x 
                  <?= htmlspecialchars($product['quantity']) ?>
                  <input type="hidden" name="product_ids[]" value="<?= $product['id'] ?>">
                  <input type="hidden" name="quantities[]" value="<?= $product['quantity'] ?>">
              </li>
          <?php endforeach; ?>
          </ul>
        </div>

        <button type="submit" class="place-order-btn">Place Order</button>
      </form>
    <?php endif; ?>
  </div>

  <?php include 'footer/footer.php'; ?>
</body>
</html>
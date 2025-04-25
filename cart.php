<?php
session_start();

// Check if the cart is in the session
$cart = $_SESSION['cart'] ?? [];

// If the cart is empty in the session, try reading it from the cookie
if (empty($cart) && isset($_COOKIE['persistent_cart'])) {
    $cart = json_decode($_COOKIE['persistent_cart'], true);
    $_SESSION['cart'] = $cart; // Store it back in the session for future requests
}

// Debugging: Check if cart data is loaded correctly
// Remove this after you've confirmed that the cart is working


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <link rel="stylesheet" href="css/cart.css">
    <style>
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f8f8;
        }
        .no-image-placeholder {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #777;
        }
    </style>
</head>
<body>
    <?php include 'navbar/navbar.php'; ?>

    <div id="cart-page">
        <h2>Your Shopping Cart</h2>

        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0; // Initialize total
                    foreach ($cart as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['image_path'])): ?>
                                <img src="<?= htmlspecialchars($item['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($item['name']) ?>" 
                                     class="product-image"
                                     onerror="this.onerror=null; this.src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';">
                            <?php else: ?>
                                <div class="no-image-placeholder">No image</div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-total">
                <h3>Total: $<?= number_format($total, 2) ?></h3>
                <a href="order_page.php?from=cart"><button>Proceed to Order</button></a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer/footer.php'; ?>
</body>
</html>
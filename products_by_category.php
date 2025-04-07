<?php
session_start();
require_once 'db_connect.php';

// Get user if logged in
$user = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

// Get category
$categoryName = isset($_GET['category']) ? $_GET['category'] : '';
if (!$categoryName) {
    die("Category not specified.");
}

try {
    $stmt = $pdo->prepare("SELECT p.*, sc.sub_name, c.category_name
                           FROM product p
                           JOIN sub_category sc ON p.subcategory_id = sc.subcategory_id
                           JOIN category c ON sc.category_id = c.category_id
                           WHERE c.category_name = ?");
    $stmt->execute([$categoryName]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoryName) ?> Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/category_products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .category-title {
            text-align: center;
            margin: 40px 0 20px;
            font-size: 2rem;
            color: #333;
        }
        .product-card a {
    display: block;  /* Makes the whole card clickable */
    text-decoration: none;  /* Remove underline */
    color: inherit;  /* Inherit text color */
}

.product-card img {
    max-width: 100%; /* Make sure the image is responsive */
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <div id="navbar-container">
        <div class="loading-spinner"></div>
    </div>

    <!-- Page Title -->
    <h2 class="category-title"><?= htmlspecialchars($categoryName) ?> Products</h2>

    <!-- Product Grid -->
    <div class="product-grid">
    <?php if ($products): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <!-- Wrap the card in a link to the product details page -->
                <a href="product_details.php?id=<?= htmlspecialchars($product['id']) ?>" class="product-link">
                    <img src="get_image.php?id=<?= htmlspecialchars($product['id']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['brand']) ?></p>
                    <p>$<?= number_format($product['price'], 2) ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; padding: 20px;">No products found in this category.</p>
    <?php endif; ?>
</div>

    <!-- Footer -->
    <div id="footer-container">
        <div class="loading-spinner"></div>
    </div>

    <!-- JS for loading navbar/footer -->
    <script src="js/loadComponents.js"></script>
</body>
</html>

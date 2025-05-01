<?php
session_start();
require_once 'db_connect.php';

// Redirect if the product ID is not set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID not specified.");
}
#get product id
$productId = $_GET['id'];

try {
    // Fetch product details by ID
    $stmt = $pdo->prepare("SELECT p.*, sc.sub_name, c.category_name FROM product p
                           JOIN sub_category sc ON p.subcategory_id = sc.subcategory_id
                           JOIN category c ON sc.category_id = c.category_id
                           WHERE p.id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // If product not found
    if (!$product) {
        die("Product not found.");
    }
} catch (PDOException $e) {
    die("Error fetching product details: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <link rel="stylesheet" href="css/product_details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .product-details-container {
            display: flex;
            justify-content: flex-start;
            margin: 40px;
           padding: 70px;
           flex-wrap: wrap;
        }
        .product-image {
            width: 40%; 
            text-align: center;
        }
        .product-image img {
            width: 100%; 
            max-width: 350px;
        }
        .product-info {
            width: 40%;
        }
        .product-info h1 {
            font-size: 2rem;
            color: #333;
        }
        .product-info p {
            font-size: 1.2rem;
        }
        .product-info .price {
            font-size: 1.5rem;
            color: #9e2548;
            margin: 20px 0;
        }
        .product-info input {
            width: 60px;
            padding: 5px;
            margin-right: 20px;
            font-size: 1rem;
        }
        .product-info button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #9e2548;
            color: white;
            border: none;
            cursor: pointer;
            margin: 10px 0;
        }
        .product-info button:hover {
            background-color: #7d1e34;
        }
        .product-description {
            margin-top: 20px;
            justify-content: center;
           
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div id="navbar-container">
    <div class="loading-spinner"></div>
</div>

<!-- Product Details -->
<div class="product-details-container">
    <!-- Left Side: Product Image -->
    <div class="product-image">
        <img src="get_image.php?id=<?= htmlspecialchars($product['id']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <!-- Right Side: Product Info -->
    <div class="product-info">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
        <p class="price">$<?= number_format($product['price'], 2) ?></p>

        <!-- Quantity Input -->
        <input type="number" id="quantity" value="1" min="1" max="10" step="1">

        <!-- Buttons -->
        <button onclick="addToCart(<?= $product['id'] ?>)">Add to Cart</button>
        <button onclick="orderNow(<?= $product['id'] ?>)">Order</button>

        <!-- Product Description -->
        <div class="product-description">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
    </div>
</div>

<!-- Footer -->
<div id="footer-container">
    <div class="loading-spinner"></div>
</div>

<!-- JS for Add to Cart and Order buttons -->
<script>
    function addToCart(productId) {
        var quantity = document.getElementById("quantity").value;
        
        // Send data to server (AJAX or form submission)
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "add_to_cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Added to cart successfully!");
            }
        };
        xhr.send("product_id=" + productId + "&quantity=" + quantity);
    }

    function orderNow(productId) {
        var quantity = document.getElementById("quantity").value;
        
        // Redirect to the order page with product details
        window.location.href = "order_page.php?id=" + productId + "&quantity=" + quantity;
    }
</script>

<!-- JS for loading navbar/footer -->
<script src="js/loadComponents.js"></script>
</body>
</html>
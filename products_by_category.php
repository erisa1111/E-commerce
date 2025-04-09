<?php 
// Connect to DB
require_once 'db_connect.php';

// STEP 1: Get the category name from URL
$categoryName = $_GET['category'] ?? null;
if (!$categoryName) {
    echo "Category not specified.";
    exit;
}

$selectedSub = $_GET['sub'] ?? null;
$selectedBrand = $_GET['brand'] ?? null;
$selectedGender = $_GET['gender'] ?? null;


try {
    $stmt = $pdo->prepare("SELECT DISTINCT sc.sub_name 
                           FROM sub_category sc
                           JOIN category c ON sc.category_id = c.category_id
                           WHERE c.category_name = ?");
    $stmt->execute([$categoryName]);
    $subcategories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $subcategories = [];
}

try {
    $stmt = $pdo->prepare("SELECT DISTINCT p.brand
                           FROM product p
                           JOIN sub_category sc ON p.subcategory_id = sc.subcategory_id
                           JOIN category c ON sc.category_id = c.category_id
                           WHERE c.category_name = ?");
    $stmt->execute([$categoryName]);
    $brands = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $brands = [];
}



$sql = "SELECT p.* FROM product p
        JOIN sub_category sc ON p.subcategory_id = sc.subcategory_id
        JOIN category c ON sc.category_id = c.category_id
        WHERE c.category_name = ?";
$params = [$categoryName];

if (!empty($selectedSub)) {
    $sql .= " AND sc.sub_name IN (" . implode(',', array_fill(0, count($selectedSub), '?')) . ")";
    $params = array_merge($params, $selectedSub); 
}

if (!empty($selectedBrand)) {
    $sql .= " AND p.brand IN (" . implode(',', array_fill(0, count($selectedBrand), '?')) . ")";
    $params = array_merge($params, $selectedBrand); 
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
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
            display: block;  
            text-decoration: none;  
            color: inherit; 
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

    <div class="products-page-container">
    <form method="GET" action="products_by_category.php">
        <input type="hidden" name="category" value="<?= htmlspecialchars($categoryName) ?>">

        <!-- Subcategory Filter -->
        <div class="filter-section">
            <h4>Subcategories</h4>
            <?php foreach ($subcategories as $sub): ?>
                <label>
                    <input type="checkbox" name="sub[]" value="<?= $sub ?>" <?= isset($_GET['sub']) && in_array($sub, $_GET['sub']) ? 'checked' : '' ?>>
                    <?= $sub ?>
                </label><br>
            <?php endforeach; ?>
        </div>

        <!-- Brand Filter -->
        <div class="filter-section">
            <h4>Brands</h4>
            <?php foreach ($brands as $brand): ?>
                <label>
                    <input type="checkbox" name="brand[]" value="<?= $brand ?>" <?= isset($_GET['brand']) && in_array($brand, $_GET['brand']) ? 'checked' : '' ?>>
                    <?= $brand ?>
                </label><br>
            <?php endforeach; ?>
        </div>

        <button type="submit">Apply Filters</button>
    </form>

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
    </div>

    <!-- Footer -->
    <div id="footer-container">
        <div class="loading-spinner"></div>
    </div>

    <!-- JS for loading navbar/footer -->
    <script src="js/loadComponents.js"></script>
</body>
</html>

<?php
session_start();
require_once 'db_connect.php';

// Check admin status more securely
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch categories and subcategories
try {
    $categories = $pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
    $subcategories = $pdo->query("SELECT * FROM sub_category")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    // Validate and sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);
    $brand = filter_var($_POST['brand'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $subcategory_id = filter_var($_POST['subcategory'], FILTER_SANITIZE_NUMBER_INT);

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";

        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Validate image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            die("Sorry, your file is too large.");
        }

        // Allow only specific formats
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Create a unique filename
        $uniqueFileName = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $uniqueFileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $targetFile;
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        die("Image upload is required.");
    }

    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO product (name, description, image, price, stock_quantity, brand, subcategory_id) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $image, $price, $stock, $brand, $subcategory_id]);

        $_SESSION['message'] = "Product added successfully!";
        header("Location: products.php");
        exit();
    } catch (PDOException $e) {
        die("Error adding product: " . $e->getMessage());
    }
}

// Fetch products
try {
    $stmt = $pdo->query("SELECT p.*, sc.sub_name, c.category_name 
                         FROM product p
                         JOIN sub_category sc ON p.subcategory_id = sc.subcategory_id
                         JOIN category c ON sc.category_id = c.category_id
                         ORDER BY p.id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}

// Display success message if exists
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Management</title>
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div id="navbar-container"></div>
    
    <div class="container">
        <h2>Product Management</h2>
        
        <button type="button" class="add-product-btn" id="openModalBtn">
            <i class="fas fa-plus"></i> Add New Product
        </button>
        
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td>
    <?php if ($product['image']): ?>
        <img src="get_image.php?id=<?= htmlspecialchars($product['id']) ?>" class="product-image" alt="Product Image">
    <?php endif; ?>
</td>


                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                        <td><?= htmlspecialchars($product['brand']) ?></td>
                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                        <td><?= htmlspecialchars($product['sub_name']) ?></td>
                        <td class="actions">
                        <a href="edit_product.php?id=<?= htmlspecialchars($product['id']) ?>" class="action-btn edit-btn"><i class="fas fa-edit"></i></a>

                        <form method="POST" action="delete_product.php">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
        <button type="submit" class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
    </form>
    </a>
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
        <!-- Add Product Modal -->
        <div id="productModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Add New Product</h3>
                    <span class="close-btn">&times;</span>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label for="subcategory">Subcategory</label>
                        <select class="form-control" id="subcategory" name="subcategory" required>
                            <option value="">Select a category first</option>
                            <?php foreach ($subcategories as $subcategory): ?>
                                <option value="<?= $subcategory['subcategory_id'] ?>" data-category="<?= $subcategory['category_id'] ?>">
                                    <?= htmlspecialchars($subcategory['sub_name']) ?>
                                </option>
                
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn" name="add_product">
                            <i class="fas fa-check"></i> Add Product
                        </button>
                    </div>
                
                
                </form>

    </div>
    </div>
    </div>
    
    <div id="footer-container"></div>
    

    <script src="js/products.js"></script>
</body>
</html>
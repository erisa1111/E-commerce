<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Product ID is required.");
}

$product_id = $_GET['id'];

// i emrr te dhanat per produktin prej db
try {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        die("Product not found.");
    }
} catch (PDOException $e) {
    die("Error fetching product details: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $brand = $_POST['brand'];
 
    $subcategory_id = $_POST['subcategory'];
    
    // Keep old image unless new one is uploaded
    $image = $product['image'];

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
    
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
    
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            die("Uploaded file is not a valid image.");
        }

        if ($_FILES["image"]["size"] > 5000000) {
            die("Image file is too large. Max size is 5MB.");
        }

        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        // Generate a unique name for the new image
        $uniqueName = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $uniqueName;

        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // If a new image was uploaded, replace the old image path
            $image = $targetFile;

            // Optionally, delete the old image if it exists (this is not required)
            if (file_exists($product['image']) && $product['image'] !== $image) {
                unlink($product['image']);
            }
        } else {
            die("Failed to upload new image.");
        }
    }

    // Update product in the database
    // Update product in the database
try {
    $stmt = $pdo->prepare("UPDATE product SET name = ?, description = ?, image = ?, price = ?, stock_quantity = ?, brand = ?, subcategory_id = ? WHERE id = ?");
    $stmt->execute([$name, $description, $image, $price, $stock, $brand, $subcategory_id, $product_id]);
    header("Location: products.php");
    exit();
} catch (PDOException $e) {
    die("Error updating product: " . $e->getMessage());
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/edit_product.css">

    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
</head>
<body>

<div id="navbar-container"></div>
    <div class="edit_container">
    <h2>Edit Product</h2>

    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div>
            <label for="brand">Brand</label>
            <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($product['brand']) ?>" required>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div>
            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>

        <div>
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($product['stock_quantity']) ?>" required>
        </div>

        <div>
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <?php
                $categories = $pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categories as $category) {
                    echo '<option value="' . $category['category_id'] . '"' . 
                         ($category['category_id'] == $product['category_id'] ? ' selected' : '') . '>' . 
                         htmlspecialchars($category['category_name']) . '</option>';
                }
                ?>
            </select>
        </div>

        <div>
            <label for="subcategory">Subcategory</label>
            <select id="subcategory" name="subcategory" required>
                <?php
                $subcategories = $pdo->query("SELECT * FROM sub_category")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($subcategories as $subcategory) {
                    echo '<option value="' . $subcategory['subcategory_id'] . '"' . 
                         ($subcategory['subcategory_id'] == $product['subcategory_id'] ? ' selected' : '') . '>' . 
                         htmlspecialchars($subcategory['sub_name']) . '</option>';
                }
                ?>
            </select>
        </div>

        <?php if (!empty($product['image'])): ?>
            <div>
                <p>Current Image:</p>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Current Image" style="max-width: 200px;">
            </div>
        <?php endif; ?>

        <div>
            <label for="image">Change Product Image</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <div>
            <button type="submit" name="update_product">Update Product</button>
        </div>
    </form>

    </div>
    <div id="footer-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch("navbar/navbar.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-container').innerHTML = data;
                });
            
            fetch("footer/footer.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById('footer-container').innerHTML = data;
                });
        });
    </script>
</body>
</html>



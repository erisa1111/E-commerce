<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    try {
        // e shlyen produktin prej db
        $stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
        $stmt->execute([$product_id]);

        // Redirect back to products page
        header("Location: products.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting product: " . $e->getMessage());
    }
} else {
    die("Product ID is required.");
}
?>

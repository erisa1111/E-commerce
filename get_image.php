<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT image FROM product WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product && $product['image']) {
            $imagePath = $product['image'];

            if (file_exists($imagePath)) {
                $imageType = mime_content_type($imagePath); // detects if jpg/png/etc
                header("Content-Type: $imageType");
                readfile($imagePath);
                exit;
            } else {
                die("Image file not found on server.");
            }
        } else {
            die("Image not found in database.");
        }
    } catch (PDOException $e) {
        die("Error fetching image: " . $e->getMessage());
    }
}
?>


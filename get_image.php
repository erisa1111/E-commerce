<?php
require_once 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    header('Content-Type: text/plain');
    exit('Invalid product ID');
}

$productId = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT image FROM product WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product || empty($product['image'])) {
        //nese ksa imazh vec e bon return no si lloj frame
        header('Content-Type: image/png');
        echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
        exit;
    }

    $imagePath = $product['image'];
    
    // If path is relative, prepend the document root
    if (strpos($imagePath, '/') !== 0) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($imagePath, '/');
    }

    if (!file_exists($imagePath)) {
        throw new Exception("Image file not found at: " . $imagePath);
    }

    $imageType = mime_content_type($imagePath);
    if (strpos($imageType, 'image/') !== 0) {
        throw new Exception("File is not a valid image");
    }

    header("Content-Type: $imageType");
    readfile($imagePath);
    exit;

} catch (Exception $e) {
    error_log("Image Error: " . $e->getMessage());
    // Return transparent 1x1 pixel on error
    header('Content-Type: image/png');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
}
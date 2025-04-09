<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo "Access denied.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;
    $newStatus = $_POST['order_status'] ?? null;

    if ($orderId && $newStatus) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $orderId]);

            // Optionally: Notify user via email or store a message for display
            $_SESSION['success'] = "Order #$orderId updated to '$newStatus'.";

            header("Location: admin_orders.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Failed to update order: " . $e->getMessage();
            header("Location: admin_orders.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Missing order ID or status.";
        header("Location: admin_orders.php");
        exit();
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
    exit();
}

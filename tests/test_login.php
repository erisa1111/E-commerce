<?php
ob_start();
require_once 'db_connect.php';

// Start session before any output
session_start();

// Simulate POST request for testing
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'email' => 'marijankolaj1@gmail.com',
    'password' => '123'
];

// Prevent actual redirection during tests
define('TESTING', true);

// Include the login script
require_once 'login.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    echo "Login Test Passed: User logged in successfully.\n";
    echo "User ID: " . $_SESSION['user_id'] . "\n";
    echo "User Role: " . $_SESSION['user_role'] . "\n";
} else {
    echo "Login Test Failed: User not logged in.\n";
    if (isset($error)) {
        echo "Error: " . $error . "\n";
    }
}

ob_end_flush();
?>
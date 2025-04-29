<?php
//maje nmen per me testu mire edhe me funksionu e bon koment ne signup.php header-in
// Simulate POST request for testing
$_SERVER['REQUEST_METHOD'] = 'POST';

// Simulate POST data for the sign-up form
$_POST = [
    'name' => 'TestSignUp',
    'surname' => 'Doe',
    'phone_number' => '123456789',
    'country' => 'CountryX',
    'city' => 'CityY',
    'address' => 'Test Address',
    'email' => 'joe.doe@example.com',
    'password' => 'password123'
];

// Start output buffering to capture echo/print output
ob_start();

// Include the signup.php to simulate the sign-up logic
require_once 'signup.php';

// Capture the output of the signup script
$output = ob_get_clean();

// Check if the output includes the expected success message
if (strpos($output, 'User registered successfully.') !== false) {
    echo "Sign-up Test Passed: User registered successfully.\n";
} else {
    echo "Sign-up Test Failed: User not registered.\n";
}

// --- Cleanup: Remove the test user from the database ---
$stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
$stmt->execute(['john.doe@example.com']);
echo "Test User Removed.\n";
?>
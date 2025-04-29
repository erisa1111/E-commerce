<?php
session_start();

// Ensure session variables are set correctly after login
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    echo "Session Test Passed: Session variables are correctly set.\n";
} else {
    echo "Session Test Failed: Session variables not set.\n";
}
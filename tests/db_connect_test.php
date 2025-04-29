<?php
echo "🔍 Running test: db_connect.php...\n";

require_once 'db_connect.php';

if (isset($pdo) && $pdo instanceof PDO) {
    echo "✅ Test passed: PDO connection established.\n";
} else {
    echo "❌ Test failed: PDO connection not established.\n";
}
?>

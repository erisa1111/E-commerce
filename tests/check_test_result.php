<?php
$logFile = __DIR__ . '/header_log.txt';

if (!file_exists($logFile)) {
    echo "❌ Test failed: No redirect header captured.\n";
    exit;
}

$header = file_get_contents($logFile);
if (strpos($header, 'order_success.php') !== false) {
    echo "✅ Test passed: Redirected to success page.\n";
    echo "Captured header: $header\n";
} else {
    echo "❌ Test failed: No redirect to success page.\n";
    echo "Captured header: $header\n";
}
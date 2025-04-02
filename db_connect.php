<?php
$host = 'sql7.freesqldatabase.com'; // or your host
$dbname = 'sql7770773';
$username = 'sql7770773';
$password = 'uxIQa4fDvJ';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<?php
$host = 'sql7.freesqldatabase.com'; 
$dbname = 'sql7772011';
$username = 'sql7772011';
$password = '7sBkIT4SNC';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
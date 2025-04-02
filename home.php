<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once 'db_connect.php';

// Get complete user data
$stmt = $pdo->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowHeaven - Welcome</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body>
    <!-- Dynamic Navbar -->
    <div id="navbar-container">
        <!-- Fallback content if JS fails -->
        <nav class="navbar">
            <a href="/" class="logo">GlowHeaven</a>
            <div class="user-menu">
                <span>Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main>
        <section class="hero">
            <h1>Welcome to GlowHeaven, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p>Discover the best skincare and beauty products tailored for you.</p>
            <a href="#" class="shop-now-btn">Shop Now</a>
        </section>
        
        <section class="user-dashboard">
            <h2>Your Account</h2>
            <div class="user-info">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Member since:</strong> <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <div id="footer-container">
        <footer>
            <p>&copy; <?php echo date('Y'); ?> GlowHeaven. All rights reserved.</p>
        </footer>
    </div>

    <!-- JavaScript to enhance the page -->
    <script>
        // Load navbar and footer if JavaScript is enabled
        document.addEventListener('DOMContentLoaded', function() {
            fetch("navbar/navbar.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-container').innerHTML = data;
                });
            
            fetch("footer/footer.html")
                .then(response => response.text())
                .then(data => {
                    document.getElementById('footer-container').innerHTML = data;
                });
        });
    </script>
</body>
</html>
<?php
session_start();

// Only connect to DB if user is logged in
$user = null;
if (isset($_SESSION['user_id'])) {
    require_once 'db_connect.php';
    
    try {
        $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowHeaven - Premium Skincare & Beauty</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar Container -->
    <div id="navbar-container">
        <div class="loading-spinner"></div>
    </div>

    <main class="container">
        <section class="hero">
            <h1>Your Perfect Skin Journey Starts Here</h1>
            <p>Discover our premium skincare collection</p>
            <div class="cta-buttons">
                <a href="products.php" class="btn-primary">Shop Now</a>
                <?php if (!$user): ?>
                    <a href="signup.php" class="btn-secondary">Join Now</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer Container -->
    <div id="footer-container">
        <div class="loading-spinner"></div>
    </div>

    <!-- Load the external JavaScript file -->
    <script src="js/loadComponents.js"></script>
</body>
</html>
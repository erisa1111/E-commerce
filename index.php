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

    <!-- Welcome Image Section -->
    <div class="welcome-image-container">
        <img src="img/welcome.png" alt="Welcome to GlowHeaven">
    </div>

    <!-- Cosmetics Intro -->
    <section class="cosmetics-intro">
        <h2>Enhance Your Natural Beauty</h2>
        <p>Explore premium beauty brands and products tailored to your unique glow. We bring the best of skincare, makeup, and more to your fingertips.</p>
    </section>

    <!-- Brands Section -->
    <section class="brands-section">
        <h2>Our Brands</h2>
        <div style="display: flex; align-items: center; justify-content: center;">
            <button class="arrow-btn" onclick="scrollBrands(-200)"><i class="fas fa-chevron-left"></i></button>
            <div class="brands-scroll-container" id="brands-scroll">
                <div class="brand-item">Brand 1</div>
                <div class="brand-item">Brand 2</div>
                <div class="brand-item">Brand 3</div>
                <div class="brand-item">Brand 4</div>
                <div class="brand-item">Brand 5</div>
            </div>
            <button class="arrow-btn" onclick="scrollBrands(200)"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Category Cards Grid -->
    <section class="category-grid">
    <a href="products_by_category.php?category=Perfumes" class="category-card" style="background-image: url('img/perfumes.png');">
        <div class="category-label">Perfumes</div>
    </a>
    <a href="products_by_category.php?category=Skin%20Care" class="category-card" style="background-image: url('img/skincare.png');">
        <div class="category-label">Skin Care</div>
    </a>
    <a href="products_by_category.php?category=Makeup" class="category-card" style="background-image: url('img/makeUp.png');">
        <div class="category-label">Makeup</div>
    </a>
    <a href="products_by_category.php?category=Hair" class="category-card" style="background-image: url('img/hair.png');">
        <div class="category-label">Hair</div>
    </a>
    <a href="products_by_category.php?category=Sun%20Protection" class="category-card" style="background-image: url('img/sunscreen.png');">
        <div class="category-label">Sun Protection</div>
    </a>
    <a href="products_by_category.php?category=New%20Arrivals" class="category-card" style="background-image: url('img/newarrivals.png');">
        <div class="category-label">New Arrivals</div>
    </a>
    </section>

    <!-- Footer Container -->
    <div id="footer-container">
        <div class="loading-spinner"></div>
    </div>

    <!-- Load the external JavaScript file -->
    <script src="js/loadComponents.js"></script>
</body>
</html>
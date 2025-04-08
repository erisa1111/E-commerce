<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


require_once 'db_connect.php';


$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowHeaven - Welcome</title>
    
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .welcome-user {
            text-align: center;
            margin-top: 20px;
            font-size: 1.5rem;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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
        <div class="brands-scroll-wrapper">
        <div class="brands-scroll-container" id="brands-scroll" style="display: flex; overflow-x: auto; gap: 20px; padding: 10px;">
            <div class="brand-item"><img src="img/armaniLogo.png" alt="Armani" style="height: 100px;"></div>
            <div class="brand-item"><img src="img/CelvinKleinLogo.png" alt="Calvin Klein" style="height: 45px;"></div>
            <div class="brand-item"><img src="img/ChloeLogo.png" alt="Chloe" style="height: 65px;"></div>
           
            <div class="brand-item"><img src="img/hugoBossLogo.png" alt="Hugo" style="height: 60px;"></div>
            <div class="brand-item"><img src="img/lamerLogo.png" alt="lamer" style="height: 35px;"></div>
            <div class="brand-item"><img src="img/gucciLogo.png" alt="gucci" style="height: 56px;"></div>
            <div class="brand-item"><img src="img/lancomeLogo.png" alt="lancome" style="height: 68px;"></div>
            <div class="brand-item"><img src="img/macLogo.png" alt="mac" style="height: 47px;"></div>
            <div class="brand-item"><img src="img/estelauderLogo.png" alt="este" style="height: 50px;"></div>
            <div class="brand-item"><img src="img/ClarinsLogo.png" alt="clarins" style="height: 75px;"></div>
            <div class="brand-item"><img src="img/diorLogo.png" alt="dior" style="height: 56px;"></div>
            <div class="brand-item"><img src="img/PradaLogo.png" alt="prada" style="height: 60px;"></div>
            
        </div>
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

    <br><br><br><br><br><br><br>

    <!-- Footer -->
    <div id="footer-container">
        <div class="loading-spinner"></div>
    </div>

    <!-- JS for Navbar, Footer & Brand Scroll -->
    <script src="js/loadComponents.js"></script>
    <script>
        function scrollBrands(offset) {
            const container = document.getElementById('brands-scroll');
            container.scrollLeft += offset;
        }
    </script>
</body>
</html>
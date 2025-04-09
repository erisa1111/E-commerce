<?php 
// Avoid starting the session again if it's already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connect.php';

$user = null;
if (isset($_SESSION['user_id'])) {
    try {
      $stmt = $pdo->prepare("SELECT name, surname, address, city, country FROM users WHERE id = ?");        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!--?php
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";  qeta kod e perdor per me kqyr hala a je loggedIN

e qeto e bon per me destroy qat session qe mos me kan logged in anymore
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar</title>
  <link rel="stylesheet" href="navbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>

    .user-dropdown {
      position: relative;
      display: inline-block;
    }
    .user-dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
      border-radius: 4px;
    }
    .user-dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    .user-dropdown-content a:hover {
      background-color: #f1f1f1;
      border-radius: 4px;
    }
    .user-dropdown:hover .user-dropdown-content {
      display: block;
    }
    .user-icon-wrapper {
      cursor: pointer;
      padding: 8px;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="top-bar">
      <div class="left-nav"></div>
      <h1>GlowHeaven</h1>
      <div class="right-nav">
        <div class="search-container">
          <input type="text" class="search-input" placeholder="Search...">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
        </div>
        <a href="cart.php" title="View Cart">
    <i class="fa-solid fa-cart-shopping" ></i>
</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="user-dropdown">
            <div class="user-icon-wrapper">
              <i class="fa-regular fa-user"></i>
            </div>
            <div class="user-dropdown-content">
              <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <!-- Admin dropdown items -->
                <a href="admin_orders.php">Orders</a>
                <a href="products.php">Products</a>
                <a href="profile.php">Edit Profile</a>
                <a href="logout.php">Log Out</a>
              <?php else: ?>
                <!-- Regular user dropdown items -->
                <p style=" margin-left: 15px;color:black;"> <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?></p>
                <a href="order_history.php">My Orders</a>
                <a href="profile.php">Edit Profile</a>
                <a href="logout.php">Log Out</a>
              <?php endif; ?>
            </div>
          </div>
          <a href="logout.php">
            <i class="fa-solid fa-sign-out-alt" ></i>
          </a>
          <?php else: ?>
          <div class="user-dropdown">
            <div class="user-icon-wrapper">
               <i class="fa-regular fa-user"></i>
            </div>
            <div class="user-dropdown-content">
               <a href="login.php">Log In</a>
               <a href="signup.php">Register Today</a>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>

    <div class="down-bar">
    <div class="nav-items">
    <div class="nav-item" onclick="window.location.href='home.php'" style="cursor: pointer;">
  Home
</div>
     
      <a class="nav-item" href="products_by_category.php?category=Perfumes">
  Perfumes
  <div class="dropdown">
    <div class="dropdown-item">Eau de Parfum</div>
    <div class="dropdown-item">Eau de Toilette</div>
    <div class="dropdown-item">Body Mist</div>
    <div class="dropdown-item">Perfume Oils</div>
    <div class="dropdown-item">Travel-size</div>
    <div class="dropdown-item">Fragrance Sets</div>
    <div class="dropdown-item">Oud Perfumes</div>
    <div class="dropdown-item">Unisex Scents</div>
    <div class="dropdown-item">Rollerballs</div>
    <div class="dropdown-item">Solid Perfumes</div>
  </div>
</a>

<a class="nav-item" href="products_by_category.php?category=Skin%20Care">
  Skin Care
  <div class="dropdown">
    <div class="dropdown-item">Cleansers</div>
    <div class="dropdown-item">Toners</div>
    <div class="dropdown-item">Serums</div>
    <div class="dropdown-item">Moisturizers</div>
    <div class="dropdown-item">Eye Creams</div>
    <div class="dropdown-item">Face Masks</div>
    <div class="dropdown-item">Exfoliators</div>
    <div class="dropdown-item">Lip Care</div>
    <div class="dropdown-item">Anti-Aging</div>
    <div class="dropdown-item">Brightening</div>
  </div>
</a>

<a class="nav-item" href="products_by_category.php?category=Makeup">
  Makeup
  <div class="dropdown">
    <div class="dropdown-item">Foundations</div>
    <div class="dropdown-item">Concealers</div>
    <div class="dropdown-item">Powders</div>
    <div class="dropdown-item">Primers</div>
    <div class="dropdown-item">Blushes</div>
    <div class="dropdown-item">Highlighters</div>
    <div class="dropdown-item">Lipsticks</div>
    <div class="dropdown-item">Mascaras</div>
    <div class="dropdown-item">Eyeliners</div>
    <div class="dropdown-item">Brows</div>
  </div>
</a>

<a class="nav-item" href="products_by_category.php?category=Hair">
  Hair
  <div class="dropdown">
    <div class="dropdown-item">Shampoos</div>
    <div class="dropdown-item">Conditioners</div>
    <div class="dropdown-item">Hair Masks</div>
    <div class="dropdown-item">Hair Oils</div>
    <div class="dropdown-item">Serums</div>
    <div class="dropdown-item">Heat Protectants</div>
    <div class="dropdown-item">Dry Shampoo</div>
    <div class="dropdown-item">Hair Color</div>
    <div class="dropdown-item">Curl Enhancers</div>
    <div class="dropdown-item">Hair Sprays</div>
  </div>
</a>

<a class="nav-item" href="products_by_category.php?category=Sun%20Protection">
  Sun Protection
  <div class="dropdown">
    <div class="dropdown-item">Face SPF</div>
    <div class="dropdown-item">Body SPF</div>
    <div class="dropdown-item">Tinted Sunscreens</div>
    <div class="dropdown-item">Sunscreen Sticks</div>
    <div class="dropdown-item">After-Sun</div>
    <div class="dropdown-item">Mineral SPF</div>
    <div class="dropdown-item">Spray SPF</div>
    <div class="dropdown-item">Waterproof SPF</div>
    <div class="dropdown-item">Kids & Baby SPF</div>
    <div class="dropdown-item">SPF Lip Balm</div>
  </div>
</a>

<a class="nav-item" href="products_by_category.php?category=New%20Arrivals">
  New Arrivals
  <div class="dropdown">
    <div class="dropdown-item">New in Skincare</div>
    <div class="dropdown-item">New in Makeup</div>
    <div class="dropdown-item">New in Hair</div>
    <div class="dropdown-item">New in Perfume</div>
    <div class="dropdown-item">New in Body</div>
    <div class="dropdown-item">New Brands</div>
    <div class="dropdown-item">Limited Edition</div>
    <div class="dropdown-item">Trending Now</div>
    <div class="dropdown-item">Restocked</div>
    <div class="dropdown-item">Staff Picks</div>
  </div>
</a>

    </div>
  </div>
  </div>
</body>
</html>
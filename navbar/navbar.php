
<?php 
session_start();
require_once '../db_connect.php';

// Fetch user data if logged in
$user = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT name, surname FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
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
        <i class="fa-solid fa-cart-shopping" style="cursor:pointer;"></i>
        
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="user-dropdown">
            <div class="user-icon-wrapper">
              <i class="fa-regular fa-user"></i>
            </div>
            <div class="user-dropdown-content">
              <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <!-- Admin dropdown items -->
                <a href="orders.php">Orders</a>
                <a href="products.php">Products</a>
                <a href="profile.php">Edit Profile</a>
                <a href="logout.php">Log Out</a>
              <?php else: ?>
                <!-- Regular user dropdown items -->
                <p style=" margin-left: 15px;color:black;"> <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?></p>
                <a href="my_orders.php">My Orders</a>
                <a href="profile.php">Edit Profile</a>
                <a href="logout.php">Log Out</a>
              <?php endif; ?>
            </div>
          </div>
          <a href="logout.php">
            <i class="fa-solid fa-sign-out-alt" style="color:white;"></i>
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
        <div class="nav-item">
          Face
          <div class="dropdown">
            <div class="dropdown-item">Moisturizers</div>
            <div class="dropdown-item">Cleansers</div>
            <div class="dropdown-item">Serums</div>
            <div class="dropdown-item">Masks</div>
          </div>
        </div>
        <div class="nav-item">
          Body
          <div class="dropdown">
            <div class="dropdown-item">Body Lotions</div>
            <div class="dropdown-item">Sunscreen</div>
            <div class="dropdown-item">Exfoliators</div>
            <div class="dropdown-item">Body Oils</div>
          </div>
        </div>
        <div class="nav-item">
          Makeup
          <div class="dropdown">
            <div class="dropdown-item">Foundation</div>
            <div class="dropdown-item">Lipstick</div>
            <div class="dropdown-item">Eyeshadow</div>
            <div class="dropdown-item">Mascara</div>
          </div>
        </div>
        <div class="nav-item">
          Perfumes
          <div class="dropdown">
            <div class="dropdown-item">Floral</div>
            <div class="dropdown-item">Woody</div>
            <div class="dropdown-item">Citrus</div>
            <div class="dropdown-item">Unisex</div>
          </div>
        </div>
        <div class="nav-item">
          Hair
          <div class="dropdown">
            <div class="dropdown-item">Shampoo</div>
            <div class="dropdown-item">Conditioner</div>
            <div class="dropdown-item">Hair Masks</div>
            <div class="dropdown-item">Styling Products</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

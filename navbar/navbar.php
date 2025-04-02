<?php session_start();  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="profile.php">
                    <i class="fa-regular fa-user"></i>
                </a>
                <a href="logout.php">
                    <i class="fa-solid fa-sign-out-alt"  style="color:white;"></i> 
                </a>
            <?php else: ?>
                <a href="login.php">
                    <i class="fa-regular fa-user"></i>
                </a>
                
            <?php endif; ?>
        </div>
    
    </div>

    <div class="down-bar">
        <div class="nav-items">
            <div class="nav-item">
                Face </i>
                <div class="dropdown">
                    <div class="dropdown-item">Moisturizers</div>
                    <div class="dropdown-item">Cleansers</div>
                    <div class="dropdown-item">Serums</div>
                    <div class="dropdown-item">Masks</div>
                </div>
            </div>
    
            <div class="nav-item">
                Body </i>
                <div class="dropdown">
                    <div class="dropdown-item">Body Lotions</div>
                    <div class="dropdown-item">Sunscreen</div>
                    <div class="dropdown-item">Exfoliators</div>
                    <div class="dropdown-item">Body Oils</div>
                </div>
            </div>
    
            <div class="nav-item">
                Makeup </i>
                <div class="dropdown">
                    <div class="dropdown-item">Foundation</div>
                    <div class="dropdown-item">Lipstick</div>
                    <div class="dropdown-item">Eyeshadow</div>
                    <div class="dropdown-item">Mascara</div>
                </div>
            </div>
    
            <div class="nav-item">
                Perfumes </i>
                <div class="dropdown">
                    <div class="dropdown-item">Floral</div>
                    <div class="dropdown-item">Woody</div>
                    <div class="dropdown-item">Citrus</div>
                    <div class="dropdown-item">Unisex</div>
                </div>
            </div>
    
            <div class="nav-item">
                Hair </i>
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



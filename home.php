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
   
    <link rel="stylesheet" href="navbar/navbar.css">
    <link rel="stylesheet" href="footer/footer.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body>
    <div id="navbar-container">
        <nav class="navbar">
            <a href="/" class="logo">GlowHeaven</a>
            <div class="user-menu">
                <?php if ($user): ?>
                    <span>Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
                <?php else: ?>
                    <span>Error: User not found.</span>
                <?php endif; ?>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </div>

    <main>
        <section class="hero">
            <?php if ($user): ?>
                <h1>Welcome to GlowHeaven, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <?php else: ?>
                <h1>Error: User not found.</h1>
            <?php endif; ?>
            <p>Discover the best skincare and beauty products tailored for you.</p>
            <a href="#" class="shop-now-btn">Shop Now</a>
        </section>
        
        <section class="user-dashboard">
            <h2>Your Account</h2>
            <div class="user-info">
                <?php if ($user): ?>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <?php else: ?>
                    <p>Error: No account details found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <div id="footer-container">
        <footer>
            <p>&copy; <?php echo date('Y'); ?> GlowHeaven. All rights reserved.</p>
        </footer>
    </div>

    <script>
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
